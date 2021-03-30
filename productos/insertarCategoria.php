<?php
include("../conexionbd.php");
$descripcion = $_POST['descripcion'];
$comentarios = $_POST['comentarios'];


$info = new SplFileInfo($_FILES['foto_categoria']['name']);
$ruta_archivo = "/productos/fotos/" . $descripcion . '.' . $info->getExtension();
$ruta_guardar =  "./fotos/" . $descripcion . '.' . $info->getExtension();

move_uploaded_file($_FILES['foto_categoria']['tmp_name'], $ruta_guardar);
$stmt = $con->prepare("INSERT INTO categorias(descripcion, comentarios, fotoCategoria) VALUES (?,?,?)");
$stmt->bind_param('sss', $descripcion, $comentarios, $ruta_archivo);
if(!$stmt->execute()){
    echo "<span class='resultado-error'>Fallo la ejecucion: (" . $stmt->errno . ") " . $stmt->error . " ❌ </span>";
    $stmt->close();
}else{
    echo "<span class='resultado-ok'> Se agrego la categoria : " . $descripcion . " 👌 </span>" ;
}
$con->close();
?>