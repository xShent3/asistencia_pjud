
let userToDelete = null;

function setUser(userName, rut) {
    document.getElementById('userName').innerText = userName;
    userToDelete = rut;
}

function deleteUser() {
    if (userToDelete) {
        window.location.href = 'ver_usuarios.php?rut=' + userToDelete;
    }
}

function populateForm(rut, name, email, cargo, id_horario) {
    document.getElementById('rut').value = rut;
    document.getElementById('nombre').value = name;
    document.getElementById('correo').value = email;
    document.getElementById('cargo').value = cargo;
    document.getElementById('id_horario').value = id_horario;
}

$(document).ready(function() {
    $('#usuariospjud').DataTable({
    });
});



