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
    <title>Listado Productos</title>
</head>

<script>  

 function ConfirmDelete() {
        return confirm("¿Deseas Eliminar el registro?");
    }
    
    function ConfirmExit(){
        return confirm("¿Deseas cerrar Sesión?");
    }

    $(document).ready(function(){
        $("#productoBuscar").keyup(function(){
            _this = this;
            // Show only matching TR, hide rest of them
            $.each($("#productos-contenido tbody tr"), function() {
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
				<li><a href="/usuarios/altaUsuario"><img class="icon-servicios" src="/imagenes/users.png">Usuarios</a></li>
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
                    <td class="izquierda inferior titulo one"><span>Producto a buscar : </span></td>
                    <td class="inferior two"><input class="input-alta" type="search" name="productoBuscar" id="productoBuscar"></td>
                    <td class="derecha three"><button class="btn-buscar" id="btnBuscar"> 🔍︎ Buscar</button></td>
                </tr>
            </tbody>
    </table>
    <table class="listado-tablas" id="productos-contenido">    
            <thead>
                <tr>
                    <th class="listado-th">ID</th>
                    <th class="listado-th">Descripcion</th>
                    <th class="listado-th">Categoria</th> 
                    <th class="listado-th">$ Compra</th>                                        
                    <th class="listado-th">$ Venta</th>                    
                    <th class="listado-th">Stock</th>
                    <th class="listado-th">Cant.</th>
                    <th class="listado-th">Imagen</th>
                    <th class="listado-th">Comentarios</th>
                    <th class="listado-th">Modificar</th>
                    <th class="listado-th">Eliminar</th>
                </tr>
            </thead>
            <?php
            include('../conexionbd.php');
            $consulta = "SELECT p.id_producto, p.descripcion, c.descripcion, p.precio_costo, p.precio_venta, 
                                p.stock, p.stock_cantidad, p.imagen, p.comentarios, p.id_categoria_producto
                            FROM productos p
                            LEFT JOIN categorias c ON c.id_categoria = p.id_categoria_producto";
            if ($resultado = $con->query($consulta)) {

                /* obtener el array de objetos */
                while ($fila = $resultado->fetch_row()) {
                    $id_producto = $fila[0];                    
                    $descripcion_p = $fila[1];
                    $descripcion_categoria = $fila[2];
                    $precio_compra_p = $fila[3];
                    $precio_venta_p = $fila[4];
                    $stock = $fila[5];
                    $cant_stock = $fila[6];
                    $imagen_p = $fila[7];                   
                    $comentarios_p =$fila[8];       
                    $id_categoria = $fila[9];
                   

                    echo "<tr>
                              <td>$id_producto</td>
                              <td>$descripcion_p</td>
                              <td>$descripcion_categoria</td>
                              <td>$precio_compra_p</td>
                              <td>$precio_venta_p</td>
                              <td>$stock</td>
                              <td>$cant_stock</td>
                              <td>
                                <img src='$imagen_p' width='50px' heigth='50px'>
                              </td>
                              <td>$comentarios_p</td>                              
                              <td>
                                <span>
                                    <a href='modificarProducto?id_producto=$id_producto&id_categoria=$id_categoria&stock=$stock&cant_stock=$cant_stock' style='text-decoration:none;'>✏️</a>
                                <span>
                              </td>
                              <td>                               
                                    <a href='eliminarProducto?id_producto=$id_producto' Onclick='return ConfirmDelete()'>🗑️</a>                                
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