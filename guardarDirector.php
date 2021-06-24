<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$botonAceptar = isset($_POST['botonAceptar'])?TRUE:FALSE;
$id_artista = ( isset($_POST['id_artista']) && SanitizeVars::INT($_POST['id_artista']) ) ? SanitizeVars::INT($_POST['id_artista']) : FALSE;
$di_nombreArtistico = ( isset($_POST['di_nombreArtistico']) && SanitizeVars::STRING($_POST['di_nombreArtistico']) ) ? SanitizeVars::STRING($_POST['di_nombreArtistico']) : FALSE;
$hash = ( isset($_POST['hash']) && $_POST['hash']!="" ) ? $_POST['hash'] : FALSE;
if(!$botonAceptar || !$id_artista || !$di_nombreArtistico ){
	 header("location: nuevoDirector.php?msg=Faltan datos Obligatorios");
} else {

		//-- armamos el SQL
    $sql = "INSERT INTO director(id_artista, di_nombreArtistico)
        VALUES ('$id_artista','$di_nombreArtistico')";
		//die($sql);
		$ok = @mysqli_query($conex,$sql);
		//-- informamos del error o continuamos
		if (!$ok) {
			 $errorNro = @mysqli_errno($conex);
			 $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
			 header("location: nuevoDirector.php?msg=$errorMsg");
		}
  header("location: listadoDirector.php");
}
?>
