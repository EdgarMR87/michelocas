<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="/imagenes/productos.png"/>
    <title>Modificar Producto</title>
</head>

<script>      

    function ConfirmExit(){
        return confirm("¿Deseas cerrar Sesión?");
    }
    
$(document).ready(function(){
        $('#stock').val('<?php echo $_GET['stock']; ?>');
        if($('#stock').val() == 'S'){             
                $('#cant_stock_p').css("display", "");
                $('#span-cant-stock').css("display", "");
                $('#cant_stock_p').val('<?php echo $_GET['cant_stock']; ?>');
            } else if($('#stock').val() == 'N'){
                $('#cant_stock_p').css("display", "none");
                $('#span-cant-stock').css("display", "none");
            }

        if(stock == 'S'){    
            $('#cant_stock_p').css("display", "");
            $('#span-cant-stock').css("display", "");
        }

        $.ajax({
            type:'post',
            url:'obtenerCategorias',        
            dataType: "html",     
            success:function(resp){            
                $('#categorias').html(resp);
                $('#categorias').val('<?php echo $_GET['id_categoria']; ?>');
            }
        });
        
       
       

        $("select[name=stock]").change(function(){
            if($('select[name=stock]').val() == 'S'){             
                $('#cant_stock_p').css("display", "");
                $('#span-cant-stock').css("display", "");
            } else if($('select[name=stock]').val() == 'N'){
                $('#cant_stock_p').css("display", "none");
                $('#span-cant-stock').css("display", "none");
            }
        });
      
        const $seleccionArchivos = document.querySelector("#foto_producto"),
        $imagenPrevisualizacion = document.querySelector("#imagen");
        // Escuchar cuando cambie
        $seleccionArchivos.addEventListener("change", () => {
        // Los archivos seleccionados, pueden ser muchos o uno
        const archivos = $seleccionArchivos.files;
        // Si no hay archivos salimos de la función y quitamos la imagen
        if (!archivos || !archivos.length) {
          $imagenPrevisualizacion.src = "";
          return;
        }
        // Ahora tomamos el primer archivo, el cual vamos a previsualizar
        const primerArchivo = archivos[0];
        // Lo convertimos a un objeto de tipo objectURL
        const objectURL = URL.createObjectURL(primerArchivo);
        // Y a la fuente de la imagen le ponemos el objectURL
        $imagenPrevisualizacion.src = objectURL;
        });

       /*FUNCION PARA INSERTAR PRODUCTO*/
    $("#btn-actualizar").click(function(evento){
        $('#resp').html('');
        if($('#descripcion_p').val().length == 0) {
            alert('No puede estar vacio el campo Descripcion');
            return false;
        }else{
            var paqueteDeDatos = new FormData();
            /* Todos los campos deben ser añadidos al objeto FormData. Para ello 
		    usamos el método append. Los argumentos son el nombre con el que se mandará el 
			dato al script que lo reciba, y el valor del dato.
			Presta especial atención a la forma en que agregamos el contenido 
			del campo de fichero, con el nombre 'archivo'. */ 
            if($('#foto_producto')[0].files.length == 0){
                var src = document.getElementById('imagen').src;
                if(src == null){
                    }else{
                        paqueteDeDatos.append('foto_producto', src);
                        paqueteDeDatos.append('files', 'no');
                    }
            }else{
                paqueteDeDatos.append('foto_producto', $('#foto_producto')[0].files[0]);
                paqueteDeDatos.append('files', 'si');
            }			
            paqueteDeDatos.append('id_producto', $('#id_producto').prop('value'));
			paqueteDeDatos.append('descripcion_p', $('#descripcion_p').prop('value'));
			paqueteDeDatos.append('categorias', $('#categorias').prop('value'));
            paqueteDeDatos.append('precio_compra', $('#precio_compra').prop('value'));                      
            paqueteDeDatos.append('precio_venta', $('#precio_venta').prop('value'));
            paqueteDeDatos.append('comentarios_p', $('#comentarios_p').prop('value'));
            paqueteDeDatos.append('stock', $('#stock').prop('value'));
            paqueteDeDatos.append('cant_stock_p', $('#cant_stock_p').prop('value'));
            
            $.ajax({
                url: 'actualizarProducto',
                type: 'POST', // Siempre que se envíen ficheros, por POST, no por GET.
			    contentType: false,
			    data: paqueteDeDatos, // Al atributo data se le asigna el objeto FormData.
			    processData: false,
			    cache: false, 
			    success: function(resultado){ // En caso de que todo salga bien.
                    $('#resp').html(resultado); 
                    $('#form-modificar-producto')[0].reset();
                    $('#foto_producto').attr("src", "");
                    $('#cant_stock_p').css("display", "none");
                    $('#span-cant-stock').css("display", "none");
			    }		    
            });
            return false;
        }
    });




});


</script>

<body>


<header>
		<div class="menu_bar">
			<a href="#" class="bt-menu"><span class="icon-list2"></span>Menu</a>
		</div>
 
		<nav>
			<ul>
				<li><a href="/usuarios/altaUsuario"><img class="icon-servicios" src="/imagenes/users.png">Usuarios</a></li>
				<li><a href="/mesas/altaMesa"><img class="icon-servicios" src="/imagenes/mesas.png">Mesas</a></li>
				<li><a href="/productos/altaProducto"><img class="icon-servicios"  src="/imagenes/productos.png">Productos</a></li>				
                <li><a href="/ventas/menuPrincipal"><img class="icon-servicios"  src="/imagenes/facturas.png">Ventas</a></li>
                <li><a href="/gastos/altaGasto"><img class="icon-servicios"  src="/imagenes/gastos.png">Gastos</a></li>
                <li class="salir"><a href="../logout.php"><img class="icon-salir"  src="/imagenes/salir.png" onclick="ConfirmExit();">Salir</a></li>
			</ul>
		</nav>
  </header>
  
  <main>
        <div class="wrappers">
            <div class="btn-submenu">  
                <a href="altaProducto">  
                <img class="img-submenu" src="/imagenes/nuevo.png" alt="">
                <div class="a-submenu">Nuevo Producto</div>
              </a>
            </div>
            <div class="btn-submenu">
                <a href="listadoProductos">
                  <img class="img-submenu" src="/imagenes/listado.png" alt="">
                  <div class="a-submenu">Listado Productos</div>
                </a>
            </div>
            <div class="btn-submenu">  
                <a href="altaCategoria">  
                <img class="img-submenu" src="/imagenes/nuevo.png" alt="">
                <div class="a-submenu">Nueva Categoria</div>
              </a>
            </div>
            <div class="btn-submenu">
                <a href="listadoCategorias">
                  <img class="img-submenu" src="/imagenes/listado.png" alt="">
                  <div class="a-submenu">Listado Categorias</div>
                </a>
            </div>
        </div>
    </main>

    <main class="contenido">    
      <div class="form-alta-usuario">
        <form id="form-modificar-producto">
          <table class="alta-usuario">
          <?php
          include('../conexionbd.php');
          $consulta = "SELECT * FROM productos WHERE id_producto = " . $_GET['id_producto'];
          if($resultado = $con->query($consulta)){
              while($fila = $resultado->fetch_row()){
                  $descripcion_p = $fila[1];
                  $precio_compra = $fila[3];
                  $precio_venta = $fila[4];
                  $cant_stock = $fila[6];
                  $imagen_p = $fila[7];
                  $comentarios = $fila[8];
          ?>
            <tr>  
                <input type="hidden" name="id_producto" id="id_producto" value="<?php echo $_GET['id_producto']; ?>">       
              <td class="izquierda"> <span>Descripcion : </span> </td>
              <td> <input class="input-alta" type="text" name="descripcion_p" id="descripcion_p" value="<?php echo $descripcion_p; ?>" required> </td>
              <td class="izquierda"> <span>Categoria : </span> </td>
              <td>
                <select class="input-alta" name="categorias" id="categorias">
                </select>
              </td>              
            </tr>
            <tr>              
              <td class="izquierda"> <span>Precio Compra : </span> </td>
              <td><input id="precio_compra" type="number" step="1" class="input-alta" value="<?php echo $precio_compra; ?>"></td>
              <td class="izquierda"> <span>Precio Venta : </span> </td>
              <td><input id="precio_venta" type="number" step="1" class="input-alta" value="<?php echo $precio_venta; ?>"></td>              
            </tr>
            <tr>
              <td class='izquierda'><span> Stock : </span></td>
              <td>
                <select class="input-alta" required class="input" name="stock" id="stock">
                  <option value="no" disabled selected>Selecciona una opción</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                </select>
              </td>
              <td class="izquierda"><span id="span-cant-stock" style="display: none;"> Cant. Stock : </span></td>
              <td>
                <input style="display: none;" class="input-alta" type="number" name="cant_stock_p" id="cant_stock_p" value="<?php echo $cant_stock; ?>">
              </td>
            </tr>
            <tr>            
              <td class="izquierda"> <span>Foto : </span> </td>          
              <td><input class="input-alta" type="file" name="foto_producto" id="foto_producto"></td>
              <td class="izquierda"><span> &#128073; </span></td>
              <td><img id="imagen" name="imagen" width="100px" height="100px" src="<?php echo $imagen_p; ?>"></td>            
            </tr> 
            <tr>
              <td class="izquierda"> <span>Comentarios : </span> </td>          
              <td>
                <textarea class="input-alta" name="comentarios_p" id="comentarios_p" cols="23" rows="3" ><?php echo $comentarios; ?></textarea>
              </td>
              </td>
            </tr>      
            <tr>
              <td class="izquierda" colspan="2"><button id="btn-actualizar" class="btn-actualizar"> ☑ Actualizar</button></td>
              <td class="derecha" colspan="2"><button id="btn-limpiar" class="btn-limpiar" type="reset"> ☒ Limpiar</button></td>
            </tr>
          </table>
        </form>
      </div>  
      <div id="resp"> </div>
    </main>
    <?php
    }
    /* liberar el conjunto de resultados */
    $resultado->close();
    }
    /* cerrar la conexi贸n */
    $con->close();
    ?>
    </body>
</html>