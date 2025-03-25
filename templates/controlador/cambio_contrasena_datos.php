<?php
session_start();
$mensaje = '';
$tipo_mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $password_actual = $_POST['password_actual'];
    $nueva_password = $_POST['nueva_password'];
    $repetir_password = $_POST['repetir_password'];
    $rut_usuario = $_SESSION['rut']; 
    $sql_verificar = "SELECT contraseña FROM usuario WHERE RUT = ?";
    $stmt_verificar = $conn->prepare($sql_verificar);
    $stmt_verificar->bind_param("i", $rut_usuario);
    $stmt_verificar->execute();
    $resultado = $stmt_verificar->get_result();
    
    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        $hash_actual = $usuario['contraseña'];
        if (password_verify($password_actual, $hash_actual)) {
            if ($nueva_password === $repetir_password) {
                $hash_nueva = password_hash($nueva_password, PASSWORD_DEFAULT);

                $sql_actualizar = "UPDATE usuario SET contraseña = ? WHERE RUT = ?";
                $stmt_actualizar = $conn->prepare($sql_actualizar);
                $stmt_actualizar->bind_param("si", $hash_nueva, $rut_usuario);
                
                if ($stmt_actualizar->execute()) {
                    $mensaje = "Contraseña actualizada correctamente";
                    $tipo_mensaje = "success";
                } else {
                    $mensaje = "Error al actualizar la contraseña: " . $conn->error;
                    $tipo_mensaje = "danger";
                }
                $stmt_actualizar->close();
            } else {
                $mensaje = "Las nuevas contraseñas no coinciden";
                $tipo_mensaje = "warning";
            }
        } else {
            $mensaje = "La contraseña actual es incorrecta";
            $tipo_mensaje = "danger";
        }
    } else {
        $mensaje = "Error al obtener información del usuario";
        $tipo_mensaje = "danger";
    }
    $stmt_verificar->close();
}
?>