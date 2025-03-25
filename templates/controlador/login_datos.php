<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rut = $_POST['username'];
    $contraseña = $_POST['password']; 
    $sql_usuario = "SELECT contraseña, id_rol, id_tribunal, nombre_completo FROM usuario WHERE RUT = ?";
    $stmt = $conn->prepare($sql_usuario);
    $stmt->bind_param("s", $rut);
    $stmt->execute();
    $result_usuario = $stmt->get_result();

    if ($result_usuario->num_rows > 0) {
        $row = $result_usuario->fetch_assoc();
        $contraseña_hash = $row['contraseña'];
        $id_rol = $row['id_rol'];
        $id_tribunal = $row['id_tribunal'];
        $nombre_completo = $row['nombre_completo'];
        if (password_verify($contraseña, $contraseña_hash)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['rut'] = $rut;
            $_SESSION['id_rol'] = $id_rol;
            $_SESSION['id_tribunal'] = $id_tribunal;  
            $_SESSION['nombre_completo'] = $nombre_completo;
            switch($id_rol) {
                case 1: 
                    $redirect_url = "/templates/vista/adm_tribunal/ver_usuarios.php";
                    break;
                case 2: 
                case 3: 
                    $redirect_url = "/templates/vista/adm_tribunal/ver_usuarios.php";
                    break;
                case 4: 
                    $redirect_url = "/templates/vista/user/ver_horario.php";
                    break;
                default:
                    $redirect_url = "/pagina_error.php";
                    break;
            }
            
            header("Location: $redirect_url");
            exit();
        } else {
            mostrarErrorLogin();
        }
    } else {
        mostrarErrorLogin();
    }
}
function mostrarErrorLogin() {
    echo "<script>
            $(document).ready(function(){
                $('#loginErrorModal').modal('show');
                setTimeout(function(){
                    $('#loginErrorModal').modal('hide');
                }, 2000);
            });
          </script>";
}
?>
