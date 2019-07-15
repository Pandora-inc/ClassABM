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

	/**
	 * Sobrecarga del metodo retornand cun campo select
	 *
	 * @param object $db
	 *        	Objeto de coneccion a la base.
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 * {@inheritdoc}
	 * @see class_campo::campoFormBuscar()
	 */
	public function campoFormBuscar($db, &$busqueda)
	{
		$retorno = "";
		$retorno .= "<select name='c_" . $this->campo . "' id='c_" . $this->campo . "' class='input-select'> \n";
		$retorno .= "<option value=''></option> \n";

		if ($campo['ordenInversoBit'])
		{
			// TODO - esto no es un error pero es poco performante y genera codigo duplicado hay que corregirlo.

			if ((isset ($_REQUEST['c_' . $this->campo]) and $_REQUEST['c_' . $this->campo] == "0"))
			{
				$sel = "selected='selected'";
				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}
			$retorno .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";

			if ((isset ($_REQUEST['c_' . $this->campo]) and $_REQUEST['c_' . $this->campo] == true))
			{
				$sel = "selected='selected'";

				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}
			echo "<option value='1' $sel>" . ($this->textoBitTrue != "" ? $this->textoBitTrue : $this->textoBitTrue) . "</option> \n";
		}
		else
		{

			if ((isset ($_REQUEST['c_' . $this->campo]) and $_REQUEST['c_' . $this->campo] == true))
			{
				$sel = "selected='selected'";

				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}
			$retorno .= "<option value='1' $sel>" . ($this->textoBitTrue != "" ? $this->textoBitTrue : $this->textoBitTrue) . "</option> \n";

			if ((isset ($_REQUEST['c_' . $this->campo]) and $_REQUEST['c_' . $this->campo] == "0"))
			{
				$sel = "selected='selected'";

				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}
			$retorno .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";
		}

		$retorno .= "</select> \n";

		return $retorno;
	}

	/**
	 * Comprueba el valor de un campo y hace el retorno que corresponda.
	 *
	 * @return string
	 */
	public function getMostrarListar()
	{
		if ($this->getCampo () != "" and $this->getCampo () != false and $this->getCampo () != 0)
		{
			return $this->textoBitTrue;
		}
		else
		{

			return $this->textoBitFalse;
		}
	}
}

?>