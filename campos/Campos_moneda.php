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
	 * Dato guardado en la base de datos.
	 *
	 * @name dato
	 * @var float
	 */
	protected $dato = 0;

	/**
	 * Constructor de la clase.
	 * Puede recibir un array con los datos a inicializar. Utiliza el constructor padre y en caso de corresponder carga los propios.
	 *
	 * @param array $array
	 */
	public function __construct(array $array = array())
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
	public function getCantidadDecimales(): int
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
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 *        	
	 * @return string
	 *
	 * {@inheritdoc}
	 * @see class_campo::campoFormBuscar()
	 */
	public function campoFormBuscar(&$busqueda): string
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

	/**
	 * Comprueba el valor de un campo y hace el retorno que corresponda.
	 *
	 * @return string
	 */
	public function getMostrarListar()
	{
		if ($this->getCampo () != "")
		{
			setlocale (LC_MONETARY, 'es_AR');
			return money_format ('%.2n', $this->getDato ());
		}
	}

	/**
	 * Comprueba que este habilitado el centrado de la columna y en caso de estarlo retorna la etiqueta para realizarlo.
	 *
	 * @return string
	 */
	public function get_centrar_columna(): string
	{
		if ($this->isCentrarColumna () == true)
		{
			return ' align="center" ';
		}
		else
		{
			return " style='text-align: right;' ";
		}
	}

	/**
	 * Arma un Td con el dato de valor del campo
	 *
	 * @return string
	 */
	public function get_celda_dato(): string
	{
		setlocale (LC_MONETARY, 'es_AR');

		// FIXME el formato deberia ser algo asi '%.$this->getCantidadDecimales ()n' ya que si no se omite el uso de dicho parametro.

		if ($this->getValor () != "" and $this->getValor () > 0)
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . money_format ('%.2n', $this->getValor ()) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
		else
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . money_format ('%.2n', 0) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
	}
}

?>