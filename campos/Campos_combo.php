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

/**
 *
 * @author iberlot
 *
 */
class Campos_combo extends class_campo
{

	/**
	 * Datos para el tipo de campo "combo".
	 *
	 * @example Array("key" => "value"...)
	 *
	 * @name datos
	 * @var array
	 */
	protected $datos = array ();

	/**
	 *
	 * @param array $array
	 */
	public function __construct($array)
	{
		parent::__construct ($array);
		// TODO - Insert your code here
	}
}

