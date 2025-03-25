<?php
include '../modelo/conexion_bd.php';
include '../controlador/login_datos.php';
require_once '../controlador/logout.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poder Judicial - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../modelo/estilos.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container-fluid d-flex flex-column align-items-center justify-content-center min-vh-100 bg-blue">
        <div class="text-center mb-4">
            <h1 class="text-white">Poder Judicial</h1>
            <p class="text-white-50">Accede a tu cuenta</p>
        </div>
        <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px;">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">RUT</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu RUT (sin guion)" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
            </form>
        </div>

        <div class="modal fade" id="loginErrorModal" tabindex="-1" aria-labelledby="loginErrorModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginErrorModalLabel">Error de Login</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Credenciales incorrectas. Inténtalo de nuevo.
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <a href="recuperar_contrasena.php" class="btn btn-outline-light">Recuperar contraseña</a>
        </div>
        
        <footer class="mt-auto text-center text-white-50">
            <p>© 2025 Poder Judicial. Todos los derechos reservados.</p>
        </footer>
    </div>
    <script>
        $(document).ready(function() {
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && $result->num_rows == 0) { ?>
                $('#loginErrorModal').modal('show');
                setTimeout(function() {
                    $('#loginErrorModal').modal('hide');
                }, 2000);
            <?php } ?>
        });
    </script>
</body>
</html>
