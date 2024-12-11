<?php
// Incluye el archivo de seguridad para verificar la sesiÃ³n del usuario
include("seguridad.php");
// Verifica si el nivel de acceso del usuario es 8 (administrador)
if ($_SESSION['nivel'] == 8) {
?>
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  <link rel="stylesheet" href="Bootstrap-submenu_files/bootstrap.min.css">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>NODO INTERNET</title>
    <link href="estilo.css" rel="stylesheet" type="text/css" />
    <link rel="icon" href="../img/datos.ico">
  </head>
  <body>
    <article class="container mt-5 justify-content-center">
      <div class="row">
        <div class="col-md-6 offset-md-3 shadow-lg rounded" style="background-color: #F8F9F9;">
          <div class="row pb-4">
            <div class="col p-2 text-white rounded" style="background-color: #922B21;">
              <center>
                <h2>REGISTRO DE USUARIOS</h2>
              </center>
            </div>
          </div>
          <div class="row">
            <div class="col-8 mx-auto">
              <!-- Formulario -->
              <form id="form1" name="form1" method="post" action="grabar_usuario.php">
                <div class="form-group">
                  <label for="usr" style="color:#922B21">USUARIO:</label>
                  <input type="text" class="form-control" id="usr" name="usr">
                </div>
                <div class="form-group">
                  <label for="pwd1" style="color:#922B21">CONTRASE&Ntilde;A:</label>
                  <input type="password" class="form-control" id="pwd1" name="pwd1">
                </div>
                <div class="form-group">
                  <label for="pwd2" style="color:#922B21">REPITA CONTRASE&Ntilde;A:</label>
                  <input type="password" class="form-control" id="pwd2" name="pwd2">
                </div>
                <div class="form-group">
                  <label for="nivel" style="color:#922B21">NIVEL: </label>
                  <select class="form-control form-control-lg" name="nivel" id="nivel">
                    <option disabled selected>Seleccione...</option>
                    <!-- Opciones de nivel de usuario -->
                    <!--   <option value="8">Administrador</option>  -->
                    <option value="5">Turnos EWSD</option>
                    <option value="6">Operador</option>
                    <option value="4">Help Desk</option>
                  </select>
                </div>
                <div class="form-group pb-4">
                  <label for="nombre" style="color:#922B21">NOMBRE:</label>
                  <input type="text" class="form-control" id="nom" name="nom">
                </div>
                <div class="row">
                  <div class="col-5 mx-auto pb-4">
                    <button type="submit" class="btn btn-primary" name="grabar" id="grabar">Registrar Usuario</button>
                  </div>
                  <div class="col-3 mx-auto pb-4">
                    <button type="submit" formaction="salir.php" class="btn btn-primary">Cerrar</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </article>
  </body>
  </html>
<?php
} else
  header("Location: ../index.php");// Redirige al usuario a la pÃ¡gina de inicio si no tiene el nivel de acceso adecuado
?>