<?php
/**
 * Archivo de la case campo numero .
 */
require_once 'class_campo.php';

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
// require_once '../funciones.php';

/**
 * Clase que va a manejar a todos los campos de tipo numero.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 6 dic. 2018
 * @name Campos_numero.php
 *
 * @version 0.1 version inicial del archivo.
 *
 */
class Campos_numero extends class_campo
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
	 * Establece si el dato se formatea en el listado o no.
	 *
	 * @var bool
	 */
	protected $formatear = true;

	public function __toString(): string
	{
		$retorno = "Campo: " . $this->campo;
		$retorno .= " Valor: " . $this->getValor ();

		return $retorno;
	}

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
		$this->setTipo ('numero');
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
	public function setCantidadDecimales(int $cantidadDecimales)
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
		$retorno .= "<input type='number' class='input-text $requerido currency' max='250000000.00'  name='c_" . $this->campo . "' value='" . $valor . "' /> \n";

		return $retorno;
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
		if ($this->getValor () != "" and $this->getValor () > 0)
		{

			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . (($this->isFormatear () == true) ? number_format ($this->getValor (), $this->getCantidadDecimales (), ',', '.') : $this->getValor ()) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
		else
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . (($this->isFormatear () == true) ? number_format (0, $this->getCantidadDecimales (), ',', '.') : 0) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
	}

	public function generar_elemento_form_update(): string
	{
		return "<input type='number' class='input-text " . $this->getAtrRequerido () . " max='250000000.00' name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled () . " value='" . $this->getValor () . "' " . $this->establecerMaxLeng () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . "/> \n";
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
			return number_format ((int) $this->getDato (), $this->getCantidadDecimales (), ',', '.');
		}
	}

	/**
	 * Retorna el valor del atributo $formatear
	 *
	 * @return boolean $formatear el dato de la variable.
	 */
	public function isFormatear(): bool
	{
		return $this->formatear;
	}

	/**
	 * Setter del parametro $formatear de la clase.
	 *
	 * @param boolean $formatear
	 *        	dato a cargar en la variable.
	 */
	public function setFormatear(bool $formatear)
	{
		$this->formatear = $formatear;
	}
}

?>