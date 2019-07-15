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
	 * Establece si hay que limpiar o no las entidades html del campo.
	 * Esto es util para lo que es textos formatesados.
	 *
	 * @var boolean
	 */
	protected $noLimpiar = false;

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
	 * Comprueba el valor de un campo y hace el retorno que corresponda.
	 *
	 * @return string
	 */
	public function getMostrarListar()
	{
		if ($this->getDato () != "")
		{
			if ($this->isNoLimpiar () == true)
			{
				return substr ((html_entity_decode ($this->getDato ())), 0, $this->getMaxMostrar ());
			}
			else
			{
				return substr ($this->getDato (), 0, $this->getMaxMostrar ());
			}
		}
	}

	/**
	 *
	 * @return boolean
	 */
	public function isNoLimpiar()
	{
		return $this->noLimpiar;
	}

	/**
	 *
	 * @param boolean $noLimpiar
	 */
	public function setNoLimpiar($noLimpiar)
	{
		$this->noLimpiar = $noLimpiar;
	}
}

?>