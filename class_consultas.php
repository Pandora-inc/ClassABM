<?php

class consulta
{
	
	public $registro = array();
	
	var $campo;
	var $tabla;
	var $valor;
	var $id;
	var $accion;

	function generarSelect($registro)
	{
		$this->contenido = $cosa;
	}

	function generarUppdate($registro)
	{
		echo $this->contenido;
	}

	function generarInsert($registro)
	{
		echo $this->contenido;
	}

	function generarDelete($registro)
	{
		echo $this->contenido;
	}
	
	function devolverSQL($registro)
	{
		
		foreach($registro as $campo)
		{
			echo "En este equipo juegan: ";
			foreach($equipo as $jugador)
			{
				echo $jugador ." ";
			}
			echo "<br>";
		}
	}
}

?>