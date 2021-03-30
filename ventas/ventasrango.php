<?php
$fechainicio = $_POST['fechainicio'];
$fechafinal = $_POST['fechafinal'];
session_start();
$categoria_usuario = $_SESSION["categoria"];



include('../conexionbd.php');
$consulta = "SELECT * FROM venta WHERE fecha_venta BETWEEN '".$fechainicio ."' AND '" . $fechafinal."'";
if ($resultado = $con->query($consulta)) {
    /* obtener el array de objetos */
    while ($fila = $resultado->fetch_row()) {
                    $id_venta = $fila[0];
                    $usuario = $fila[1];
                    $mesa = $fila[3];
                    $fecha = $fila[4];
                    $estado = $fila[5];
                    $total = $fila[6];
                    $formapago = $fila[7];     
                    $obtener_total = "SELECT SUM(subtotal_partida_v) FROM producto_venta WHERE id_venta_v = $id_venta";
                    $resultado_total = $con->query($obtener_total);
                    $importe_total = 0;
                    while($imprimir_total = $resultado_total->fetch_row()){
                        $importe_total = $imprimir_total[0];
                    }
                    $gran_total = $gran_total + $importe_total;
                    $importe_total = number_format($importe_total, 2);
        echo "<tr><td>$id_venta</td>
              <td>$usuario</td>
              <td>$mesa</td>
              <td>$fecha</td=>
              <td>$estado</td>
              <td>$total</td>                          
              <td>$formapago</td>";
              if($estado == 'pagada'){
                                switch ($categoria_usuario) {
                                    case 'administrador':
                                        echo "<td></td><td></td>";    
                                    break;
                                    case 'cajero':
                                  echo "<td></td><td></td>";          
                                    break;
                                }                       
                              } elseif($estado == 'cancelada') {
                                  echo "<td></td><td></td>";
                              }else{
                                  switch ($categoria_usuario) {
                                    case 'administrador':
                                        echo "<td>
                                                <span>
                                                    <a href='agregarproductoventa?id_mesa_venta=$mesa&id_venta=$id_venta' style='text-decoration:none;'>&#9998;</a>
                                                <span>
                                            </td>";                           
                                        echo "<td>                               
                                            <a href='cancelarventa?id_venta=$id_venta&id_mesa=$mesa' Onclick='return ConfirmarCancelar()'>&#x2612;
                                            <span class='far fa-trash-alt'></span>
                                            </a>                                
                                        </td>";
                                break;
                    }
                }
                echo "</tr>";
                }
       $gran_total = number_format($gran_total, 2);
                echo "<td coldspan='3'> Total de Ventas :  &nbsp; $  &nbsp; $gran_total</td>";
    /* liberar el conjunto de resultados */
    $resultado->close();
}
/* cerrar la conexión */
$con->close();
?>   