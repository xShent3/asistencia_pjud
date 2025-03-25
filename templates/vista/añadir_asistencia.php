<?php
include '../modelo/conexion_bd.php';
include '../controlador/añadir_datos_asistencia.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poder Judicial - Añadir Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../modelo/estilos.css">
    <script>
        function updateClock() {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            document.getElementById('clock').innerText = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        window.onload = updateClock;
    </script>
    <style>
        .menu-collapsed {
            width: 0;
            overflow: hidden;
            transition: width 0.3s ease;
        }

        .menu-expanded {
            width: 240px;
            transition: width 0.3s ease;
        }

        .menu-toggler {
            cursor: pointer;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .clock-container {
            font-size: 120px;
            color: white;
            font-weight: bold;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        #letra {
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid" id="asistencia">
        <div class="row">
            <div class="col-md-6 p-5" id="letra">
                <h2>Formulario de Asistencia</h2>
                <form id="asistenciaForm" method="POST" action="">
                    <div class="mb-3">
                        <label for="rut" class="form-label">RUT:</label>
                        <input type="text" class="form-control" id="rut" name="rut" placeholder="Ingrese tu RUT sin puntos y DV" required>
                    </div>

                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="autorizacion" name="autorizacion" value="1">
                        <label class="form-check-label" for="autorizacion">Autorizar este registro</label>
                    </div>

                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="teletrabajo" name="teletrabajo" value="1">
                        <label class="form-check-label" for="teletrabajo">Teletrabajo</label>
                    </div>

                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmModal">Añadir Asistencia</a>
                </form>
                <?php if (isset($message)): ?>
                    <div class="alert alert-info mt-3" role="alert">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-6 p-5">
                <div class="clock-container" id="clock"></div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Acción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo isset($mensaje) ? $mensaje : '¿Está seguro de registrar la asistencia?'; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmButton">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm"></div>
            <div class="col-sm">
                <img src="../modelo/img/poderjudicial__400x400.jpg" alt="Logo">
            </div>
            <div class="col-sm"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.getElementById('confirmButton').addEventListener('click', function() {
            document.getElementById('asistenciaForm').submit();
        });
    </script>
</body>
</html>
