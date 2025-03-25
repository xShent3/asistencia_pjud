<?php
include '../../modelo/conexion_bd.php';
include '../../controlador/crear_user_datos.php';
include '../../controlador/verificacion.php';
verificarAcceso([1])
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        header {
            background-color: #003366;
            color: white;
            padding: 10px 20px;
        }
        header a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin: 0 15px;
        }
        header a:hover {
            text-decoration: underline;
        }
        .image-container {
            text-align: center;
            margin-top: 20px;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        a {
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header class="d-flex justify-content-between align-items-center">
        <a href='../pagina_login.php'>Cerrar Sesión</a>
        <div class="dropdown">
        <a href="#" class="text-white fw-bold dropdown-toggle" data-bs-toggle="dropdown">Reportes</a>
        <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="../adm_tribunal/horario_diario.php">Asistencia Diaria</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_dia.php">Asistencia General por Día</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_mensual.php">Asistencia General Mensual</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_fecha.php">Asistencia General Rango</a></li>
              <li><a class="dropdown-item" href="../adm_total/adm_asistencia_registros.php">Asistencia Total de Registros</a></li>
        </ul>
    </div>
        <div>
            <a href="../adm_tribunal/ver_usuarios.php">Inicio</a>
            <a href="../adm_tribunal/buscar_usuario.php">Buscar Usuario</a>
            <a href="../cambio_contrasena.php">Cambio de contraseña</a>
        </div>
    </header>
    <div class="container-fluid mt-4">
        <div class="container mt-4">
            <h1>Crear Usuario</h1>
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                    <?php unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>
            <form class="mt-4" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre completo" required>
                </div>
                <div class="mb-3">
                    <label for="rut" class="form-label">Rut</label>
                    <input type="text" class="form-control" id="rut" name="rut" 
                        placeholder="Ej: 12345678-9" required
                        pattern="\d{7,8}-[\dKk]">
                    <div class="invalid-feedback" id="rut-error"></div>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="correo" name="correo" placeholder="Ingresa el correo electrónico" required>
                </div>
                <div class="mb-3">
                    <label for="correo" class="form-label">contraseña</label>
                    <input type="password" class="form-control" id="contraseña" name="contraseña" placeholder="Ingresa el contraseña" required>
                </div>
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol</label>
                    <select class="form-select" id="rol" name="id_rol" required>
                        <option value="" disabled selected>Seleccione un rol</option>
                        <?php
                        if ($result_roles->num_rows > 0) {
                            while ($row = $result_roles->fetch_assoc()) {
                                echo "<option value='".$row['id_rol']."'>".$row['nombre_rol']."</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay roles disponibles</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tribunal" class="form-label">Tribunal</label>
                    <select class="form-select" id="tribunal" name="id_tribunal" required>
                        <option value="" disabled selected>Seleccione un tribunal</option>
                        <?php
                        if ($result_tribunal->num_rows > 0) {
                            while ($row = $result_tribunal->fetch_assoc()) {
                                echo "<option value='".$row['id_tribunal']."'>".$row['nombre_tribunal']."</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay tribunales disponibles</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="horario" class="form-label">Horario</label>
                    <select class="form-select" id="horario" name="id_horario" required>
                        <option value="" disabled selected>Seleccione un Horario</option>
                        <?php
                        if ($result_horarios->num_rows > 0) {
                            while ($row = $result_horarios->fetch_assoc()) {
                                echo "<option value='".$row['id_horario']."'>".$row['nombre_horario']." (".$row['hora_inicio']." - ".$row['hora_termino'].")</option>";
                            }
                        } else {
                            echo "<option value='' disabled>No hay horarios disponibles</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Usuario</button>
            </form>
        </div>
    </div>

  
    <div class="modal fade" id="rutExistenteModal" tabindex="-1" aria-labelledby="rutExistenteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rutExistenteModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    El RUT ingresado ya existe en la base de datos.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['show_modal']) && $_SESSION['show_modal']): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = new bootstrap.Modal(document.getElementById('rutExistenteModal'));
            myModal.show();
            <?php unset($_SESSION['show_modal']); ?>
        });
    </script>
    <?php endif; ?>

    <script>
        document.getElementById('rut').addEventListener('input', function(e) {
            const input = e.target;
            let rut = input.value.toUpperCase();

            rut = rut.replace(/[^0-9K]/g, '');

            rut = rut.replace(/-/g, '');


            if (rut.length > 9) {
                rut = rut.substring(0, 9);
            }

            if (rut.length > 1) {
                const cuerpo = rut.slice(0, -1);
                const dv = rut.slice(-1);
                rut = `${cuerpo}-${dv}`;
            }

            input.value = rut;
            validarRUTenTiempoReal(input);
        });

        function validarRUTenTiempoReal(input) {
            const rutCompleto = input.value;
            const errorElement = document.getElementById('rut-error');
            
            if (!/^(\d{7,8})-([\dKk])$/.test(rutCompleto)) {
                errorElement.textContent = 'Formato inválido. Use: 12345678-9';
                input.classList.add('is-invalid');
                return;
            }
            
            const [cuerpo, dv] = rutCompleto.split('-');
            const dvCalculado = calcularDV(cuerpo).toUpperCase();
            
            if (dv.toUpperCase() !== dvCalculado) {
                errorElement.textContent = 'Dígito verificador incorrecto';
                input.classList.add('is-invalid');
            } else {
                errorElement.textContent = '';
                input.classList.remove('is-invalid');
            }
        }
        function calcularDV(cuerpo) {
            let suma = 0;
            let multiplicador = 2;
            for (let i = cuerpo.length - 1; i >= 0; i--) {
                suma += parseInt(cuerpo[i]) * multiplicador;
                multiplicador = multiplicador === 7 ? 2 : multiplicador + 1;
            }
            
            const resultado = 11 - (suma % 11);
            return resultado === 11 ? '0' : resultado === 10 ? 'K' : resultado.toString();
        }
        document.querySelector('form').addEventListener('submit', function(e) {
            const rutInput = document.getElementById('rut');
            validarRUTenTiempoReal(rutInput);
            
            if (rutInput.classList.contains('is-invalid')) {
                e.preventDefault();
                alert('Por favor corrija el RUT antes de enviar el formulario');
            }
        });
    </script>
</body>
</html>