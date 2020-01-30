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
	 * @name incluirOpcionVacia
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

	public function __toString(): string
	{
		$retorno = "Campo: " . $this->campo;
		$retorno .= " Valor: " . $this->getValor ();

		if (is_array ($this->getDatos ($this->getValor ())))
		{
			$retorno .= " Dato: " . implode ("-", $this->getDatos ($this->getValor ()));
		}
		else
		{
			$retorno .= " Dato: " . $this->getDatos ($this->getValor ());
		}

		return $retorno;
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
	 *
	 * @return boolean el dato de la variable $incluirOpcionVacia
	 */
	public function isIncluirOpcionVacia(): bool
	{
		return $this->incluirOpcionVacia;
	}

	/**
	 *
	 * @return boolean el dato de la variable $mostrarValor
	 */
	public function isMostrarValor(): bool
	{
		return $this->mostrarValor;
	}

	/**
	 *
	 * @return boolean el dato de la variable $textoMayuscula
	 */
	public function isTextoMayuscula(): bool
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
	 * @return array
	 */
	public function getDatos($busca = "")
	{
		if ($busca == "")
		{
			return $this->datos;
		}
		elseif (array_key_exists ($this->getValor (), $this->datos))
		{
			return $this->datos[$this->getValor ()];
		}
		else
		{
			throw new Exception ("El valor " . $this->getValor () . " no existe entre los posibles datos.");
		}
	}

	public function campoFormBuscar(&$busqueda): string
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
	 * Arma un Td con el dato de valor del campo
	 *
	 * @return string
	 */
	public function get_celda_dato(): string
	{
		if ($this->isNoLimpiar () == true)
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . html_entity_decode ($this->getDatos ($this->getValor ())) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
		else
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . $this->getDatos ($this->getValor ()) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
	}

	public function generar_elemento_form_update(): string
	{
		$imprForm = "<select name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " class='input-select " . $this->getAtrRequerido () . "' " . $this->getAtrDisabled () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . "> \n";

		if ($this->isIncluirOpcionVacia ())
		{
			$imprForm .= "<option value=''></option> \n";
		}

		foreach ($this->getDatos () as $valor => $texto)
		{
			if ($this->getValor () == Funciones::limpiarEntidadesHTML ($valor))
			{
				$sel = "selected='selected'";
			}
			else
			{
				$sel = "";
			}
			$imprForm .= "<option value='$valor' " . $sel . ">$texto</option> \n";
		}
		$imprForm .= "</select> \n";
		return $imprForm;
	}
}

?>