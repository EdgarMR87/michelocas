<?php
include('conexionbd.php');
$id_partida_v = $_GET['id_partida_v'];
$id_venta = $_GET['id_venta'];
$id_mesa_venta = $_GET['id_mesa_venta'];
$subtotal_partida_v = $_GET['subtotal_partida_v'];

//OBTENEMOS EL TOTAL DE UNA VENTA 
$total_venta = '';
$obtener_total_venta = "SELECT total_venta FROM venta WHERE id_venta = $id_venta";
if ($resultado_totalventa = $con->query($obtener_total_venta)) {
    /* obtener el array de objetos */    
    while ($fila = $resultado_totalventa->fetch_row()) { 
        $total_venta = $fila[0]; 
    }
}   

//RESTAMOS AL TOTAL EL IMPORTE A BORRAR
$nuevo_total = $total_venta - $subtotal_partida_v;

//HACER UN UPDATE 
$actualizar = $con->prepare("UPDATE venta SET total_venta =? WHERE id_venta=?");
$actualizar->bind_param('si', $nuevo_total, $id_venta);
$actualizar->execute();

//ELIMINAMOS LA PARTIDA SELECCIONADO
$stmt = $con->prepare("DELETE FROM producto_venta WHERE id_partida_v=?");
$stmt->bind_param('i', $id_partida_v);
if(!$stmt->execute()){
    echo "<span style='color: #ad1414'>Fallo la ejecucion: (" . $stmt->errno . ") " . $stmt->error . "   &#x2718; </span>";
    $stmt->close();
} else {
    echo "<script> alert('Se elimino correctamente la partida');
            window.location.href = 'agregarproductoventa?id_mesa_venta=$id_mesa_venta&id_venta=$id_venta'
          </script>";
}    
?>