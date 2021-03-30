<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
    <link rel="stylesheet" href="index.css">
    <link rel="icon" type="image/png" href="/imagenes/LogoMichelocas.png" />
    <title>Acceso | Michelocas</title>

</head>
<body>

  <div class="login-box">
        <img class="avatar" src="imagenes/LogoMichelocas.png" alt="Michelocas">
        <h1>Acceso Michelocas</h1>
       <form id="formulario" method="post" action="validarUsuario">
           <table>
           <tr>               
               <td><span class="span-titulo">Usuario :</span></td><td><img src="imagenes/user.png"></td>
               <td><input class="input_form" type="text" name="usuario" placeholder="Escribe tu usuario"></td>
           </tr>
           <tr>
           <td><span class="span-titulo">Password :</span></td><td><img src="imagenes/password.png"></td>
           <td><input class="input_form" type="password" name="contrasena" placeholder="Escribe tu password"></td>
           </tr>
           </table>
           <input class="acceso" type="submit" value="Ingresar" id="btn-ingresar">
       </form>
  </div> 

</body>
</html>
    