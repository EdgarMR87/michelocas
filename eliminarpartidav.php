<?php
include('conexionbd.php');
$id_partida_v = $_GET['id_partida_v'];
$id_venta = $_GET['id_venta'];
$id_mesa_venta = $_GET['id_mesa_venta'];
$subtotal_partida_v = $_GET['subtotal_partida_v'];

$fecha_movimiento = date("Y-m-d H:i:s");
$tipo_movimiento = "C";
$comentarios_movimiento = "Venta a mesa";

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

$result = mysqli_query($con, "SELECT id_producto_v, cantidad_v FROM producto_venta where id_partida_v =" . $id_partida_v);
$row = mysqli_fetch_assoc($result);
$cantidad_v = $row['cantidad_v'];
$id_producto_v = $row['id_producto_v'];

$result2 = mysqli_query($con, "SELECT id_categoria_producto FROM productos where id_producto =" . $id_producto_v);
$row2 = mysqli_fetch_assoc($result2);
$id_categoria_p = $row2['id_categoria_producto'];

$result3 = mysqli_query($con, "SELECT stock_cantidad FROM productos where id_producto=" . $id_producto_v);            
$row3 = mysqli_fetch_assoc($result3);
$cantidad_disponible = $row3['stock_cantidad'];

switch ($id_categoria_p) {
    case '3':
        
        //REGISTRAMOS LA CANTIDAD DEVUELTA A LOS INVENTARIOS              
        $movimiento = $con->prepare("INSERT INTO movimientos(id_venta_compra_movimiento, id_producto_movimiento, cantidad_movimiento, 
                            fecha_movimiento, tipo_movimiento, comentarios) VALUES(?,?,?,?,?,?)");
        $movimiento->bind_param("iiisss", $id_venta, $id_producto_v, $cantidad_v, $fecha_movimiento, $tipo_movimiento, $comentarios_movimiento);
        $movimiento->execute();

        //ACTUALIZAMOS STOCK
        $actual_stock = $cantidad_disponible + $cantidad_v;
        $actualizar2 = $con->prepare("UPDATE productos SET stock_cantidad =? WHERE id_producto=?");
        $actualizar2->bind_param('ii', $actual_stock, $id_producto_v);
        $actualizar2->execute();
    break;
}

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