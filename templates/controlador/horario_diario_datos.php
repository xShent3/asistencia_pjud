<?php
session_start();
if (isset($_GET['fecha'])) {
    $fecha = $_GET['fecha'];
} else {
    $fecha = date('Y-m-d');
}
$id_tribunal = $_SESSION['id_tribunal'];
setlocale(LC_TIME, 'es_ES.UTF-8','es_ES','Spanish_Spain.1252');
$query = "SELECT 
            u.nombre_completo, 
            IFNULL(du.hora_inicio, 'No se ha presentado') AS hora_inicio, 
            IFNULL(du.hora_fin, 'No se ha presentado') AS hora_fin,
            h.hora_inicio AS horario_inicio,
            h.hora_termino AS horario_termino,
            du.auth,
            IFNULL(du.tiempo_excedido, '00:00:00') AS tiempo_excedido 
          FROM usuario u
          JOIN horarios h ON u.id_horario = h.id_horario
          LEFT JOIN dias_usuario du ON u.RUT = du.RUT AND DATE(du.dia) = ?
          WHERE u.id_tribunal = ?
          ORDER BY (du.hora_inicio IS NOT NULL) DESC, u.nombre_completo ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $fecha, $id_tribunal);
$stmt->execute();
$resultado = $stmt->get_result();
$timestamp = strtotime($fecha);
$nombre_dia = ucfirst(strftime("%A", $timestamp));
$sql_total_atraso = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_excedido))) AS total_atraso 
                     FROM dias_usuario 
                     WHERE DATE(dia) = ? 
                     AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ?)";
$stmt_total_atraso = $conn->prepare($sql_total_atraso);
$stmt_total_atraso->bind_param("si", $fecha, $id_tribunal);
$stmt_total_atraso->execute();
$total_atraso_result = $stmt_total_atraso->get_result()->fetch_assoc();
$total_atraso = $total_atraso_result['total_atraso'] ?? '00:00:00';
$stmt_total_atraso->close();
?>
