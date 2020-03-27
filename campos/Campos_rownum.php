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
class Campos_rownum extends class_campo
{

	public function __toString(): string
	{
		return $this->getCampo ();
	}

	/**
	 * Constructor de la clase.
	 * Puede recibir un array con los datos a inicializar. Utiliza el constructor padre y en caso de corresponder carga los propios.
	 *
	 * @param array $array
	 */
	public function __construct(array $array = array())
	{
		if (isset ($array) and !empty ($array))
		{
			parent::__construct ($array);
		}
		else
		{
			parent::__construct ();
		}

		$this->setTipo ('rownum');
	}

	/**
	 * Arma el string del nombre del campo (tabla.campo AS nombrecampo) para agregar en el SELECT
	 *
	 * @return string
	 */
	public function get_campo_select(): string
	{
		if ($this->isBuscar () == true or $this->isNoListar () == false)
		{
			return $this->getCampo ();
		}
	}

	/**
	 * Retorna el valor de campoOrder
	 *
	 * @return string
	 */
	public function getCampoOrder(): string
	{
		if ($this->campoOrder != "")
		{
			return $this->campoOrder;
		}
		else
		{
			return $this->getCampo ();
		}
	}
}

?>