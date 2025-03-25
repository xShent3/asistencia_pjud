<?php
session_start();
if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
} else {
    die("No se ha especificado la fecha.");
}
$id_tribunal = $_SESSION['id_tribunal'];
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish');
$query = "SELECT 
            u.nombre_completo, 
            h.hora_inicio AS horario_inicio,
            h.hora_termino AS horario_fin,
            du.hora_inicio,
            du.hora_fin
          FROM usuario u
          JOIN horarios h ON u.id_horario = h.id_horario
          LEFT JOIN dias_usuario du ON u.RUT = du.RUT AND DATE(du.dia) = ?
          WHERE u.id_tribunal = ? AND u.estado = 1
          ORDER BY (du.hora_inicio IS NULL) ASC, u.nombre_completo";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $fecha, $id_tribunal);
$stmt->execute();
$resultado = $stmt->get_result();
$timestamp = strtotime($fecha);
$nombre_dia = ucfirst(strftime("%A", $timestamp));
?>
