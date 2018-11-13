<?php

/**
 * Listado con todas las funciones utilizadas por el sistema
 *
 * Las funciones aca son genericas y pueden ser utilizadas por cualquier sistema
 *
 * @author iberlot
 * @version 20151223
 * @package Mytthos
 * @category Config
 *
 */

/**
 * Devuelve el usuario encerrado entre parentesis
 *
 * @param string $cadena
 *        	Cadena en la que se encuetra el texto encerrado entre parentesis a extraer
 * @return string $final Texto que se encuentra entre parentesis extraido para su uso
 *
 */
function usuario($cadena)
{
	$maximo = strlen ($cadena);
	$ide = "(";
	$ide2 = ")";
	$total = strpos ($cadena, $ide);
	$total2 = stripos ($cadena, $ide2);
	$total3 = ($maximo - $total2 - 1);
	$final = substr ($cadena, $total + 1, -1);

	return $final;
}

/**
 * Devuelve los datos del usuario Referente
 *
 * @param int $anio
 *        	a�o del requerimiento del que se quiere saber el referente
 * @param int $reque
 *        	Numero del requerimiento del que se quiere saber el referente
 *
 * @return string $final Texto que se encuentra entre parentesis extraido para su uso
 */
function usrRefernt($anio, $reque)
{
	include ("config.php");
	include ("conexion.php");

	$sqlUsrRefernet = "SELECT NRO_DOC, TIPO_DOC FROM PORTAL.USRREFERENT Where  ANIO = '" . $anio . "' and REQUERIMIENTO = '" . $reque . "'";
	$stmtRefer = oci_parse ($linkOracle, $sqlUsrRefernet);
	oci_execute ($stmtRefer) or die (' Error en sqlUsrRefernet ' . var_dump ($sqlUsrRefernet) . ' en linea ' . __LINE__);
	$refer = oci_fetch_array ($stmtRefer, OCI_ASSOC + OCI_RETURN_NULLS);

	$referNumDoc = $refer['NRO_DOC'];
	$referTipDoc = $refer['TIPO_DOC'];

	list ($nuevoReqRefer, $referentCuenta, $EmailRefernt) = datosPersona ($refer['NRO_DOC'], $refer['TIPO_DOC']);

	return array (
			$nuevoReqRefer,
			$referentCuenta,
			$EmailRefernt
	);
}

/**
 * Para el admin, imprime el link para abrir el ligthbox para subir y recortar la foto de $idRegistro, $tabla que se llame $tipoFoto
 * Opcionalmente puede imprimir la imagen al lado del link
 *
 * @param boolean $incluirImagen
 *        	Imprime la imagen ademas del link
 * @param string $textoLink
 *        	El texto del link para editar la imagen
 *        	@idRegistro int El id del registro al que pertenece la imagen (ver class_archivos)
 *        	@tabla string El nombre de la tabla al que pertenece el registro (ver class_archivos)
 *        	@tipoFoto string El identificador de la foto, que se usa para guardar y recuperar las diferentes imagenes de un registro (ver class_archivos)
 *
 */
function linkEditarFoto($incluirImagen, $textoLink, $tituloLigthbox = "", $idRegistro, $tabla, $tipoFoto, $wMax, $hMax, $wMin, $hMin, $aspectRatio = 0)
{
	global $cl_archivos, $db, $sitio;

	$rnd = rand (1, 9999999);

	if ($incluirImagen)
	{
		$foto = $cl_archivos->getArchivoPrincipal ($tabla, $idRegistro, $tipoFoto);

		// para el ancho o alto del <img> de vista previa
		if (is_array ($foto))
		{
			$imgSize = @getimagesize (dirname (dirname (__FILE__)) . "/archivos/" . $foto[archivo]);
			if (is_array ($imgSize))
			{
				if ($imgSize[1] > $imgSize[0])
				{
					$widthHeight = "height";
				}
				else
				{
					$widthHeight = "width";
				}
			}
		}

		echo "<img $widthHeight='150' id='img$rnd' src='" . (is_array ($foto) ? $sitio->pathBase . "archivos/" . $foto[archivo] : "") . "' style='" . (is_array ($foto) ? "" : "display:none") . "' /><br/>";
	}
	/*
	 * FIXME El codigo aca sitado tiene que estar como string y echo o ser llamado desde un json
	 * ?>
	 * <script>
	 * $(document).ready(function(){
	 * $(".lnk<?=$rnd?>").colorbox({iframe:true, rel:'<?=$rnd?>', width:"90%", height:"90%",
	 * onClosed: function(){
	 * <?
	 *
	 * if ($incluirImagen)
	 * {
	 * ?>
	 * //actualiza la imagen por ajax
	 * $.ajax({
	 * url: "ajax.php?getImgSrc=1",
	 * data: "idRegistro=<?=$idRegistro?>&tabla=<?=$tabla?>&tipoFoto=<?=$tipoFoto?>",
	 * success: function(r){
	 * if(r != ''){
	 * $('#img<?=$rnd?>').css('display', 'inline');
	 * $('#img<?=$rnd?>').attr('src', '../archivos/'+r+'?'+Math.random());
	 * }else{
	 * $('#img<?=$rnd?>').css('display', 'none');
	 * }
	 * }
	 * });
	 * <?
	 * }
	 * ?>
	 * }
	 * });
	 * });
	 * </script>
	 * <a class='lnk<?=$rnd?>' href="editar_foto.php?idRegistro=<?=$idRegistro?>&tabla=<?=$tabla?>&tipoFoto=<?=$tipoFoto?>&wMax=<?=$wMax?>&hMax=<?=$hMax?>&wMin=<?=$wMin?>&hMin=<?=$hMin?>&aspectRatio=<?=$aspectRatio?>" rel="colorbox" title="<?=$tituloLigthbox?>"><?=$textoLink?></a>
	 * <?
	 */
}

?>