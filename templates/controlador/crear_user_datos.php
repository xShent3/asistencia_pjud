<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rut_completo = $_POST['rut'];
    if (!preg_match('/^(\d{1,8})-([\dKk])$/', $rut_completo, $matches)) {
        die("Formato de RUT inválido");
    }
    $rut = $matches[1];
    $check_sql = "SELECT RUT FROM usuario WHERE RUT = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $rut);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $_SESSION['show_modal'] = true; 
        $_SESSION['message'] = "El RUT ya existe en el sistema";
        header("Location: crear_usuarios.php"); 
        exit();
    }
    $check_stmt->close();
    
    $nombre = $_POST['nombre'];
    $rut_completo = $_POST['rut']; 
    $correo = $_POST['correo'];
    $cargo = isset($_POST['cargo']) && !empty($_POST['cargo']) ? $_POST['cargo'] : NULL; 
    $id_rol = $_POST['id_rol'];
    $id_tribunal = $_POST['id_tribunal'];
    $creado_por = $_SESSION['rut'];
    $id_horario = $_POST['id_horario'];
    $estado = 1;
    $contraseña_plana = $_POST['contraseña'];
    $contraseña_hash = password_hash($contraseña_plana, PASSWORD_DEFAULT); 

    
    if (preg_match('/^(\d{1,8})-(\d|K)$/', $rut_completo, $matches)) {
        $rut = $matches[1]; 
        $dv = strtoupper($matches[2]); 
    }
    $sql = "INSERT INTO usuario (RUT, DV, nombre_completo, correo, cargo, estado, contraseña, creado_por, id_tribunal, id_rol, id_horario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssisiiii", $rut, $dv, $nombre, $correo, $cargo, $estado, $contraseña_hash, $creado_por, $id_tribunal, $id_rol, $id_horario);

    if ($stmt->execute()) {
        $message = "Usuario creado exitosamente.";
    } else {
        $message = "Error al crear el usuario: " . $stmt->error;
    }

    $stmt->close();
}
$sql_roles = "SELECT id_rol, nombre_rol FROM roles_adm WHERE id_rol != 3";
$result_roles = $conn->query($sql_roles);

$sql_tribunal = "SELECT id_tribunal, nombre_tribunal FROM tribunales";
$result_tribunal = $conn->query($sql_tribunal);

$sql_usuario = "SELECT RUT, nombre_completo FROM usuario";
$result_usuario = $conn->query($sql_usuario);

$sql_horarios = "SELECT id_horario, nombre_horario, hora_inicio, hora_termino FROM horarios";
$result_horarios = $conn->query($sql_horarios);

$conn->close();
?>