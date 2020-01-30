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
	 * Directorio donde se guardaran los archivos subidos.
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
}

?>