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
	 * @return number
	 */
	public function getCantidadDecimales()
	{
		return $this->cantidadDecimales;
	}

	/**
	 *
	 * @param number $cantidadDecimales
	 */
	public function setCantidadDecimales($cantidadDecimales)
	{
		$this->cantidadDecimales = $cantidadDecimales;
	}

	/**
	 *
	 * @param object $db
	 *        	Objeto de coneccion a la base.
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 *        	
	 * @return string
	 *
	 * {@inheritdoc}
	 * @see class_campo::campoFormBuscar()
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
		else
		{
			$valor = "";
		}

		$retorno .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='c_" . $this->campo . "' value='" . $valor . "' /> \n";

		return $retorno;
	}
}

?>