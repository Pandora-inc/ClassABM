<?php
/**
 *
 * @author iberlot
 *
 */
require_once 'class_campo.php';

// require_once '../funciones.php';
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

/**
 * Clase encargada de manejar todo lo referente a los campos de texto.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 * @since 16 Nov. 2018
 *       
 */
class Campos_textarea extends class_campo
{

	/**
	 * Maximo de caracteres que mostrara por pantalla.
	 *
	 * @name maxMostrar
	 * @var integer
	 */
	protected $maxMostrar = 0;

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

			if (array_key_exists ('maxMostrar', $array))
			{
				$this->setMaxMostrar ($array['maxMostrar']);
			}
			// XXX esto existe para ofrecer compatibilidad con verciones anteriores
			if (array_key_exists ('tmostrar', $array))
			{
				$this->setMaxMostrar ($array['tmostrar']);
			}
		}
		else
		{
			parent::__construct ();
		}
	}

	/**
	 * Retorna el valor de maxMostrar.
	 *
	 * @return number
	 */
	public function getMaxMostrar(): int
	{
		return $this->maxMostrar;
	}

	/**
	 *
	 * Comprueba y setea el valor de maxMostrar
	 *
	 * @param number $maxMostrar
	 */
	public function setMaxMostrar(int $maxMostrar)
	{
		$this->maxMostrar = $maxMostrar;
	}

	/**
	 * Arma un Td con el dato de valor del campo
	 *
	 * @return string
	 */
	public function get_celda_dato(): string
	{
		if ($this->isNoLimpiar () == true)
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . substr (html_entity_decode ($this->getValor ()), 0, $this->getMaxMostrar ()) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
		else
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . substr ($this->getValor (), 0, $this->getMaxMostrar ()) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
	}

	public function generar_elemento_form_update(): string
	{
		return "<textarea class='input-textarea " . $this->getAtrRequerido () . " name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled () . " value='" . $this->getValor () . "' " . $this->establecerMaxLeng () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . "/>" . $this->getValor () . "</textarea>\n";
	}
}

?>