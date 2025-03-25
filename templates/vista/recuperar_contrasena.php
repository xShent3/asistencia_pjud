<?php
include '../modelo/conexion_bd.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
function generarContrasenaAleatoria($longitud = 8) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+';
    $contrasena = '';
    $max = strlen($caracteres) - 1;
    
    for ($i = 0; $i < $longitud; $i++) {
        $contrasena .= $caracteres[random_int(0, $max)];
    }
    
    return $contrasena;
}
function enviarCorreoNuevaContrasena($correo, $nuevaContrasena, $nombreCompleto) {
    $mail = new PHPMailer(true);
    
    try {

    /*  $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vicentex.sepulvedamiller@gmail.com';
        $mail->Password   = ''; 
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8'; */


        $mail->isSMTP();
        $mail->Host       = 'mail.pjud.cl'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = '······';
        $mail->Password   = '······'; #comente esta parte ya que tiene informacion personal
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        

        $mail->setFrom('reply@asistencia.net', 'asistencia tribunal');
        $mail->addAddress($correo);
        $mail->ErrorInfo;

        $mail->isHTML(true);
        $mail->Subject = 'Nueva contraseña - Poder Judicial';
        
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2 style='color: #003366;'>Poder Judicial - Nueva Contraseña</h2>
                <p>Estimado/a {$nombreCompleto},</p>
                <p>Hemos recibido una solicitud para restablecer tu contraseña.</p>
                <p>Tu nueva contraseña es: <strong>{$nuevaContrasena}</strong></p>
                <p>Te recomendamos cambiar esta contraseña una vez que inicies sesión en el sistema.</p>
                <p>Si no solicitaste este cambio, contacta inmediatamente a soporte técnico.</p>
                <hr>
                <p style='font-size: 12px; color: #666;'>© 2025 Poder Judicial. Todos los derechos reservados.</p>
            </div>
        ";
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$error = false;
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $rut = filter_input(INPUT_POST, 'rut', FILTER_SANITIZE_NUMBER_INT);
    
    if (!empty($rut)) {

        if (isset($conexion)) {
            $conn = $conexion;
        } elseif (isset($conn)) {

        } elseif (isset($mysqli)) {
            $conn = $mysqli;
        } elseif (isset($db)) {
            $conn = $db;
        } elseif (isset($pdo)) {
            try {
                $stmt = $pdo->prepare("SELECT RUT, correo, nombre_completo FROM usuario WHERE RUT = ?");
                $stmt->execute([$rut]);
                $usuario = $stmt->fetch();
                if ($usuario) {
                    $nuevaContrasena = generarContrasenaAleatoria();
                    $hashContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                    $updateStmt = $pdo->prepare("UPDATE usuario SET contraseña = ? WHERE RUT = ?");
                    $updateStmt->execute([$hashContrasena, $rut]);
                    if (enviarCorreoNuevaContrasena($usuario['correo'], $nuevaContrasena, $usuario['nombre_completo'])) {
                        $mensaje = "Se ha enviado una nueva contraseña a tu correo electrónico registrado.";
                        $error = false;
                    } else {
                        $mensaje = "Error al enviar el correo. Por favor, intenta más tarde.";
                        $error = true;
                    }
                } else {
                    $mensaje = "El RUT ingresado no está registrado en el sistema.";
                    $error = true;
                }
            } catch (PDOException $e) {
                $mensaje = "Error en el sistema. Por favor, intenta más tarde.";
                $error = true;
            }
            goto output;
        } else {
            $mensaje = "Error de conexión a la base de datos. Contacta al administrador.";
            $error = true;
            goto output;
        }
        
        $query = "SELECT RUT, correo, nombre_completo FROM usuario WHERE RUT = ?";
        

        $stmt = $conn->prepare($query);
        
        if ($stmt) {

            $stmt->bind_param("i", $rut);
            
            $stmt->execute();
            
            $resultado = $stmt->get_result();
            
            if ($resultado->num_rows > 0) {
                $usuario = $resultado->fetch_assoc();
                $nuevaContrasena = generarContrasenaAleatoria();
                $hashContrasena = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE usuario SET contraseña = ? WHERE RUT = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("si", $hashContrasena, $rut);
                $actualizacionExitosa = $updateStmt->execute();
                $updateStmt->close();
                if ($actualizacionExitosa) {
                    if (enviarCorreoNuevaContrasena($usuario['correo'], $nuevaContrasena, $usuario['nombre_completo'])) {
                        $mensaje = "Se ha enviado una nueva contraseña a tu correo electrónico registrado.";
                        $error = false;
                    } else {
                        $mensaje = "Error al enviar el correo. Por favor, intenta más tarde.";
                        $error = true;
                    }
                } else {
                    $mensaje = "Error al actualizar la contraseña. Por favor, intenta más tarde.";
                    $error = true;
                }
            } else {
                $mensaje = "El RUT ingresado no está registrado en el sistema.";
                $error = true;
            }
            $stmt->close();
        } else {

            $mensaje = "Error en el sistema. Por favor, intenta más tarde.";
            $error = true;
        }
    } else {
        $mensaje = "Por favor, ingresa un RUT válido.";
        $error = true;
    }
}
output:
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poder Judicial - Recuperar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../modelo/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid d-flex flex-column align-items-center justify-content-center min-vh-100 bg-blue">
        <div class="text-center mb-4">
            <h1 class="text-white">Poder Judicial</h1>
            <p class="text-white-50">Recuperación de Contraseña</p>
        </div>
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="rut" class="form-label">RUT</label>
                    <input type="text" class="form-control" id="rut" name="rut" placeholder="Ingresa tu RUT (sin guion)" required>
                    <div class="form-text">Te enviaremos una nueva contraseña a tu correo registrado.</div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enviar</button>
            </form>
        </div>
        <div class="mt-3">
            <a href="pagina_login.php" class="btn btn-outline-light">Volver a Iniciar Sesión</a>
        </div>
        <div class="modal fade" id="respuestaModal" tabindex="-1" aria-labelledby="respuestaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="respuestaModalLabel"><?php echo $error ? 'Error' : 'Solicitud Procesada'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php echo $mensaje; ?>
                    </div>
                </div>
            </div>
        </div>
        <footer class="mt-auto text-center text-white-50">
            <p>© 2025 Poder Judicial. Todos los derechos reservados.</p>
        </footer>
    </div>
    <script>
        $(document).ready(function() {
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                $('#respuestaModal').modal('show');
                <?php if ($error): ?>
                setTimeout(function() {
                    $('#respuestaModal').modal('hide');
                }, 3000);
                <?php else: ?>
                setTimeout(function() {
                    $('#respuestaModal').modal('hide');
                    window.location.href = "pagina_login.php";
                }, 3000);
                <?php endif; ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>