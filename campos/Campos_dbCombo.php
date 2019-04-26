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
class Campos_dbCombo extends class_campo
{

	/**
	 * Quiery opcional para el tipo de campo dbCombo.
	 *
	 * @name sqlQuery
	 * @var string
	 */
	private $sqlQuery = '';

	/**
	 * Para los tipo "combo" o "dbCombo", si esta en True incluye <option value=''></option>
	 *
	 * @name incluirOpcionVacia =
	 * @var boolean
	 */
	protected $incluirOpcionVacia = true;

	/**
	 * Muestra el valor del campo en el combo.
	 *
	 * @name mostrarValor
	 * @var boolean
	 */
	protected $mostrarValor = true;

	/**
	 * Pone el texto del combo en mayusculas.
	 *
	 * @name textoMayuscula
	 * @var boolean
	 */
	protected $textoMayuscula = true;

	/**
	 *
	 * @return boolean el dato de la variable $incluirOpcionVacia
	 */
	public function isIncluirOpcionVacia()
	{
		return $this->incluirOpcionVacia;
	}

	/**
	 *
	 * @return boolean el dato de la variable $mostrarValor
	 */
	public function isMostrarValor()
	{
		return $this->mostrarValor;
	}

	/**
	 *
	 * @return boolean el dato de la variable $textoMayuscula
	 */
	public function isTextoMayuscula()
	{
		return $this->textoMayuscula;
	}

	/**
	 *
	 * @param
	 *        	boolean a cargar en la variable $incluirOpcionVacia
	 */
	public function setIncluirOpcionVacia($incluirOpcionVacia)
	{
		$this->incluirOpcionVacia = $incluirOpcionVacia;
	}

	/**
	 *
	 * @param
	 *        	boolean a cargar en la variable $mostrarValor
	 */
	public function setMostrarValor($mostrarValor)
	{
		$this->mostrarValor = $mostrarValor;
	}

	/**
	 *
	 * @param
	 *        	boolean a cargar en la variable $textoMayuscula
	 */
	public function setTextoMayuscula($textoMayuscula)
	{
		$this->textoMayuscula = $textoMayuscula;
	}

	/**
	 *
	 * @param array $array
	 */
	public function __construct($array = array())
	{
		if (isset ($array) and ! empty ($array))
		{
			parent::__construct ($array);
		}
		else
		{
			parent::__construct ();
		}
	}

	/**
	 * Retorna el valor de sqlQuery
	 *
	 * @return string
	 */
	public function getSqlQuery()
	{
		return $this->sqlQuery;
	}

	/**
	 * Comprueba y setea el valor de sqlQuery
	 *
	 * @param string $sqlQuery
	 */
	public function setSqlQuery($sqlQuery)
	{
		$this->sqlQuery = $sqlQuery;
	}

	public function campoFormBuscar($db, &$busqueda)
	{
		$retorno = "";

		$retorno .= "<select name='c_" . $this->campo . "' id='c_" . $this->campo . "' class='input-select'> \n";
		$retorno .= "<option value=''></option> \n";

		$resultdbCombo = $db->query ($this->sqlQuery);

		while ($filadbCombo = $db->fetch_array ($resultdbCombo))
		{
			if ((isset ($_REQUEST ['c_' . $this->campo]) and $_REQUEST ['c_' . $this->campo] == $filadbCombo [$this->campoValor]))
			{
				$sel = "selected='selected'";

				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST ['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}

			$combobit = "";

			if (isset ($this->mostrarValor) and ($this->mostrarValor == true))
			{
				$combobit .= ' (' . $filadbCombo [$this->campoValor] . ') ';
			}

			if (isset ($this->textoMayuscula) and ($this->textoMayuscula == true))
			{
				$combobit .= substr ($filadbCombo [$this->campoTexto], 0, 50);
			}
			else
			{
				$combobit .= ucwords (strtolower (substr ($filadbCombo [$this->campoTexto], 0, 50)));
			}

			$retorno .= "<option value='" . $filadbCombo [$this->campoValor] . "' $sel>" . $combobit . "</option> \n";
		}
		$retorno .= "</select> \n";

		// $imprForm .= str_replace ('%IDCAMPO%', $this->campo, $this->jsSelectConBusqueda);
		str_replace ('%IDCAMPO%', $this->campo, $this->jsSelectConBusqueda);

		return $retorno;
	}
}

?>