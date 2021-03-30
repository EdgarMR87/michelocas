<?php
include('../conexionbd.php');
$id_producto = $_GET['id_producto'];
$stmt = $con->prepare("DELETE FROM productos WHERE id_producto=?");
$stmt->bind_param('i', $id_producto);
if(!$stmt->execute()){
    echo "<span style='color: #ad1414'>Fallo la ejecucion: (" . $stmt->errno . ") " . $stmt->error . "   &#x2718; </span>";
    $stmt->close();
} else {
    echo "<script> alert('Se elimino correctamente');
                window.location.href = 'listadoProductos'
          </script>";
} 
?>