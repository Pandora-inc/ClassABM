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

			if (array_key_exists ("textoBitTrue", $array))
			{
				$this->setTextoBitTrue ($array['textoBitTrue']);
			}

			if (array_key_exists ("textoBitFalse", $array))
			{
				$this->setTextoBitFalse ($array['textoBitFalse']);
			}
		}
		else
		{
			parent::__construct ();
		}
		$this->setTipo ('bit');
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
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 * {@inheritdoc}
	 * @see class_campo::campoFormBuscar()
	 */
	public function campoFormBuscar(&$busqueda): string
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
	 * Arma un Td con el dato de valor del campo
	 *
	 * @return string
	 */
	public function get_celda_dato(): string
	{
		if ($this->getValor () == true)
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . $this->getTextoBitTrue () . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
		else
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . $this->getTextoBitFalse () . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
	}

	public function generar_elemento_form_update(): string
	{
		$imprForm = "<select name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " class='input-select " . $this->getAtrRequerido () . "' " . $this->getAtrDisabled () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . " > \n";

		if ($this->isOrdenInversoBit () != "")
		{
			if (!$this->getValor ())
			{
				$sel = "selected='selected'";
			}
			else
			{
				$sel = "";
			}
			$imprForm .= "<option value='0' " . $sel . ">" . ($this->textoBitFalse != "" ? $this->textoBitFalse : $this->textoBitFalse) . "</option> \n";

			if ($this->getValor ())
			{
				$sel = "selected='selected'";
			}
			else
			{
				$sel = "";
			}
			$imprForm .= "<option value='1' " . $sel . ">" . ($this->getTextoBitTrue () != "" ? $this->getTextoBitTrue () : $this->textoBitTrue) . "</option> \n";
		}
		else
		{

			if ($this->getValor ())
			{
				$sel = "selected='selected'";
			}
			else
			{
				$sel = "";
			}
			$imprForm .= "<option value='1' " . $sel . ">" . (($this->getTextoBitTrue () != "") ? $this->getTextoBitTrue () : $this->textoBitTrue) . "</option> \n";

			if (!$this->getValor ())
			{
				$sel = "selected='selected'";
			}
			else
			{
				$sel = "";
			}
			$imprForm .= "<option value='0' " . $sel . ">" . (($this->getTextoBitFalse () != "") ? $this->getTextoBitFalse () : $this->textoBitFalse) . "</option> \n";
		}

		$imprForm .= "</select> \n";
		return $imprForm;
	}

	public function generar_elemento_form_nuevo(): string
	{
		$imprForm = "<select name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " class='input-select " . $this->getAtrRequerido () . "' " . $this->getAtrDisabled () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . " > \n";

		if ($this->isOrdenInversoBit () != "")
		{
			$imprForm .= "<option value='0' >" . ($this->textoBitFalse != "" ? $this->textoBitFalse : $this->textoBitFalse) . "</option> \n";

			$imprForm .= "<option value='1' >" . ($this->getTextoBitTrue () != "" ? $this->getTextoBitTrue () : $this->textoBitTrue) . "</option> \n";
		}
		else
		{

			$imprForm .= "<option value='1' >" . (($this->getTextoBitTrue () != "") ? $this->getTextoBitTrue () : $this->textoBitTrue) . "</option> \n";

			$imprForm .= "<option value='0' >" . (($this->getTextoBitFalse () != "") ? $this->getTextoBitFalse () : $this->textoBitFalse) . "</option> \n";
		}

		$imprForm .= "</select> \n";
		return $imprForm;
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