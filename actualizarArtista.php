<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$botonAceptar = isset($_POST['botonAceptar'])?TRUE:FALSE;
$id_artista = ( isset($_POST['id_artista']) &&  SanitizeVars::INT($_POST['id_artista']) ) ? SanitizeVars::INT($_POST['id_artista']) : FALSE;
$ar_nombre = ( isset($_POST['ar_nombre']) && SanitizeVars::STRING($_POST['ar_nombre']) ) ? SanitizeVars::STRING($_POST['ar_nombre']) : FALSE;
$ar_apellido = ( isset($_POST['ar_apellido']) && SanitizeVars::STRING($_POST['ar_apellido']) ) ? SanitizeVars::STRING($_POST['ar_apellido']) : FALSE;
$ar_dni = ( isset($_POST['ar_dni']) && SanitizeVars::STRING($_POST['ar_dni']) ) ? SanitizeVars::STRING($_POST['ar_dni']) : FALSE;
$ar_mail = ( isset($_POST['ar_mail']) && SanitizeVars::EMAIL($_POST['ar_mail']) ) ? SanitizeVars::EMAIL($_POST['ar_mail']) : FALSE;
$hash = ( isset($_POST['hash']) && $_POST['hash']!="" ) ? $_POST['hash'] : FALSE;


if(!$conex){
    $errorNro = @mysqli_errno($conex);
    $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
    $url = "location: editarArtista.php?msg=$errorMsg&id_artista=$id_artista&hash=$hash";
    header($url);
} else {
    if(!$id_artista || !ArrayHash::check($hash, array('id_artista'=>$id_artista))){
        $errorMsg = "No Coincide el Hash con el ID del Artista";
        $url = "location: editarArtista.php?msg=$errorMsg&id_artista=$id_artista&hash=$hash";
        header($url);
    } else {
        //-- armamos el SQL
        $sql = "UPDATE artista
                SET ar_nombre='$ar_nombre',ar_apellido='$ar_apellido',ar_dni='$ar_dni',ar_mail='$ar_mail'
                WHERE (id_artista = '$id_artista')";
        //die($sql);
        $ok = @mysqli_query($conex,$sql);
        //-- informamos del error o continuamos
        if (!$ok) {
            $errorNro = @mysqli_errno($conex);
            $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
            $url = "location: editarArtista.php?msg=$errorMsg&id_artista=$id_artista&hash=$hash";
            header($url);
        } else header("location: listadoArtista.php");
    }




}

?>
