<?php
include("../conexionbd.php");

$descripcion_p = $_POST["descripcion_p"];
$id_categoria = $_POST["categorias"];
$precio_compra = $_POST["precio_compra"];
$precio_venta = $_POST["precio_venta"];
$stock = $_POST['stock'];
$cant_stock_p = $_POST['cant_stock_p'];
$precio_venta = $_POST["precio_venta"];
$comentarios_p = $_POST["comentarios_p"];

$info = new SplFileInfo($_FILES['foto_producto']['name']);
$ruta_archivo = "/productos/fotos/" . $descripcion_p . '.' . $info->getExtension();
$ruta_guardar =  "./fotos/" . $descripcion_p . '.' . $info->getExtension();
move_uploaded_file($_FILES['foto_producto']['tmp_name'], $ruta_guardar);

$consulta = $con->prepare("SELECT id_producto, descripcion FROM productos WHERE descripcion =? AND id_categoria_producto=?");
$consulta->bind_param('si', $descripcion_p, $id_categoria);
if(!$consulta->execute()){
    echo "<span class='resultado-error'>Fallo la ejecucion: ('" . $consulta->errno . "') " . $consulta->error . " ❌ </span>";
    $consulta->close();
}
else{
    $id_producto_existe = "";
    $descripcion_prod = "";
     /* vincular las variables de resultados */
    $consulta->bind_result($id_producto_buscar, $descripcion_producto);
     /* obtener los valores */
     while ($consulta->fetch()) {
        $id_producto_existe = $id_producto_buscar;
        $descripcion_prod = $descripcion_producto;
    }    
    if($id_producto_existe == ""){
        $insert= $con->prepare("INSERT INTO productos(descripcion, id_categoria_producto, precio_costo, precio_venta, stock, stock_cantidad, imagen, comentarios) VALUES (?,?,?,?,?,?,?,?)");
        $insert->bind_param('sisssiss', $descripcion_p, $id_categoria, $precio_compra, $precio_venta, $stock, $cant_stock_p, $ruta_archivo, $comentarios_p);
        if (!$insert->execute()) {
            echo "<span class='resultado-error'>Fallo la ejecucion: ('" . $insert->errno . "') " . $insert->error . " ❌ </span>";
            $insert->close();
        } else {
            echo "<span class='resultado-ok'> Se inserto el Producto : " . $descripcion_p . " 👌 </span>"; 
        }
    } else{
        echo "<span class='resultado-ok'> Ya existe el Producto : " . $descripcion_prod . " </span>"; 
    }
}




?>