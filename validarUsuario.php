<?php
include("conexionbd.php");
$usuario = $_POST['usuario'];
$contrasena = $_POST['contrasena'];

$result = mysqli_query($con, "SELECT * FROM usuarios where usuario=" . "'" . $usuario . "'");

$row = mysqli_fetch_assoc($result);
$hash = $row['contrasena'];
$id_usuario =  $row['id_usuario'];

if (password_verify($contrasena, $hash)) {
    session_start();
    date_default_timezone_set('America/Mexico_City');
    session_name($row['usuario']);
    $_SESSION["id_usuario"] = $row['id_usuario'];    
    $_SESSION["usuario"] = $row['usuario'];
    $_SESSION["categoria"] = $row['categoria'];
    $_SESSION["autentificado"] = "SI";
    $query = "UPDATE usuarios SET estado = '1' WHERE id_usuario =". $row['id_usuario'];
    $result = mysqli_query($con, $query);
    switch ($row['categoria']) {
        case 'administrador':
            echo '<script type="text/javascript"> 
            alert("Bienvenido");	
            window.location.href="/ventas/menuPrincipal";
            </script>';   
            break;
        case 'cajero':
            echo '<script type="text/javascript"> 
            alert("Bienvenido");	
            window.location.href="menuPrincipalCajero";
            </script>';   
        break;
        case 'mesero':
            echo '<script type="text/javascript"> 
            alert("Bienvenido");	
            window.location.href="menuPrincipalMeseros";
            </script>';   
            break;
        case 'cocinero':
            echo '<script type="text/javascript"> 
            alert("Bienvenido");	
            window.location.href="menuPrincipalCocina";
            </script>';   
            break;
    }    
  
    
 } else {
    echo '<script type="text/javascript"> 
	alert("USUARIO O CONTRASEÑA INCORRECTOS");
	window.location.href="https://michelocas.kusoftdevelopment.com/";
    </script>';   
} 


?>