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
    <title>Alta Productos</title>
</head>

<script>      

    function ConfirmExit(){
        return confirm("¿Deseas cerrar Sesión?");
    }

$(document).ready(function(){
        
        $("select[name=stock]").change(function(){
            if($('select[name=stock]').val() == 'S'){             
                $('#cant_stock_p').css("display", "");
                $('#span-cant-stock').css("display", "");
            } else if($('select[name=stock]').val() == 'N'){
                $('#cant_stock_p').css("display", "none");
                $('#span-cant-stock').css("display", "none");
            }
        });

      $.ajax({
        type:'post',
        url:'obtenerCategorias',        
        dataType: "html",     
        success:function(resp){            
            $('#categorias').html(resp);
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
    $("#btn-agregar").click(function(evento){
        $('#resp').html('');
        if($('#descripcion_p').val().length == 0) {
            alert('No puede estar vacio el campo Descripcion');
            return false;
        }else if($('#precio_venta').val().length == 0){
             alert('No puede estar vacio el campo Precio Venta');
            return false;
        }else if($('#stock').val().length == 0){
             alert('No puede estar vacio el campo Stock');
            return false;
        }else
        {
            var paqueteDeDatos = new FormData();
            /* Todos los campos deben ser añadidos al objeto FormData. Para ello 
		    usamos el método append. Los argumentos son el nombre con el que se mandará el 
			dato al script que lo reciba, y el valor del dato.
			Presta especial atención a la forma en que agregamos el contenido 
			del campo de fichero, con el nombre 'archivo'. */
			paqueteDeDatos.append('foto_producto', $('#foto_producto')[0].files[0]);
			paqueteDeDatos.append('descripcion_p', $('#descripcion_p').prop('value'));
			paqueteDeDatos.append('categorias', $('#categorias').prop('value'));
            paqueteDeDatos.append('precio_compra', $('#precio_compra').prop('value'));                      
            paqueteDeDatos.append('precio_venta', $('#precio_venta').prop('value'));
            paqueteDeDatos.append('comentarios_p', $('#comentarios_p').prop('value'));
            paqueteDeDatos.append('stock', $('#stock').prop('value'));
            paqueteDeDatos.append('cant_stock_p', $('#cant_stock_p').prop('value'));
            
            $.ajax({
                url: 'insertarProducto',
                type: 'POST', // Siempre que se envíen ficheros, por POST, no por GET.
			    contentType: false,
			    data: paqueteDeDatos, // Al atributo data se le asigna el objeto FormData.
			    processData: false,
			    cache: false, 
			    success: function(resultado){ // En caso de que todo salga bien.
                    $('#resp').html(resultado); 
                    $('#form-alta-producto')[0].reset();
                    $('#imagen').attr("src", "");
                    $('#cant_stock_p').css("display", "none");
                    $('#span-cant-stock').css("display", "none");
                    $("#descripcion_p").focus();
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
                  <div class="a-submenu">Listado Categiorias</div>
                </a>
            </div>
        </div>
    </main>

    <main class="contenido">    
      <div class="form-alta-usuario">
        <form id="form-alta-producto">
          <table class="alta-usuario">
            <tr>         
              <td class="izquierda"> <span>Descripcion : </span> </td>
              <td> <input class="input-alta" type="text" name="descripcion_p" id="descripcion_p" placeholder=" 👤 Nombre del producto" required> </td>
              <td class="izquierda"> <span>Categoria : </span> </td>
              <td>
                <select class="input-alta" name="categorias" id="categorias" required>
                </select>
              </td>              
            </tr>
            <tr>              
              <td class="izquierda"> <span>Precio Compra : </span> </td>
              <td><input id="precio_compra" type="number" step=".1" class="input-alta"></td>
              <td class="izquierda"> <span>Precio Venta : </span> </td>
              <td><input id="precio_venta" type="number" step=".1" class="input-alta" required></td>              
            </tr>
            <tr>
              <td class='izquierda'><span> Stock : </span></td>
              <td>
                <select class="input-alta" required class="input" name="stock" id="stock" required>
                  <option value="" disabled selected>Selecciona una opción</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                </select>
              </td>
              <td class="izquierda"><span id="span-cant-stock" style="display: none;"> Cant. Stock : </span></td>
              <td>
                <input style="display: none;" class="input-alta" type="number" name="cant_stock_p" id="cant_stock_p" value="0">
              </td>
            </tr>
            <tr>            
              <td class="izquierda"> <span>Foto : </span> </td>          
              <td><input class="input-alta" type="file" name="foto_producto" id="foto_producto"></td>
              <td class="izquierda"><span> &#128073; </span></td>
              <td><img id="imagen" name="imagen" width="100px" height="100px"></td>            
            </tr> 
            <tr>
              <td class="izquierda"> <span>Comentarios : </span> </td>          
              <td>
                <textarea class="input-alta" name="comentarios_p" id="comentarios_p" cols="23" rows="3" placeholder="Lugar de ubicacion de la mesa"></textarea></td>
              </td>
            </tr>    
          </table>
          <div class="botones">
              <button id="btn-agregar" class="btn-agregar"> ☑ Agregar</button>
              <button id="btn-limpiar" class="btn-limpiar" type="reset"> ☒ Limpiar</button>
            </div>
        </form>
      </div>  
      <div id="resp"> </div>
    </main>

</body>
</html>