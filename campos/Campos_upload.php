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
class Campos_upload extends class_campo
{

	/**
	 * Alto en caso de que el dato sea una imagen.
	 *
	 * @var integer
	 */
	protected $alto = 0;

	/**
	 * Anco en caso de que el dato sea una imagen.
	 *
	 * @var integer
	 */
	protected $ancho = 0;

	/**
	 * Directorio donde se guarda el upload.
	 *
	 * @var string
	 */
	protected $directorio = "";

	/**
	 * Constructor de la clase.
	 * Puede recibir un array con los datos a inicializar. Utiliza el constructor padre y en caso de corresponder carga los propios.
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
	}

	/**
	 * Comprueba el valor de un campo y hace el retorno que corresponda.
	 *
	 * @return string
	 */
	public function getMostrarListar()
	{
		if ($this->getDato () != "")
		{
			$datos = explode (".", $this->getDato ());
			if (in_array (strtolower (end ($datos)), array (
					'jpg',
					'jpeg',
					'bmp',
					'png'
			)))
			{
				$otrosImagen = "";
				$otrosImagen .= " height='" . $this->alto . "' ";
				$otrosImagen .= " width='" . $this->ancho . "' ";

				return "<img " . $otrosImagen . " src='" . $this->directorio . "/" . $this->getDato () . "'>";
			}
			elseif ($this->isNoMostrar () == false)
			{
				return $this->getDato ();
			}
		}
	}

	/**
	 *
	 * @return number
	 */
	public function getAlto()
	{
		return $this->alto;
	}

	/**
	 *
	 * @param number $alto
	 */
	public function setAlto($alto)
	{
		$this->alto = $alto;
	}

	/**
	 *
	 * @return number
	 */
	public function getAncho()
	{
		return $this->ancho;
	}

	/**
	 *
	 * @param number $ancho
	 */
	public function setAncho($ancho)
	{
		$this->ancho = $ancho;
	}
}

?>