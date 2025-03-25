<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/datos_usuarios.php';
include '../../controlador/verificacion.php';
verificarAcceso([1,2,3]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Poder Judicial - Ver Usuarios</title>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
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
      <?php if ($_SESSION['id_rol'] == 1) : ?>

        <a href="../adm_total/crear_usuarios.php">Crear Usuario</a>
      <?php elseif ($_SESSION['id_rol'] == 2) : ?>

        <a href="formulario_usuario.php">Crear Usuario</a>
      <?php elseif ($_SESSION['id_rol'] == 3) : ?>
        <a href="formulario_usuario.php">Crear Usuario</a> 
      <?php endif; ?>
      <a href="buscar_usuario.php">Buscar Usuario</a> 
      <a href="../cambio_contrasena.php">Cambio de contraseña</a>
    </div>
  </header>
            
  <div class="container-fluid mt-4">
  <div class="d-flex justify-content-center text-center">
    <?php
    if ($result_tribunal->num_rows > 0) {
        while ($row = $result_tribunal->fetch_assoc()) {
            echo "<h3 value='".$row['id_tribunal']."'>".$row['nombre_tribunal']."</h3>";
        }
    } else {
        echo "<h3 value='' disabled>No hay roles disponibles</h3>";
    }
    ?>
  </div>
  
<div class="row mb-3">
  <div class="col-12">
    <div class="d-flex flex-wrap align-items-center justify-content-center">
      <div class="me-4 d-flex align-items-center">
        <button type="button" class="btn btn-warning btn-sm" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 2048 2048">
            <path fill="#fffefe" d="M1848 896q42 0 78 15t64 41t42 63t16 79q0 39-15 76t-43 65l-717 717l-377 94l94-377l717-716q29-29 65-43t76-14m72 198q0-32-20-51t-52-19q-14 0-27 4t-23 15l-692 692l-34 135l135-34l692-691q21-21 21-51M256 128v1792h506l-31 128H128V0h1115l549 549v192q-37 5-66 15t-62 31V640h-512V128zm1024 91v293h293zm128 677v128H512V896zm-896 640v-128h513l-128 128zm769-384l-128 128H512v-128z"/>
          </svg>
        </button>
        <span class="ms-2">Modificar Usuario</span>
      </div>
      
      <div class="me-4 d-flex align-items-center">
        <button type="button" class="btn btn-info btn-sm" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" style="vertical-align: -0.2em;">
            <path fill="#fffefe" d="M22 3H2c-1.09.04-1.96.91-2 2v14c.04 1.09.91 1.96 2 2h20c1.09-.04 1.96-.91 2-2V5a2.074 2.074 0 0 0-2-2m0 16H2V5h20zm-8-2v-1.25c0-1.66-3.34-2.5-5-2.5s-5 .84-5 2.5V17zM9 7a2.5 2.5 0 0 0-2.5 2.5A2.5 2.5 0 0 0 9 12a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 9 7m5 0v1h6V7zm0 2v1h6V9zm0 2v1h4v-1z"/>
          </svg>
        </button>
        <span class="ms-2">Ver Detalles</span>
      </div>
      
      <div class="me-4 d-flex align-items-center">
        <button type="button" class="btn btn-danger btn-sm" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" color="white" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/>
          </svg>
        </button>
        <span class="ms-2">Usuario - Activo/Inactivo</span>
      </div>
      
      <div class="d-flex align-items-center">
        <button type="button" class="btn btn-success btn-sm" disabled>
          <svg xmlns="http://www.w3.org/2000/svg" color="white" width="26" height="26" viewBox="0 0 26 26">
            <path fill="currentColor" d="M16.563 15.9c-.159-.052-1.164-.505-.536-2.414h-.009c1.637-1.686 2.888-4.399 2.888-7.07c0-4.107-2.731-6.26-5.905-6.26c-3.176 0-5.892 2.152-5.892 6.26c0 2.682 1.244 5.406 2.891 7.088c.642 1.684-.506 2.309-.746 2.396c-3.324 1.203-7.224 3.394-7.224 5.557v.811c0 2.947 5.714 3.617 11.002 3.617c5.296 0 10.938-.67 10.938-3.617v-.811c0-2.228-3.919-4.402-7.407-5.557m-5.516 8.709c0-2.549 1.623-5.99 1.623-5.99l-1.123-.881c0-.842 1.453-1.723 1.453-1.723s1.449.895 1.449 1.723l-1.119.881s1.623 3.428 1.623 6.018c0 .406-3.906.312-3.906-.028"/>
          </svg>
        </button>
        <span class="ms-2">Subrogante/Normal</span>
      </div>
    </div>
  </div>

<div class="row">
    <div class="table-responsive" style="max-width: 90%">
        <table id="usuariospjud" class="table-responsive">
            <thead>
                <tr>
                    <th scope="col">RUT</th>
                    <th scope="col" style="padding-left:30px; padding-right:30px">Nombre completo</th>
                    <th scope="col">Horario</th>
                    <th scope="col" style="padding-left:30px; padding-right:30px">Correo electrónico</th>
                    <th scope="col" style="padding-left:30px; padding-right:30px">Tribunales</th>
                    <th scope="col">Estado</th>
                    <?php if($_SESSION['id_rol'] == 1): ?>
                    <th scope="col" style="padding-left:30px; padding-right:30px">Rol</th>
                    <?php endif; ?>
                    <th scope="col" style="padding-left:30px; padding-right:30px">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['rut']}</td>
                            <td style='padding-left:30px; padding-right:30px'>{$row['nombre_completo']}</td>
                            <td>{$row['nombre_horario']} ({$row['hora_inicio']} - {$row['hora_termino']})</td>
                            <td style='padding-left:30px; padding-right:30px'>{$row['correo']}</td>
                            <td style='padding-left:30px; padding-right:30px'>{$row['nombre_tribunal']}</td>";
                        
                        if($row['estado'] == 1){
                            echo "<td>activo</td>";
                        } elseif($row['estado'] == 0){
                            echo "<td>inactivo</td>";
                        };
                        
                        if($_SESSION['id_rol'] == 1){
                            echo "<td style='padding-left:30px; padding-right:30px'>{$row['nombre_rol']}</td>";
                        }
                        
                        echo "<td style='padding-left:30px; padding-right:30px'>
                                <div class='btn-group' role='group'>";
                        
                        if ($_SESSION['id_rol'] == 1) {
                            echo "<a href='#' class='btn btn-warning btn-sm letraChica' data-bs-toggle='modal' data-bs-target='#modifyModal' onclick=\"populateForm('{$row['rut']}', '{$row['nombre_completo']}', '{$row['correo']}', '{$row['id_horario']}')\">
                                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 2048 2048'><path fill='#fffefe' d='M1848 896q42 0 78 15t64 41t42 63t16 79q0 39-15 76t-43 65l-717 717l-377 94l94-377l717-716q29-29 65-43t76-14m72 198q0-32-20-51t-52-19q-14 0-27 4t-23 15l-692 692l-34 135l135-34l692-691q21-21 21-51M256 128v1792h506l-31 128H128V0h1115l549 549v192q-37 5-66 15t-62 31V640h-512V128zm1024 91v293h293zm128 677v128H512V896zm-896 640v-128h513l-128 128zm769-384l-128 128H512v-128z'/></svg>
                                  </a>
                                  <a href='ver_detalle.php?rut={$row['rut']}' class='btn btn-info btn-sm letraChica'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' style='vertical-align: -0.2em;'><path fill='#fffefe' d='M22 3H2c-1.09.04-1.96.91-2 2v14c.04 1.09.91 1.96 2 2h20c1.09-.04 1.96-.91 2-2V5a2.074 2.074 0 0 0-2-2m0 16H2V5h20zm-8-2v-1.25c0-1.66-3.34-2.5-5-2.5s-5 .84-5 2.5V17zM9 7a2.5 2.5 0 0 0-2.5 2.5A2.5 2.5 0 0 0 9 12a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 9 7m5 0v1h6V7zm0 2v1h6V9zm0 2v1h4v-1z'/></svg>
                                  </a>";
                            if ($row['id_rol'] != 1) {
                                echo "<a href='#' class='btn btn-danger btn-sm letraChica' data-bs-toggle='modal' data-bs-target='#statusModal' onclick=\"setUser('{$row['nombre_completo']}', '{$row['rut']}', '{$row['estado']}')\">
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' color='white' viewBox='0 0 24 24'><path fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'/></svg>
                                      </a>";
                            }
                            
                        } elseif ($_SESSION['id_rol'] == 2 || $_SESSION['id_rol'] == 3) {
                            echo "<a href='ver_detalle.php?rut={$row['rut']}' class='btn btn-info btn-sm letraChica'>
                                    <svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' style='vertical-align: -0.2em;'><path fill='#fffefe' d='M22 3H2c-1.09.04-1.96.91-2 2v14c.04 1.09.91 1.96 2 2h20c1.09-.04 1.96-.91 2-2V5a2.074 2.074 0 0 0-2-2m0 16H2V5h20zm-8-2v-1.25c0-1.66-3.34-2.5-5-2.5s-5 .84-5 2.5V17zM9 7a2.5 2.5 0 0 0-2.5 2.5A2.5 2.5 0 0 0 9 12a2.5 2.5 0 0 0 2.5-2.5A2.5 2.5 0 0 0 9 7m5 0v1h6V7zm0 2v1h6V9zm0 2v1h4v-1z'/></svg>
                                  </a>";
                            
                            if ($row['id_rol'] != 1) {
                                echo "<a href='#' class='btn btn-warning btn-sm letraChica' data-bs-toggle='modal' data-bs-target='#modifyModal' onclick=\"populateForm('{$row['rut']}', '{$row['nombre_completo']}', '{$row['correo']}', '{$row['id_horario']}')\">
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 2048 2048'><path fill='#fffefe' d='M1848 896q42 0 78 15t64 41t42 63t16 79q0 39-15 76t-43 65l-717 717l-377 94l94-377l717-716q29-29 65-43t76-14m72 198q0-32-20-51t-52-19q-14 0-27 4t-23 15l-692 692l-34 135l135-34l692-691q21-21 21-51M256 128v1792h506l-31 128H128V0h1115l549 549v192q-37 5-66 15t-62 31V640h-512V128zm1024 91v293h293zm128 677v128H512V896zm-896 640v-128h513l-128 128zm769-384l-128 128H512v-128z'/></svg>
                                      </a>
                                      <a href='#' class='btn btn-danger btn-sm letraChica' data-bs-toggle='modal' data-bs-target='#statusModal' onclick=\"setUser('{$row['nombre_completo']}', '{$row['rut']}', '{$row['estado']}')\">
                                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' color='white' viewBox='0 0 24 24'><path fill='none' stroke='currentColor' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 5h8m-8 4h5m-5 6h8m-8 4h5M3 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1zm0 10a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z'/></svg>
                                      </a>";
                            }
                        }
                        
                        if($row['id_rol'] == 3 || $row['id_rol'] == 4) {
                            echo "<a href='#' class='btn btn-success btn-sm letraChica' data-bs-toggle='modal' data-bs-target='#cambioRolModal' onclick=\"setRole('{$row['nombre_completo']}', '{$row['rut']}', '{$row['id_rol']}')\">
                                    <svg xmlns='http://www.w3.org/2000/svg' color='white' width='26' height='26' viewBox='0 0 26 26'><path fill='currentColor' d='M16.563 15.9c-.159-.052-1.164-.505-.536-2.414h-.009c1.637-1.686 2.888-4.399 2.888-7.07c0-4.107-2.731-6.26-5.905-6.26c-3.176 0-5.892 2.152-5.892 6.26c0 2.682 1.244 5.406 2.891 7.088c.642 1.684-.506 2.309-.746 2.396c-3.324 1.203-7.224 3.394-7.224 5.557v.811c0 2.947 5.714 3.617 11.002 3.617c5.296 0 10.938-.67 10.938-3.617v-.811c0-2.228-3.919-4.402-7.407-5.557m-5.516 8.709c0-2.549 1.623-5.99 1.623-5.99l-1.123-.881c0-.842 1.453-1.723 1.453-1.723s1.449.895 1.449 1.723l-1.119.881s1.623 3.428 1.623 6.018c0 .406-3.906.312-3.906-.028'/></svg>
                                  </a>";
                        }
                        
                        echo "</div>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>No hay usuarios registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>
  <div class="modal fade" id="cambioRolModal" tabindex="-1" aria-labelledby="cambioRolModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cambioRolModalLabel">Cambiar Rol</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="ver_usuarios.php">
          <div class="modal-body">
            <input type="hidden" name="rut" id="rutUsuarioRol">
            <input type="hidden" name="nuevo_rol" id="nuevoRol">
            <p id="mensajeCambioRol"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Confirmar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modifyModalLabel">Modificar Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="modifyForm" method="POST">
          <div class="mb-3">
            <label for="rut" class="form-label">RUT</label>
            <input type="text" class="form-control" id="rut" name="rut" readonly>
          </div>
          <div class="mb-3">
            <label for="nombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="correo" name="correo" required>
          </div>
          <div class="mb-3">
            <label for="id_horario" class="form-label">Horario</label>
            <select class="form-select" id="id_horario" name="id_horario" required>
              <?php
              foreach ($horarios as $horario) {
                  echo "<option value='{$horario['id_horario']}'>
                          {$horario['nombre_horario']} ({$horario['hora_inicio']} - {$horario['hora_termino']})
                        </option>";
              }
              ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </form>
      </div>
    </div>
  </div>
</div>
  <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="statusModalLabel">Cambiar Estado</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="ver_usuarios.php">
          <div class="modal-body">
            <input type="hidden" id="rutUsuario" name="rut">
            <input type="hidden" id="nuevoEstado" name="nuevo_estado">
            <p id="mensajeEstado"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Confirmar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function setRole(nombre, rut, rolActual) {
      const nuevoRol = rolActual == 3 ? 4 : 3;
      const mensaje = rolActual == 3 
        ? `¿Convertir a ${nombre} en Usuario Normal?` 
        : `¿Convertir a ${nombre} en Subrogante?`;
      
      document.getElementById('rutUsuarioRol').value = rut;
      document.getElementById('nuevoRol').value = nuevoRol;
      document.getElementById('mensajeCambioRol').textContent = mensaje;
    }
    function setUser(nombre, rut, estadoActual) {
      document.getElementById('rutUsuario').value = rut;
      document.getElementById('nuevoEstado').value = estadoActual == 1 ? 0 : 1;
      
      const mensaje = estadoActual == 1 
        ? `¿Deseas desactivar al usuario ${nombre}?` 
        : `¿Deseas activar al usuario ${nombre}?`;
      
      document.getElementById('mensajeEstado').textContent = mensaje;
    }
    $(document).ready(function() {
      $('#usuariospjud').DataTable();
    })
    function populateForm(rut, nombre, correo, id_horario) {
      document.getElementById('rut').value = rut;
      document.getElementById('nombre').value = nombre;
      document.getElementById('correo').value = correo;
      document.getElementById('id_horario').value = id_horario;
    }
  </script>
  <script src="../modelo/script.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
