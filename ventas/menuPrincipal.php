<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="../imagenes/logoMichelocas.ico" type="image/x-icon">
    <title>Menu Principal</title>
</head>
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

    <main>
        <div class="wrapper">
            <div class="btn-submenu">  
                <a href="listadoventas">  
                <img class="img-submenu" src="../imagenes/nuevo.png" alt="">
                <div class="a-submenu">Ventas Del Dia</div>
              </a>
            </div>
            <div class="btn-submenu">
                <a href="listadoMenu">
                  <img class="img-submenu" src="../imagenes/listado.png" alt="">
                  <div class="a-submenu">Buscar Ventas</div>
                </a>
            </div>
        </div>
    </main>
    
    <main class="listado-partidas-venta"> 
        <?php
            include('../conexionbd.php');
            $consulta = "SELECT * FROM mesas";
            if ($resultado = $con->query($consulta)) {
                /* obtener el array de objetos */
                while ($fila = $resultado->fetch_row()) {
                    $id_mesa = $fila[0];
                    $numero_mesa = $fila[1];
                    $estado = $fila[3];
                    if($estado == 'libre'){
                        echo "<div class='mesa $estado'>
                                <p><a id='$numero_mesa' href='../abrirVentaMesa?id_mesa=$id_mesa' Onclick='return confirmarAbriMesa(this)'>Mesa : $numero_mesa </a><p>
                                <p>$</p>
                                </div>";
                    } elseif($estado == 'ocupado'){
                        $obtenernumventa = "SELECT id_venta, total_venta FROM venta WHERE id_mesa_venta = $id_mesa AND estado_venta='abierta'";
                        $resultado2 = $con->query($obtenernumventa);
                        while ($fila2 = $resultado2->fetch_row()) {
                            $id_venta = $fila2[0];
                            $total_venta = $fila2[1];
                        }
                        echo "<div class='mesa $estado' id='$id_mesa'>
                            <p><a href='../agregarproductoventa?id_mesa_venta=$id_mesa&id_venta=$id_venta'>Mesa : $numero_mesa </a><p>
                            <p>$ $total_venta</p>
                            </div>";
                    }
                }
                /* liberar el conjunto de resultados */
                $resultado->close();
            }
            /* cerrar la conexión */
            $con->close();            
            ?>     
    </main>      
</body>
    <?php
        }ob_end_flush();
    ?>
</html>