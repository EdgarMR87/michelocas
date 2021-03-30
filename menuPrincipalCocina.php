<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="/imagenes/productos.png"/>
    <title>Menu Principal Cocina</title>
</head>

<script>

function ConfirmExit() {
        return confirm("¿Deseas cerrar Sesión?");
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
    
function confirmarTeminado(b) {
    var mensaje;
    var opcion =  confirm("¿El producto esta listo?");
    if (opcion == true) {
        $.ajax({
            url: 'actualizarPartidaVenta',
            type: 'POST', // Siempre que se envíen ficheros, por POST, no por GET.					
			data: {'partida' : b.dataset.partida}, // Al atributo data se le asigna el objeto FormData.			
			success: function(resultado){ // En caso de que todo salga bien.
            $('#resultado-insert').html(resultado);
            },
            error: function (){ // Si hay algún error.
                alert("Algo ha fallado.");
            }
        });
    } else {} 
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
                        <p class="texto"> <?php echo $_SESSION['categoria'] ?> </p>
                        <img class="icon-servicios-cocina" src="/imagenes/cocina.png">                        
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

<table class="listado-tablas" id="listado_ventas">
    <thead>
        <tr>  
            <th class="listado-th">Id Partida</th>
            <th class="listado-th">Cantidad</th>
            <th class="listado-th">Descripcion</th>                    
            <th class="listado-th"># Venta</th> 
            <th class="listado-th"># Mesa</th> 
            <th class="listado-th">Comentarios</th> 
            <th class="listado-th">Estatus</th>                                       
            <th class="listado-th">Terminar</th>    
        </tr>
    </thead>
    
<tbody>
<?php
            include('conexionbd.php');
            $consulta = "SELECT pv.id_partida_v, pv.cantidad_v, p.descripcion, pv.comentarios_v, pv.id_venta_v, v.id_mesa_venta, pv.estado_partida_v
                            FROM producto_venta pv
                            LEFT JOIN productos p ON p.id_producto = pv.id_producto_v
                            LEFT JOIN venta v ON v.id_venta = pv.id_venta_v
                            WHERE pv.estado_partida_v = 'pendiente'";
                       
            if ($resultado = $con->query($consulta)) {
                
                while ($fila = $resultado->fetch_row()) {
                    $id_partida = $fila[0];
                    $cantidad = $fila[1];
                    $descripcion = $fila[2];
                    $comentarios = $fila[3];
                    $venta = $fila[4];
                    $mesa = $fila[5];
                    $estado = $fila[6];
                    
                    echo "<tr id='$id_partida'>
                            <td class='listado-th'>$id_partida</td>
                            <td class='listado-th'>$cantidad</td>
                            <td class='listado-th'>$descripcion</td>
                            <td class='listado-th'>$comentarios</td>
                            <td class='listado-th'>$venta</td>
                            <td class='listado-th'>$mesa</td>
                            <td class='listado-th'>$estado</td>
                            <td class='listado-th'><input id='terminado' data-partida='$id_partida'  type='button' value='Terminado' class='btn-terminado' onclick='return confirmarTeminado(this)'></td>
                      </tr>";
                }
                /* liberar el conjunto de resultados */
                $resultado->close();
            }                  
            /* cerrar la conexión */
            $con->close();            
    
    ?>     
    </tbody>
    </table>
    <div id="resultado-insert"></div>
</main>


</body>
<?php
	}ob_end_flush();
	?>
</html>