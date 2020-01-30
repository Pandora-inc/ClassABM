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
	 * Tipos de archivos que esta permitido subir al servidor.
	 *
	 * @var array
	 */
	protected $tiposPermitidos = array (
			'jpg',
			'jpeg',
			'bmp',
			'png'
	);

	/**
	 * Habilita la carga del archivo sin la extencion correspondiente.
	 *
	 * @var bool
	 */
	protected $grabarSinExtencion = FALSE;

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

	/**
	 * Retorna el valor del atributo $tiposPermitidos
	 *
	 * @return array $tiposPermitidos el dato de la variable.
	 */
	public function getTiposPermitidos(): array
	{
		return $this->tiposPermitidos;
	}

	/**
	 * Setter del parametro $tiposPermitidos de la clase.
	 *
	 * @param array $tiposPermitidos
	 *        	dato a cargar en la variable.
	 */
	public function setTiposPermitidos(array $tiposPermitidos)
	{
		$this->tiposPermitidos = $tiposPermitidos;
	}

	/**
	 * Retorna el valor del atributo $directorio
	 *
	 * @return string $directorio el dato de la variable.
	 */
	public function getDirectorio(): string
	{
		return $this->directorio;
	}

	/**
	 * Setter del parametro $directorio de la clase.
	 *
	 * @param string $directorio
	 *        	dato a cargar en la variable.
	 */
	public function setDirectorio(string $directorio)
	{
		$this->directorio = $directorio;
	}

	/**
	 * Arma un Td con el dato de valor del campo
	 *
	 * @return string
	 */
	public function get_celda_dato(): string
	{
		$dato = explode (".", $this->getValor ());

		if (in_array (strtolower (end ($dato)), $this->tiposPermitidos))
		{
			$otrosImagen = "";
			$otrosImagen .= " height='" . $this->getAlto () . "' ";
			$otrosImagen .= " width='" . $this->getAncho () . "' ";

			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . "><img " . $otrosImagen . " src='" . $this->getDirectorio () . "/" . $this->getValor () . "'></td> \n";
		}
		elseif ($this->isMostrar () == true)
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->getValor () . "</td> \n";
		}
	}

	public function generar_elemento_form_update(): string
	{
		return "<input type='file' class='input-text " . $this->getAtrRequerido () . " name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled () . " value='" . $this->getValor () . "' " . $this->establecerHint () . " " . $this->getAdicionalInput () . "/> \n";
	}

	/**
	 * Retorna el valor del atributo $grabarSinExtencion
	 *
	 * @return boolean $grabarSinExtencion el dato de la variable.
	 */
	public function isGrabarSinExtencion(): bool
	{
		return $this->grabarSinExtencion;
	}

	/**
	 * Setter del parametro $grabarSinExtencion de la clase.
	 * Si el valor pasado es cualquier cosa que no sea: TRUE, 1 o 'v' el campo sera seteado como falso.
	 *
	 * @param boolean|int|string $grabarSinExtencion
	 *        	dato a cargar en la variable.
	 */
	public function setGrabarSinExtencion($grabarSinExtencion)
	{
		if ($grabarSinExtencion == TRUE or $grabarSinExtencion == 1 or mb_strtoupper ($grabarSinExtencion) == 'V')
		{
			$this->grabarSinExtencion = TRUE;
		}
	}
}

?>