<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$botonAceptar = isset($_POST['botonAceptar'])?TRUE:FALSE;
$id_genero = ( isset($_POST['id_genero']) &&  SanitizeVars::INT($_POST['id_genero']) ) ? SanitizeVars::INT($_POST['id_genero']) : FALSE;
$ge_nombre = ( isset($_POST['ge_nombre']) && SanitizeVars::STRING($_POST['ge_nombre']) ) ? SanitizeVars::STRING($_POST['ge_nombre']) : FALSE;
$hash = ( isset($_POST['hash']) && $_POST['hash']!="" ) ? $_POST['hash'] : FALSE;


if(!$conex){
    $errorNro = @mysqli_errno($conex);
    $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
    $url = "location: editarGenero.php?msg=$errorMsg&id_genero=$id_genero&hash=$hash";
    header($url);
} else {
    if(!$id_genero || !ArrayHash::check($hash, array('id_genero'=>$id_genero))){
        $errorMsg = "No Coincide el Hash con el ID del Genero";
        $url = "location: editarGenero.php?msg=$errorMsg&id_genero=$id_genero&hash=$hash";
        header($url);
    } else {
        //-- armamos el SQL
        $sql = "UPDATE genero
                SET ge_nombre = '$ge_nombre'
                WHERE (id_genero = '$id_genero')";
        //die($sql);
        $ok = @mysqli_query($conex,$sql);
        //-- informamos del error o continuamos
        if (!$ok) {
            $errorNro = @mysqli_errno($conex);
            $errorMsg = "Error({$errorNro}): ".@mysqli_error($conex);
            $url = "location: editarGenero.php?msg=$errorMsg&id_genero=$id_genero&hash=$hash";
            header($url);
        } else header("location: listadoGenero.php");
    }




}

?>
