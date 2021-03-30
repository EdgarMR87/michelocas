<?php
include('conexionbd.php');
date_default_timezone_set('America/Mexico_City');

$id_partida_venta =  $_POST["partida"];
$estado = 'terminado';

$update = $con->prepare("UPDATE producto_venta SET estado_partida_v = ? WHERE id_partida_v = ?");
$update->bind_param('si', $estado, $id_partida_venta);
if (!$update->execute()) {
    echo "<span style='color: #ad1414'>Fallo la ejecucion: ('" . $update->errno . "') " . $update->error . "   &#x2718; </span>";
    $update->close();
} else {
    echo "<script type='text/javascript'>
            refrescar(); 
        </script>";
}
$con->close();

?>