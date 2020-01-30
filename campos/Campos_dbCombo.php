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
require_once '/web/html/classes/class_db.php';
require_once '/web/html/classes/class_sitio.php';

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
	 * Objeto de coneccion a la base de datos.
	 *
	 * @var class_db
	 */
	private $db;

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

	public function __construct(array $array = array(), $db = null)
	{
		if (!isset ($db) or empty ($db) or $db == null)
		{
			if (!$this->db = Sitios::openConnection ())
			{
				global $db;

				if (isset ($db) and !empty ($db) and $db != null)
				{
					$this->db = $db;
				}
			}
		}
		else
		{
			$this->db = $db;
		}

		if (isset ($array) and !empty ($array))
		{
			parent::__construct ($array);
		}
		else
		{
			parent::__construct ();
		}
		$this->setTipo ('dbcombo');
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
	 * Retorna el valor de sqlQuery
	 *
	 * @return string
	 */
	public function getSqlQuery()
	{
		return $this->sqlQuery;
	}

	/**
	 * Comprueba que la clase tenga definida una salQuery propia
	 *
	 * @return boolean
	 */
	public function tieneSqlQuery()
	{
		if (isset ($this->sqlQuery) and $this->sqlQuery != "")
		{
			return true;
		}
		else
		{
			return false;
		}
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

	public function campoFormBuscar(&$busqueda): string
	{
		$retorno = "";

		$retorno .= "<select name='c_" . $this->campo . "' id='c_" . $this->campo . "' class='input-select'> \n";
		$retorno .= "<option value=''></option> \n";

		$resultdbCombo = $this->db->query ($this->sqlQuery);

		while ($filadbCombo = $this->db->fetch_array ($resultdbCombo))
		{
			if ((isset ($_REQUEST['c_' . $this->campo]) and $_REQUEST['c_' . $this->campo] == $filadbCombo[$this->campoValor]))
			{
				$sel = "selected='selected'";

				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
			}
			else
			{
				$sel = "";
			}

			$combobit = "";

			if (isset ($this->mostrarValor) and ($this->mostrarValor == true))
			{
				$combobit .= ' (' . $filadbCombo[$this->campoValor] . ') ';
			}

			if (isset ($this->textoMayuscula) and ($this->textoMayuscula == true))
			{
				$combobit .= substr ($filadbCombo[$this->campoTexto], 0, 50);
			}
			else
			{
				$combobit .= ucwords (strtolower (substr ($filadbCombo[$this->campoTexto], 0, 50)));
			}

			$retorno .= "<option value='" . $filadbCombo[$this->campoValor] . "' $sel>" . $combobit . "</option> \n";
		}
		$retorno .= "</select> \n";

		// $imprForm .= str_replace ('%IDCAMPO%', $this->campo, $this->jsSelectConBusqueda);
		str_replace ('%IDCAMPO%', $this->campo, $this->jsSelectConBusqueda);

		return $retorno;
	}

	public function generar_elemento_form_update(): string
	{
		$imprForm = "<select name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " class='input-select " . $this->getAtrRequerido () . "' " . $this->getAtrDisabled () . " " . $this->getAdicionalInput () . "> \n";
		if ($this->isIncluirOpcionVacia ())
		{
			$imprForm .= "<option value=''></option> \n";
		}

		if ($this->tieneSqlQuery () == true)
		{
			$sqlQuery = $this->getSqlQuery ();
		}
		else
		{
			$sqlQuery = "SELECT " . $this->getCampoTexto () . ", " . $this->getCampoValor () . " FROM " . $this->getJoinTable ();
		}

		// FIXME comprobar e implementar customCompare
		// if (isset ($campo->customCompare']) and $campo->existeDato('customCompare'))
		// {
		// $sqlQuery .= " WHERE 1=1 AND " . $campo->customCompareCampo'] . " = '" . $customCompareValor . "'";
		// // $sqlQuery .= " WHERE 1=1 AND " . $campo->customCompareCampo'] . " = " . $this->tabla . '.' . $campo->customCompareValor'];

		// if ($campo->['customOrder'] != "")
		// {
		// $sqlQuery .= " ORDER BY " . $tabla . '.' . $campo->customOrder'];
		// }
		// }

		$resultCombo = $this->db->query ($sqlQuery);

		while ($filaCombo = $this->db->fetch_array ($resultCombo))
		{
			// $filaCombo = Funciones::limpiarEntidadesHTML ($filaCombo);
			$filaCombo = array_merge (array_change_key_case ($filaCombo, CASE_UPPER), array_change_key_case ($filaCombo, CASE_LOWER));

			if ($filaCombo[strtoupper ($this->getCampoValor ())] == $this->getValor ())
			{
				$selected = "selected";
			}
			else
			{
				$selected = "";
			}

			$combobit = "";

			if ($this->isMostrarValor () == true)
			{
				$combobit .= ' (' . $filaCombo[strtoupper ($this->getCampoValor ())] . ') ';
			}

			if ($this->isTextoMayuscula () == true)
			{
				$combobit .= substr ($filaCombo[$this->getCampoTexto ()], 0, 50);
			}
			else
			{
				$combobit .= ucwords (strtolower (substr ($filaCombo[$this->getCampoTexto ()], 0, 50)));
			}

			$imprForm .= "<option value='" . $filaCombo[strtoupper ($this->getCampoValor ())] . "' $selected>" . $combobit . "</option> \n";
		}
		$imprForm .= "</select> \n";

		return $imprForm;
	}
}

?>