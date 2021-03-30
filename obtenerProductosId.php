<?php
include('conexionbd.php');
$consulta = "SELECT p.id_producto, p.precio_venta, p.descripcion, p.imagen, c.id_categoria
             FROM productos p 
             LEFT JOIN categorias c ON c.id_categoria = p.id_categoria_producto             
             WHERE p.id_categoria_producto = " . $_POST['id_categoria'] . " ORDER BY id_producto ASC";

if ($resultado = $con->query($consulta)) {  
    /* obtener el array de objetos */    
    while ($fila = $resultado->fetch_row()) { 
        $id_producto = $fila[0];                    
        $precio_venta_p = $fila[1];
        $descripcion_tamano = $fila[2];
        $descripcion_impresion_p = $fila[2];
        $imagen_p = $fila[3];
        $id_categoria_p = $fila[4];

        echo "<div class='tamano-venta'>
                    <a id='$id_producto' data-name='$descripcion_impresion_p'  data-precio='$precio_venta_p' href='' Onclick='return agregarProductoVenta(this)'>
                    <img class='img-producto' src='$imagen_p'>
                    <span>$descripcion_tamano / $ $precio_venta_p</span></a>
              </div>";
    }   
    echo "<div class='categorias-venta'>
            <a id='$id_categoria_p' href='' onclick='return location.reload();'>
            <img class='img-producto' src='imagenes/Regresar.png'>
            Regresar</a>
        </div>";   
    /* liberar el conjunto de resultados */
    $resultado->close();
}
/* cerrar la conexi贸n */
$con->close();
?>
