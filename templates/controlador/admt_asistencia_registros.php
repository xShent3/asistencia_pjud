<?php
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01');
$fecha_fin    = isset($_GET['fecha_fin'])    ? $_GET['fecha_fin']    : date('Y-m-t');
$selected_tribunal = isset($_GET['tribunal']) ? $_GET['tribunal'] : null;
$queryTribunales = "SELECT id_tribunal, nombre_tribunal FROM tribunales ORDER BY nombre_tribunal";
$result_tribunales = $conn->query($queryTribunales);
$tribunales = [];
while ($row = $result_tribunales->fetch_assoc()) {
    $tribunales[] = $row;
}

if (!$selected_tribunal && count($tribunales) > 0) {
    $selected_tribunal = $tribunales[0]['id_tribunal'];
}
$query = "SELECT d.dia, d.hora_inicio, d.hora_fin, d.tiempo_excedido, d.tiempo_salida, 
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
?>