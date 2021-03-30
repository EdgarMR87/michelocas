<?php
include('conexionbd.php');
date_default_timezone_set('America/Mexico_City');
session_start();
$categoria_usuario =   $_SESSION["categoria"];
$forma_de_pago = $_POST['forma_de_pago'];
$imp_tarjeta = $_POST['imp_tarjeta'];
$imp_efectivo = $_POST['imp_efectivo'];
$propina = $_POST['propina'];
$mesero = $_POST['mesero'];


$id_venta = $_POST['id_venta'];
$id_mesa_venta = $_POST['id_mesa_venta'];
$totalapagar = $_POST['totalapagar'];
$estado = 'pagada';
$estadomesa = 'libre';

$update = $con->prepare("UPDATE venta SET estado_venta=?, forma_pago=?, imp_efectivo=?, imp_tarjeta=?, propina_venta=? WHERE id_venta =?");
$update->bind_param('ssdddi', $estado, $forma_de_pago, $imp_efectivo, $imp_tarjeta, $propina, $id_venta);
$update2 = $con->prepare("UPDATE mesas SET estado = ? WHERE num_mesa=?");
$update2->bind_param('si', $estadomesa, $id_mesa_venta);
if (!$update->execute()) {
    echo "<span style='color: #ad1414'>Fallo la ejecucion: ('" . $update->errno . "') " . $update->error . "   &#x2718; </span>";
    $update->close();
} else {
    if (!$update2->execute()) {
        echo "<span style='color: #ad1414'>Fallo la ejecucion: ('" . $update->errno . "') " . $update->error . "   &#x2718; </span>";
        $update2->close();
    }
    else
    {
        switch ($categoria_usuario) {
            case 'administrador':
                echo"<script>window.open('ticket?id_venta=$id_venta&num_mesa=$id_mesa_venta&estado=pagada&mesero=$mesero','Ticket de Venta','width=350, height=600');</script>";
                echo "<script> alert('Pago efectuado');
                
            window.location.href = '/ventas/menuPrincipal'
            </script>"; 
            break;
            case 'cajero':

                echo "<script> alert('Pago efectuado');
                window.open('ticket?id_venta=$id_venta&num_mesa=$id_mesa_venta&estado=pagada&mesero=$mesero','Ticket de Venta','width=350, height=600');
                window.location.href = 'menuPrincipalCajero'
                </script>"; 
                break;
            }
    }
}

?>
