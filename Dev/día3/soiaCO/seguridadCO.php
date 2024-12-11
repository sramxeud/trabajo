<?php
session_start();
if($_SESSION['ingreso']!="accesopermitido"){
	header("Location:index.php?error=2");	
}
?>
