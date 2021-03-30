<?php
include('conexionbd.php');
date_default_timezone_set('America/Mexico_City');
session_start();
$categoria_usuario =   $_SESSION["categoria"];
$id_usuario_registro = $_SESSION["id_usuario"];

$id_mesa_venta = $_POST['id_mesa_venta'];
date_default_timezone_set('America/Mexico_City');
$fecha_venta = date("Y-m-d");
$hora_venta = date("H:i:s");
$estado = "abierta";
$id_mesero_venta = $_POST['id_usuario_venta'];

$estado_mesa = "ocupado";

$update = $con->prepare("UPDATE mesas SET estado=? WHERE id_mesa=?");
$update->bind_param('ss', $estado_mesa, $id_mesa_venta);
if(!$update->execute()){
    echo "<script> alert('Fallo la ejecucion : " . $update->errno . " " . $update->error . "'); </script>";
    $update->close();
} else{
    
    $insert = $con->prepare("INSERT INTO venta(id_usuario_venta, id_mesero_venta, id_mesa_venta, fecha_venta, estado_venta) VALUES (?,?,?,?,?)");
    $insert->bind_param('iiiss', $id_usuario_registro, $id_mesero_venta, $id_mesa_venta, $fecha_venta, $estado);
    if (!$insert->execute()) {
        echo "<script> alert('Fallo la ejecucion : " . $insert->errno . " " . $insert->error . "'); </script>";
        $insert->close();
    } else {  
        $id_venta = mysqli_insert_id($con);
        switch ($categoria_usuario) {
            case 'administrador':
                echo "<script> 
                alert('Se inserto correctamente la venta : ". $id_venta . "');
                window.location.href = 'agregarproductoventa?id_mesa_venta=$id_mesa_venta&id_venta=$id_venta';
                </script>"; 
            break;
            case 'cajero':
                echo "<script> 
            alert('Se inserto correctamente la venta : ". $id_venta . "');
            window.location.href = 'agregarproductoventa?id_mesa_venta=$id_mesa_venta&id_venta=$id_venta';
            </script>"; 
            }
    }
   
}  
 ?>