<?php
include('../conexionbd.php');
$id_producto = $_POST['id_producto'];
$descripcion_p = $_POST['descripcion_p'];
$id_categoria_p = $_POST['categorias'];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$stock = $_POST['stock'];
$cant_stock_p = $_POST['cant_stock_p'];
$comentarios_p = $_POST['comentarios_p'];
$ruta_archivo = "";


$fecha_movimiento = date("Y-m-d H:i:s");
$tipo_movimiento = "A";
$comentarios_movimiento = "Ajuste desde el menu productos";
$id_venta_v = 0;

if($_POST['files'] == 'si'){
$info = new SplFileInfo($_FILES['foto_producto']['name']);
$ruta_archivo = "/productos/fotos/" . $descripcion_p . '.' . $info->getExtension();
$ruta_guardar = "./fotos/" . $descripcion_p . '.' . $info->getExtension();
if(file_exists($ruta_guardar)){
    chmod($ruta_guardar, 0755);
    unlink($ruta_guardar);
    }
move_uploaded_file($_FILES['foto_producto']['tmp_name'], $ruta_guardar);
}
else{
$ruta_archivo = $_POST['foto_producto'];
}


$update = $con->prepare("UPDATE productos SET descripcion=?, id_categoria_producto=?, precio_costo=?, precio_venta=?, stock=?, stock_cantidad=?,
                        imagen=?, comentarios=? WHERE id_producto=?");
                       
$update->bind_param('sisssissi', $descripcion_p, $id_categoria_p, $precio_compra, $precio_venta, 
                    $stock, $cant_stock_p, $ruta_archivo, $comentarios_p, $id_producto);

if (!$update->execute()) {
    echo "<span class='resultado-error'>Fallo la ejecucion: ('" . $update->errno . "') " . $update->error . " ❌ </span>";
    $update->close();
} else {
    switch ($id_categoria_p){

        case '1' || '3' || '5' || '6':
        //DESCONTAMOS LA CANTIDAD A LOS INVENTARIOS
        $movimiento = $con->prepare("INSERT INTO movimientos(id_venta_compra_movimiento, id_producto_movimiento, cantidad_movimiento, 
        fecha_movimiento, tipo_movimiento, comentarios) VALUES(?,?,?,?,?,?)");
        $movimiento->bind_param("iiisss", $id_venta_v, $id_producto, $cant_stock_p, $fecha_movimiento, $tipo_movimiento, $comentarios_movimiento);
        $movimiento->execute();

        break;

    }
    echo "<script> alert('Se actualizo conrrectamente el Producto : $descripcion_p');
    window.location.href = 'listadoProductos'
    </script>"; 
}
?>