<html lang="en"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilosGral.css">
    <link rel="icon" type="image/png" href="/imagenes/Mesa.png">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Agregar Productos a Mesa</title>
<script>
 
    function comprobar(obj){
        if (obj.checked)
        document.getElementById('efectivo').readOnly = false;
        else{
        document.getElementById('efectivo').readOnly = true; 
        $('#efectivo').val(''); 
        }     
    }

    function comprobarT(obj){
        if (obj.checked){
        document.getElementById('tarjeta').readOnly = false;
        $('#lbl-propina').css('display', 'block');   
        $('#propina').css('display', 'block');   
        }else{
        document.getElementById('tarjeta').readOnly = true;  
        $('#lbl-propina').css('display', 'none');   
        $('#propina').css('display', 'none');   
        $('#tarjeta').val('');
        }     
}

function ventanaNueva(documento){	
    if(!$('#ch-efectivo').prop('checked') && !$('#ch-tarjeta').prop('checked')){
           alert("Debes seleccionar una forma de pago")
       }else{
          	window.open(documento,'nuevaVentana','width=350, height=600');
       }
}

function ConfirmDelete() {
  return confirm("¿Deseas Eliminar el registro?");
}


function ConfirmarTraspaso(b) {
  return confirm("¿Deseas Realizar El traspado del producto : " + b.id);
}

/*FUNCION PARA MOSTRAR LOS PRODUCTOS*/
function mostrarProductos(b){
    var dataen = 'id_categoria=' + b.id;
    $.ajax({
        type:'post',
        url:'obtenerProductosId.php',
        data: dataen, 
        dataType: "html",     
        success: function(resp){      
            $('#categorias-ventas').css('display', 'none');      
            $("#productos-id").html(resp);
            $('#productos-id').css('display', 'block');   
            $('#tamanos-id').css('display', 'none');
        }
    });
    return false;
}

/*FUNCION PARA MOSTRAR TAMAÑOS O SUBCATEGORIAS*/
function mostrarTamanos(b, c){    
    $.ajax({
        type:'post',
        url:'obtenerSubcategoriaId.php',
        data: {'id_categoria_p' : c, 'descripcion_p' : b.id}, 
        dataType: "html",     
        success: function(resp){      
            $('#categorias-ventas').css('display', 'none');      
            $('#productos-id').css('display', 'none');    
            $('#tamanos-id').css('display', 'grid');
            $("#tamanos-id").html(resp);            
        }
    });
    return false;   
}

/*FUNCION PARA AGREGAR PRODUCTO A LA BD Y CARGARLO EN LA  LOS PRODUCTOS*/
function agregarProductoVenta(a){ 
    var cantidad_v  = $('#cantidad_v').val();
    var comentarios_v  = $('#comentarios_v').val();
    var id_mesa_venta = '<?php echo $_GET['id_mesa_venta']; ?>';
    $.ajax({
        type:'post',
        url:'insertarProductoVenta.php',
        data: {'id_mesa_venta' : id_mesa_venta,  'id_producto_v' : a.id, 'id_venta_v' : <?php echo $_GET['id_venta'] ?> , 'cantidad_v' : cantidad_v, 'precio_unitario_v' : a.dataset.precio, 'comentarios_v' : comentarios_v, 'descripcion_p' : a.dataset.name}, 
        dataType: "html",     
        success: function(resp){         
            $("#tabla-productos-venta tbody").append(resp); 
            $("#cantidad_v").val('1'); 
            $("#comentarios_v").val(''); 
            alert('Se inserto correctamente el producto');                    
        }
    });
    return false;      
}

$(document).ready(function(){

    $("#pago").focusout(function() {
        $('#cambio').val("");
        var pagocon = $('#pago').val(); 
        var importeapagar = $('#totalapagar').val();
        var cambio = pagocon - importeapagar;
        if(cambio < 0) {
            alert("El cambio no puede ser negativo")
        } else{
            $('#cambio').val(cambio);
            $("#pagarcuenta").removeAttr('disabled');
        }
	});
 


   $("#pagarcuenta").click(function(evento){ 
    var totalapagar = $('#totalapagar').val();
       var id_venta = <?php echo $_GET['id_venta']; ?>;
       var id_mesa_venta = '<?php echo $_GET['id_mesa_venta']; ?>';
       var forma_de_pago = '';
       var imp_efectivo = '';
       var imp_tarjeta = '';
       var propina = '';
       var mesero = $('#mesero').val();
       
       if(!$('#ch-efectivo').prop('checked') && !$('#ch-tarjeta').prop('checked')){
         
       }else{
           if($('#ch-efectivo').prop('checked') && $('#ch-tarjeta').prop('checked')){
               forma_de_pago ='Mixta';
               imp_efectivo = $('#efectivo').val();
               imp_tarjeta = $('#tarjeta').val();
               propina = $('#propina').val();
            } else if($('#ch-efectivo').prop('checked') && !$('#ch-tarjeta').prop('checked')){
                forma_de_pago = 'Efectivo' ;
                imp_efectivo = $('#efectivo').val();
            } else if(!$('#ch-efectivo').prop('checked') && $('#ch-tarjeta').prop('checked')){
                forma_de_pago = 'Tarjeta' ;
                imp_tarjeta = $('#tarjeta').val();
                propina = $('#propina').val();
            }  


        $.ajax({
        url: 'actualizarventa.php',
						type: 'POST', // Siempre que se envíen ficheros, por POST, no por GET.					
						data: {'totalapagar' : totalapagar,  'id_venta' : id_venta , 'id_mesa_venta' : id_mesa_venta, 'forma_de_pago' : forma_de_pago, 'imp_efectivo' : imp_efectivo, 'imp_tarjeta' :imp_tarjeta, 
						        'propina' : propina, 'mesero':mesero}, // Al atributo data se le asigna el objeto FormData.
						dataType: "html",						
						success: function(resultado){ // En caso de que todo salga bien.
                                $('#tamanos-id').html(resultado);                     
						},
						error: function (){ // Si hay algún error.
							alert("Algo ha fallado.");
						}
    });
}
  });
});





</script>
</head>


<body>
<?php
    ob_start();
    date_default_timezone_set('America/Mexico_City');
    session_start();
    if($_SESSION["autentificado"]!="SI"){
    //si no está logueado lo envío a la página de autentificación
        echo "<script>
                    alert('No haz iniciado sesión');
                    window.location.href = 'https://michelocas.kusoftdevelopment.com/'; 
              </script>";
    }else{
    ?>


<?php 
    $categoria_usuario =   $_SESSION["categoria"];
    switch ($categoria_usuario) {
        case 'administrador':?>
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
        <?php
        break;
        case 'mesero':
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
        <?php
        break;
    }
    ?>  

<div class="sub-menu">
        <div class="contenedor-ventas" id="nuevo">
            <img src="/imagenes/Numero.png" class="icon-sub">
            <p class="texto-sub"># Venta : <?php echo $_GET['id_venta']; ?></p>
        </div>
        <div class="contenedor-ventas" id="listado">
            <img src="/imagenes/Mesa.png" class="icon-sub">
            <p class="texto-sub">Mesa :  <?php echo $_GET['id_mesa_venta']; ?> </p>
            <p class="texto-sub"><?php  include('conexionbd.php');
            $mesero =''; 
            $consulta = "SELECT u.usuario FROM venta LEFT JOIN usuarios u ON u.id_usuario = id_mesero_venta WHERE id_venta = ".$_GET['id_venta']; 
            if ($resultado = $con->query($consulta)) {
                while ($fila = $resultado->fetch_assoc()) {
                    $mesero = $fila['usuario'];
                    echo "Mesero : ".$fila['usuario'];
                    echo "<input type='hidden' value='".$fila['usuario']."' name='mesero'>";
                }
            }
            ?></p>
        </div>
</div>



<main class="tablamesas">
    <div class="form-alta-productos">
        <table class="encabezado-agregar">
            <tbody><tr>
                        <td class="izquierda"><span>Cantidad : </span></td>
                        <td class="derecha"><input class="input-alta alinear" type="number" name="cantidad_v" id="cantidad_v" value="1" min="1"></td>
                        <td class="izquierda"><span>Comentarios : </span></td>
                        <td class="derecha"><input class="input-alta" type="text" name="comentarios_v" id="comentarios_v"></td>
            </tr></tbody>
        </table>
    </div>   
    

<div class="categorias-ventas" id="categorias-ventas">
    <?php 
            include('conexionbd.php');
                $consulta = "SELECT * FROM categorias";
                    if ($resultado = $con->query($consulta)) {
                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<div class='categorias-venta'>
                                    <a id=" . $fila['id_categoria'] . " onclick='return mostrarProductos(this)'>
                                    <img src='". $fila['fotoCategoria']. "'>".$fila['descripcion']."</a>
                                </div>";
                        }
                        /* liberar el conjunto de resultados */
                        $resultado->close();
                    }
                    /* cerrar la conexión */
                $con->close();
        ?>
</div>

<div class="productos-venta" id="productos-id"></div>
<div class="tamanos-venta" id="tamanos-id"></div>

<div class="listado-productos-venta">
    <table class="tabla-productos-venta" id="tabla-productos-venta">       
            <tr>
                <th class="listado-th">ID</th>
                <th style="display: none;">ID Producto</th>
                <th class="listado-th">Producto</th>
                <th class="listado-th">Cantidad</th>
                <th class="listado-th">Precio Unitario</th>
                <th class="listado-th">Subtotal</th>
                <th class="listado-th">Comentarios</th>
                <?php 
                 if($categoria_usuario == 'administrador'){
                     echo "<th class='listado-th'>✏️</th>
                     <th class='listado-th'>🗑️</th>";
                 }
                 else{
                     echo "<th class='listado-th'>Traspaso</th>";
                 }
                 ?>
           
            </tr>
    
      
        <?php 
        include('conexionbd.php');
        $id_venta = $_GET['id_venta'];
        $consulta = "SELECT id_partida_v, id_producto_v, p.descripcion, cantidad_v, precio_unitario_v, subtotal_partida_v, comentarios_v
                        FROM producto_venta
                        LEFT JOIN productos p ON p.id_producto = id_producto_v WHERE id_venta_v = $id_venta ORDER BY id_partida_v ASC";
        if ($resultado = $con->query($consulta)) {
            $id_venta = $_GET['id_venta'];
            $id_mesa_venta = $_GET['id_mesa_venta'];
            /* obtener el array de objetos */
            while ($fila = $resultado->fetch_row()) {
                $id_partida_v = $fila[0];                    
                $id_producto_v = $fila[1];
                $descripcion_p = $fila[2];
                $cantidad_v = $fila[3];
                $precio_unitario_v = $fila[4];
                $subtotal_partida_v = $fila[5];
                $comentarios_v = $fila[6];
                $total_pagar = $total_pagar + $subtotal_partida_v;
                
                echo "<tr>
                        <td>$id_partida_v</td>
                        <td style='display: none;'>$id_producto_v</td>
                        <td>$descripcion_p</td>
                        <td>$cantidad_v</td>
                        <td>$precio_unitario_v</td>
                        <td>$subtotal_partida_v</td>
                        <td>$comentarios_v</td>";
                if($categoria_usuario == 'administrador'){
                echo"
                        <td>
                            <a href='modificarpartidav?id_partida_v=$id_partida_v' style='text-decoration:none;'>✎</a>
                        </td>
                        <td>                               
                            <a href='eliminarpartidav?id_partida_v=$id_partida_v&id_venta=$id_venta&id_mesa_venta=$id_mesa_venta&subtotal_partida_v=$subtotal_partida_v' onclick='return ConfirmDelete()'>☒</a>                                
                      </td>";
                }else{
                   echo "<td>
                            <a id='$descripcion_p' href='traspasarpartidaventa?id_partida_v=$id_partida_v&id_venta=$id_venta' onclick='return ConfirmarTraspaso(this)'>🔀</a>
                        </td>";
                }
                echo "</tr><tr></tr><tr></tr>";
        }     
        $total_pagar_formato = number_format($total_pagar, 2);  
      
        /* liberar el conjunto de resultados */
        $resultado->close();
    }    
    /* cerrar la conexión */
    $con->close();    
    ?>   
        
    </table>
    
    <div class="botones-pago">
    <table>
    <?php 
      echo"<tr>
      <td class='formato-pago' colspan='5'> Total a pagar :  </td>
      <td class='formato-pago' colspan='3'> $ $total_pagar_formato
      <input type='hidden' name='totalapagar' id='totalapagar' value='$total_pagar_formato'>
      </td>
    </tr>";
    ?>
    <tr>
            <td colspan="2">
                <input type="button" value="Pagar" class="btn-agregar" id="pagarcuenta"  onclick="ventanaNueva('ticket?id_venta=<?php echo $_GET['id_venta'] ?>&num_mesa=<?php echo $_GET['id_mesa_venta']; ?>&estado=pagada')">
            </td>
            <td colspan="2">
            <input type="button" value="Imprimir" class="btn-limpiar" onclick="ventanaNueva('ticket?id_venta=<?php echo $_GET['id_venta'] ?>&num_mesa=<?php echo $_GET['id_mesa_venta']; ?>&mesero=<?php echo $mesero; ?>&estado=abierta')">
            </td>
            <td colspan="2">
            <input type="button" value="Trapasar Cuenta" class="btn-actualizar" href="traspasarcuenta?id_venta=<?php echo $_GET['id_venta'] ?>&id_mesa_venta=<?php echo $_GET['id_mesa_venta']; ?>">
            </td>
        </tr>
    </table>
    </div>

    <div class="forma-pago">
    <label for="">Forma de Pago : </label>    
        <div class="div-pago">
            <label class="forma-pago-label"><input class="checkbox-pago" type="checkbox" id="ch-efectivo" value="efectivo" onChange="comprobar(this);">Efectivo :</label>
            <input type="number" step="1.0" name="efectivo" id="efectivo" class="input-alta" readonly >
        </div>    
        <div class="div-pago">
            <label class="forma-pago-label">
                <input class="checkbox-pago" type="checkbox" id="ch-tarjeta" value="tarjeta" onChange="comprobarT(this);">Tarjeta :
            </label>
            <input type="number" step="1.0" name="tarjeta" id="tarjeta" class="input-alta" readonly >
        </div>    
        <div class="div-pago">
            <label id="lbl-propina" class="forma-pago-label-propina"> Propina : </label>
            <input type="number" name="propina" id="propina" step='1.0' class="input-alta">
        </div>    
    </div>

    <!--
    <div class="tablacambio">
    <table>
    <tr>
    <td><span>Paga Con : </span></td>
    <td><input type="number" name="pago" id="pago"></td>
    </tr>
    <tr>
        <td><span>Cambio : </span></td>
        <td><input type="number" name="cambio" id="cambio" disabled></td>
    </tr>
    </table>
    </div> 
    </div>
-->  

</main>
</body>
<?php
	}ob_end_flush();
	?>
</html>
