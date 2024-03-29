<?php

/**
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 22 nov. 2018
 * @lenguage PHP
 * @name funciones.php
 * @version 0.1 version inicial del archivo.
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

/**
 * Clase abstracta con una agrupacion de funciones genericas.
 *
 * @author iberlot
 *        
 */
abstract class Funciones
{

	/**
	 * Retorna el ultimo indice del array
	 *
	 * @param array $array
	 * @return mixed
	 */
	public static function endKey($array)
	{

		// Aquí utilizamos end() para poner el puntero
		// en el último elemento, no para devolver su valor
		end ($array);

		return key ($array);
	}

	/**
	 * Convierte de un array todas las entidades HTML para que sea seguro mostrar en pantalla strings ingresados por los usuarios
	 *
	 * @example $_REQUEST = limpiarEntidadesHTML($_REQUEST);
	 *         
	 * @param String[] $param
	 * @return String[] - Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	public static function limpiarEntidadesHTML($param, $sitio = "")
	{
		if (!isset ($sitio) and empty ($sitio))
		{
			global $sitio;
		}

		if (is_array ($param))
		{
			// Hay veces que devuelve error aca =(
			return array_map ("Funciones::limpiarEntidadesHTML", $param);
		}
		else
		{
			if (isset ($sitio->charset))
			{
				return htmlentities ($param, ENT_QUOTES, $sitio->charset);
			}
			else
			{
				$param = htmlentities ($param);

				return $param;
			}
		}
	}
}
