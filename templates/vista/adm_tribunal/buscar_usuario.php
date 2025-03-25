<?php
session_start();
require_once __DIR__ . '/../../modelo/conexion_bd.php'; 
require_once __DIR__ . '/../../controlador/verificacion.php';
require_once __DIR__ . '/../../controlador/buscar_user_datos.php';
verificarAcceso([1, 2, 3]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../modelo/estilos.css">
</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
    <a href="../pagina_login.php">Cerrar Sesión</a>
    <div class="dropdown">
          <a href="#" class="text-white fw-bold dropdown-toggle" data-bs-toggle="dropdown">Reportes</a>
          <?php if ($_SESSION['id_rol'] == 1) : ?>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_mensual.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_registros.php">Asistencia Total de Registros</a></li>
            </ul>
          <?php elseif ($_SESSION['id_rol'] == 2) : ?>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="asistencia_general_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="asistencia_general_mes.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="asistencia_general_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="asistencia_registros.php">Asistencia Total de Registros</a></li>
            </ul>
          <?php elseif ($_SESSION['id_rol'] == 3) : ?>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="asistencia_general_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="asistencia_general_mes.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="asistencia_general_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="asistencia_registros.php">Asistencia Total de Registros</a></li>
            </ul>
          <?php endif; ?>
      </div> 
    <div>
    <div>
    <?php if ($_SESSION['id_rol'] == 1) : ?>

        <a href="../adm_total/crear_usuarios.php">Crear Usuario</a>
        <?php elseif ($_SESSION['id_rol'] == 2) : ?>

        <a href="formulario_usuario.php">Crear Usuario</a>
        <?php elseif ($_SESSION['id_rol'] == 3) : ?>

            <a href="formulario_usuario.php">Crear Usuario</a>
            <?php endif; ?>
            <a href="ver_usuarios.php">Inicio</a>
            <a href="../cambio_contrasena.php">Cambio de contraseña</a>  
        </div>
    </header>
    
    <div class="container mt-4">
        <h2>Buscar Usuario</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="rut" class="form-label">RUT:</label>
                <input type="text" class="form-control" id="rut" name="rut" placeholder="Ingrese el RUT sin puntos y sin DV" required>
            </div>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <?php if (!empty($mensaje)) : ?>
            <div class="alert alert-info mt-3">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <?php if ($usuario) : ?>
            <table class="table table-bordered mt-4">
                <thead class="table-light">
                    <tr>
                        <th>RUT</th>
                        <th>Nombre Completo</th>
                        <th>Estado</th>
                        <th>Tribunal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $usuario['RUT']; ?></td>
                        <td><?php echo $usuario['nombre_completo']; ?></td>
                        <td><?php echo ($usuario['estado'] == 0 ? 'Disponible' : 'Asignado'); ?></td>
                        <td><?php echo (!empty($usuario['nombre_tribunal']) ? $usuario['nombre_tribunal'] : 'N/A'); ?></td>
                        <td>
                            <?php if ($usuario['estado'] == 0) : ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="rut_usuario" value="<?php echo $usuario['RUT']; ?>">
                                    <button type="submit" name="asignar_tribunal" class="btn btn-success">
                                        Asignar a mi tribunal
                                    </button>
                                </form>
                            <?php else : ?>
                                <?php echo "El usuario {$usuario['nombre_completo']} está trabajando en {$usuario['nombre_tribunal']}"; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
