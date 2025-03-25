<?php
session_start();
include '../../modelo/conexion_bd.php';
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$id_tribunal = isset($_GET['tribunal']) ? $_GET['tribunal'] : $_SESSION['id_tribunal'];
$_GET['fecha'] = $fecha;
include '../../controlador/horario_diario_datos.php';
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=horario_diario_{$fecha}.xls");
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
echo "<th>Nombre</th>";
echo "<th>Horario Programado</th>";
echo "<th>Hora de Llegada</th>";
echo "<th>Hora de Salida</th>";
echo "<th>Autorización</th>";
echo "<th>Tiempo Excedido</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
while($fila = $resultado->fetch_assoc()){
    echo "<tr>";
    echo "<td>".$fila['nombre_completo']."</td>";
    echo "<td>".$fila['horario_inicio']." - ".$fila['horario_termino']."</td>";
    echo "<td>".$fila['hora_inicio']."</td>";
    echo "<td>".($fila['hora_fin'] ?? 'En curso')."</td>";
    echo "<td>".(($fila['auth'] == 1) ? 'Sí' : 'No')."</td>";
    echo "<td>".$fila['tiempo_excedido']."</td>";
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";
echo "</body>";
echo "</html>";
exit;
?>
