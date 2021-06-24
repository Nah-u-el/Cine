<?php

//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$id_director = isset($_REQUEST['id_director'])?SanitizeVars::INT($_REQUEST['id_director']):FALSE;
$hash = isset($_REQUEST['hash'])?SanitizeVars::STRING($_REQUEST['hash']):FALSE;

if ($id_director && $hash && (ArrayHash::check($hash, array('id_director'=>$id_director)))) {
		//-- levantamos el template a usar
        $tpl = new TemplatePower('templates/editarDirector.html');
        $tpl-> prepare();
		//-- armamos el SQL
		$sql = "SELECT * FROM director WHERE (id_director = '$id_director')";
		$resultado = @mysqli_query($conex,$sql);
		if ($resultado) {
			$mostrarTabla = ($resultado && mysqli_num_rows($resultado) == 1)?true:false;
			if($mostrarTabla){
					while($fila = mysqli_fetch_array($resultado)){
						$tpl->newBlock("editar");
						$tpl->assign("id_director", $fila['id_director']);
						$tpl->assign("di_nombreArtistico", $fila['di_nombreArtistico']);
						$tpl->assign('hash', ArrayHash::encode(array('id_director'=>$fila['id_director'])));
					}
			} else {
				  header("location: listadoDirector.php");
			}
			if(isset($_GET['msg']) && $_GET['msg']!=""){
				$tpl->newBlock("mensaje");
				$tpl->assign("mensaje_tipo", $_GET['msg']);
			}

		} else {
			$errorNo = mysqli_errno($conex);
			$error = mysqli_error($conex);
			$error_completo = $errorNo.': '.$error;
			$tpl->newBlock("mensaje");
			$tpl->assign("mensaje_tipo", $error_completo);
		};

		//-- enviamos el template al navegador
		echo $tpl->getOutputContent();

} else {
	header("location: listadoDirector.php");
}


?>
