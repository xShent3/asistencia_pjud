<?php
session_start();
$meses_es = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$id_tribunal = $_SESSION['id_tribunal'];
$currentYear = date('Y');
$query_years = "SELECT DISTINCT YEAR(dia) as anio FROM dias_usuario ORDER BY anio DESC";
$result_years = $conn->query($query_years);
$selected_year = $currentYear;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_year = $_POST['anio'];
}

$query_usuarios = "SELECT COUNT(*) as total FROM usuario WHERE id_tribunal = ? AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $id_tribunal);
$stmt_usuarios->execute();
$total_usuarios = $stmt_usuarios->get_result()->fetch_assoc()['total'];
$query_dias = "SELECT DISTINCT DATE(dia) as fecha, MONTH(dia) as mes 
              FROM dias_usuario 
              WHERE YEAR(dia) = ? 
                AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)";
$stmt_dias = $conn->prepare($query_dias);
$stmt_dias->bind_param("ii", $selected_year, $id_tribunal);
$stmt_dias->execute();
$dias_result = $stmt_dias->get_result();

$dias_por_mes = [];
while ($row = $dias_result->fetch_assoc()) {
    $mes = $row['mes'];
    if (!isset($dias_por_mes[$mes])) {
        $dias_por_mes[$mes] = [];
    }
    $dias_por_mes[$mes][] = $row['fecha'];
}
$meses_con_registros = array_keys($dias_por_mes);
sort($meses_con_registros); 
$query = "SELECT 
            MONTH(dia) as mes,
            SUM(TIME_TO_SEC(tiempo_excedido)) as total_excedido,
            SUM(TIME_TO_SEC(tiempo_salida)) as total_salida,
            SUM(CASE WHEN (tiempo_excedido > '00:00:00' OR tiempo_salida <> '00:00:00') THEN 1 ELSE 0 END) as incumplieron,
            COUNT(*) as registros
          FROM dias_usuario
          WHERE YEAR(dia) = ?
            AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
          GROUP BY MONTH(dia)
          ORDER BY mes";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $selected_year, $id_tribunal);
$stmt->execute();
$result = $stmt->get_result();
$datos_mes = [];
for ($m = 1; $m <= 12; $m++) {
    $datos_mes[$m] = [
        'ausentes' => 0,
        'porcentaje_cumplimiento' => 100,
        'inasistencias_horas' => 0,
        'total_horas_tribunal' => 0,
        'porcentaje_inasistencia' => 0
    ];
}
while ($row = $result->fetch_assoc()) {
    $mes = $row['mes'];
    $dias_registrados = count($dias_por_mes[$mes] ?? []);
    
    if ($dias_registrados > 0) {
        
        $ausentes_potenciales = $total_usuarios * $dias_registrados;
        $ausentes_reales = $ausentes_potenciales - $row['registros'];
        
        
        $cumplieron = $row['registros'] - $row['incumplieron'];
        $porcentaje_cumplimiento = round(($cumplieron / $row['registros']) * 100, 2);
        
        
        $total_segundos = $row['total_excedido'] + $row['total_salida'];
        $inasistencias_horas = $total_segundos / 3600;
        
        
        $horas_por_usuario = ($id_tribunal == 29) ? 44 : 45;
        $semanas = $dias_registrados / 5;
        $total_horas = $total_usuarios * $horas_por_usuario * $semanas;
        
        
        $porcentaje_inasistencia = $total_horas > 0 ? round(($inasistencias_horas / $total_horas) * 100, 2) : 0;

        $datos_mes[$mes] = [
            'ausentes' => $ausentes_reales,
            'porcentaje_cumplimiento' => $porcentaje_cumplimiento,
            'inasistencias_horas' => $inasistencias_horas,
            'total_horas_tribunal' => $total_horas,
            'porcentaje_inasistencia' => $porcentaje_inasistencia
        ];
    }
}

$detalles_mes = [];
$query_detalle = "SELECT MONTH(dia) as mes, u.RUT, u.nombre_completo, SUM(TIME_TO_SEC(tiempo_excedido)) as total_tardanza
FROM dias_usuario du
JOIN usuario u ON du.RUT = u.RUT
WHERE YEAR(dia) = ? 
  AND u.id_tribunal = ?
  AND (tiempo_excedido > '00:00:00' OR tiempo_salida <> '00:00:00')
GROUP BY MONTH(dia), u.RUT";
$stmt_detalle = $conn->prepare($query_detalle);
$stmt_detalle->bind_param("ii", $selected_year, $id_tribunal);
$stmt_detalle->execute();
$result_detalle = $stmt_detalle->get_result();
while($row = $result_detalle->fetch_assoc()){
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
