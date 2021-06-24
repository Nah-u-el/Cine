<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

//-- levantamos el template a usar
$tpl = new TemplatePower('templates/listadoDirector.html');
$tpl-> prepare();

if( isset($_POST['botonBuscar']) && $_POST['botonBuscar'] ){
		//-- limpio la entrada
		$di_nombreArtistico = isset($_POST['di_nombreArtistico'])?SanitizeVars::SQL($_POST['di_nombreArtistico']):FALSE;
		//-- armo el filtro de búsqueda
		$where = array();

		if($di_nombreArtistico){
			$where[] = "(di_nombreArtistico LIKE '%{$di_nombreArtistico}%')";
			$tpl->assign("di_nombreArtistico", $di_nombreArtistico);
		}

	  $where = implode('AND', $where);
	  $sql = "SELECT * FROM director ".(!empty($where)?"WHERE {$where}":"");
} else {
		$sql = "SELECT * FROM director ORDER BY di_nombreArtistico ASC";
}

//-- EJECUTAMOS LA CONSULTA Y MOSTRAMOS EL RESULTADO
$resultado = @mysqli_query($conex,$sql);
if ($resultado) {
			$mostrarTabla = ($resultado && mysqli_num_rows($resultado) > 0)?true:false;
			if($mostrarTabla){
					while($fila = mysqli_fetch_array($resultado)){
						$tpl->newBlock("listado");
						$tpl->assign("id_director", $fila['id_director']);
						$tpl->assign("id_artista", $fila['id_artista']);
						$tpl->assign("di_nombreArtistico", $fila['di_nombreArtistico']);
						$tpl->assign('hash', ArrayHash::encode(array('id_director'=>$fila['id_director'])));
					}
			} else {
				  $tpl->newBlock("no_resultado");
			}
} else {
      $errorNo = mysqli_errno($conex);
			$error = mysqli_error($conex);
			$error_completo = $errorNo.': '.$error;
			$tpl->newBlock("mensaje");
			$tpl->assign("mensaje_tipo", $error_completo);

}
//-- enviamos el template al navegador
echo $tpl->getOutputContent();
?>
