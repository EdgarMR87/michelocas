<?php

include('conexionbd.php');
session_start();
$idCliente = $_SESSION['id_usuario'];
$query = "UPDATE usuarios SET estado = '0' WHERE id_usuario ="."'".$_SESSION['id_usuario']."'";
$result = mysqli_query($con, $query);
session_destroy();
mysqli_close($con);
header("Location: https://michelocas.kusoftdevelopment.com/");

?>