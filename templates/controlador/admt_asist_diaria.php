<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? null;
    $tribunal = $_POST['tribunal'] ?? null;
    
    if (!$fecha) {
        die("No se ha especificado la fecha en POST.");
    }
    header("Location: asistencia_diaria.php?fecha={$fecha}&tribunal={$tribunal}");
    exit;
}

verificarAcceso([1,2,3]);
if (!isset($_GET['fecha'])) {
    die("No se ha especificado la fecha (GET).");
}
$fecha = $_GET['fecha'];
if (isset($_GET['tribunal'])) {
    $id_tribunal = $_GET['tribunal'];
} else {
    $id_tribunal = $_SESSION['id_tribunal'];
}
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
$sql_total_atraso = "SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(tiempo_excedido))) AS total_atraso 
                     FROM dias_usuario 
                     WHERE DATE(dia) = ? 
                     AND RUT IN (SELECT RUT FROM usuario WHERE id_tribunal = ? AND estado = 1)";
$stmt_total_atraso = $conn->prepare($sql_total_atraso);
$stmt_total_atraso->bind_param("si", $fecha, $id_tribunal);
$stmt_total_atraso->execute();
$total_atraso_result = $stmt_total_atraso->get_result()->fetch_assoc();
$total_atraso = $total_atraso_result['total_atraso'] ?? '00:00:00';
$stmt_total_atraso->close();
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish');
$timestamp = strtotime($fecha);
$nombre_dia = ucfirst(strftime("%A", $timestamp));
?>
