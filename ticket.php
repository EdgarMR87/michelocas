<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="ticket.css">
        <script>
            window.print();
        </script>
    </head>
    <body>
        <div class="ticket">
            <img src="/imagenes/LogoMichelocas.png"
                alt="Logotipo" width="200" height="150">
            <div class="margen-alto-ticket">
                <p class="centrado">
                | Michelocas | 
                <br><?php
                date_default_timezone_set('America/Mexico_City');
                echo date("F j, Y, g:i a"); ?>
                <br>
                <?php
                echo "<span style='font-weight:bold;'># Mesa : " . $_GET['num_mesa'] . " - " . " # Venta : " . $_GET['id_venta'] . "</span>";                  
                echo "<br>";
                echo "<span style='font-weight:bold;'> Mesero : " . $_GET['mesero'] . "</span>" ?>                   
                </p>
            </div>
            <table>
                <thead>
                    <tr>
                        <th class="cantidad">Cant.</th>
                        <th class="producto">Producto</th>
                        <th class="precio">P/U</th>
                        <th class="precio">Subtotal</th>
                    </tr>
                </thead>
                <tbody>

                <?php
                include('conexionbd.php');
                $id_venta = $_GET['id_venta'];
                $estado = '';
                $forma_pago= '';
                $consulta = "SELECT cantidad_v, p.descripcion, precio_unitario_v, subtotal_partida_v, v.forma_pago, v.imp_efectivo, v.imp_tarjeta, v.propina_venta, v.estado_venta
                                FROM producto_venta 
                                LEFT JOIN productos p ON p.id_producto = id_producto_v
                                LEFT JOIN venta v ON v.id_venta = id_venta_v
                                WHERE id_venta_v = $id_venta ";
                if ($resultado = $con->query($consulta)) {
                    /* obtener el array de objetos */
                    while ($fila = $resultado->fetch_row()) {
                        $cantidad = $fila[0];
                        $producto = $fila[1];
                        $precioUnitario = $fila[2];
                        $SubtotalPartida = $fila[3];
                        $forma_pago = $fila[4];
                        $imp_efectivo = $fila[5];
                        $imp_tarjeta = $fila[6];
                        $propina = $fila[7];
                        $estado = $fila[8];

                        echo "<tr>   
                                <td class='cantidad' style='text-align: center'>$cantidad</td>
                                <td class='producto'>$producto</td>
                                <td class='precio' style='text-align: end'>$precioUnitario</td>
                                <td class='precio' style='text-align: end'>$SubtotalPartida</td>                            
                            </tr>";
                        $total_pagar = $total_pagar + $SubtotalPartida;
                    } /* liberar el conjunto de resultados */
                    $total_pagar = number_format($total_pagar, 2);
                    echo "<tr>
                            <td colspan='2' style='text-align: right; font-size: 18px; font-weight: bold;'> Total a pagar :  </td>
                            <td colspan='2' style='text-align: center; font-size: 18px; font-weight: bold; width: 85px;'> $ $total_pagar </td>
                        <tr>
                       ";
                            switch($forma_pago){
                                case "Efectivo":
                                    $total_pagar;
                                    $total_pagar = number_format($total_pagar, 2);
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Importe Efectivo  : </td>
                                            <td colspan='2' class='imp-ticket'>$ $total_pagar</td>                                            
                                        </tr>";
                                       
                                break;
                                case "Tarjeta":
                                    $total_pagar;
                                    $total_pagar = number_format($total_pagar, 2);
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Forma de Pago : </td>
                                            <td colspan='2' class='imp-ticket'> $forma_pago</td>
                                        </tr>";
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Importe Tarjeta  : </td>
                                            <td colspan='2' class='imp-ticket'>$ $total_pagar</td>
                                        </tr>";
                                break;
                                case "Mixta":
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Importe Efectivo  : </td>
                                            <td colspan='2' class='imp-ticket'>$ $imp_efectivo</td>
                                        </tr>";
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Importe Tarjeta  : </td>
                                            <td colspan='2' class='imp-ticket'>$ $imp_tarjeta</td>
                                        </tr>";
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Importe Propina  : </td>
                                            <td colspan='2' class='imp-ticket'>$ $propina</td>
                                        </tr>";
                                    echo "<hr>";
                                    $total_t_p = $imp_tarjeta + $propina;
                                    $total_t_p = number_format($total_t_p, 2);
                                    echo "<tr>
                                            <td colspan='2' class='titulo-ticket'>Importe Total Tarjeta  : </td>
                                            <td colspan='2' class='imp-ticket'>$ $total_t_p</td>
                                        </tr>";
                                break;
                            }                            
                                    $resultado->close();
                                }            
                                /* cerrar la conexi贸n */
                                $con->close();         
                ?>
                </tbody>
            </table>
            <?php echo "<center><span style='font-size: 40px; font-weight:bold; text-transform: uppercase;'>" . $_GET['estado'] . "</span></center>"; ?>
            <p class="centrado">!GRACIAS POR SU COMPRA!
                <br></p>
        </div>
    </body>
</html>