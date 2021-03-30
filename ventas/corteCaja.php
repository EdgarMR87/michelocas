<html>
    <head>
        <link rel="stylesheet" href="/ticket.css">
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
            <?php
                include('conexionbd.php');?>
                <br> | Michelocas |
                <br><span class='negrita-16'>Corte de Caja : <?php echo $_GET['fecha_corte']; ?></span>
                <br><?php
                   date_default_timezone_set('America/Mexico_City');
                   echo "Impreso : " . date("F j, Y, g:i a"); ?></p>
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
                include('../conexionbd.php');
                $fecha_corte = $_GET['fecha_corte'];
                $consulta = "SELECT SUM(cantidad_v), p.descripcion , precio_unitario_v, SUM(subtotal_partida_v) 
                            FROM producto_venta pv 
                            LEFT JOIN productos p ON p.id_producto = pv.id_producto_v 
                            LEFT JOIN venta v ON v.id_venta = pv.id_venta_v 
                            WHERE v.estado_venta = 'pagada' AND v.fecha_venta =' $fecha_corte '
                            GROUP BY pv.id_producto_v ASC";
                if ($resultado = $con->query($consulta)) {
                    /* obtener el array de objetos */
                    while ($fila = $resultado->fetch_row()) {
                        $cantidad = $fila[0];
                        $producto = $fila[1];
                        $precioUnitario = $fila[2];
                        $SubtotalPartida = $fila[3];
                        echo "<tr>   
                                <td class='cantidad' style='text-align: center'>$cantidad</td>
                                <td class='producto' style='font-size: 12px;'>$producto</td>
                                <td class='precio' style='text-align: end'>$precioUnitario</td>
                                <td class='precio' style='text-align: end'>$SubtotalPartida</td>                            
                            </tr>";
                        $total_pagar = $total_pagar + $SubtotalPartida;
                    } /* liberar el conjunto de resultados */
                    $total_pagar = number_format($total_pagar, 2);
                    echo "<tr>
                            <td colspan='2' style='text-align: right; font-size: 18px; font-weight: bold;'> Total Venta :  </td>
                            <td colspan='2' style='text-align: center; font-size: 18px; font-weight: bold; width: 85px;'> $ $total_pagar </td>
                        <tr>";
                                    $resultado->close();
                                }            
                                /* cerrar la conexi贸n */
                                $con->close();         
                ?>
                </tbody>
            </table>
           <!-- <p class="centrado">隆GRACIAS POR SU COMPRA! 
                <br></p>-->
        </div>
    </body>
</html>