<?php /*
error_reporting(E_ALL); // Configura PHP para reportar todos los errores.
ini_set('display_errors', 1); // Muestra los errores en la pagina 
*/

session_start();
if(!empty($_SESSION['check'])){ //preguntamos si se activo guardar contraseña
  $usr=$_SESSION['usr'];    //asignamos el nombre del usuario a una variable
  $pwd=$_SESSION['pwd'];        //asignamos la contraseña del usuario a una variable
}
?>3
<!DOCTYPE html>
<!-- saved from url=(0051)https://getbootstrap.com/docs/4.0/examples/sign-in/ -->
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=divice-width, user-scalable=no, initial-scale=1.0" , maximum-scale=1.0, minimum=1.0>
    <link rel="stylesheet" href="includes/display-data.css"/>
    <link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bootstrap-4.1.3-dist/css/all.css">
    <script src="bootstrap-4.1.3-dist/js/jquery.min.js"></script>
    <script src="bootstrap-4.1.3-dist/js/popper.min.js"></script>
    <script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>     
   
    <script src="soia/Bootstrap-submenu_files/docs.js" defer=""></script>  
     
    <!-- CSS PARA EL FORMS DE USSER Y PASS -->
    <link href="bootstrap-4.1.3-dist/css/signin.css" rel="stylesheet">

    <link rel="icon" href="img/datos.ico">
    <title>inicio</title>  

</head>
<script>
window.setInterval (BlinkIt, 500);
var color = "red";
function BlinkIt () {
var blink = document.getElementById ("blink");
color = (color == "#ffffff")? "red" : "#ffffff";
blink.style.color = color;
blink.style.fontSize='40px';}
</script>
  <body class="text-center">

    <form class="form-signin" action="soia/control.php" method="post">
      <div class="container">
        <div class="row">
            <div class="row">
              <div class="col-12">
                  <div class="col-6">
                    <img class="mb-4" src="img/datos.png" alt="" width="250" height="52">
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
                <body style="background-color:#FDFBFB;"><img class="mb-4" src="img/nodo.gif" alt="" width="60" height="60"></body>
              </div>    
          </div>
        </div>
      </div>  
      <article id="admin"><br>
        <div class="form-row"> 
              <div class="col-12">            
               <!-- preguntamos si se activo remember-me y colocamos el nombre del usuario -->
              <input style="color: #050168; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.035) inset; height: 20px; font-size: 15px; border: 0 solid #A20404;" type="text-center" name="usr" id="usr" class="form-check-input-rent" required placeholder="Usuario" value="<?php if(!empty($_SESSION['check'])){echo $usr; }?>"> 
              </div>               
        </div> 
        </br>
        <div class="form-row" s> 
              <div class="col-12"> 
                <!-- preguntamos si se activo remember-me y colocamos la contraseña -->
                <input style="height: 20px; font-size: 15px; border: 0 solid #A20404;" type="password" name="pwd" id="pwd" class="form-control-rent" placeholder="Password" required value="<?php if(!empty($_SESSION['check'])){echo $pwd; }?>">
              </div>               
        </div>
        </br>          
        <div class="checkbox mb-12">       
            <!-- preguntamos si se activo remember-me y lo activamos si fue activado previamente -->
            <input type="checkbox" name="remember-me" id="remember-me" value="1" <?php if(!empty($_SESSION['check']))echo 'checked';?>> Recuerdame        
        </div>
        
        <button class="btn btn-lg btn-primary btn-block" type="submit">Ingresar</button> <br>

        <a class="btn btn-success" href="soia/cambio_login.php" target="_blank" onclick="window.open(this.href, this.target, 'width=550,height=250'); return false;">Actualiza Password</a>
        <br>
        <script>
        var d = new Date();
        document.write('Fecha: '+d.getDate(),'/'+d.getMonth(),'/'+d.getFullYear(),'<br>Dia de la semana: '+d.getDay(),'<br>Hora: '+d.getHours(),':'+d.getMinutes(),':'+d.getSeconds(),'<br>Hora UTC: '+d.getUTCHours());
        </script>
        <div class="form-row"> 
              <div class="col-12"> 
                <p class="mt-2 mb-3 text-muted">Â© SISTEMA DE GESTION UNI-SOIA</p>
                <p class="mt-2 mb-3 text-muted">**** 2024-2025 ****</p>
              </div>
        </div>
      </article>
    </form>

</body>
</html>