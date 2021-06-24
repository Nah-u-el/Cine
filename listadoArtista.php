<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

//-- levantamos el template a usar
$tpl = new TemplatePower('templates/listadoArtista.html');
$tpl-> prepare();

if( isset($_POST['botonBuscar']) && $_POST['botonBuscar'] ){
		//-- limpio la entrada
		$ar_nombre = isset($_POST['ar_nombre'])?SanitizeVars::SQL($_POST['ar_nombre']):FALSE;
		//-- armo el filtro de búsqueda
		$where = array();

		if($ar_nombre){
			$where[] = "(ar_nombre LIKE '%{$ar_nombre}%')";
      $tpl->assign("ar_nombre", $ar_nombre);
		}

	  $where = implode('AND', $where);
	  $sql = "SELECT * FROM artista ".(!empty($where)?"WHERE {$where}":"");
} else {
		$sql = "SELECT * FROM artista ORDER BY ar_nombre ASC";
}

//-- EJECUTAMOS LA CONSULTA Y MOSTRAMOS EL RESULTADO
$resultado = @mysqli_query($conex,$sql);
if ($resultado) {
			$mostrarTabla = ($resultado && mysqli_num_rows($resultado) > 0)?true:false;
			if($mostrarTabla){
					while($fila = mysqli_fetch_array($resultado)){
						$tpl->newBlock("listado");
						$tpl->assign("id_artista", $fila['id_artista']);
						$tpl->assign("ar_nombre", $fila['ar_nombre']);
            $tpl->assign("ar_apellido", $fila['ar_apellido']);
            $tpl->assign("ar_dni", $fila['ar_dni']);
            $tpl->assign("ar_mail", $fila['ar_mail']);
						$tpl->assign('hash', ArrayHash::encode(array('id_artista'=>$fila['id_artista'])));
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
