<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilosGral.css">
    <link rel="icon" type="image/png" href="/imagenes/cubo.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <title>Seleccionar Mesero</title>
</head>

<script>    
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

    function datosVenta(b) { 
    $.ajax({
        type:'post',
        url:'insertarVenta',
        data: {'id_mesa_venta' : b.dataset.mesa, 'id_usuario_venta' : b.dataset.idusuario}, 
        dataType: "html",
        success: function(resp){                
            $('#resultado-insert-venta').html(resp);       
        }
    });
    return false;   
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
                    window.location.href = 'https://michelocas.kusoftdevelopment.com/'; 
              </script>";
    }else{
    ?>

    <header>
      
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

<main class="abrir-mesa">

<input type="hidden" name="id_mesa" value="">
<div class="formas_pago">
    <span><h1>Selecciona un Mesero(a) : </h1></span>
    <br>
    <?php
    include('conexionbd.php');
    $consulta = "SELECT id_usuario, usuario FROM usuarios WHERE categoria = 'mesero'";
    if ($resultado = $con->query($consulta)) {

        /* obtener el array de objetos */
        while ($fila = $resultado->fetch_row()) {
            $id_usuario = $fila[0];
            $usuario = $fila[1];
            echo "<div class='efectivo'>
                    <a data-mesa='" . $_GET['id_mesa'] . "' data-idusuario='$id_usuario' href='' Onclick='return datosVenta(this)'>
                    <img src='imagenes/mesero.png'>
                    <p>$usuario</p>
                </div>";

        }
    }
    ?>



<!--
    <div class="efectivo">
        <a id="Efectivo" data-mesa='<?php echo $_GET['id_mesa']; ?>' data-idusuario='<?php echo $_SESSION['id_usuario']; ?>' href="" Onclick='return datosVenta(this)'>
        <img src="imagenes/Efectivo.png" alt="Efectivo">
        <p>Efectivo</p>
        </a>
    </div>
    <div class="tarjeta">
        <a id="Tarjeta" data-mesa='<?php echo $_GET['id_mesa']; ?>' data-idusuario='<?php echo $_SESSION['id_usuario']; ?>' href="" Onclick='return datosVenta(this)'>
        <img src="imagenes/Tarjeta.png" alt="Tarjeta">
        <p>Tarjeta</p>
        </a>
    </div>
</div>
-->
<div id="resultado-insert-venta"></div>    
</main>
</body>
<?php
}ob_end_flush();
?>
</html>