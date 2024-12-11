<?php
//include("seguridad.php");
//if($_SESSION['nivel']==8)
{
?>
<!DOCTYPE html>
<html LANG="es">
<head>
<meta charset="utf-8">
	<meta name="viewport" content="width=divice-width, user-scalable=no, initial-scale=1.0" , maximum-scale=1.0, minimum=1.0>
	<link rel="stylesheet" href="../bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<script src="../bootstrap-4.1.3-dist/js/jquery.min.js"></script>
	<script src="../bootstrap-4.1.3-dist/js/popper.min.js"></script>
	<script src="../bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
<title>NODO INTERNET</title>
<link href="estilo.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<form id="form1" name="form1" method="post" action="grabar_nuevo_login.php">
  				<table width="500" border="0" align="center" cellpadding="5" cellspacing="0">
    				  <tr>
      					<td colspan="2" class="titulocentro">CAMBIO DE PASSWORD DE INGRESO</td>
    					</tr>
    					<tr>
      					<td width="207" class="texto2">CODIGO-USR.: </td>
      					<td width="273"><input type="text" name="usr" id="usr" /></td>
    					</tr>
    					<tr>
      					<td class="texto2">CONTRASEÃ‘A ACTUAL: </td>
      					<td><input type="password" name="pwd1" id="pwd1" /></td>   
    					</tr>
    					<tr>
      					<td class="texto2">NUEVA CONTRASEÃ‘A: </td>
      					<td><input type="password" name="pwd2" id="pwd2" /></td>
    					</tr>
    					<tr>
      					<td class="texto2">REPITA CONTRASEÃ‘A: </td>
      					<td><input name="pwd3" type="password" id="pwd3" size="40" /></td>
    					</tr>
    					<tr>
      					<td colspan="2" class="centro">
      					<input type="submit" name="grabar" id="grabar" value="Registrar Cambio" /> 
      					<input type="submit" onclick="window.close();" value="Cerrar" /> 
     					</td>
    				  </tr>
  				</table>			
			</form>	
		</div>
	</div>
</div>

</body>
</html>
<?php
}
//else
	//header("Location:../index.php");
?>