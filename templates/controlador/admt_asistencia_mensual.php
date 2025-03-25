<?php
$meses_es = [
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
];

$query_tribunales = "SELECT id_tribunal, nombre_tribunal FROM tribunales ORDER BY nombre_tribunal";
$result_tribunales = $conn->query($query_tribunales);
$tribunales = [];
while ($row = $result_tribunales->fetch_assoc()) {
    $tribunales[] = $row;
}

$query_years = "SELECT DISTINCT YEAR(dia) as anio FROM dias_usuario ORDER BY anio DESC";
$result_years = $conn->query($query_years);
$years = [];
while ($row = $result_years->fetch_assoc()) {
    $years[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_year = $_POST['anio'];
    $selected_tribunal = $_POST['tribunal'];
} else {
    $selected_year = date('Y');
    $selected_tribunal = (count($tribunales) > 0) ? $tribunales[0]['id_tribunal'] : 0;
}

$query_usuarios = "SELECT COUNT(*) as total FROM usuario WHERE id_tribunal = ? AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $selected_tribunal);
$stmt_usuarios->execute();
$total_usuarios = $stmt_usuarios->get_result()->fetch_assoc()['total'];


$query_dias_con_registros = "SELECT DISTINCT DATE(dia) as fecha, MONTH(dia) as mes
                             FROM dias_usuario
                             WHERE YEAR(dia) = ? AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
                             ORDER BY fecha";
$stmt_dias = $conn->prepare($query_dias_con_registros);
$stmt_dias->bind_param("ii", $selected_year, $selected_tribunal);
$stmt_dias->execute();
$result_dias = $stmt_dias->get_result();


$dias_con_registros_por_mes = [];
while ($row = $result_dias->fetch_assoc()) {
    $mes = $row['mes'];
    if (!isset($dias_con_registros_por_mes[$mes])) {
        $dias_con_registros_por_mes[$mes] = [];
    }
    $dias_con_registros_por_mes[$mes][] = $row['fecha'];
}
$meses_con_registros = array_keys($dias_con_registros_por_mes);
sort($meses_con_registros); 
$query = "SELECT MONTH(dia) as mes, DATE(dia) as fecha,
                 SUM(TIME_TO_SEC(tiempo_excedido)) as total_excedido,
                 SUM(TIME_TO_SEC(tiempo_salida)) as total_salida,
                 SUM(CASE WHEN (tiempo_excedido > '00:00:00' OR tiempo_salida <> '00:00:00') THEN 1 ELSE 0 END) as incumplieron,
                 COUNT(*) as registros
          FROM dias_usuario
          WHERE YEAR(dia) = ? AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
          GROUP BY MONTH(dia), DATE(dia)
          ORDER BY fecha";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $selected_year, $selected_tribunal);
$stmt->execute();
$result = $stmt->get_result();
$datos_mes = [];
for ($m = 1; $m <= 12; $m++) {
    $datos_mes[$m] = [
        'incumplieron' => 0,
        'total_excedido' => 0,
        'total_salida' => 0,
        'registros' => 0,
        'dias_con_registros' => isset($dias_con_registros_por_mes[$m]) ? count($dias_con_registros_por_mes[$m]) : 0,
        'inasistencias_horas' => 0
    ];
}

while ($row = $result->fetch_assoc()) {
    $mes_num = $row['mes'];
    $datos_mes[$mes_num]['incumplieron'] += $row['incumplieron'];
    $datos_mes[$mes_num]['total_excedido'] += $row['total_excedido'];
    $datos_mes[$mes_num]['total_salida'] += $row['total_salida'];
    $datos_mes[$mes_num]['registros'] += $row['registros'];
}

for ($m = 1; $m <= 12; $m++) {
    $dias_con_registros = $datos_mes[$m]['dias_con_registros'];
    if ($dias_con_registros > 0) {
        $ausentes_potenciales = $total_usuarios * $dias_con_registros;
        $ausentes_reales = $ausentes_potenciales - $datos_mes[$m]['registros'];
        $datos_mes[$m]['ausentes'] = $ausentes_reales;
        $cumplieron = $datos_mes[$m]['registros'] - $datos_mes[$m]['incumplieron'];
        $porcentaje_cumplimiento = round(($cumplieron / $datos_mes[$m]['registros']) * 100, 2);
        $datos_mes[$m]['porcentaje_cumplimiento'] = $porcentaje_cumplimiento;
        
        $total_seconds = $datos_mes[$m]['total_excedido'] + $datos_mes[$m]['total_salida'];
        $inasistencias_horas = $total_seconds / 3600;
        $datos_mes[$m]['inasistencias_horas'] = $inasistencias_horas;
        
        $horas_por_usuario = ($selected_tribunal == 29) ? 44 : 45;
        $weeks_in_month = $dias_con_registros / 5; 
        $total_horas_tribunal = $total_usuarios * $horas_por_usuario * $weeks_in_month;
        $datos_mes[$m]['total_horas_tribunal'] = $total_horas_tribunal;
        
        $porcentaje_inasistencia = $total_horas_tribunal > 0 ? round(($inasistencias_horas / $total_horas_tribunal) * 100, 2) : 0;
        $datos_mes[$m]['porcentaje_inasistencia'] = $porcentaje_inasistencia;
    } else {
        $datos_mes[$m]['ausentes'] = 0;
        $datos_mes[$m]['porcentaje_cumplimiento'] = 100;
        $datos_mes[$m]['inasistencias_horas'] = 0;
        $datos_mes[$m]['total_horas_tribunal'] = 0;
        $datos_mes[$m]['porcentaje_inasistencia'] = 0;
    }
}

$detalles_mes = [];
$query_detalle = "SELECT MONTH(dia) as mes, u.RUT, u.nombre_completo, SUM(TIME_TO_SEC(tiempo_excedido)) as total_tardanza
                  FROM dias_usuario du
                  JOIN usuario u ON du.RUT = u.RUT
                  WHERE YEAR(dia) = ? AND u.id_tribunal = ?
                    AND (tiempo_excedido > '00:00:00' OR tiempo_salida <> '00:00:00')
                  GROUP BY MONTH(dia), u.RUT";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("ii", $selected_year, $selected_tribunal);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();
while ($row = $result_detalle->fetch_assoc()) {
    $mes = $row['mes'];
    if (!isset($detalles_mes[$mes])) {
         $detalles_mes[$mes] = [];
    }
    
    $row['total_tardanza'] = sprintf(
        '%d:%02d:%02d',
        floor($row['total_tardanza'] / 3600),
        floor(($row['total_tardanza'] % 3600) / 60),
        $row['total_tardanza'] % 60
    );
    $detalles_mes[$mes][] = $row;
}
?>
