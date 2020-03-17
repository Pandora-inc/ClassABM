<?php

/**
 * Archivo principar de la clase.
 *
 * @author lquiroga <@> lquiroga@gmail.com
 * @todo FechaC 06/03/2019 - Lenguaje PHP
 *
 * @name Session.php
 *
 */
class Session
{
	protected static $sessionStarted = false;
	protected static $cookieStarted = false;

	/**
	 * Inicia la sesion.
	 */
	public static function start()
	{
		if (!isset ($_SESSION))
		{

			session_start ();
		}

		self::$sessionStarted = true;
	}

	/**
	 * Termina la sesion.
	 */
	public static function destroy()
	{
		session_destroy ();

		self::$sessionStarted = false;
	}

	/**
	 * Setea valores en la sesion
	 *
	 * @param
	 *        	$prop
	 * @param
	 *        	$value
	 */
	public static function set($prop, $value)
	{
		if (!self::$sessionStarted && !isset ($_SESSION))
		{

			self::start ();
		}

		$_SESSION[$prop] = $value;
	}

	public static function setCookie($prop, $value)
	{
		$_COOKIE[$prop] = $value;
	}

	/**
	 * Devuelve dato de la sesion
	 *
	 * @param
	 *        	$prop
	 * @return mixed
	 */
	public static function get($prop)
	{
		if (!self::$sessionStarted)
		{

			self::start ();
		}
		if (isset ($_SESSION[$prop]))
		{
			return $_SESSION[$prop];
		}
	}

	/**
	 * Comprueba si un dato existe
	 *
	 * @param
	 *        	$prop
	 * @return mixed
	 */
	public static function has($prop)
	{
		if (!self::$sessionStarted)
		{

			self::start ();
		}

		return isset ($_SESSION[$prop]);
	}

	/**
	 * Limpia valor especifico
	 *
	 * @param
	 *        	$prop
	 * @return mixed
	 */
	public static function clearValue($prop)
	{
		if (!self::$sessionStarted)
		{

			self::start ();
		}

		unset ($_SESSION[$prop]);
	}
}
