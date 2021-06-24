<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$botonAceptar = isset($_POST['botonAceptar'])?TRUE:FALSE;
$ar_nombre = ( isset($_POST['ar_nombre']) && SanitizeVars::STRING($_POST['ar_nombre']) ) ? SanitizeVars::STRING($_POST['ar_nombre']) : FALSE;
$ar_apellido = ( isset($_POST['ar_apellido']) && SanitizeVars::STRING($_POST['ar_apellido']) ) ? SanitizeVars::STRING($_POST['ar_apellido']) : FALSE;
$ar_dni = ( isset($_POST['ar_dni']) && SanitizeVars::INT($_POST['ar_dni']) ) ? SanitizeVars::INT($_POST['ar_dni']) : FALSE;
$ar_mail = ( isset($_POST['ar_mail']) && SanitizeVars::EMAIL($_POST['ar_mail']) ) ? SanitizeVars::EMAIL($_POST['ar_mail']) : FALSE;
$hash = ( isset($_POST['hash']) && $_POST['hash']!="" ) ? $_POST['hash'] : FALSE;
if(!$botonAceptar || !$ar_nombre || !$ar_apellido  || !$ar_dni || !$ar_mail){
	 header("location: nuevoArtista.php?msg=Faltan datos Obligatorios");
} else {

		//-- armamos el SQL
    $sql="INSERT INTO artista(ar_nombre, ar_apellido, ar_dni, ar_mail)
    VALUES ('$ar_nombre','$ar_apellido','$ar_dni','$ar_mail')";
    $ok = @mysqli_query($conex,$sql);
		//die($sql);

		//-- informamos del error o continuamos
		if (!$ok) {
			 $errorNro = @mysqli_errno($conex);
			 $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
			 header("location: nuevoArtista.php?msg=$errorMsg");
		}
  header("location: listadoArtista.php");
}
?>
