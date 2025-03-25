<?php
$meses_es = [
    1 => 'Enero',   2 => 'Febrero', 3 => 'Marzo',    4 => 'Abril',
    5 => 'Mayo',    6 => 'Junio',   7 => 'Julio',    8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_tribunal = $_POST['tribunal'] ?? null;
    $selected_year     = $_POST['anio'] ?? date('Y');
    $selected_month    = $_POST['mes']  ?? date('m');
    header("Location: adm_asistencia_dia.php?tribunal={$selected_tribunal}&anio={$selected_year}&mes={$selected_month}");
    exit;
}

$selected_tribunal = isset($_GET['tribunal']) ? $_GET['tribunal'] : '';
$selected_year     = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$selected_month    = isset($_GET['mes'])  ? $_GET['mes']  : date('m');

$query_tribunales = "SELECT id_tribunal, nombre_tribunal FROM tribunales ORDER BY nombre_tribunal";
$result_tribunales = $conn->query($query_tribunales);

$query_usuarios = "SELECT RUT, nombre_completo FROM usuario 
                   WHERE id_tribunal = ? AND estado = 1";
$stmt_usuarios = $conn->prepare($query_usuarios);
$stmt_usuarios->bind_param("i", $selected_tribunal);
$stmt_usuarios->execute();
$usuarios_result = $stmt_usuarios->get_result();
$usuarios = $usuarios_result->fetch_all(MYSQLI_ASSOC);
$total_usuarios = count($usuarios);
$mapRUTNombre = [];
foreach ($usuarios as $u) {
    $mapRUTNombre[$u['RUT']] = $u['nombre_completo'];
}

$query_years = "SELECT DISTINCT YEAR(dia) as anio FROM dias_usuario ORDER BY anio DESC";
$result_years = $conn->query($query_years);

$query_dias_con_registros = "SELECT DISTINCT DATE(dia) as fecha
                            FROM dias_usuario
                            WHERE YEAR(dia) = ?
                              AND MONTH(dia) = ?
                              AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
                            ORDER BY fecha";
$stmt_dias = $conn->prepare($query_dias_con_registros);
$stmt_dias->bind_param("iii", $selected_year, $selected_month, $selected_tribunal);
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
  WHERE YEAR(du.dia) = ?
    AND MONTH(du.dia) = ?
    AND du.RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)
";
$stmt_registros = $conn->prepare($query_registros);
$stmt_registros->bind_param("iii", $selected_year, $selected_month, $selected_tribunal);
$stmt_registros->execute();
$registros = $stmt_registros->get_result()->fetch_all(MYSQLI_ASSOC);
$datos = [];
foreach ($dias_con_registros as $fecha_str) {
    $datos[$fecha_str] = [
        'total_tardanza'     => 0,
        'incumplieron'       => 0,
        'cumplieron'         => 0,
        'ausentes'           => 0,
        'es_fin_de_semana'   => false,
        'lista_incumplieron' => [],
        'lista_cumplieron'   => [],
        'lista_ausentes'     => []
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
                list($h, $m, $s) = explode(":", $r['tiempo_excedido']);
                $datos[$fecha_str]['total_tardanza'] += ($h * 3600) + ($m * 60) + $s;
            }
            
            if ($r['tiempo_excedido'] > '00:00:00' || $r['tiempo_salida'] != '00:00:00') {
                $datos[$fecha_str]['incumplieron']++;
                $datos[$fecha_str]['lista_incumplieron'][] = $r['nombre_completo'];
            } else {
                $datos[$fecha_str]['cumplieron']++;
                $datos[$fecha_str]['lista_cumplieron'][] = $r['nombre_completo'];
            }
        }
    }

    $lista_ausentes_rut = array_diff(array_keys($mapRUTNombre), $usuarios_registrados);
    $datos[$fecha_str]['ausentes'] = count($lista_ausentes_rut);
    $lista_nombres_ausentes = [];
    foreach ($lista_ausentes_rut as $rut) {
        $lista_nombres_ausentes[] = $mapRUTNombre[$rut];
    }
    $datos[$fecha_str]['lista_ausentes'] = $lista_nombres_ausentes;
    
    $datos[$fecha_str]['total_tardanza'] = gmdate('H:i:s', $datos[$fecha_str]['total_tardanza']);
}

$total_tardanza_mes_seg = 0;
foreach ($datos as $infoDia) {
    list($h, $m, $s) = explode(':', $infoDia['total_tardanza']);
    $total_tardanza_mes_seg += $h * 3600 + $m * 60 + $s;
}
$horas = floor($total_tardanza_mes_seg / 3600);
$minutos = floor(($total_tardanza_mes_seg % 3600) / 60);
$segundos = $total_tardanza_mes_seg % 60;
$total_tardanza_mes = sprintf('%02d:%02d:%02d', $horas, $minutos, $segundos);
?>
