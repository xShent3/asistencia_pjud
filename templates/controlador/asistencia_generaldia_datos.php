<?php
session_start();
$meses_es = [
  1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
  5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
  9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$id_tribunal = $_SESSION['id_tribunal'];
if (!isset($selected_year)) {
    $selected_year = date('Y');
}
if (!isset($selected_month)) {
    $selected_month = date('m');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_year = $_POST['anio'] ?? $selected_year;
    $selected_month = $_POST['mes'] ?? $selected_month;
}
$query_t = "SELECT nombre_tribunal FROM tribunales WHERE id_tribunal = ?";
$stmt_t = $conn->prepare($query_t);
$stmt_t->bind_param("i", $id_tribunal);
$stmt_t->execute();
$nombre_tribunal = $stmt_t->get_result()->fetch_assoc()['nombre_tribunal'] ?? '';
$query_usuarios = "SELECT RUT, nombre_completo FROM usuario 
                   WHERE id_tribunal = ? AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $id_tribunal);
$stmt_usuarios->execute();
$usuarios_result = $stmt_usuarios->get_result();
$usuarios = $usuarios_result->fetch_all(MYSQLI_ASSOC);
$total_usuarios = count($usuarios);


$mapRUTNombre = [];
foreach ($usuarios as $u) {
    $mapRUTNombre[$u['RUT']] = $u['nombre_completo'];
}
$query_periodos = "SELECT DISTINCT YEAR(dia) AS anio 
                   FROM dias_usuario
                   ORDER BY anio DESC";
$result_periodos = $conn->query($query_periodos);
$query_dias_con_registros = "
  SELECT DISTINCT DATE(dia) as fecha 
  FROM dias_usuario
  WHERE YEAR(dia) = ?
    AND MONTH(dia) = ?
    AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
  ORDER BY fecha
";
$stmt_dias = $conn->prepare($query_dias_con_registros);
$stmt_dias->bind_param("iii", $selected_year, $selected_month, $id_tribunal);
$stmt_dias->execute();
$result_dias = $stmt_dias->get_result();
$dias_con_registros = [];
while ($row = $result_dias->fetch_assoc()) {
    $dias_con_registros[] = $row['fecha'];
}
$query_registros = "
  SELECT DATE(dia) as fecha, RUT, tiempo_excedido, tiempo_salida, hora_inicio 
  FROM dias_usuario
  WHERE YEAR(dia) = ?
    AND MONTH(dia) = ?
    AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
";
$stmt_registros = $conn->prepare($query_registros);
$stmt_registros->bind_param("iii", $selected_year, $selected_month, $id_tribunal);
$stmt_registros->execute();
$registros = $stmt_registros->get_result()->fetch_all(MYSQLI_ASSOC);
$datos = [];
$total_tardanza_mes_seg = 0; 

foreach ($dias_con_registros as $fecha_str) {
    $datos[$fecha_str] = [
        'total_tardanza'    => 0,
        'incumplieron'      => 0,
        'cumplieron'        => 0,
        'ausentes'          => 0,
        'es_fin_de_semana'  => false,
        'lista_incumplieron'=> [],
        'lista_cumplieron'  => [],
        'lista_ausentes'    => []
    ];
    
    
    $dia_semana = date('w', strtotime($fecha_str));
    if ($dia_semana == 0 || $dia_semana == 6) {
        $datos[$fecha_str]['es_fin_de_semana'] = true;
    }
    
    $usuarios_registrados = [];
    foreach ($registros as $registro) {
        if ($registro['fecha'] == $fecha_str) {
            $usuarios_registrados[] = $registro['RUT'];
            
            $tardanza_seg = 0;
            if (!empty($registro['tiempo_excedido']) && $registro['tiempo_excedido'] !== '00:00:00') {
                $parts = explode(":", $registro['tiempo_excedido']);
                $h = isset($parts[0]) ? (int)$parts[0] : 0;
                $m = isset($parts[1]) ? (int)$parts[1] : 0;
                $s = isset($parts[2]) ? (int)$parts[2] : 0;
                $tardanza_seg = ($h * 3600) + ($m * 60) + $s;
            }
            $datos[$fecha_str]['total_tardanza'] += $tardanza_seg;
            
            
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
    $lista_nombres_ausentes = [];
    foreach ($lista_ausentes_rut as $rut) {
        $lista_nombres_ausentes[] = $mapRUTNombre[$rut];
    }
    $datos[$fecha_str]['lista_ausentes'] = $lista_nombres_ausentes;
    
    
    $total_tardanza_mes_seg += $datos[$fecha_str]['total_tardanza'];
    
    
    $horas_dia = floor($datos[$fecha_str]['total_tardanza'] / 3600);
    $minutos_dia = floor(($datos[$fecha_str]['total_tardanza'] % 3600) / 60);
    $segundos_dia = $datos[$fecha_str]['total_tardanza'] % 60;
    $datos[$fecha_str]['total_tardanza'] = sprintf('%02d:%02d:%02d', $horas_dia, $minutos_dia, $segundos_dia);
}


$horas_mes = floor($total_tardanza_mes_seg / 3600);
$minutos_mes = floor(($total_tardanza_mes_seg % 3600) / 60);
$segundos_mes = $total_tardanza_mes_seg % 60;
$total_tardanza_mes = sprintf('%02d:%02d:%02d', $horas_mes, $minutos_mes, $segundos_mes);
?>