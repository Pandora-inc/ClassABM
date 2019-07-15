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
		if (isset ($array) and !empty ($array))
		{
			parent::__construct ($array);
		}
		else
		{
			parent::__construct ();
		}

		if (array_key_exists ('datos', $array))
		{
			$this->setDatos ($array['datos']);
		}
	}

	/**
	 * Comprueba y setea el valor de datos
	 *
	 * @param array $datos
	 */
	public function setDatos($datos)
	{
		$this->datos = $datos;
	}

	/**
	 * Retorna el valor de datos
	 *
	 * @param mixed $index
	 *        	parametro opcional, en caso de estar definido busca el indice y retorna el valor asociado. en caso de no estarlo retorna todo el array.
	 *
	 * @return array|mixed
	 */
	public function getDatos($index = "")
	{
		if ($index == "")
		{
			return $this->datos;
		}
		else
		{
			if (array_key_exists ($index, $this->datos))
			{
				return $this->datos[$index];
			}
			else
			{
				throw new Exception ("No se encontro el indice buscado. " . $index);
			}
		}
	}

	public function campoFormBuscar($db, &$busqueda)
	{
		$retorno = "";

		$retorno .= "<select name='c_" . $this->campo . "' id='c_" . $this->campo . "' class='input-select'> \n";
		$retorno .= "<option value=''></option> \n";

		foreach ($campo['datos'] as $valor => $texto)
		{
			if ((isset ($_REQUEST['c_' . $this->campo]) and $_REQUEST['c_' . $this->campo] == $valor))
			{
				$sel = "selected='selected'";
				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}
			$retorno .= "<option value='$valor' $sel>$texto</option> \n";
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
		if ($this->getCampo () != "")
		{
			return $this->getDatos ($this->getDato ());
		}
	}
}

?>