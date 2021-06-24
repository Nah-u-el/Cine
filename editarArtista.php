<?php

//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

$id_artista = isset($_REQUEST['id_artista'])?SanitizeVars::INT($_REQUEST['id_artista']):FALSE;
$hash = isset($_REQUEST['hash'])?SanitizeVars::STRING($_REQUEST['hash']):FALSE;

if ($id_artista && $hash && (ArrayHash::check($hash, array('id_artista'=>$id_artista)))) {
		//-- levantamos el template a usar
        $tpl = new TemplatePower('templates/editarArtista.html');
        $tpl-> prepare();
		//-- armamos el SQL
		$sql = "SELECT * FROM artista WHERE (id_artista = '$id_artista')";
		$resultado = @mysqli_query($conex,$sql);
		if ($resultado) {
			$mostrarTabla = ($resultado && mysqli_num_rows($resultado) == 1)?true:false;
			if($mostrarTabla){
					while($fila = mysqli_fetch_array($resultado)){
						$tpl->newBlock("editar");
						$tpl->assign("id_artista", $fila['id_artista']);
						$tpl->assign("ar_nombre", $fila['ar_nombre']);
            $tpl->assign("ar_apellido", $fila['ar_apellido']);
            $tpl->assign("ar_dni", $fila['ar_dni']);
            $tpl->assign("ar_mail", $fila['ar_mail']);
						$tpl->assign('hash', ArrayHash::encode(array('id_artista'=>$fila['id_artista'])));
					}
			} else {
				  header("location: listadoArtista.php");
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
	header("location: listadoArtista.php");
}


?>
