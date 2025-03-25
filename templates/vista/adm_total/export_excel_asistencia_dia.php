<?php
session_start();
include '../../modelo/conexion_bd.php';
$selected_year  = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$selected_month = isset($_GET['mes'])  ? $_GET['mes']  : date('m');
$selected_tribunal = isset($_GET['tribunal']) ? $_GET['tribunal'] : $_SESSION['id_tribunal'];
$_GET['anio']  = $selected_year;
$_GET['mes']   = $selected_month;
$_GET['tribunal'] = $selected_tribunal;
include '../../controlador/admt_asistencia_dia.php';
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=asistencia_dia_{$selected_year}_{$selected_month}.xls");
echo "<html>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<style>
        table, th, td { border: 1px solid #000; border-collapse: collapse; }
        th, td { padding: 5px; }
      </style>";
echo "</head>";
echo "<body>";
echo "<table>";
echo "<thead>";
echo "<tr>";
echo "<th>Fecha</th>";
echo "<th>Tiempo Total de Tardanza</th>";
echo "<th>Incumplieron Horario</th>";
echo "<th>Ausentes</th>";
echo "<th>Asistieron</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($datos as $fecha => $valores) {
    $fecha_formateada = DateTime::createFromFormat('Y-m-d', $fecha)->format('d/m/Y');
    echo "<tr>";
    echo "<td>{$fecha_formateada}</td>";
    echo "<td>{$valores['total_tardanza']}</td>";
    echo "<td>{$valores['incumplieron']}</td>";
    echo "<td>{$valores['ausentes']}</td>";
    $asistieron = count(array_merge($valores['lista_incumplieron'], $valores['lista_cumplieron']));
    echo "<td>{$asistieron}</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</body>";
echo "</html>";
exit;
?>
