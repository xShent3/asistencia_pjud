<?php
include '../modelo/conexion_bd.php';
include '../controlador/verificacion.php';
include '../controlador/cambio_contrasena_datos.php';
verificarAcceso([1,2,3,4]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poder Judicial - Cambio de Contraseña</title>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../modelo/estilos.css">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Cambio de Contraseña</h4>
          </div>
          <div class="card-body">
            <?php if (!empty($mensaje)) : ?>
              <div class="alert alert-<?php echo $tipo_mensaje; ?>" role="alert">
                <?php echo $mensaje; ?>
              </div>
            <?php endif; ?>
            
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" id="formCambioPassword">
              <div class="mb-3">
                <label for="password_actual" class="form-label">Contraseña Actual</label>
                <input type="password" class="form-control" id="password_actual" name="password_actual" required>
              </div>
              <div class="mb-3">
                <label for="nueva_password" class="form-label">Nueva Contraseña</label>
                <input type="password" class="form-control" id="nueva_password" name="nueva_password" required>
              </div>
              <div class="mb-3">
                <label for="repetir_password" class="form-label">Repetir Nueva Contraseña</label>
                <input type="password" class="form-control" id="repetir_password" name="repetir_password" required>
                <div id="passwordHelp" class="form-text text-danger d-none">Las contraseñas no coinciden</div>
              </div>
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="btnCambiar">Cambiar Contraseña</button>
                <a href="javascript:history.back()" class="btn btn-secondary">Volver</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('formCambioPassword').addEventListener('submit', function(event) {
      const nuevaPassword = document.getElementById('nueva_password').value;
      const repetirPassword = document.getElementById('repetir_password').value;
      const passwordHelp = document.getElementById('passwordHelp');
      
      if (nuevaPassword !== repetirPassword) {
        event.preventDefault();
        passwordHelp.classList.remove('d-none');
        document.getElementById('nueva_password').value = '';
        document.getElementById('repetir_password').value = '';
      } else {
        passwordHelp.classList.add('d-none');
      }
    });
    document.getElementById('nueva_password').addEventListener('input', function() {
      document.getElementById('passwordHelp').classList.add('d-none');
    });
    
    document.getElementById('repetir_password').addEventListener('input', function() {
      document.getElementById('passwordHelp').classList.add('d-none');
    });
  </script>
</body>
</html>