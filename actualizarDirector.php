<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$botonAceptar = isset($_POST['botonAceptar'])?TRUE:FALSE;
$id_director = ( isset($_POST['id_director']) &&  SanitizeVars::INT($_POST['id_director']) ) ? SanitizeVars::INT($_POST['id_director']) : FALSE;
$di_nombreArtistico = ( isset($_POST['di_nombreArtistico']) && SanitizeVars::STRING($_POST['di_nombreArtistico']) ) ? SanitizeVars::STRING($_POST['di_nombreArtistico']) : FALSE;
$hash = ( isset($_POST['hash']) && $_POST['hash']!="" ) ? $_POST['hash'] : FALSE;


if(!$conex){
    $errorNro = @mysqli_errno($conex);
    $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
    $url = "location: editarDirector.php?msg=$errorMsg&id_director=$id_director&hash=$hash";
    header($url);
} else {
    if(!$id_director || !ArrayHash::check($hash, array('id_director'=>$id_director))){
        $errorMsg = "No Coincide el Hash con el ID del Genero";
        $url = "location: editarDirector.php?msg=$errorMsg&id_director=$id_director&hash=$hash";
        header($url);
    } else {
        //-- armamos el SQL
        $sql = "UPDATE director
                SET di_nombreArtistico = '$di_nombreArtistico'
                WHERE (id_director = '$id_director')";
        //die($sql);
        $ok = @mysqli_query($conex,$sql);
        //-- informamos del error o continuamos
        if (!$ok) {
            $errorNro = @mysqli_errno($conex);
            $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
            $url = "location: editarDirector.php?msg=$errorMsg&id_director=$id_director&hash=$hash";
            header($url);
        } else header("location: listadoDirector.php");
    }




}

?>
