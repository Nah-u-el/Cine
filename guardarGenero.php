<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$botonAceptar = isset($_POST['botonAceptar'])?TRUE:FALSE;
$ge_nombre = ( isset($_POST['ge_nombre']) && SanitizeVars::STRING($_POST['ge_nombre']) ) ? SanitizeVars::STRING($_POST['ge_nombre']) : FALSE;
$hash = ( isset($_POST['hash']) && $_POST['hash']!="" ) ? $_POST['hash'] : FALSE;
if(!$botonAceptar || !$ge_nombre ){
	 header("location: nuevoGenero.php?msg=Faltan datos Obligatorios");
} else {

		//-- armamos el SQL
		$sql = "INSERT INTO genero(ge_nombre)
				VALUES('$ge_nombre')";
		//die($sql);
		$ok = @mysqli_query($conex,$sql);
		//-- informamos del error o continuamos
		if (!$ok) {
			 $errorNro = @mysqli_errno($conex);
			 $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
			 header("location: nuevoGenero.php?msg=$errorMsg");
		}
  header("location: listadoGenero.php");
}
?>
