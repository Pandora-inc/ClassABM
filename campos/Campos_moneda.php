<?php

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
class Campos_moneda extends class_campo
{

	/**
	 * A utilizar con los tipos de campo Numero y Moneda.
	 * Derine el numero de valores despues de la coma.
	 *
	 * @todo Por defecto su valor es 2.
	 *
	 * @name cantidadDecimales
	 * @var integer
	 */
	protected $cantidadDecimales = 2;

	/**
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
		else
		{
			$valor = "";
		}

		$retorno .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='c_" . $this->campo . "' value='" . $valor . "' /> \n";

		return $retorno;
	}
}

