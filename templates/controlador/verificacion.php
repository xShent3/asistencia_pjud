<?php
function verificarAcceso($rolesPermitidos) {   
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header("Location: ../pagina_login.php");
        exit();
    }

    if (!in_array($_SESSION['id_rol'], $rolesPermitidos)) {
        switch ($_SESSION['id_rol']) {
            case 1:
                $redirect_url = "/templates/vista/adm_total/crear_usuarios.php";
                break;
            case 2:
            case 3:
                $redirect_url = "/templates/vista/adm_tribunal/formulario_usuario.php";
                break;
            case 4: 
                $redirect_url = "/templates/vista/user/ver_horario.php";
                break;
            default: 
                $redirect_url = "/pagina_login.php";
                break;
        }

        header("Location: $redirect_url");
        exit();
    }
}
?>