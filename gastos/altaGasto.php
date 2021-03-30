<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="/imagenes/LogoMichelocas.png"/>
    <title>Registro de Gastos</title>
</head>

<script>

  function ConfirmExit() {
        return confirm("¿Deseas cerrar Sesión?");
    }

    $(document).ready(function() {
        $("#btn-agregar").click(function(evento) {            
            $.ajax({
                type: $('#form-alta-gasto').attr('method'),
                url: "insertarGasto.php",
                dataType: 'html', 
                data: $('#form-alta-gasto').serialize(),
                success: function(data) {
                    $('#resp').html(data);
                    $('#form-alta-gasto')[0].reset();
                    setTimeout('document.location.reload()',5000);
                }
            });
            return false;
        });
    });

</script>

    <body>

    <?php
    session_start();
    if($_SESSION["autentificado"]!="SI"){
        echo "<script>
                alert('No haz iniciado sesion');
                window.location.href = 'https://michelocas.kusoftdevelopment.com/'; 
            </script>";
    }else{
    ?>

    <header>
		<div class="menu_bar">
			<a href="#" class="bt-menu"><span class="icon-list2"></span>Menu</a>
		</div>
 
		<nav>
			<ul>
				<li><a href="/usuarios/altaUsuario"><img class="icon-servicios" src="../imagenes/users.png">Usuarios</a></li>
				<li><a href="/mesas/altaMesa"><img class="icon-servicios" src="../imagenes/mesas.png">Mesas</a></li>
				<li><a href="/productos/altaProducto"><img class="icon-servicios"  src="../imagenes/productos.png">Productos</a></li>				
                <li><a href="/ventas/menuPrincipal"><img class="icon-servicios"  src="../imagenes/facturas.png">Ventas</a></li>
                <li><a href="/gastos/altaGasto"><img class="icon-servicios"  src="../imagenes/gastos.png">Gastos</a></li>
                <li class="salir"><a href="../logout.php" onclick="return ConfirmExit();"><img class="icon-salir"  src="../imagenes/salir.png">Salir</a></li>
			</ul>
		</nav>
    </header>

    <main class="listado-partidas-venta">         
        <table class="alta-usuario" id="alta-gasto">
            <form method="POST"  id="form-alta-gasto">
                <td class="arriba"><span># Factura </span></td>
                <td class="arriba"><input class="input-alta" type="text" name="num_factura" id="num_factura"></td>
                <td class="arriba"><span>Descripción Gasto </span></td>
                <td class="arriba"><input class="input-alta" type="text" name="descripcion_gasto" id="descripcion_gasto"></td>
                <td class="arriba"><span>$ Importe Gasto </span></td>
                <td class="arriba"><input class="input-alta"  type="number" step=".01" name="importe_gasto" id="importe_gasto"></td>
                <td><input type="button" value="Agregar Gasto" class="btn-agregar" id="btn-agregar"></td>
            </form>
        </table>      



        <table class="listado-tablas">
            <thead>
                <tr>
                    <th class="listado-th">Id Gasto</th>
                    <th class="listado-th"># Factura</th>
                    <th class="listado-th">Fecha Gasto</th>
                    <th class="listado-th">Descripcion Gasto</th>
                    <th class="listado-th">$ Total Gasto</th>
                </tr>
            </thead>
            <tbody>
        <?php
            include('../conexionbd.php');
            date_default_timezone_set('America/Mexico_City');
            $hoy = date('Y-m-d');
            $consulta = "SELECT * FROM gastos WHERE fecha_factura ='". $hoy ."'";
            $total_dia = "";
            if ($resultado = $con->query($consulta)) {
                /* obtener el array de objetos */
                while ($fila = $resultado->fetch_row()) {
                    $id_gastos = $fila[0];
                    $numero_factura = $fila[1];
                    $fecha_factura = $fila[2];
                    $descripcion_gasto = $fila[3];
                    $total_factura = $fila[4];
                    echo    "<tr>
                                <td>$id_gastos</td>
                                <td>$numero_factura</td>
                                <td>$fecha_factura</td>
                                <td>$descripcion_gasto</td>
                                <td>$total_factura</td>
                            </tr>";
                            $total_dia = $total_dia + $total_factura;
                }
                $total_dia = number_format($total_dia, 2, '.', ',');
                echo "
                <tr>
                    <td colspan='4'> Total de Gastos del Día</td>
                    <td>$ $total_dia</td>
                </tr>
                </tbody></table>";
                /* liberar el conjunto de resultados */
                $resultado->close();
            }
            /* cerrar la conexión */
            $con->close();
            ?>
            <div id="resp"> </div>
    </main>
</body>
    <?php
        }ob_end_flush();
    ?>
</html>