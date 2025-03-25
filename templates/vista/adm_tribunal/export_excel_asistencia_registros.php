<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/verificacion.php';
verificarAcceso([1,2,3]);
$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fecha_fin    = $_GET['fecha_fin'] ?? date('Y-m-t');
$selected_tribunal = $_GET['tribunal'] ?? null;
if (!$selected_tribunal) {

    $selected_tribunal = 1;
}
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=asistencia_{$fecha_inicio}_a_{$fecha_fin}_tribunal_{$selected_tribunal}.xls");
header("Pragma: no-cache");
header("Expires: 0");
$query = "SELECT d.dia, d.hora_inicio, d.hora_fin, d.tiempo_excedido,
                 d.auth, d.teletrabajo, d.mod_por, d.RUT, u.nombre_completo 
          FROM dias_usuario d 
          JOIN usuario u ON d.RUT = u.RUT 
          WHERE d.dia BETWEEN ? AND ?
            AND u.id_tribunal = ?
          ORDER BY d.dia DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $fecha_inicio, $fecha_fin, $selected_tribunal);
$stmt->execute();
$result = $stmt->get_result();
echo "<table border='1'>";
echo "<tr>";
echo "<th>Fecha</th>";
echo "<th>RUT</th>";
echo "<th>Nombre</th>";
echo "<th>Hora de Entrada</th>";
echo "<th>Hora de Salida</th>";
echo "<th>Tiempo Excedido</th>";
echo "<th>Autorizacion</th>";
echo "<th>Teletrabajo</th>";
echo "<th>Modificado por</th>";
echo "</tr>";
while ($row = $result->fetch_assoc()) {
    $fecha = date('d/m/Y', strtotime($row['dia']));
    $hora_entrada = $row['hora_inicio'] ? date('H:i:s', strtotime($row['hora_inicio'])) : '';
    $hora_salida  = $row['hora_fin'] ? date('H:i:s', strtotime($row['hora_fin'])) : '';
    $tiempo_excedido = $row['tiempo_excedido'] ?: '00:00:00';
    $autorizacion    = $row['auth'] == 1 ? 'Si' : 'No';
    $teletrabajo     = $row['teletrabajo'] == 1 ? 'Si' : 'No';
    $mod_por         = !empty($row['mod_por']) ? $row['mod_por'] : '-';
    $nombre          = htmlspecialchars($row['nombre_completo']);
    $rut             = $row['RUT'];
    
    echo "<tr>";
    echo "<td>{$fecha}</td>";
    echo "<td>{$rut}</td>";
    echo "<td>{$nombre}</td>";
    echo "<td>{$hora_entrada}</td>";
    echo "<td>{$hora_salida}</td>";
    echo "<td>{$tiempo_excedido}</td>";
    echo "<td>{$autorizacion}</td>";
    echo "<td>{$teletrabajo}</td>";
    echo "<td>{$mod_por}</td>";
    echo "</tr>";
}
echo "</table>";
$stmt->close();
$conn->close();
exit();
?>
