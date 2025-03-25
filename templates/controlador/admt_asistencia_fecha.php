<?php
$meses_es = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_tribunal = $_POST['tribunal'] ?? null;
    $fecha_inicio = $_POST['fecha_inicio'] ?? date('Y-m-d');
    $fecha_fin    = $_POST['fecha_fin']    ?? date('Y-m-d');
    header("Location: adm_asistencia_fecha.php?tribunal={$selected_tribunal}&fecha_inicio={$fecha_inicio}&fecha_fin={$fecha_fin}");
    exit;
}
$query_tribunales = "SELECT id_tribunal, nombre_tribunal FROM tribunales ORDER BY nombre_tribunal";
$result_tribunales = $conn->query($query_tribunales);
$selected_tribunal = $_GET['tribunal'] ?? null;
$fecha_inicio      = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fecha_fin         = $_GET['fecha_fin']    ?? date('Y-m-t');
if (!$selected_tribunal && $result_tribunales->num_rows > 0) {
    $first = $result_tribunales->fetch_assoc();
    $selected_tribunal = $first['id_tribunal'];
    $result_tribunales->data_seek(0);
}
$query_usuarios = "SELECT RUT, nombre_completo FROM usuario 
                   WHERE id_tribunal = ? AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $selected_tribunal);
$stmt_usuarios->execute();
$usuarios = $stmt_usuarios->get_result()->fetch_all(MYSQLI_ASSOC);
$total_usuarios = count($usuarios);
$mapRUTNombre = [];
foreach ($usuarios as $u) {
    $mapRUTNombre[$u['RUT']] = $u['nombre_completo'];
}
$query_dias_con_registros = "
    SELECT DISTINCT DATE(dia) as fecha 
    FROM dias_usuario
    WHERE dia BETWEEN ? AND ?
      AND RUT IN (
          SELECT RUT FROM usuario 
          WHERE id_tribunal = ?
      )
    ORDER BY fecha DESC
";
$stmt_dias = $conn->prepare($query_dias_con_registros);
$stmt_dias->bind_param("ssi", $fecha_inicio, $fecha_fin, $selected_tribunal);
$stmt_dias->execute();
$result_dias = $stmt_dias->get_result();

$dias_con_registros = [];
while ($row = $result_dias->fetch_assoc()) {
    $dias_con_registros[] = $row['fecha'];
}
$query_registros = "
    SELECT DATE(du.dia) as fecha,
           du.RUT,
           du.tiempo_excedido,
           du.tiempo_salida,
           du.hora_inicio,
           u.nombre_completo
    FROM dias_usuario du
    JOIN usuario u ON du.RUT = u.RUT
    WHERE du.dia BETWEEN ? AND ?
      AND du.RUT IN (
          SELECT RUT FROM usuario
          WHERE id_tribunal = ?
      )
";
$stmt_registros = $conn->prepare($query_registros);
$stmt_registros->bind_param("ssi", $fecha_inicio, $fecha_fin, $selected_tribunal);
$stmt_registros->execute();
$registros = $stmt_registros->get_result()->fetch_all(MYSQLI_ASSOC);
$total_tardanza_periodo_seg = 0;
$datos = [];

foreach ($dias_con_registros as $fecha_str) {
    $daily_tardanza_seg = 0;
    
    $datos[$fecha_str] = [
        'total_tardanza'    => '00:00:00',
        'incumplieron'      => 0,
        'ausentes'          => 0,
        'es_fin_de_semana'  => false,
        'lista_incumplieron'=> [],
        'lista_ausentes'    => [],
        'lista_cumplieron'  => []
    ];
    $dia_semana = date('w', strtotime($fecha_str));
    if ($dia_semana == 0 || $dia_semana == 6) {
        $datos[$fecha_str]['es_fin_de_semana'] = true;
    }
    
    $usuarios_registrados = [];
    foreach ($registros as $r) {
        if ($r['fecha'] == $fecha_str) {
            $usuarios_registrados[] = $r['RUT'];
            if (!empty($r['tiempo_excedido']) && $r['tiempo_excedido'] !== '00:00:00') {
                list($h, $m, $s) = sscanf($r['tiempo_excedido'], "%d:%d:%d");
                $daily_tardanza_seg += ($h * 3600) + ($m * 60) + $s;
            }
            if ($r['tiempo_excedido'] > '00:00:00' || $r['tiempo_salida'] != '00:00:00') {
                $datos[$fecha_str]['incumplieron']++;
                $datos[$fecha_str]['lista_incumplieron'][] = $r['nombre_completo'];
            } else {
                $datos[$fecha_str]['lista_cumplieron'][] = $r['nombre_completo'];
            }
        }
    }
    $lista_ausentes_rut = array_diff(array_keys($mapRUTNombre), $usuarios_registrados);
    $datos[$fecha_str]['ausentes'] = count($lista_ausentes_rut);
    foreach ($lista_ausentes_rut as $rut_ausente) {
        $datos[$fecha_str]['lista_ausentes'][] = $mapRUTNombre[$rut_ausente];
    }
    $datos[$fecha_str]['total_tardanza'] = sprintf(
        '%d:%02d:%02d',
        floor($daily_tardanza_seg / 3600),
        floor(($daily_tardanza_seg % 3600) / 60),
        $daily_tardanza_seg % 60
    );
    $total_tardanza_periodo_seg += $daily_tardanza_seg;
}

$total_tardanza_periodo = sprintf(
    '%d:%02d:%02d',
    floor($total_tardanza_periodo_seg / 3600),
    floor(($total_tardanza_periodo_seg % 3600) / 60),
    $total_tardanza_periodo_seg % 60
);
?>
