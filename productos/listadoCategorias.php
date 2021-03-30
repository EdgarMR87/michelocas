<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="icon" type="image/png" href="/imagenes/categorias.png"/>
    <title>Listado Categorias</title>
</head>

<script>  

 function ConfirmDelete(a) {
        return confirm("¿Deseas Eliminar el registro : " + a.id + "?");
    }
    
    function ConfirmExit(){
        return confirm("¿Deseas cerrar Sesión?");
    }

    $(document).ready(function(){
        $("#categoriaBuscar").keyup(function(){
            _this = this;
            // Show only matching TR, hide rest of them
            $.each($("#categorias-contenido tbody tr"), function() {
                if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
                $(this).hide();
                else
                $(this).show();
            });
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
				<li><a href="/descripcions/altadescripcion"><img class="icon-servicios" src="/imagenes/users.png">descripcions</a></li>
				<li><a href="/mesas/altaMesa"><img class="icon-servicios" src="/imagenes/mesas.png">Mesas</a></li>
				<li><a href="/productos/altaProducto"><img class="icon-servicios"  src="/imagenes/productos.png">Productos</a></li>				
                <li><a href="/ventas/menuPrincipal"><img class="icon-servicios"  src="/imagenes/facturas.png">Ventas</a></li>
                <li><a href="/gastos/altaGasto"><img class="icon-servicios"  src="/imagenes/gastos.png">Gastos</a></li>
                 <li class="salir"><a href="../logout.php" onclick="return ConfirmExit();"><img class="icon-salir"  src="/imagenes/salir.png" onclick="">Salir</a></li>
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
    <table class="listado-tablas">
            <tbody>
                <tr>
                    <td class="izquierda inferior titulo one"><span>Categoria a buscar : </span></td>
                    <td class="inferior two"><input class="input-alta" type="search" name="categoriaBuscar" id="categoriaBuscar"></td>
                    <td class="derecha three"><button class="btn-buscar" id="btnBuscar"> 🔍︎ Buscar</button></td>
                </tr>
            </tbody>
    </table>

      <table class="listado-tablas" id="categorias-contenido">
            <thead>
                <tr>
                    <th class="listado-th">ID</th>                    
                    <th class="listado-th">Descripcion</th>                    
                    <th class="listado-th">Comentarios</th>                     
                    <th class="listado-th">Ruta Imagen</th>
                    <th class="listado-th">Imagen</th>                    
                    <th class="listado-th">Modificar</th>
                    <th class="listado-th">Eliminar</th>
                </tr>
            </thead>
            <?php
            include('../conexionbd.php');
            $consulta = "SELECT * FROM categorias";
            if ($resultado = $con->query($consulta)) {

                /* obtener el array de objetos */
                while ($fila = $resultado->fetch_row()) {
                    $id_categoria = $fila[0];                    
                    $descripcion = $fila[1];
                    $comentarios = $fila[2];
                    $ruta_imagen = $fila[3];                    
                                    
                    echo "<tr>
                              <td>$id_categoria</td>                              
                              <td>$descripcion</td>
                              <td>$comentarios</td>
                              <td>$ruta_imagen</td>                             
                              <td><img class='img-listado' src='$ruta_imagen' alt=''></td>
                              <td>
                                <span>
                                    <a href='modificarCategoria?id_categoria=$id_categoria' style='text-decoration:none;'>✏️</a>
                                <span>
                              </td>
                              <td>  
                                    <span>                             
                                    <a id='$descripcion' href='eliminarCategoria?id_categoria=$id_categoria&descripcion=$descripcion' Onclick='return ConfirmDelete(this)'>🗑️</a>
                                    </span>                                   
                              </td>
                         </tr>";
                }
            
                /* liberar el conjunto de resultados */
                $resultado->close();
            }
            /* cerrar la conexión */
            $con->close();
            
            ?>                        
            
        </table>    
    </main>
    
</body>
</html>
