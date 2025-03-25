<?php
if (!isset($conn) || $conn === null) {
    die("Error: La conexión a la base de datos no está establecida.");
}
$mensaje = '';
$usuario = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar_tribunal'])) {
    $rut_usuario = trim($_POST['rut_usuario']);
    $id_tribunal_admin = $_SESSION['id_tribunal'];
    
    $sql = "UPDATE usuario 
            SET estado = 1, id_tribunal = ?
            WHERE RUT = ? AND estado = 0";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    $stmt->bind_param('ii', $id_tribunal_admin, $rut_usuario);
    
    if ($stmt->execute()) {
        $mensaje = 'Usuario asignado correctamente';
    } else {
        $mensaje = 'Error al asignar usuario: ' . $stmt->error;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rut'])) {
    $rut = trim($_POST['rut']);
    
    $sql = "SELECT u.*, t.nombre_tribunal 
            FROM usuario u
            LEFT JOIN tribunales t ON u.id_tribunal = t.id_tribunal
            WHERE u.RUT = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    $stmt->bind_param('i', $rut);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        $mensaje = 'Usuario no encontrado';
    }
}
?>