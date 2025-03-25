<?php
session_start();
$id_tribunal = $_SESSION['id_tribunal'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rut']) && in_array($_SESSION['id_rol'], [2, 3])) {
    $rut = $_POST['rut'];
    $check_admin_sql = "SELECT id_rol FROM usuario WHERE RUT = ?";
    $stmt_check = $conn->prepare($check_admin_sql);
    $stmt_check->bind_param("i", $rut);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result(); 
    if ($result_check->num_rows > 0) {
        $user_role = $result_check->fetch_assoc()['id_rol'];
        
        if ($user_role == 1) {
            $_SESSION['error'] = "No tiene permiso para modificar usuarios administradores";
            header("Location: ver_usuarios.php");
            exit();
        }
    }
    $stmt_check->close();
}
if ($_SESSION['id_rol'] == 2 || $_SESSION['id_rol'] == 3) {
    $sql = "SELECT rut, nombre_completo, correo, cargo, usuario.id_tribunal, usuario.id_horario, 
                   nombre_horario, hora_inicio, hora_termino, nombre_tribunal, estado, roles_adm.id_rol, nombre_rol
            FROM usuario
            JOIN horarios ON usuario.id_horario = horarios.id_horario
            JOIN tribunales ON usuario.id_tribunal = tribunales.id_tribunal
            JOIN roles_adm ON usuario.id_rol = roles_adm.id_rol
            WHERE tribunales.id_tribunal = $id_tribunal AND usuario.estado = 1";
} elseif ($_SESSION['id_rol'] == 1) {
    $sql = "SELECT rut, nombre_completo, correo, cargo, usuario.id_tribunal, usuario.id_horario, 
                   nombre_horario, hora_inicio, hora_termino, nombre_tribunal, estado, roles_adm.id_rol, nombre_rol
            FROM usuario
            JOIN horarios ON usuario.id_horario = horarios.id_horario
            JOIN roles_adm ON usuario.id_rol = roles_adm.id_rol
            JOIN tribunales ON usuario.id_tribunal = tribunales.id_tribunal
            WHERE usuario.estado = 1";
}
$result = $conn->query($sql);
$sql_tribunal = "SELECT id_tribunal, nombre_tribunal FROM tribunales WHERE id_tribunal = $id_tribunal";
$result_tribunal = $conn->query($sql_tribunal);

$sql_horarios = "SELECT id_horario, nombre_horario, hora_inicio, hora_termino FROM horarios";
$result_horarios = $conn->query($sql_horarios);
$horarios = [];
if ($result_horarios->num_rows > 0) {
    while ($row = $result_horarios->fetch_assoc()) {
        $horarios[] = $row;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['nuevo_rol'])) {
        $rut = $_POST['rut'];
        $nuevo_rol = $_POST['nuevo_rol'];
        
        if (!in_array($nuevo_rol, [3, 4])) {
            $_SESSION['error'] = "Rol no permitido";
            header("Location: ver_usuarios.php");
            exit();
        }

        $sql = "UPDATE usuario SET id_rol = ? WHERE rut = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $nuevo_rol, $rut);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Rol actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el rol: " . $stmt->error;
        }
        
        $stmt->close();
        header("Location: ver_usuarios.php");
        exit();
    }
    
    
    elseif (isset($_POST['nuevo_estado'])) {
        $rut = $_POST['rut'];
        $nuevo_estado = $_POST['nuevo_estado'];
        
        $sql = "UPDATE usuario SET estado = ? WHERE rut = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $nuevo_estado, $rut);
        
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Estado actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el estado: " . $stmt->error;
        }
        
        $stmt->close();
        header("Location: ver_usuarios.php");
        exit();
    }
    
    
    elseif (isset($_POST['rut'])) {
        $rut = $_POST['rut'];
        $nombre = $_POST['nombre'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $id_horario = $_POST['id_horario'] ?? null;
    
        
        if (!$nombre || !$correo || !$id_horario) {
            $_SESSION['error'] = "Faltan campos requeridos";
            header("Location: ver_usuarios.php");
            exit();
        }
    
        
        $sql_check_horario = "SELECT id_horario FROM horarios WHERE id_horario = ?";
        $stmt_check = $conn->prepare($sql_check_horario);
        $stmt_check->bind_param("i", $id_horario);
        $stmt_check->execute();
        
        if (!$stmt_check->get_result()->num_rows) {
            $_SESSION['error'] = "Horario no válido";
            header("Location: ver_usuarios.php");
            exit();
        }
    
        $sql_update = "UPDATE usuario SET 
                      nombre_completo = ?, 
                      correo = ?, 
                      id_horario = ?
                      WHERE rut = ?";
        
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssss", $nombre, $correo, $id_horario, $rut);
    
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario actualizado correctamente";
        } else {
            $_SESSION['error'] = "Error al actualizar el usuario: " . $stmt->error;
        }
    
        $stmt->close();
        header("Location: ver_usuarios.php");
        exit();
    }
}
?>
