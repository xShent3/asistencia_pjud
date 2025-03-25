<?php
session_start();
$id_tribunal = $_SESSION['id_tribunal'];


if (!isset($fecha_inicio)) {
    $fecha_inicio = date('Y-m-01');
}
if (!isset($fecha_fin)) {
    $fecha_fin = date('Y-m-t');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_inicio = $_POST['fecha_inicio'] ?? $fecha_inicio;
    $fecha_fin = $_POST['fecha_fin'] ?? $fecha_fin;
}

$query_usuarios = "SELECT RUT, nombre_completo FROM usuario WHERE id_tribunal = ? AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $id_tribunal);
$stmt_usuarios->execute();
$result_usuarios = $stmt_usuarios->get_result();
$usuarios = $result_usuarios->fetch_all(MYSQLI_ASSOC);
$total_usuarios = count($usuarios);
$mapRUTNombre = [];
foreach ($usuarios as $u) {
    $mapRUTNombre[$u['RUT']] = $u['nombre_completo'];
}
$query_dias = "SELECT DISTINCT DATE(dia) as fecha 
               FROM dias_usuario 
               WHERE dia BETWEEN ? AND ? 
                 AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
               ORDER BY fecha DESC";
$stmt_dias = $conn->prepare($query_dias);
$stmt_dias->bind_param("ssi", $fecha_inicio, $fecha_fin, $id_tribunal);
$stmt_dias->execute();
$result_dias = $stmt_dias->get_result();
$dias_con_registros = [];
while ($row = $result_dias->fetch_assoc()) {
    $dias_con_registros[] = $row['fecha'];
}

$query_registros = "SELECT DATE(dia) as fecha, RUT, tiempo_excedido, tiempo_salida, hora_inicio 
                    FROM dias_usuario 
                    WHERE dia BETWEEN ? AND ? 
                      AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)";
$stmt_registros = $conn->prepare($query_registros);
$stmt_registros->bind_param("ssi", $fecha_inicio, $fecha_fin, $id_tribunal);
$stmt_registros->execute();
$registros = $stmt_registros->get_result()->fetch_all(MYSQLI_ASSOC);
$datos = [];
$total_tardanza_range_seg = 0;

foreach ($dias_con_registros as $fecha_str) {
    $datos[$fecha_str] = [
        'total_tardanza' => '00:00:00',
        'incumplieron'   => 0,
        'cumplieron'     => 0,
        'ausentes'       => 0,
        'es_fin_de_semana' => false,
        'lista_incumplieron' => [],
        'lista_cumplieron' => [],
        'lista_ausentes' => []
    ];
    $dia_semana = date('w', strtotime($fecha_str));
    if ($dia_semana == 0 || $dia_semana == 6) {
        $datos[$fecha_str]['es_fin_de_semana'] = true;
    }
    
    $usuarios_registrados = [];
    $tardanza_seg_dia = 0; 
    
    foreach ($registros as $registro) {
        if ($registro['fecha'] == $fecha_str) {
            $usuarios_registrados[] = $registro['RUT'];
            
            if (!empty($registro['tiempo_excedido'])) {
                $parts = explode(":", $registro['tiempo_excedido']);
                $h = isset($parts[0]) ? (int)$parts[0] : 0;
                $m = isset($parts[1]) ? (int)$parts[1] : 0;
                $s = isset($parts[2]) ? (int)$parts[2] : 0;
                $segundos = ($h * 3600) + ($m * 60) + $s;
                $tardanza_seg_dia += $segundos;
            }
            
            
            if ($registro['tiempo_excedido'] > '00:00:00' || $registro['tiempo_salida'] != '00:00:00') {
                $datos[$fecha_str]['incumplieron']++;
                $datos[$fecha_str]['lista_incumplieron'][] = $mapRUTNombre[$registro['RUT']];
            } else {
                $datos[$fecha_str]['cumplieron']++;
                $datos[$fecha_str]['lista_cumplieron'][] = $mapRUTNombre[$registro['RUT']];
            }
        }
    }
    
    $lista_ausentes_rut = array_diff(array_keys($mapRUTNombre), array_unique($usuarios_registrados));
    $datos[$fecha_str]['ausentes'] = count($lista_ausentes_rut);
    foreach ($lista_ausentes_rut as $rut) {
        $datos[$fecha_str]['lista_ausentes'][] = $mapRUTNombre[$rut];
    }
    
    
    $datos[$fecha_str]['total_tardanza'] = sprintf(
        '%d:%02d:%02d',
        floor($tardanza_seg_dia / 3600),
        floor(($tardanza_seg_dia % 3600) / 60),
        $tardanza_seg_dia % 60
    );
    
    $total_tardanza_range_seg += $tardanza_seg_dia;
}

$horas = floor($total_tardanza_range_seg / 3600);
$minutos = floor(($total_tardanza_range_seg % 3600) / 60);
$segundos = $total_tardanza_range_seg % 60;
$total_tardanza_mes = sprintf('%d:%02d:%02d', $horas, $minutos, $segundos);
$total_tardanza_range = $total_tardanza_mes;
$query_t = "SELECT nombre_tribunal FROM tribunales WHERE id_tribunal = ?";
$stmt_t = $conn->prepare($query_t);
$stmt_t->bind_param("i", $id_tribunal);
$stmt_t->execute();
$nombre_tribunal = $stmt_t->get_result()->fetch_assoc()['nombre_tribunal'];
?>
