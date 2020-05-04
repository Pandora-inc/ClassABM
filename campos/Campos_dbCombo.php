<?php
/**
 * Archivo con el funcionamiento de la clase dbCombo
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
require_once 'class_campo.php';
// require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/classes/class_db.php';
require_once '/web/html/classes/class_db.php';
require_once '/web/html/classes/class_sitio.php';

// require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'] . '/classes/class_sitio.php';

// require_once '../funciones.php';

/**
 * Clase que agrupa todo lo relacionado a los calpos dbCombo.
 *
 * @author iberlot <iberlot@pandora-inc.com.ar>
 * @version 1.0.1
 * @since 1.0.1 - Se agrega la opcion de selects dinamicos que dependen de otro elemento
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
	 * Valor actual del dato
	 *
	 * @var mixed
	 */
	private $valoriIndice;

	/**
	 * Para los tipo "combo" o "dbCombo", si esta en True incluye <option value=''></option>
	 *
	 * @name incluirOpcionVacia
	 * @var bool
	 */
	protected $incluirOpcionVacia = true;

	/**
	 * Muestra el valor del campo en el combo.
	 *
	 * @name mostrarValor
	 * @var bool
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
	 * Establece si los valores del select del campo dependen de lo seleccionado en otro.
	 *
	 * @var boolean
	 */
	protected $esDinamico = false;

	/**
	 * Campo que al modificarse afecta al campo actual.
	 *
	 * @var string
	 */
	protected $campoDinamicoDende = "";

	/**
	 * Direccion por deccion a la que se dirige para recuperar los datos.
	 *
	 * @var string
	 */
	protected $direcionDinamico = "#";

	/**
	 * En caso de que utilice un orden especial.
	 *
	 * @var string
	 */
	protected $customOrder = "";

	/**
	 * Script js que se encarga del funcionamiento del dinamic.
	 *
	 * @var string
	 */
	protected $js_dinamic = '<script>
$(document).ready(function() {
    $("#{DependeDe} option:selected").each(function () {

						dato = $(this).val();
						dato1 = "select_dinamico";
						dato2 = "{DependeDe}";

						$.post("{DondeVoy}", { accion: dato1, campo: dato2, valor: dato }, function(data){
							$("#{CampoACambiar}").html(data);
						});
					});
});
</script>';

	/**
	 * Retorna el valor del atributo $customOrder
	 *
	 * @return string $customOrder el dato de la variable.
	 */
	public function getCustomOrder()
	{
		return $this->customOrder;
	}

	/**
	 * Setter del parametro $customOrder de la clase.
	 *
	 * @param string $customOrder
	 *        	dato a cargar en la variable.
	 */
	public function setCustomOrder($customOrder)
	{
		$this->customOrder = $customOrder;
	}

	/**
	 * Constructor de la clase.
	 *
	 * @param array $array
	 *        	Array con los parametro del objeto para poder inicializarlo rapidamente
	 * @param class_db $db
	 *        	Conector a la base de datos, si es nulo intenta recuperarlo global o crear uno.
	 */
	public function __construct(array $array = array (), $db = null)
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

		if ($this->esDinamico == true)
		{
			$this->preparar_script_dinamic ();
		}
	}

	/**
	 * Remplaza los nombres de los campos a los correspondientes en la base y la direccion a la que se reenviara el form.
	 */
	public function preparar_script_dinamic()
	{
		$this->js_dinamic = str_ireplace ('{DependeDe}', $this->campoDinamicoDende, $this->js_dinamic);
		$this->js_dinamic = str_ireplace ('{CampoACambiar}', "c_" . $this->campo, $this->js_dinamic);
		$this->js_dinamic = str_ireplace ('{DondeVoy}', $this->direcionDinamico, $this->js_dinamic);
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see class_campo::isIncluirOpcionVacia()
	 */
	public function isIncluirOpcionVacia(): bool
	{
		return $this->incluirOpcionVacia;
	}

	/**
	 * Retorna el valor de la variable
	 *
	 * @return boolean el dato de la variable $mostrarValor
	 */
	public function isMostrarValor(): bool
	{
		return $this->mostrarValor;
	}

	/**
	 * Retorna el valor de la variable
	 *
	 * @return boolean el dato de la variable $textoMayuscula
	 */
	public function isTextoMayuscula(): bool
	{
		return $this->textoMayuscula;
	}

	/**
	 * Establece el valor de la variable
	 *
	 * @param
	 *        	bool a cargar en la variable $incluirOpcionVacia
	 */
	public function setIncluirOpcionVacia(bool $incluirOpcionVacia)
	{
		$this->incluirOpcionVacia = $incluirOpcionVacia;
	}

	/**
	 * Establece el valor de la variable
	 *
	 * @param
	 *        	bool a cargar en la variable $mostrarValor
	 */
	public function setMostrarValor(bool $mostrarValor)
	{
		$this->mostrarValor = $mostrarValor;
	}

	/**
	 * Establece el valor de la variable
	 *
	 * @param
	 *        	boolean a cargar en la variable $textoMayuscula
	 */
	public function setTextoMayuscula(bool $textoMayuscula)
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
	public function setSqlQuery(String $sqlQuery)
	{
		$this->sqlQuery = $sqlQuery;
	}

	/**
	 *
	 * {@inheritdoc}
	 * @see class_campo::campoFormBuscar()
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 *
	 */
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

	/**
	 *
	 * {@inheritdoc}
	 * @see class_campo::generar_elemento_form_update()
	 */
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
			$sqlQuery = "SELECT " . $this->getCampoTexto () . ", " . $this->getCampoValor () . " FROM " . $this->getJoinTable () . ($this->customOrder == "" ? "" : " ORDER BY " . $this->customOrder);
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

			if ($filaCombo[strtoupper ($this->getCampoTexto ())] == $this->getValor ())
			{
				$selected = "selected";

				$this->setValoriIndice ($filaCombo[strtoupper ($this->getCampoValor ())]);
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

	/**
	 *
	 * {@inheritdoc}
	 * @see class_campo::generar_elemento_form_nuevo()
	 */
	public function generar_elemento_form_nuevo(): string
	{
		$imprForm = "<select name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " class='input-select " . $this->getAtrRequerido () . "' " . $this->getAtrDisabled () . " " . $this->getAdicionalInput () . ($this->esDinamico == true ? " onChange='function_c_" . $this->campo . "()'" : " ") . "> \n";
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
			$sqlQuery = "SELECT " . $this->getCampoTexto () . ", " . $this->getCampoValor () . " FROM " . $this->getJoinTable () . ($this->customOrder == "" ? "" : " ORDER BY " . $this->customOrder);
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

			$combobit = "";

			if ($this->isMostrarValor () == true)
			{
				$combobit .= ' (' . $filaCombo[strtoupper ($this->getCampoValor ())] . ') ';
			}

			if ($this->isTextoMayuscula () == true)
			{
				$combobit .= substr ($filaCombo[strtoupper ($this->getCampoTexto ())], 0, 50);
			}
			else
			{
				$combobit .= ucwords (strtolower (substr ($filaCombo[strtoupper ($this->getCampoTexto ())], 0, 50)));
			}
			$imprForm .= "<option value='" . $filaCombo[strtoupper ($this->getCampoValor ())] . "' >" . $combobit . "</option> \n";
		}
		$imprForm .= "</select> \n";

		return $imprForm;
	}

	/**
	 * Arma un string con el nombre de la tabla join y el del campo de texto.
	 *
	 * @return string
	 */
	public function nombreJoinLargo(): string
	{
		if ($this->existeDato ("joinTable") and $this->isOmitirJoin () == false)
		{
			$tablaJoin = $this->getJoinTable ();
			$tablaJoin = explode (".", $tablaJoin);
			$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

			if ($this->existeDato ("campoTexto"))
			{
				return $tablaJoin . "_" . $this->getCampoTexto ();
			}
			else
			{
				return $tablaJoin . "_" . $campo->getCampo ();
			}
		}
	}

	/**
	 * Retorna el valor del atributo $valoriIndice
	 *
	 * @return mixed $valoriIndice el dato de la variable.
	 */
	public function getValoriIndice()
	{
		return $this->valoriIndice;
	}

	/**
	 * Setter del parametro $valoriIndice de la clase.
	 *
	 * @param mixed $valoriIndice
	 *        	dato a cargar en la variable.
	 */
	public function setValoriIndice($valoriIndice)
	{
		$this->valoriIndice = $valoriIndice;
	}

	/**
	 * Retorna el valor del atributo $esDinamico
	 *
	 * @return boolean $esDinamico el dato de la variable.
	 */
	public function isEsDinamico()
	{
		return $this->esDinamico;
	}

	/**
	 * Setter del parametro $esDinamico de la clase.
	 *
	 * @param boolean $esDinamico
	 *        	dato a cargar en la variable.
	 */
	public function setEsDinamico($esDinamico)
	{
		$this->esDinamico = $esDinamico;
	}

	/**
	 * Retorna el valor del atributo $campoDinamicoDende
	 *
	 * @return string $campoDinamicoDende el dato de la variable.
	 */
	public function getCampoDinamicoDende()
	{
		return $this->campoDinamicoDende;
	}

	/**
	 * Setter del parametro $campoDinamicoDende de la clase.
	 *
	 * @param string $campoDinamicoDende
	 *        	dato a cargar en la variable.
	 */
	public function setCampoDinamicoDende($campoDinamicoDende)
	{
		$this->campoDinamicoDende = $campoDinamicoDende;
	}

	/**
	 * Retorna el valor del atributo $direcionDinamico
	 *
	 * @return string $direcionDinamico el dato de la variable.
	 */
	public function getDirecionDinamico()
	{
		return $this->direcionDinamico;
	}

	/**
	 * Setter del parametro $direcionDinamico de la clase.
	 *
	 * @param string $direcionDinamico
	 *        	dato a cargar en la variable.
	 */
	public function setDirecionDinamico($direcionDinamico)
	{
		$this->direcionDinamico = $direcionDinamico;
	}

	/**
	 * Retorna el valor del atributo $js_dinamic
	 *
	 * @return string $js_dinamic el dato de la variable.
	 */
	public function getJs_dinamic()
	{
		$this->preparar_script_dinamic ();
		return $this->js_dinamic;
	}

	/**
	 * Setter del parametro $js_dinamic de la clase.
	 *
	 * @param string $js_dinamic
	 *        	dato a cargar en la variable.
	 */
	public function setJs_dinamic($js_dinamic)
	{
		$this->js_dinamic = $js_dinamic;
	}
}

?>