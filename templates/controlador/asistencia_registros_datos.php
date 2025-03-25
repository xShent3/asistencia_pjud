<?php
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin    = isset($_GET['fecha_fin'])    ? $_GET['fecha_fin']    : date('Y-m-t');
$query = "SELECT d.dia, d.hora_inicio, d.hora_fin, d.tiempo_excedido, d.tiempo_salida, 
                 d.auth, d.teletrabajo, d.mod_por, d.RUT, u.nombre_completo 
          FROM dias_usuario d 
          JOIN usuario u ON d.RUT = u.RUT 
          WHERE d.dia BETWEEN ? AND ?
          ORDER BY d.dia DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
$stmt->execute();
$result = $stmt->get_result();
?>