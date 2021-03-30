<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="/imagenes/mesero.png"/>
    <title>Menu Principal Meseros</title>
</head>

<script>
    
  function ConfirmExit() {
        return confirm("¿Deseas cerrar Sesión?");
    }

function confirmarAbriMesa(b) {
        var btnId = b.id;
        return confirm("¿Deseas Abrir una venta para la mesa : " + btnId);
    }
    
function startTime(){
        today=new Date();
        h=today.getHours();
        m=today.getMinutes();
        s=today.getSeconds();
        m=checkTime(m);
        s=checkTime(s);
        document.getElementById('reloj').innerHTML=h+":"+m+":"+s;
        t=setTimeout('startTime()',500);
        }
    function checkTime(i){
        if (i<10) {
            i=0 + i;
        }
        return i;
    }

window.onload = function(){
    startTime();
    }
    


$(document).ready(function(){
    //Cada 10 segundos (10000 milisegundos) se ejecutará la función refrescar
    setTimeout(refrescar, 10000);
  });

  function refrescar(){
    //Actualiza la página
    location.reload();
  }
</script>

<body>    
    <?php
    ob_start();
    date_default_timezone_set('America/Mexico_City');
    session_start();
    if($_SESSION["autentificado"]!="SI"){
    //si no está logueado lo envío a la página de autentificación
        echo "<script>
                    alert('No haz iniciado sesión');
                    window.location.href = 'https://michelocas.kusoftdevelopment.com'; 
              </script>";
    }else{
    ?>

    <header>
        <center>
		    <div class="menu_bar">
    			<a href="#" class="bt-menu"><span class="icon-list2"></span>Menu</a>
	    	</div>
            <nav class="menu-cocina">
			    <ul>
    				<li>                            
                        <p class="texto"> <?php echo $_SESSION['usuario'] ?> </p>
                        <img class="icon-servicios-cocina" src="/imagenes/mesero.png">                        
                    </li>
            	    <li>
                        <p class="texto" id="reloj"></p>                       
                        <img class="icon-servicios-cocina" src="/imagenes/reloj.png">                       
                    </li>   
                    <li class="salir">
                        <a href="logout" Onclick="return ConfirmExit()">
                            <img class="icon-salir"  src="/imagenes/salir.png">
                            <p class="texto">Salir</p>
                        </a>
                    </li>     
                </ul>
		    </nav>         
        </center>  
    </header>

    <main class="listado-partidas-venta"> 
        <?php
            include('conexionbd.php');
            $consulta = "SELECT * FROM mesas";
            if ($resultado = $con->query($consulta)) {
                /* obtener el array de objetos */
                while ($fila = $resultado->fetch_row()) {
                    $id_mesa = $fila[0];
                    $numero_mesa = $fila[1];
                    $estado = $fila[3];
                    if($estado == 'libre'){
                        echo "<div class='mesa $estado'>
                                <p><a id='$numero_mesa' href='abrirVentaMesa?id_mesa=$id_mesa' Onclick='return confirmarAbriMesa(this)'>Mesa : $numero_mesa </a><p>
                                <p>$</p>
                                </div>";
                    } elseif($estado == 'ocupado'){
                        $obtenernumventa = "SELECT id_venta, total_venta FROM venta WHERE id_mesa_venta = $id_mesa AND estado_venta='abierta'";
                        $resultado2 = $con->query($obtenernumventa);
                        while ($fila2 = $resultado2->fetch_row()) {
                            $id_venta = $fila2[0];
                            $total_venta = $fila2[1];
                        }
                        $obtenerProdTerminado = "SELECT COUNT(estado_partida_v) FROM producto_venta 
                                                        WHERE estado_partida_v = 'terminado' AND id_venta_v = $id_venta";
                        $resultado3 = $con->query($obtenerProdTerminado);
                        while($fila3 = $resultado3->fetch_row()){
                            $num_prod_terminado = $fila3[0];
                        }
                        echo "<div class='mesa $estado' id='$id_mesa'>
                            <div class='alerta'><p>$num_prod_terminado</p></div>
                            <p><a href='agregarproductoventa?id_mesa_venta=$id_mesa&id_venta=$id_venta'>Mesa : $numero_mesa </a><p>
                            <p>$ $total_venta</p>
                            </div>";
                    }
                }
                /* liberar el conjunto de resultados */
                $resultado->close();
                $resultado2->close();
                $resultado3->close();
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