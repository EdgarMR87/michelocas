<?php
include("../conexionbd.php");
$id_categoria = $_POST['id_categoria'];
$descripcion = $_POST['descripcion'];
$comentarios = $_POST['comentarios'];

if($_POST['files'] == 'si'){
$info = new SplFileInfo($_FILES['foto_categoria']['name']);
$ruta_archivo = "/productos/fotos/" . $descripcion . '.' . $info->getExtension();
$ruta_guardar =  "./fotos/" . $descripcion . '.' . $info->getExtension();
if(file_exists($ruta_archivo)){
    chmod($ruta_archivo, 0755);
    unlink($ruta_archivo);
    }
move_uploaded_file($_FILES['foto_categoria']['tmp_name'], $ruta_guardar);
}
else{
$ruta_guardar = $_POST['foto_categoria'];
}

$update = $con->prepare("UPDATE categorias SET descripcion=?, comentarios=?, fotoCategoria=? WHERE id_categoria=?");
$update->bind_param('sssi', $descripcion, $comentarios, $ruta_guardar, $id_categoria);

if (!$update->execute()) {
    echo "<span class='resultado-error'>Fallo la ejecucion: ('" . $update->errno . "') " . $update->error . " ❌ </span>";
    $update->close();
} else {
    echo "<script> alert('Se actualizo conrrectamente la Categoria : $descripcion');
    window.location.href = 'listadoCategorias'
    </script>";
}

?>