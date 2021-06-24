<?php

//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$id_genero = isset($_REQUEST['id_genero'])?SanitizeVars::INT($_REQUEST['id_genero']):FALSE;
$hash = isset($_REQUEST['hash'])?SanitizeVars::STRING($_REQUEST['hash']):FALSE;

if ($id_genero && $hash && (ArrayHash::check($hash, array('id_genero'=>$id_genero)))) {
		//-- levantamos el template a usar
        $tpl = new TemplatePower('templates/editarGenero.html');
        $tpl-> prepare();
		//-- armamos el SQL
		$sql = "SELECT * FROM genero WHERE (id_genero = '$id_genero')";
		$resultado = @mysqli_query($conex,$sql);
		if ($resultado) {
			$mostrarTabla = ($resultado && mysqli_num_rows($resultado) == 1)?true:false;
			if($mostrarTabla){
					while($fila = mysqli_fetch_array($resultado)){
						$tpl->newBlock("editar");
						$tpl->assign("id_genero", $fila['id_genero']);
						$tpl->assign("ge_nombre", $fila['ge_nombre']);
						$tpl->assign('hash', ArrayHash::encode(array('id_genero'=>$fila['id_genero'])));
					}
			} else {
				  header("location: listadoGenero.php");
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
	header("location: listadoGenero.php");
}


?>
