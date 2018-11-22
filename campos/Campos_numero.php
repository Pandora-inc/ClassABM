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
	 *
	 * @param array $array
	 */
	public function __construct($array)
	{
		parent::__construct ($array);
		// TODO - Insert your code here
	}
}

