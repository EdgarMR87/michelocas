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
    <link rel="icon" type="image/png" href="/imagenes/categorias.png" />
    <title>Modificar Categoria</title>
</head>
<script>
   

  function ConfirmExit() {
        return confirm("¿Deseas cerrar Sesión?");
    }

$(document).ready(function(){

        $("#btn-limpiar").click(function(evento) {   
            $("#imagen").attr("src","");                   
        });

        $("#btn-actualizar").click(function(evento) {            
          //Obtenemos datos formulario.  
          var paqueteDeDatos = new FormData();
          if($('#foto_categoria')[0].files.length == 0) { 
            var src = document.getElementById('imagen').src;
            if(src == null){                         
            }else{
              paqueteDeDatos.append('foto_categoria', src);
              paqueteDeDatos.append('files', 'no');
            }
          }else{
            paqueteDeDatos.append('foto_categoria', $('#foto_categoria')[0].files[0]);
            paqueteDeDatos.append('files', 'si');
          }        
          paqueteDeDatos.append('id_categoria', $('#id_categoria').prop('value'));
    		  paqueteDeDatos.append('descripcion', $('#descripcion').prop('value'));
		      paqueteDeDatos.append('comentarios', $('#comentarios').prop('value'));                        
        //AJAX.
        $.ajax({  
            url  : 'actualizarCategoria',
            type : 'POST',
            contentType: false,
            data: paqueteDeDatos,
            processData: false,
            cache: false,             
            success:function(data) {    
                $('#resp').html(data);               
            }              
        });       
        return false;      
    });   

    
    const $seleccionArchivos = document.querySelector("#foto_categoria"),
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
});//Fin document.

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
                <li class="salir"><a href="#"><img class="icon-salir"  src="/imagenes/salir.png">Salir</a></li>
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
    <form id="form-actualizar-categoria">
      <table class="alta-usuario">
            <?php
            include('../conexionbd.php');            
            $consulta = "SELECT * FROM categorias WHERE id_categoria = " . $_GET['id_categoria'];
            if ($resultado = $con->query($consulta)) {
                /* obtener el array de objetos */
                while ($fila = $resultado->fetch_row()) {
                    $id_categoria = $fila[0];                    
                    $descripcion = $fila[1];
                    $comentarios = $fila[2];                    
                    $fotoCategoria = $fila[3];                                   

                    ?>
        <tr>         
          <input type="hidden" name="id_categoria" id="id_categoria" value="<?php echo $id_categoria; ?>">
          <td class="izquierda"> <span>Descripcion : </span> </td>
          <td> <input class="input-alta" type="text" name="descripcion" id="descripcion" value="<?php echo $descripcion; ?>" required> </td>
          <td class="izquierda"> <span>Comentarios : </span> </td>          
          <td>
              <textarea class="input-alta" name="comentarios" id="comentarios" cols="23" rows="3" value="<?php echo $comentarios; ?>"></textarea></td>
          </td>
        </tr>
        <tr>
          <td class="izquierda"> <span>Foto : </span> </td>          
          <td><input class="input-alta" type="file" name="foto_categoria" id="foto_categoria"></td>
          <td class="izquierda"><span> &#128073; </span></td>
          <td><img id="imagen" name="imagen" width="100px" height="100px" src="<?php echo $fotoCategoria; ?>"></td>            
        </tr>       
        <tr>
          <td class="izquierda" colspan="2"><button id="btn-actualizar" class="btn-actualizar"> ☑ Actualizar</button></td>
          <td class="derecha" colspan="2"><button id="btn-limpiar" class="btn-limpiar" type="reset"> ☒ Limpiar</button></td>
        </tr>
      </table>
    </form>
  </div>
  <div id="resp"> </div>
  <?php
        }
        /* liberar el conjunto de resultados */
        $resultado->close();
        }
        /* cerrar la conexi贸n */
        $con->close();
    ?>       
</main>
</body>
</html>