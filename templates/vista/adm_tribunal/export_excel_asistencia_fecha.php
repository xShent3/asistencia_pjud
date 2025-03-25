<?php
session_start();
include '../../modelo/conexion_bd.php';
if (isset($_GET['fecha_inicio'])) {
    $fecha_inicio = $_GET['fecha_inicio'];
}
if (isset($_GET['fecha_fin'])) {
    $fecha_fin = $_GET['fecha_fin'];
}
$selected_year  = isset($_GET['anio']) ? $_GET['anio'] : date('Y');
$selected_month = isset($_GET['mes']) ? $_GET['mes'] : date('m');
$id_tribunal    = isset($_GET['tribunal']) ? $_GET['tribunal'] : $_SESSION['id_tribunal'];
include '../../controlador/asistencia_fecha_datos.php';
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=asistencia_fecha_{$fecha_inicio}_{$fecha_fin}.xls");
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
echo "<th>Cumplieron Horario</th>";
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
    echo "<td>{$valores['cumplieron']}</td>";
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
