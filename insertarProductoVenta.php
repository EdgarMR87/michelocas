<?php
include('conexionbd.php');
$id_producto_v = $_POST['id_producto_v'];
$id_venta_v = $_POST['id_venta_v'];
$cantidad_v = $_POST['cantidad_v'];
$precio_unitario_v = $_POST['precio_unitario_v'];
$subtotal_partida_v = $cantidad_v * $precio_unitario_v;
$estado_partida_v = "pendiente";
$comentarios_v = $_POST['comentarios_v'];
$descripcion_p = $_POST['descripcion_p'];
$id_mesa_venta = $_POST['id_mesa_venta'];

$ActualizarDespues= 5;
$fecha_movimiento = date("Y-m-d H:i:s");
$tipo_movimiento = "S";
$comentarios_movimiento = "Venta a mesa";

//FUNCION PARA OBTENER EL TOTAL DE UNA VENTA Y SUMARLE EL SUBTOTAL
function obtenerTotalDeVenta($id_venta, $subtotalPartida, $conexion){  
                
    $total_venta = '';
    $obtener_total_venta = "SELECT total_venta FROM venta WHERE id_venta = $id_venta";
    if ($resultado_totalventa = $conexion->query($obtener_total_venta)) {
        /* obtener el array de objetos */    
        while ($fila = $resultado_totalventa->fetch_row()) { 
            $total_venta = $fila[0];                 
        }
    }   
    $nuevo_total = $total_venta + $subtotalPartida;
    return $nuevo_total;
}

function actualizarTotalVenta($nuevoTotal, $idVentaActualizar, $conexion){
    //HACER UN UPDATE AL TOTAL DE LA VENTA
    $actualizar = $conexion->prepare("UPDATE venta SET total_venta = ? WHERE id_venta=?");
    $actualizar->bind_param('si', $nuevoTotal, $idVentaActualizar);
    $actualizar->execute();
}

//FUNCION PARA INSERTAR NUEVO REGISTRO
function insertarNuevoRegistro($idMesa, $idProducto, $descripcion, $idVenta, $cantidad, $precioUnitario, $subtotalPartida, $estado, $comentarios, $conexion){
    //INSERTAR NUEVO REGISTRO
    $insert = $conexion->prepare("INSERT INTO producto_venta(id_producto_v, id_venta_v, cantidad_v, 
                                precio_unitario_v, subtotal_partida_v, estado_partida_v, comentarios_v) 
                                VALUES (?,?,?,?,?,?,?)");
    $insert->bind_param('iiissss', $idProducto, $idVenta, $cantidad, $precioUnitario, 
    $subtotalPartida, $estado, $comentarios);
    if (!$insert->execute()) {
        echo "<span style='color: #ad1414'>Fallo la ejecucion: ('" . $insert->errno . "') " . $insert->error . "   &#x2718; </span>";
        $insert->close();    
    } else {
        $id_partida_v = mysqli_insert_id($conexion);
        echo "<tr>
                <td>$id_partida_v</td>
                <td>$descripcion</td>
                <td>$cantidad</td>
                <td>$precioUnitario</td>
                <td>$subtotalPartida</td>
                <td>$comentarios</td>
                <td>
                    <a href='modificarpartidav?id_partida_v=$id_partida_v' style='text-decoration:none;'>✎</a>
                </td>
                <td>
                    <a href='eliminarpartidav?id_partida_v=$id_partida_v&id_venta=$idVenta&id_mesa_venta=$idMesa' onclick='return ConfirmDelete()'>☒</a>                                
                </td>
            </tr>";
}

}



$result = mysqli_query($con, "SELECT id_categoria_producto FROM productos where id_producto=" . $id_producto_v);

$row = mysqli_fetch_assoc($result);

    switch ($row['id_categoria_producto']) {
        case '1':
            
            


           
            
        break;
        case '3':
            $result = mysqli_query($con, "SELECT stock_cantidad FROM productos where id_producto=" . $id_producto_v);            
            $row = mysqli_fetch_assoc($result);
            $cantidad_disponible = $row['stock_cantidad'];
            if($cantidad_disponible < $cantidad_v){
                echo "<script>
                    alert('Solo se tienen disponibles : $cantidad_disponible');	
                </script>";
            }else{
                //DESCONTAMOS LA CANTIDAD A LOS INVENTARIOS              
                $movimiento = $con->prepare("INSERT INTO movimientos(id_venta_compra_movimiento, id_producto_movimiento, cantidad_movimiento, 
                                            fecha_movimiento, tipo_movimiento, comentarios) VALUES(?,?,?,?,?,?)");
                $movimiento->bind_param("iiisss", $id_venta_v, $id_producto_v, $cantidad_v, $fecha_movimiento, $tipo_movimiento, $comentarios_movimiento);
                $movimiento->execute();
                //ACTUALIZAMOS STOCK
                $actual_stock = $cantidad_disponible - $cantidad_v;
                $actualizar = $con->prepare("UPDATE productos SET stock_cantidad =? WHERE id_producto=?");
                $actualizar->bind_param('ii', $actual_stock, $id_producto_v);
                $actualizar->execute();
                //OBTENEMOS EL TOTAL DE LA VENTA
                $totalVenta = obtenerTotalDeVenta($id_venta_v, $subtotal_partida_v, $con);
                actualizarTotalVenta($totalVenta, $id_venta_v, $con);     
                //INSERTARMOS NUEVO REGISTRO
                insertarNuevoRegistro($id_mesa_venta, $id_producto_v, $descripcion_p, $id_venta_v, $cantidad_v, $precio_unitario_v, 
                                    $subtotal_partida_v, $estado_partida_v, $comentarios_v, $con);                
            }

            break;
        case '5':
            $result = mysqli_query($con, "SELECT stock_cantidad FROM productos where id_producto=" . $id_producto_v);            
            $row = mysqli_fetch_assoc($result);
            $cantidad_disponible = $row['stock_cantidad'];
            $cant_real = $cantidad_v * 4;
            if($cantidad_disponible < $cant_real){
                echo "<script>
                    alert('Solo se tienen disponibles : $cantidad_disponible');	
                </script>";
            }else{
           
            $movimiento = $con->prepare("INSERT INTO movimientos(id_venta_compra_movimiento, id_producto_movimiento, cantidad_movimiento, 
                                        fecha_movimiento, tipo_movimiento, comentarios) VALUES(?,?,?,?,?,?)");
            $movimiento->bind_param("iiisss", $id_venta_v, $id_producto_v, $cant_real, $fecha_movimiento, $tipo_movimiento, $comentarios_movimiento);
            $movimiento->execute();
            //ACTUALIZAMOS STOCK
            $actual_stock = $cantidad_disponible - $cant_real;
            $actualizar = $con->prepare("UPDATE productos SET stock_cantidad =? WHERE id_producto=?");
            $actualizar->bind_param('ii', $actual_stock, $id_producto_v);
            $actualizar->execute();
            //OBTENEMOS EL TOTAL DE LA VENTA
            $totalVenta = obtenerTotalDeVenta($id_venta_v, $subtotal_partida_v, $con);
            actualizarTotalVenta($totalVenta, $id_venta_v, $con);     
            //INSERTARMOS NUEVO REGISTRO
            insertarNuevoRegistro($id_mesa_venta, $id_producto_v, $descripcion_p, $id_venta_v, $cantidad_v, $precio_unitario_v, 
                                $subtotal_partida_v, $estado_partida_v, $comentarios_v, $con);
           }
            break;
        case '6':
            $result = mysqli_query($con, "SELECT stock_cantidad FROM productos where id_producto=" . $id_producto_v);            
            $row = mysqli_fetch_assoc($result);
            $cantidad_disponible = $row['stock_cantidad'];
            if($cantidad_disponible < $cantidad_v){
                echo "<script>
                    alert('Solo se tienen disponibles : $cantidad_disponible');	
                </script>";
            }else{
                //DESCONTAMOS LA CANTIDAD A LOS INVENTARIOS              
                $movimiento = $con->prepare("INSERT INTO movimientos(id_venta_compra_movimiento, id_producto_movimiento, cantidad_movimiento, 
                                            fecha_movimiento, tipo_movimiento, comentarios) VALUES(?,?,?,?,?,?)");
                $movimiento->bind_param("iiisss", $id_venta_v, $id_producto_v, $cantidad_v, $fecha_movimiento, $tipo_movimiento, $comentarios_movimiento);
                $movimiento->execute();
                //ACTUALIZAMOS STOCK
                $actual_stock = $cantidad_disponible - $cantidad_v;
                $actualizar = $con->prepare("UPDATE productos SET stock_cantidad =? WHERE id_producto=?");
                $actualizar->bind_param('ii', $actual_stock, $id_producto_v);
                $actualizar->execute();
                //OBTENEMOS EL TOTAL DE LA VENTA
                $totalVenta = obtenerTotalDeVenta($id_venta_v, $subtotal_partida_v, $con);
                actualizarTotalVenta($totalVenta, $id_venta_v, $con);     
                //INSERTARMOS NUEVO REGISTRO
                insertarNuevoRegistro($id_mesa_venta, $id_producto_v, $descripcion_p, $id_venta_v, $cantidad_v, $precio_unitario_v, 
                                    $subtotal_partida_v, $estado_partida_v, $comentarios_v, $con);                
            }
            
            break;
        default:
            //OBTENEMOS EL TOTAL DE LA VENTA
            $totalVenta = obtenerTotalDeVenta($id_venta_v, $subtotal_partida_v, $con);
            actualizarTotalVenta($totalVenta, $id_venta_v, $con);     
            //INSERTARMOS NUEVO REGISTRO
            insertarNuevoRegistro($id_mesa_venta, $id_producto_v, $descripcion_p, $id_venta_v, $cantidad_v, $precio_unitario_v, 
                    $subtotal_partida_v, $estado_partida_v, $comentarios_v, $con);                           

                
            break;
    }    









?>