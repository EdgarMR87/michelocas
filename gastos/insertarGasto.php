<?php

include('../conexionbd.php');

$num_factura = $_POST['num_factura'];
$descripcion_gasto = $_POST['descripcion_gasto'];
$importe_gasto = $_POST['importe_gasto'];
date_default_timezone_set('America/Mexico_City');
$hoy = date('Y-m-d');

$insert = $con->prepare("INSERT INTO gastos(numero_factura, fecha_factura, descripcion_gasto, total_factura) 
                        VALUES (?,?,?,?)");
$insert->bind_param('ssss', $num_factura, $hoy, $descripcion_gasto, $importe_gasto);
if (!$insert->execute()) {
    echo "<span class='resultado-error'>Fallo la ejecucion: ('" . $insert->errno . "') " . $insert->error . " ❌ </span>";
    $insert->close();
} else {
    echo "<span class='resultado-ok'> Se inserto exitosamente el Gasto : " . $descripcion_gasto . " 👌 </span>"; 
}
?>