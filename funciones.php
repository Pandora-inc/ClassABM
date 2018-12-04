<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @todo 22 nov. 2018
 * @lenguage PHP
 * @name funciones.php
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
abstract class funciones
{

	/**
	 * Retorna el ultimo indice del array
	 *
	 * @param array $array
	 * @return mixed
	 */
	public function endKey($array)
	{

		// Aquí utilizamos end() para poner el puntero
		// en el último elemento, no para devolver su valor
		end ($array);

		return key ($array);
	}
}
