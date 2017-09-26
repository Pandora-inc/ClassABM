<?php

/**
 * Clase para manejar el ordenado por columnas de manera segura y facil
 * 
 * @author Andres Carizza www.andrescarizza.com.ar
 * @author iberlot <@> ivanberlot@gmail.com
 * 
 * @version 2.0
 * Se actualizaron las funciones obsoletas y corrigieron algunos errores.
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
 * totalHorasPerdidasAqui = 108
 *
 */


class class_orderby
{
	/**
	 * Texto para el attr title de los links
	 */
	public $txtOrdenar = "";
	
	/**
	 * Flecha desendente al lado del link
	 */
	public $flechaDes = " <img src='/img/sort_des.png' border='0' />";
	
	/**
	 * Flecha ascendente al lado del link
	 */
	public $flechaAsc = " <img src='/img/sort_asc.png' border='0' />";
	
	/**
	 * Para el attr class de los links
	 */
	public $classLink = "";
	
	/**
	 * Nombre de la variable que pasa por GET para ordenar que se define en el constructor de la clase
	 */
	public $variableOrderBy;
	
	private $arrayCamposOrder;
	
	private $orderByPorDefecto;
	
	private $orderBy;

	/**
	 * Constructor
	 *
	 * @param string $orderByPorDefecto
	 *        	Order by por defecto
	 * @param string $camposOrder
	 *        	Campos por los que se puede ordenar separados por |
	 * @param string $variableOrderBy
	 *        	Nombre de la variable que pasa por GET para ordenar
	 */
	public function __construct($orderByPorDefecto, $camposOrder, $variableOrderBy = "o")
	{
		$this->orderByPorDefecto = $orderByPorDefecto;
		$this->arrayCamposOrder = explode ("|", $camposOrder);
		$this->variableOrderBy = $variableOrderBy;
		$this->orderBy = $this->leerYguardarOrderBy ();
	}

	private function leerYguardarOrderBy()
	{
		if (isset ($_GET [$this->variableOrderBy]) and trim ($_GET [$this->variableOrderBy]) != "")
		{
			$i = $_GET [$this->variableOrderBy];

			
			if (array_key_exists ($i - 1, $this->arrayCamposOrder))
			{
				
				$orderBy = $this->arrayCamposOrder [$i - 1]; // -1 para no usar el cero en el query string
				
				if (stripos ($i, " desc"))
				{
					$orderBy = $orderBy . " DESC";
					$orderBy = str_replace (",", " DESC, ", $orderBy);
					
				}
			}
			else
			{
				// se mando un order que no existe!
				$orderBy = $this->orderByPorDefecto;
			}
		}
		else
		{
			$orderBy = $this->orderByPorDefecto;
		}
		return $orderBy;
	}

	/**
	 * Obtiene el order by para la consulta SQL.
	 * Usar: "...ORDER BY ".$o->getOrderBy()
	 */
	public function getOrderBy()
	{
		return $this->orderBy;
	}

	/**
	 * Retorna el codigo HTML para poner un link para ordenar por columna y la flecha correspondiente.
	 * Usar: <th><?=linkOrderBy("Usuario","user")?></th>
	 *
	 * @param String $txt_campo
	 *        	Texto del link
	 * @param String $campo
	 *        	Campo a ordenar. Debe existir en la lista de campos definida en el constructor
	 * @return String Codigo HTML
	 */
	public function linkOrderBy($txt_campo, $campo)
	{
		
		$keyCampo = array_search ($campo, $this->arrayCamposOrder);
				
		/* Lo comento para que funcione correctamente el join
		if ($keyCampo === false)
		{
			throw new Exception ("El campo $campo no esta definido en el constructor de la clase!   ");
		}
		*/
		$keyCampo = $keyCampo + 1; // +1 para no usar el cero en el query string
		                           
		// genera el query string de variables previamente existentes
		$get = $_GET;
		unset ($get [$this->variableOrderBy]);
		$qs = http_build_query ($get);
		
		if ($qs != "")
		{
			$qs = "&" . $qs;
		}
		
		if (strtolower ($this->orderBy) == strtolower ($campo) or strtolower ($this->orderBy) == strtolower ($campo) . " desc")
		{
			if (stripos ($this->orderBy, " desc"))
			{
				return "<a href='" . $_SERVER ['PHP_SELF'] . "?$this->variableOrderBy=$keyCampo$qs' class='$this->classLink' title='$this->txtOrdenar'>$txt_campo$this->flechaDes</a>";
			}
			else
			{
				return "<a href='" . $_SERVER ['PHP_SELF'] . "?$this->variableOrderBy=$keyCampo+desc$qs' class='$this->classLink' title='$this->txtOrdenar'>$txt_campo$this->flechaAsc</a>";
			}
		}
		else
		{
			return "<a href='" . $_SERVER ['PHP_SELF'] . "?$this->variableOrderBy=$keyCampo$qs' class='$this->classLink' title='$this->txtOrdenar'>$txt_campo</a>";
		}
	}
}
?>