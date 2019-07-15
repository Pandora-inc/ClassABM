<?php
/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 6 dic. 2018
 * @lenguage PHP
 * @name Campos_feha.php
 * @version 0.1 version inicial del archivo.
 * @package
 * @project
 */

/*
 * Querido programador:
 *
 * Cuando escribi este codigo, solo Dios y yo sabiamos como funcionaba.
 * Ahora, Solo Dios lo sabe!!!
 *
 * Asi que, si esta tratando de 'optimizar' esta rutina y fracasa (seguramente),
 * por favor, incremente el siguiente contador como una advertencia para el
 * siguiente colega:
 *
 * totalHorasPerdidasAqui = 0
 *
 */
require_once 'class_campo.php';

// require_once '../funciones.php';

/**
 *
 * @author iberlot
 *        
 */
class Campos_fecha extends class_campo
{
	/**
	 * Codigo JS para poner en window.onload para cada uno de los campos de fecha *
	 */
	private $jsIniciadorCamposFecha = '
    <script>
    $(function(){
        $("#%IDCAMPO%").datepicker({
      		changeMonth: true,
      		changeYear: true,
            regional: "es",
            showAnim: "fade",
            dateFormat: "yy-mm-dd",
            altField: "#display_%IDCAMPO%",
            altFormat: "dd/mm/yy",
			yearRange: "-200:+10"
        });
        $("#display_%IDCAMPO%").focus(function(){$("#%IDCAMPO%").datepicker("show")});
        if("%VALOR%" != "") $("#%IDCAMPO%").datepicker("setDate", "%VALOR%");
    });
    </script>';

	/**
	 * Constructor de la clase.
	 * Puede recibir un array con los datos a inicializar. Utiliza el constructor padre y en caso de corresponder carga los propios.
	 *
	 * @param array $array
	 */
	public function __construct($array = array())
	{
		if (isset ($array) and !empty ($array))
		{
			parent::__construct ($array);
		}
		else
		{
			parent::__construct ();
		}
	}

	/**
	 *
	 * @param object $db
	 *        	Objeto de coneccion a la base.
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 *        	
	 * @return string
	 */
	public function campoFormBuscar($db, &$busqueda)
	{
		$retorno = "";

		if ($this->requerido == TRUE)
		{
			$requerido = " required ";
		}
		else
		{
			$requerido = " ";
		}

		if (isset ($_REQUEST['c_' . $this->campo]))
		{
			$valor = Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);

			// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
			$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
		}

		if (strlen ($valor) > 10)
		{
			$valor = substr ($valor, 0, 10); // sacar hora:min:seg
		}
		if ($valor == '0000-00-00')
		{
			$valor = "";
		}
		$jsTmp = str_replace ('%IDCAMPO%', 'c_' . $this->campo, $this->jsIniciadorCamposFecha);
		$jsTmp = str_replace ('%VALOR%', $valor, $jsTmp);

		$retorno .= $jsTmp;
		$retorno .= "<input type='text' style='position:absolute' class='input-fecha' name='c_" . $this->campo . "' id='c_" . $this->campo . "' value='" . ($valor) . "'/> \n";
		$retorno .= "<input type='text' style='position:relative;top:0px;left;0px'  name='display_c_" . $this->campo . "' id='display_c_" . $this->campo . "' class='input-fecha " . $requerido . "' " . $disabled . " " . $this->getAdicionalInput () . " readonly='readonly'/> \n";

		return $retorno;
	}
}
?>