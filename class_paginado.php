<?php
require_once 'class_db.php';

/**
 * Class para el paginado de registros.
 * Realiza de forma eficiente la consulta a BD para contar la cantidad de registros
 * y traer solo los necesarios. Imprime el paginador con Anterior Siguiente y los numeros de paginas.
 *
 * USAR:
 * include("class_paginado.php");
 * $paginado = new class_paginado;
 * $paginado->registros_por_pagina = 20;
 * $result = $paginado->query($query);
 *
 * @author Andres Carizza www.andrescarizza.com.ar
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @version 3.6.2 Se corrigen las consultas y las expreciones regulares para mejorar el performance de la recuperacion de registros. Ya no realiza dos veces la consulta si no que hace un count.
 * @version 3.6.1 Se modifico para que devolviera 1 en vez de $db->result ($result_paginado, 0, "cantidad"); cuando el conteo de rows fuera igual a uno.
 *          Con esto se corrige el error de que no mostraba datos cuando habia un unico registro.
 * @version 3.6
 *          Se actualizaron las funciones obsoletas y corrigieron algunos errores.
 * @uses class_db
 */
class class_paginado
{

	/**
	 * archivo sobre el cual se realiza el paginado (ej: $_SERVER['PHP_SELF'])
	 */
	var $parent_self;

	/**
	 * nombre de la variable registro con la que hace el paginado (la que pasa por GET)
	 */
	var $nombre_var_registro = "r";

	/**
	 * cantidad de registros por p&aacute;gina
	 */
	var $registros_por_pagina = 20;

	/**
	 * cantidad de links para adelante despues del actual.
	 * >5< 7 8 9 10
	 */
	var $cant_link_paginas_adelante = 5;

	/**
	 * cantidad de links para atras antes del actual.
	 * 1 2 3 4 >5<
	 */
	var $cant_link_paginas_atras = 4;

	/**
	 * string o html para el link Siguiente (ej: <img src='img/siguiente.gif' border=0>)
	 */
	var $siguiente = ">";

	/**
	 * string o html para el link Anterior (ej: <img src='img/anterior.gif' border=0>)
	 */
	var $anterior = "<";

	/**
	 * string o html para Siguiente cuando esta inabilitado
	 */
	var $siguiente_des = ">";

	/**
	 * string o html para Anterior cuando esta inabilitado
	 */
	var $anterior_des = "<";

	/**
	 * string o html para el link primera pagina (ej: <img src='img/primera.gif' border=0>)
	 */
	var $primera = "|<";

	/**
	 * string o html para el link ultima pagina (ej: <img src='img/ultima.gif' border=0>)
	 */
	var $ultima = ">|";

	/**
	 * si muestra los links para ir a la ultima y primera pagina *
	 */
	var $mostrar_ultima_primera_pagina = true;

	/**
	 * si esta en true no muestra los links de siguiente y anterior cuando estan inabilitados *
	 */
	var $mostrar_anterior_sigiente_inabilitado = false;

	/**
	 * string para el title de los links de los numeros de pagina
	 */
	var $str_ir_a = "Ir a la p&aacute;gina";

	/**
	 * string para Total
	 */
	var $str_total = "Total";
	var $str_registro = "registro";
	var $str_registros = "registros";
	var $str_paginas = "P&aacute;ginas";

	/**
	 * total de registros en total
	 */
	var $total_registros;

	/**
	 * pagina actual
	 */
	var $pagina;

	/**
	 * total de paginas
	 */
	var $total_paginas;

	/**
	 * si muestra el total de registros al lado del paginado
	 */
	var $mostrarTotalRegistros = true;

	/**
	 * si muestra la palabra "P&aacute;ginas:" al lado del paginado
	 */
	var $mostrarPalabraPaginas = false;

	/**
	 * ejecutar el SQL original para contar la cantidad de registros, de otra manera se modifica el query para contar la cantidad de registros de una manera m&aacute;s rapida, aunque a veces falla al modificarlo
	 */
	var $ejecutarQueryOriginalParaContar = false;

	/**
	 * un array con las variables que el paginador no debe conservar al cambiar de pagina.
	 * (ej: $variablesNoConservar=array('agregado','modificado','msg');)
	 */
	var $variablesNoConservar = array ();

	/**
	 * para asignar un estilo css diferente.
	 * Nombres de estilos css: paginado (DIV) -> link_ant, ant_desact (SPAN), link_pagina_actual (SPAN), link_pagina, link_sig, sig_desact (SPAN), rotuloTotalRegistros (SPAN)
	 */
	var $cssClassPaginado = "paginado";
	private $sumarBusqueda = "";
	private $registro = 0;
	private $desde_reg;
	private $hasta_reg;

	// /**
	// * Objeto de coneccion a la base de datos
	// *
	// * @var class_db
	// */
	// // private $db = new class_db($host, $user, $pass, $db);

	/**
	 * Ejecuta el query de mysql (que no debe tener LIMIT) que cuenta el total de registros
	 * y el que retorna solo los registros que corresponden a la pagina actual.
	 *
	 * @since 3.6.1 Se modifico para que devolviera 1 en vez de $db->result ($result_paginado, 0, "cantidad"); cuando el conteo de rows fuera igual a uno.
	 *        Con esto se corrige el error de que no mostraba datos cuando habia un unico registro.
	 *
	 * @param string $sqlQuery
	 *        	query a ejecutar
	 * @param object $db
	 *        	clase de coneccion a la base de datos.
	 * @return array resultado d ela consulta.
	 */
	function query($sqlQuery, $db)
	{
		// print_r($sqlQuery);
		// global $db;
		$query = $sqlQuery;

		if (isset ($_GET[$this->nombre_var_registro]))
		{
			$regist = $_GET[$this->nombre_var_registro];

			$this->registro = $regist; // es el numero de registro por el cual empieza
		}

		if (!isset ($this->registro))
		{
			$this->registro = 0;
		}

		if (preg_match ('/[0-9]+$/', $this->registro))
		{
		}
		else
		{
			$this->registro = 0;
		}

		$this->desde_reg = $this->registro + 1;

		// Contar cuantos registros hay
		// Transforma el query en un count y saca el ORDER BY, si existe, para que el query sea optimo en performance
		// Si existe un GROUP BY ejecuta el original
		if (!$this->ejecutarQueryOriginalParaContar and stripos ($query, "GROUP BY") === false)
		{
			$porciones = explode ("FROM", $query);
			$porciones[0] = preg_replace ('/^(SELECT).*(FROM)/', 'SELECT COUNT(*) AS CANTIDAD', $porciones[0] . " FROM ");
			$queryCount = implode ("FROM", $porciones);
			$queryCount = preg_replace ('/ORDER BY.*/', '', $queryCount);
			$result_paginado = $db->query ($queryCount);
		}
		else
		{
			$this->ejecutarQueryOriginalParaContar = true;
		}

		// XXX este codigo duplica lo anterior se comenta hasta que se elimine en la proxima revicion
		// if (isset ($ejecutarQueryOriginal) or $db->num_rows ($result_paginado) >= 0)
		// {
		// // ejecuta el count mandando el query original
		// $sql = "SELECT COUNT(*) FROM (" . $sqlQuery . ")";

		// $result_paginado = $db->query ($sql);
		// $cantidad = $db->num_rows ($result_paginado);
		// }
		// elseif ($db->num_rows ($result_paginado) == 1)
		// {
		// // $cantidad = $db->result ($result_paginado, 0, "cantidad");
		// $cantidad = 1;
		// }

		// Se remplaza por lo siguiente
		$cantidad = $db->fetch_row ($result_paginado);
		// print_r ($cantidad);
		$this->total_registros = $cantidad[0];

		// Ejecutar el query original con el LIMIT
		if ($db->getDbtype () == 'mysql')
		{
			$query .= " LIMIT $this->registro, $this->registros_por_pagina";
		}
		elseif ($db->getDbtype () == 'oracle')
		{
			$registros_por_pagina = $this->registros_por_pagina;
			$registro = $this->registro;

			$RegistroHasta = $registros_por_pagina + $registro;

			// $query = preg_replace ('/^(SELECT)/', 'SELECT ROWNUM AS FILA, ', $query);
			$query = preg_replace ('/^(SELECT)/', ' SELECT a.*, ROWNUM rnum FROM (SELECT ', $query);

			$query = "
			SELECT * FROM (
			" . $query . ") a WHERE ROWNUM <= " . $RegistroHasta . ")
			WHERE rnum > " . $registro;
			// WHERE ROWNUM > " . $registro . " AND ROWNUM <= " . $RegistroHasta;
		}
		elseif ($db->getDbtype () == 'mssql')
		{
			$ordby = "";
			$pos = "";

			$registros_por_pagina = $this->registros_por_pagina;
			$registro = $this->registro;

			$RegistroHasta = $registros_por_pagina + $registro;

			$pos = strpos ($query, "ORDER BY");

			if ($pos !== false)
			{
				$ordby = substr ($query, $pos);
				$query = substr ($query, 0, $pos);

				$porciones = explode (" ", $ordby);
				for($i = 0; $i < count ($porciones); $i ++)
				{
					$pos = strpos ($porciones[$i], ".");

					if ($pos !== false)
					{
						$porciones[$i] = "b" . substr ($porciones[$i], $pos);
					}
				}

				$ordby = implode (" ", $porciones);
			}
			$query = 'SELECT * FROM(SELECT * ,ROW_NUMBER() OVER (ORDER BY id) AS subrow FROM (' . $query . ') a) b  WHERE   subrow >= ' . $registro . ' and  subrow <= ' . $RegistroHasta . " " . $ordby;
		}

		$result = $db->query ($query);

		$this->pagina = ceil ($this->registro / $this->registros_por_pagina) + 1;
		$this->total_paginas = ceil ($this->total_registros / $this->registros_por_pagina);

		if (($this->registro + $this->registros_por_pagina) > $this->total_registros)
		{
			$this->hasta_reg = $this->total_registros;
		}
		else
		{
			$this->hasta_reg = $this->registro + $this->registros_por_pagina;
		}
		return $result;
	}

	/**
	 * Retorna el HTML del paginado
	 */
	function get_paginado()
	{
		$r = "";

		// cuando no hay links de paginado y cuando mostrarTotalRegistros = true
		if ($this->total_registros <= $this->registros_por_pagina)
		{
			if ($this->mostrarTotalRegistros)
			{
				if (!isset ($r))
				{
					$r = "";
				}

				$r .= "<div class='$this->cssClassPaginado'>";
				$r .= "<span class='rotuloTotalRegistros'>" . $this->str_total . ": " . $this->total_registros . " " . ($this->total_registros > 1 ? $this->str_registros : $this->str_registro) . "</span>";
				$r .= "</div>";
			}
			return $r;
		}

		$r .= "<div class='$this->cssClassPaginado'>";

		if ($this->mostrarPalabraPaginas)
		{
			$r .= $this->str_paginas . ": ";
		}

		unset ($_GET[$this->nombre_var_registro]);

		if (count ($this->variablesNoConservar) > 0)
		{
			for($i = 0; $i < count ($this->variablesNoConservar); $i ++)
			{
				unset ($_GET[$this->variablesNoConservar[$i]]);
			}
		}

		$qs = http_build_query ($_GET); // conserva las variables que existian previamente
		if ($qs != "")
		{
			$qs = "&" . $qs;
		}

		if (($this->registro - $this->registros_por_pagina) >= 0)
		{
			// link primera pagina
			if ($this->mostrar_ultima_primera_pagina and $this->registro - $this->registros_por_pagina > 0)
			{
				$r .= "<a href='$this->parent_self?" . $this->nombre_var_registro . "=0$qs' class='link_pri'>" . $this->primera . "</a> ";
			}
			// link anterior
			$r .= "<a href='$this->parent_self?" . $this->nombre_var_registro . "=" . ($this->registro - $this->registros_por_pagina) . "$qs' class='link_ant'>" . $this->anterior . "</a> ";
		}
		else
		{
			// link anterior deshabilitado
			if ($this->mostrar_anterior_sigiente_inabilitado)
			{
				$r .= "<span class='ant_desact'>" . $this->anterior_des . "</span> ";
			}
		}

		$link_pagina = $this->registro - ($this->registros_por_pagina * $this->cant_link_paginas_atras);
		if ($link_pagina < 0)
		{
			$link_pagina = 0;
		}

		for($i = $link_pagina; $i < $this->total_registros; $i = $i + $this->registros_por_pagina)
		{
			$pagina = ((($i) * ($this->total_registros / $this->registros_por_pagina)) / $this->total_registros) + 1; // regla de tres simple...

			if ($this->registro == $i)
			{
				// pagina actual
				$r .= "<span class='link_pagina_actual'>$pagina</span> ";
			}
			else
			{
				// link a pagina #
				$r .= "<a href='$this->parent_self?" . $this->sumarBusqueda . "&" . $this->nombre_var_registro . "=" . $i . "$qs' title='" . $this->str_ir_a . " $pagina' class='link_pagina'>" . $pagina . "</a> ";
			}

			if ($i > $this->registro)
			{ // si ya se paso link del numero de p&aacute;gina actual
				if (isset ($cant_adelante))
				{
					$cant_adelante ++;
				}
				else
				{
					$cant_adelante = 0;
				}

				if ($cant_adelante >= $this->cant_link_paginas_adelante)
				{
					break;
				}
			}
		} // FIN for

		if (($this->registro + $this->registros_por_pagina) < $this->total_registros)
		{
			// link siguiente
			$r .= "<a href='$this->parent_self?" . $this->nombre_var_registro . "=" . ($this->registro + $this->registros_por_pagina) . "$qs' class='link_sig'>" . $this->siguiente . "</a> ";
		}
		else
		{
			// siguiente desactivado
			if ($this->mostrar_anterior_sigiente_inabilitado)
			{
				$r .= "<span class='sig_desact'>" . $this->siguiente_des . "</span>";
			}
		}

		// link ultima pagina
		if ($this->mostrar_ultima_primera_pagina and (($this->registro + $this->registros_por_pagina) < $this->total_registros))
		{
			$r .= "<a href='$this->parent_self?" . $this->nombre_var_registro . "=" . ($this->registros_por_pagina * (ceil ($this->total_registros / $this->registros_por_pagina) - 1)) . "$qs' class='link_ult'>" . $this->ultima . "</a> ";
		}

		// rotulo total registros
		if ($this->mostrarTotalRegistros)
		{
			$r .= "&nbsp;&nbsp; <span class='rotuloTotalRegistros'>" . $this->str_total . ": " . $this->total_registros . " " . ($this->total_registros > 1 ? $this->str_registros : $this->str_registro) . "</span>";
		}

		$r .= "</div>";

		return $r;
	}

	/**
	 * Imprime el paginado
	 */
	function mostrar_paginado()
	{
		echo $this->get_paginado ();
	}

	// FIN function mostrar_paginado

	/**
	 * Retorna el valor del atributo $nombre_var_registro
	 *
	 * @return string $nombre_var_registro el dato de la variable.
	 */
	public function getNombre_var_registro()
	{
		return $this->nombre_var_registro;
	}

	/**
	 * Retorna el valor del atributo $registros_por_pagina
	 *
	 * @return number $registros_por_pagina el dato de la variable.
	 */
	public function getRegistros_por_pagina()
	{
		return $this->registros_por_pagina;
	}

	/**
	 * Retorna el valor del atributo $sumarBusqueda
	 *
	 * @return string $sumarBusqueda el dato de la variable.
	 */
	public function getSumarBusqueda()
	{
		return $this->sumarBusqueda;
	}

	/**
	 * Setter del parametro $nombre_var_registro de la clase.
	 *
	 * @param string $nombre_var_registro
	 *        	dato a cargar en la variable.
	 */
	public function setNombre_var_registro($nombre_var_registro)
	{
		$this->nombre_var_registro = $nombre_var_registro;
	}

	/**
	 * Setter del parametro $registros_por_pagina de la clase.
	 *
	 * @param number $registros_por_pagina
	 *        	dato a cargar en la variable.
	 */
	public function setRegistros_por_pagina($registros_por_pagina)
	{
		$this->registros_por_pagina = $registros_por_pagina;
	}

	/**
	 * Setter del parametro $sumarBusqueda de la clase.
	 *
	 * @param string $sumarBusqueda
	 *        	dato a cargar en la variable.
	 */
	public function setSumarBusqueda($sumarBusqueda)
	{
		$this->sumarBusqueda = $sumarBusqueda;
	}
} // FIN class_paginado
?>