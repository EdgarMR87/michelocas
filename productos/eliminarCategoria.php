<?php
include('../conexionbd.php');
$id_categoria = $_GET['id_categoria'];
$descripcion = $_GET['descripcion'];

$stmt = $con->prepare("DELETE FROM categorias WHERE id_categoria=?");
$stmt->bind_param('i', $id_categoria);
if(!$stmt->execute()){
    $noError = $stmt->errno;
    $errorstmtt = str_replace("'","",$stmt->error);  
    echo "<script> alert('Fallo la ejecucion, Error : " . $noError . " , " . $errorstmtt ."');
    window.location.href = 'listadoCategorias'
</script>";
  
    $stmt->close();
} else {
    echo "<script> alert('Se elimino la categoria " . $descripcion . " correctamente');
    window.location.href = 'listadoCategorias'
</script>";
}    
?>