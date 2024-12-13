<?php
/*
error_reporting(E_ALL); // Configura PHP para reportar todos los errores.
ini_set('display_errors', 1); // Muestra los errores en la pagina 
*/

session_start();
$bloqueado = false;
$tiempo_restante = 0;

if (!empty($_SESSION['bloqueado']) && $_SESSION['bloqueado'] > time()) {
    $bloqueado = true;
    $tiempo_restante = $_SESSION['bloqueado'] - time();
}

if (!empty($_SESSION['check'])) { // Preguntamos si se activó guardar contraseña
  $usr = $_SESSION['usr'];    // Asignamos el nombre del usuario a una variable
  $pwd = $_SESSION['pwd'];    // Asignamos la contraseña del usuario a una variable
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF - 8">
    
    <meta charset="utf - 8">
    <meta name="viewport" content="width=divice-width, user-scalable=no, initial-scale=1.0" , maximum-scale=1.0, minimum=1.0>
    <link rel="stylesheet" href="/v2/includes/display-data.css"/>
    <link rel="stylesheet" href="/v2/bootstrap-4.1.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/v2/bootstrap-4.1.3-dist/css/all.css">
    <script src="/v2/bootstrap-4.1.3-dist/js/jquery.min.js"></script>
    <script src="/v2/bootstrap-4.1.3-dist/js/popper.min.js"></script>
    <script src="/v2/bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>     
   
    <script src="/v2/Ejm/soia/Bootstrap-submenu_files/docs.js" defer=""></script>  
     
    <!-- CSS PARA EL FORM DE USUARIO Y CONTRASEÑA -->
    <link href="/v2/bootstrap-4.1.3-dist/css/signin.css" rel="stylesheet">

    <link rel="icon" href="/v2/img/datos.ico">
    <title>Inicio</title>  

</head>
<script>
window.setInterval (BlinkIt, 500);
var color = "red";
function BlinkIt () {
var blink = document.getElementById ("blink");
color = (color == "#ffffff")? "red" : "#ffffff";
blink.style.color = color;
blink.style.fontSize='40px';}

document.addEventListener("DOMContentLoaded", function() {
    var formBlocked = <?php echo $bloqueado ? 'true' : 'false'; ?>;
    if (formBlocked) {
        // Desactivar la tecla F5 y el botón de recarga
        document.addEventListener('keydown', function(event) {
            if (event.keyCode === 116) {
                event.preventDefault();
            }
        });

        // Desactivar el botón de recarga
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        // Habilitar campos después del tiempo de bloqueo
        var segundos = <?php echo $tiempo_restante; ?>;
        var interval = setInterval(function() {
            segundos--;
            document.getElementById("segundos").innerText = segundos;
            if (segundos <= 0) {
                clearInterval(interval);
                document.getElementById("usr").disabled = false;
                document.getElementById("pwd").disabled = false;
                document.getElementById("remember-me").disabled = false;
                document.getElementById("login-button").disabled = false;
                var contador = document.getElementById("contador");
                if (contador) {
                    contador.style.display = "none";                               
                  }
            }
        }, 1000);
    }
});
</script>
<body class="text-center">
<?php
if (isset($_GET['error'])) {
  $error = $_GET['error'];
  if ($error == 1) {
      echo '<div id="alert1" class="alert alert-danger" role="alert">Usuario o contraseña incorrecta.</div>';
      echo '<script>
              setTimeout(function() {
                  document.getElementById("alert1").style.display = "none";
              }, 1000);
            </script>';
  } elseif ($error == 2) {
      echo '<div id="alert2" class="alert alert-warning" role="alert">Demasiados intentos fallidos. Intente nuevamente en <span id="segundos">' . $tiempo_restante . '</span> segundos.</div>';
      echo '<script>
              setTimeout(function() {
                  document.getElementById("alert2").style.display = "none";
              }, 1000);
            </script>';
  } elseif ($error == 3) {
      echo '<div id="alert3" class="alert alert-warning" role="alert">Demasiados intentos fallidos. Intente nuevamente en <span id="contador">10</span> segundos.</div>';
      echo '<script>
              var contador = 9;
              var intervalo = setInterval(function() {
                  document.getElementById("contador").innerText = contador;
                  contador--;
                  if (contador < 0) {
                      clearInterval(intervalo);
                      document.getElementById("alert3").style.display = "none";
                  }
              }, 1000);
            </script>';
  }
}
?>

    <form class="form-signin" action="/v2/Ejm/control.php" method="post">
      <div class="container">
        <div class="row">
            <div class="row">
              <div class="col-12">
                  <div class="col-6">
                    <img class="mb-4" src="/v2/img/datos.png" alt="" width="250" height="52">
                  </div>        
              </div>
            </div>
          <div class="row">
              <div class="col-12" id="blink" onclick="#">
                <center><h2 class="h3 mb-3 font-weight-normal">Solo Personal Autorizado !!!!!</h2></center>
              </div>      
          </div>
          <div class="row">
              <div class="col-8">
               <h3 class="mb-4 font-weight-normal">Nodo Internet</h3> 
              </div>  
              <div class="col-4">
                <body style="background-color:#FDFBFB;"><img class="mb-4" src="/v2/img/nodo.gif" alt="" width="60" height="60"></body>
              </div>    
          </div>
        </div>
      </div>  
      <article id="admin"><br>
        <div class="form-row"> 
              <div class="col-12">            
               <!-- Preguntamos si se activó remember-me y colocamos el nombre del usuario -->
              <input style="color: #050168; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.035) inset; height: 20px; font-size: 15px; border: 0 solid #A20404;" type="text-center" name="usr" id="usr" class="form-check-input-rent" required placeholder="Usuario" value="<?php if(!empty($_SESSION['check'])){echo $usr; }?>" <?php echo $bloqueado ? 'disabled' : ''; ?>> 
              </div>               
        </div> 
        </br>
        <div class="form-row" s> 
              <div class="col-12"> 
                <!-- Preguntamos si se activó remember-me y colocamos la contraseña -->
                <input style="height: 20px; font-size: 15px; border: 0 solid #A20404;" type="password" name="pwd" id="pwd" class="form-control-rent" placeholder="Password" required value="<?php if(!empty($_SESSION['check'])){echo $pwd; }?>" <?php echo $bloqueado ? 'disabled' : ''; ?>>
              </div>               
        </div>
        </br>          
        <div class="checkbox mb-12">       
            <!-- Preguntamos si se activó remember-me y lo activamos si fue activado previamente -->
            <input type="checkbox" name="remember-me" id="remember-me" value="1" <?php if(!empty($_SESSION['check']))echo 'checked';?> <?php echo $bloqueado ? 'disabled' : ''; ?>> Recuerdame        
        </div>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit" id="login-button" <?php echo $bloqueado ? 'disabled' : ''; ?>>Ingresar</button> <br>

        <a class="btn btn-success" href="/v2/soia/cambio_login.php" target="_blank" onclick="window.open(this.href, this.target, 'width=550,height=250'); return false;">Actualiza Password</a>
        <br>
        <script>
        var d = new Date();
        document.write('Fecha: '+d.getDate(),'/'+d.getMonth(),'/'+d.getFullYear(),'<br>Dia de la semana: '+d.getDay(),'<br>Hora: '+d.getHours(),':'+d.getMinutes(),':'+d.getSeconds(),'<br>Hora UTC: '+d.getUTCHours());
        </script>
        <div class="form-row"> 
              <div class="col-12"> 
                <p class="mt-2 mb-3 text-muted">© SISTEMA DE GESTION UNI-SOIA</p>
                <p class="mt-2 mb-3 text-muted">**** 2024-2025 ****</p>
              </div>
        </div>
        <?php
        if ($bloqueado) {
            echo '<div id="contador" class="alert alert-warning" role="alert">Intentar nuevamente en <span id="segundos">' . $tiempo_restante . '</span> segundos.</div>';
        }
        ?>
      </article>
    </form>

</body>
</html>
