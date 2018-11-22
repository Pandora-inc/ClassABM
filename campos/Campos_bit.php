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
class Campos_bit extends class_campo
{

	/**
	 * Si esta en true muestra primero el false en los <select>.
	 * Por defecto es false
	 *
	 * @name ordenInversoBit
	 * @var Boolean
	 */
	protected $ordenInversoBit = '';

	/**
	 * Texto que pone cuando el tipo de campo es "bit" y este es true o =1.
	 * Si se deja vacio usa el por defecto definido en $this->textoBitTrue
	 *
	 * @name textoBitTrue
	 * @var string
	 */
	protected $textoBitTrue = 'SI';

	/**
	 * Texto que pone cuando el tipo de campo es "bit" y este es false o =0.
	 * Si se deja vacio usa el por defecto definido en $this->textoBitFalse
	 *
	 * @name textoBitFalse
	 * @var string
	 */
	protected $textoBitFalse = 'NO';

	/**
	 *
	 * @param array $array
	 */
	public function __construct($array)
	{
		parent::__construct ($array);
		// TODO - Insert your code here
	}

	/**
	 * Retorna el valor de textoBitTrue
	 *
	 * @return string
	 */
	public function getTextoBitTrue()
	{
		return $this->textoBitTrue;
	}

	/**
	 * Retorna el valor de textoBitFalse
	 *
	 * @return string
	 */
	public function getTextoBitFalse()
	{
		return $this->textoBitFalse;
	}

	/**
	 * Retorna el valor de ordenInversoBit
	 *
	 * @return boolean
	 */
	public function isOrdenInversoBit()
	{
		return $this->ordenInversoBit;
	}

	/**
	 * Comprueba y setea el valor de textoBitTrue
	 *
	 * @param string $textoBitTrue
	 */
	public function setTextoBitTrue($textoBitTrue)
	{
		$this->textoBitTrue = $textoBitTrue;
	}

	/**
	 * Comprueba y setea el valor de textoBitFalse
	 *
	 * @param string $textoBitFalse
	 */
	public function setTextoBitFalse($textoBitFalse)
	{
		$this->textoBitFalse = $textoBitFalse;
	}

	/**
	 * Comprueba y setea el valor de ordenInversoBit
	 *
	 * @param boolean $ordenInversoBit
	 */
	public function setOrdenInversoBit($ordenInversoBit)
	{
		$this->ordenInversoBit = $ordenInversoBit;
	}
}

