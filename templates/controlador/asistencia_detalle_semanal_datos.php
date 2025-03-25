<?php
session_start();
if (!isset($_GET['inicio'])) {
    die("No se ha especificado la fecha de inicio de la semana.");
}
$inicio_semana_str = $_GET['inicio'];
$id_tribunal = $_SESSION['id_tribunal'];

$inicio_semana = new DateTime($inicio_semana_str);
if ($inicio_semana->format('N') != 1) {  
    $inicio_semana->modify('last monday');
}
$fin_semana = clone $inicio_semana;
$fin_semana->modify('+6 days');

$intervalo = new DateInterval('P1D');
$periodo = new DatePeriod($inicio_semana, $intervalo, (clone $fin_semana)->modify('+1 day'));
$query_usuarios = "SELECT RUT FROM usuario 
                   WHERE id_tribunal = ? 
                   AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $id_tribunal);
$stmt_usuarios->execute();
$usuarios = $stmt_usuarios->get_result()->fetch_all(MYSQLI_ASSOC);
$total_usuarios = count($usuarios);
$query_registros = "SELECT DATE(dia) as fecha, RUT, 
                    tiempo_excedido, tiempo_salida, hora_inicio 
                    FROM dias_usuario
                    WHERE DATE(dia) BETWEEN ? AND ?
                    AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)";
$stmt_registros = $conn->prepare($query_registros);
$fecha_inicio_str = $inicio_semana->format('Y-m-d');
$fecha_fin_str = $fin_semana->format('Y-m-d');
$stmt_registros->bind_param("ssi", $fecha_inicio_str, $fecha_fin_str, $id_tribunal);
$stmt_registros->execute();
$registros = $stmt_registros->get_result()->fetch_all(MYSQLI_ASSOC);

$datos = [];
foreach ($periodo as $fecha) {
    $fecha_str = $fecha->format('Y-m-d');
    $datos[$fecha_str] = [
        'total_tardanza' => 0,
        'incumplieron' => 0,
        'cumplieron' => 0,
        'ausentes' => 0
    ];
    $usuarios_registrados = [];
    
    foreach ($registros as $registro) {
        if ($registro['fecha'] == $fecha_str) {
            $usuarios_registrados[] = $registro['RUT'];
            $datos[$fecha_str]['total_tardanza'] += strtotime($registro['tiempo_excedido']) - strtotime('00:00:00');
            
            if ($registro['tiempo_excedido'] > '00:00:00' || $registro['tiempo_salida'] != '00:00:00') {
                $datos[$fecha_str]['incumplieron']++;
            } else {
                $datos[$fecha_str]['cumplieron']++;
            }
        }
    }
    $registrados = count($usuarios_registrados);
    $datos[$fecha_str]['ausentes'] = $total_usuarios - $registrados;
    $datos[$fecha_str]['total_tardanza'] = gmdate('H:i:s', $datos[$fecha_str]['total_tardanza']);
}
?>