<?php
date_default_timezone_set('America/Santiago');


$updateQuery = "UPDATE dias_usuario du
JOIN usuario u ON du.RUT = u.RUT
JOIN horarios h ON u.id_horario = h.id_horario
SET du.hora_fin = 
    CASE 
        WHEN (WEEKDAY(du.dia) + 1 = 5 AND u.id_tribunal = 29) THEN h.hora_termino_u
        WHEN (WEEKDAY(du.dia) + 1 = 6 AND u.id_tribunal != 29) THEN h.hora_termino_u
        ELSE h.hora_termino
    END
WHERE du.hora_fin IS NULL AND du.dia < CURDATE()";
$conn->query($updateQuery);

$selectQuery = "SELECT du.*, u.id_tribunal, h.hora_inicio AS horario_inicio, 
                       h.hora_inicio_u, h.hora_termino AS horario_termino, h.hora_termino_u
                FROM dias_usuario du
                JOIN usuario u ON du.RUT = u.RUT
                JOIN horarios h ON u.id_horario = h.id_horario
                WHERE du.dia < CURDATE() 
                  AND du.hora_fin IS NOT NULL";
$resultSelect = $conn->query($selectQuery);

while ($record = $resultSelect->fetch_assoc()) {
    $dia       = $record['dia'];
    $rut       = $record['RUT'];
    $id_tribunal = $record['id_tribunal'];
    
    $dia_semana = date('N', strtotime($dia));

    if ($dia_semana == 6 && $id_tribunal != 29) {
        $hora_inicio_base = $record['hora_inicio_u'];
    } elseif ($dia_semana == 5 && $id_tribunal == 29) {
        $hora_inicio_base = $record['hora_inicio_u'];
    } else {
        $hora_inicio_base = $record['horario_inicio'];
    }

    $tiempo_excedido = "00:00:00";
    if (strtotime($record['hora_inicio']) > strtotime($hora_inicio_base)) {
        $dtRegistrada = new DateTime($record['hora_inicio']);
        $dtEsperada   = new DateTime($hora_inicio_base);
        $interval     = $dtRegistrada->diff($dtEsperada);
        $tiempo_excedido = $interval->format('%H:%I:%S');
    }

    $tiempo_salida = "00:00:00";

    $updateTimesQuery = "UPDATE dias_usuario 
                         SET tiempo_excedido = ?, tiempo_salida = ?
                         WHERE RUT = ? AND dia = ?";
    $stmtTimes = $conn->prepare($updateTimesQuery);
    $stmtTimes->bind_param("ssss", $tiempo_excedido, $tiempo_salida, $rut, $dia);
    $stmtTimes->execute();
    $stmtTimes->close();
}

$mensaje= '¿Estás seguro de que deseas añadir la asistencia?';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $message = ""; 
    $rut = $_POST['rut'];
    
    
    $auth = isset($_POST['autorizacion']) ? 1 : 0;
    
    $teletrabajo = isset($_POST['teletrabajo']) ? 1 : 0;
    
    if (is_numeric($rut)) {
        $hora_actual = date('H:i:s');
        $dia = date('Y-m-d');
        $dia_semana = date('N'); 
        
        
        $query_usuario = "SELECT u.id_horario, u.id_tribunal, 
                                 h.hora_inicio, h.hora_termino, 
                                 h.hora_inicio_u, h.hora_termino_u 
                          FROM usuario u 
                          JOIN horarios h ON u.id_horario = h.id_horario 
                          JOIN tribunales t ON u.id_tribunal = t.id_tribunal
                          WHERE u.RUT = ?";
        $stmt_usuario = $conn->prepare($query_usuario);
        $stmt_usuario->bind_param("s", $rut);
        $stmt_usuario->execute();
        $result_usuario = $stmt_usuario->get_result();

        if ($result_usuario->num_rows > 0) {
            $usuario = $result_usuario->fetch_assoc();
            $id_tribunal = $usuario['id_tribunal'];

            if ($dia_semana == 6) {  
                if ($id_tribunal == 29) {
                    
                    $message = "La CAPJ no trabaja los sábados.";
                } else {
                    
                    $usarHorasU = true;
                    $hora_inicio_usuario  = $usuario['hora_inicio_u'];
                    $hora_termino_usuario = $usuario['hora_termino_u'];
                }
            } elseif ($dia_semana == 5 && $id_tribunal == 29) { 
                $usarHorasU = true;
                $hora_inicio_usuario  = $usuario['hora_inicio_u'];
                $hora_termino_usuario = $usuario['hora_termino_u'];
            } else {
                
                $usarHorasU = false;
                $hora_inicio_usuario  = $usuario['hora_inicio'];
                $hora_termino_usuario = $usuario['hora_termino'];
            }

            if (empty($message)) {
                
                $query_check = "SELECT hora_inicio, hora_fin FROM dias_usuario WHERE RUT = ? AND dia = ?";
                $stmt_check = $conn->prepare($query_check);
                $stmt_check->bind_param("ss", $rut, $dia);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();

                if ($result_check->num_rows > 0) {
                    $registro = $result_check->fetch_assoc();
                    
                    
                    if (!is_null($registro['hora_fin'])) {
                        $message = "¡Ya has marcado tu salida hoy! No se pueden realizar más registros.";
                    } else {
                        
                        $query_update = "UPDATE dias_usuario SET hora_fin = ?, auth = ? WHERE RUT = ? AND dia = ?";
                        $stmt_update = $conn->prepare($query_update);
                        $stmt_update->bind_param("siss", $hora_actual, $auth, $rut, $dia);

                        if ($stmt_update->execute()) {
                            
                            $query_usuario2 = "SELECT hora_inicio, hora_fin FROM dias_usuario WHERE RUT = ? AND dia = ?";
                            $stmt_usuario2 = $conn->prepare($query_usuario2);
                            $stmt_usuario2->bind_param("ss", $rut, $dia);
                            $stmt_usuario2->execute();
                            $result_usuario2 = $stmt_usuario2->get_result();
                            $usuario2 = $result_usuario2->fetch_assoc();

                            
                            $hora_inicio_base  = $hora_inicio_usuario;
                            $hora_termino_base = $hora_termino_usuario;
                            
                            
                            $tiempo_excedido = "00:00:00";
                            if ($usuario2['hora_inicio'] > $hora_inicio_base) {
                                $hora_inicio_registrada = new DateTime($usuario2['hora_inicio']);
                                $hora_inicio_esperada  = new DateTime($hora_inicio_base);
                                $interval = $hora_inicio_registrada->diff($hora_inicio_esperada);
                                $tiempo_excedido = $interval->format('%H:%I:%S');
                            }

                            
                            $tiempo_salida = "00:00:00";
                            $hora_fin_registrada = new DateTime($hora_actual);
                            $hora_termino_esperada = new DateTime($hora_termino_base);
                            if ($hora_fin_registrada < $hora_termino_esperada) {
                                $interval_salida = $hora_termino_esperada->diff($hora_fin_registrada);
                                $tiempo_salida = $interval_salida->format('%H:%I:%S');
                            }

                            
                            $query_final = "UPDATE dias_usuario 
                                            SET tiempo_excedido = ?, 
                                                tiempo_salida = ? 
                                            WHERE RUT = ? AND dia = ?";
                            $stmt_final = $conn->prepare($query_final);
                            $stmt_final->bind_param("ssss", $tiempo_excedido, $tiempo_salida, $rut, $dia);
                            $stmt_final->execute();
                            $message = "Hora de salida registrada y tiempos calculados correctamente para el RUT: $rut";
                            $mensaje= 'La entrada y la salida ya han sido registradas';
                        } else {
                            $message = "Error al registrar la hora de salida. Por favor, intente nuevamente.";
                        }
                        $stmt_update->close();
                    }
                } else {
                    
                    $query_insert = "INSERT INTO dias_usuario (dia, hora_inicio, RUT, auth, teletrabajo) VALUES (?, ?, ?, ?, ?)";
                    $stmt_insert = $conn->prepare($query_insert);
                    
                    $stmt_insert->bind_param("ssiii", $dia, $hora_actual, $rut, $auth, $teletrabajo);
                    $mensaje= '¿Estás seguro de que deseas añadir la SALIDA?';
                    if ($stmt_insert->execute()) {
                        $message = "Hora de inicio registrada con éxito para el RUT: $rut";
                    } else {
                        $message = "Error al registrar la hora de inicio. Por favor, intente nuevamente.";
                    }
                    $stmt_insert->close();
                }
                $stmt_check->close();
            }
        } else {
            $message = "Usuario con el RUT $rut no encontrado.";
        }
        $stmt_usuario->close();
    } else {
        $message = "El RUT ingresado no es válido, recuerda ingresar el RUT sin puntos y DV.";
    }
}
?>
