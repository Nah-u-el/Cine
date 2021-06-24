<?php
//-- colocamos el path de las librerías en el entorno
set_include_path("./recursos/_php/".PATH_SEPARATOR."./includes/");

//-- incluimos la libreria
include_once("class.TemplatePower.inc.php");
include_once('conexion.php');
include_once('sanitize.class.php');
include_once('arrayHash.class.php');

//-- levantamos el template a usar
$tpl = new TemplatePower('templates/listadoGenero.html');
$tpl-> prepare();

if( isset($_POST['botonBuscar']) && $_POST['botonBuscar'] ){
		//-- limpio la entrada
		$ge_nombre = isset($_POST['ge_nombre'])?SanitizeVars::SQL($_POST['ge_nombre']):FALSE;
		//-- armo el filtro de búsqueda
		$where = array();

		if($ge_nombre){
			$where[] = "(ge_nombre LIKE '%{$ge_nombre}%')";
			$tpl->assign("ge_nombre", $ge_nombre);
		}

	  $where = implode('AND', $where);
	  $sql = "SELECT * FROM genero ".(!empty($where)?"WHERE {$where}":"");
} else {
		$sql = "SELECT * FROM genero ORDER BY ge_nombre ASC";
}

//-- EJECUTAMOS LA CONSULTA Y MOSTRAMOS EL RESULTADO
$resultado = @mysqli_query($conex,$sql);
if ($resultado) {
			$mostrarTabla = ($resultado && mysqli_num_rows($resultado) > 0)?true:false;
			if($mostrarTabla){
					while($fila = mysqli_fetch_array($resultado)){
						$tpl->newBlock("listado");
						$tpl->assign("id_genero", $fila['id_genero']);
						$tpl->assign("ge_nombre", $fila['ge_nombre']);
						$tpl->assign('hash', ArrayHash::encode(array('id_genero'=>$fila['id_genero'])));
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
