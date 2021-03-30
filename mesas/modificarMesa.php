<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilosGral.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Modificar Mesa</title>
    <link rel="icon" type="image/png" href="/imagenes/mesas.png" /> 
    </head>

<script>

$(document).ready(function(){    
    $("#estado").val('<?php echo $_GET['estado']; ?>');
    $("#btn-actualizar").click(function(evento){
        $('#resp').html('');
        $.ajax({
            type: 'POST', 
            url: 'actualizarMesa',
            dataType: 'html',
            data: $('#form-actualizar-mesa').serialize(),
            success: function (data) { 
                $('#resp').html(data);                
            } 
        });
        return false;
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
                <li class="salir"><a href="../logout.php"><img class="icon-salir"  src="/imagenes/salir.png">Salir</a></li>
			</ul>
		</nav>
  </header>
  
  <main>
        <div class="wrapper">
            <div class="btn-submenu">  
                <a href="altaMesa">  
                <img class="img-submenu" src="/imagenes/nuevo.png" alt="">
                <div class="a-submenu">Nueva Mesa</div>
              </a>
            </div>
            <div class="btn-submenu">
                <a href="listadoMesas">
                  <img class="img-submenu" src="/imagenes/listado.png" alt="">
                  <div class="a-submenu">Listado Mesas</div>
                </a>
            </div>
        </div>
    </main>

    <main class="contenido">
        <div class="form-alta-usuario">
            <form id="form-actualizar-mesa">
                <input type="hidden" name="id_mesa" id="id_mesa" value="<?php echo$_GET['id_mesa']; ?>">
                <table class="alta-usuario">
                    <tr>
                        <td class="izquierda"><span>&#128290; Numero de Mesa : </span></td>
                        <td><input required class="input-alta" type="text" name="numero_mesa" id="numero_mesa" value="<?php echo $_GET['numero_mesa']; ?>"></td>
                        <td class="izquierda"><span>&#9745; Estado : </span></td>
                        <td>
                            <select required class="input-alta" name="estado" id="estado">
                                <option value="-1" disabled selected>Selecciona una opcion</option>
                                <option value="libre">Libre</option>
                                <option value="ocupado">Ocupada</option>
                                <option value="pagado'">Pagado</option>
                                <option value="fueradeservicio">Fuera de servicio</option>
                                <option value="reservado">Reservado</option>             
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="izquierda"><span>&#128205; Ubicacion : </span></td>
                        <td colspan="3"><textarea class="input-alta" name="ubicacion" id="ubicacion" cols="23" rows="3" value="<?php echo $_GET['ubicacion']; ?>"></textarea></td>
                    </tr>
                    <tr>
                        <td class="izquierda" colspan="2"><button id="btn-actualizar" class="btn-actualizar"> ✍ Actualizar</button></td>
                        <td class="derecha" colspan="2"><button class="btn-limpiar" type="reset"> ☒ Limpiar</button></td>
                    </tr>
                </table>               
                <div id="resp"> </div> 
            </form>
            
        </div> 
        
    </main>
</body>
</html>