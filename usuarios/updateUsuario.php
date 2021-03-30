<?php
include('../conexionbd.php');

$id_usuario = $_POST['id_usuario'];
$usuario = $_POST['usuario'];
$password = $_POST['pass1'];
$confirmarPassword = $_POST['pass2'];
$categoria = $_POST['categoria'];
$estado = $_POST['estado'];

if( $confirmarPassword == ""){
    $update = $con->prepare("UPDATE usuarios SET usuario=?, categoria=?, estado=? WHERE id_usuario=?");
    $update->bind_param('sssi', $usuario, $categoria, $estado, $id_usuario);

}else{
    $contrasena = password_hash($_POST['pass2'], PASSWORD_DEFAULT);
    $update = $con->prepare("UPDATE usuarios SET usuario=?, password=?, contrasena=?, categoria=?, estado=? WHERE id_usuario=?");
    $update->bind_param('sssssi', $usuario, $password, $contrasena, $categoria, $estado, $id_usuario);
}

if (!$update->execute()) {

    echo "<script> alert(Fallo la ejecucion: ('" . $update->errno . "') " . $update->error . ");
                window.location.href = 'listadoUsuario'
    </script>";
    $update->close();
} else {
    echo "<script> alert('Se actualizo conrrectamente el Usuario : $usuario');
    window.location.href = 'listadoUsuario'
    </script>";
}
?>