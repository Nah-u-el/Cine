<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

/**
 segundo paso controlar los datos obligatorios, si faltan informar y
  volvemos a la pantalla de listado
*/
$id_artista = isset($_GET['id_artista'])?SanitizeVars::INT($_GET['id_artista']):FALSE;
$hash = isset($_GET['hash'])?SanitizeVars::STRING($_GET['hash']):FALSE;
$errorMsg = "";
	//-- armamos el SQL
	$errorMsg = "";

  $sql = "DELETE FROM artista WHERE (artista.id_artista='$id_artista')";
	$ok = @mysqli_query($conex, $sql);

	if(!$ok){
		$errorNro = @mysqli_errno($conex);
		$errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
		switch($errorNro){
			case 1451:
				$errorMsg = "No puede eliminar este artista porque tiene peliculas asociadas!!";
				break;
		}
	} else{
		$errorMsg = "artista Eliminado!";
	};

header('location: listadoArtista.php?msg='.$errorMsg);
?>
