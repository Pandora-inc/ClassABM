<?php
namespace Campos;

use Exception;

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
// require_once 'class_campo.php';

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
    protected $tiposPermitidos = array(
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
     * Especifica si hay que cargar los datos del archivo en la base
     *
     * @var bool
     */
    protected $cargarEnBase = true;

    /**
     * Nombre con el que se guardara el archivo.
     *
     * @var string
     */
    protected $nombreArchivo = "";

    /**
     * Tama�o maximo del archivo
     *
     * @var int
     */
    protected $limiteArchivo = 0;

    /**
     * Constructor de la clase.
     * Puede recibir un array con los datos a inicializar. Utiliza el constructor padre y en caso de corresponder carga los propios.
     *
     * @param array $array
     */
    public function __construct(array $array = array())
    {
        if (isset($array) and !empty($array)) {
            parent::__construct($array);
        } else {
            parent::__construct();
        }
        $this->setTipo('upload');
    }

    /**
     * Comprueba el valor de un campo y hace el retorno que corresponda.
     *
     * @return string
     */
    public function getMostrarListar()
    {
        if ($this->getDato() != "") {
            $datos = explode(".", $this->getDato());
            if (in_array(strtolower(end($datos)), array(
                'jpg',
                'jpeg',
                'bmp',
                'png'
            ))) {
                $otrosImagen = "";
                $otrosImagen .= " height='" . $this->alto . "' ";
                $otrosImagen .= " width='" . $this->ancho . "' ";

                return "<img " . $otrosImagen . " src='" . $this->directorio . "/" . $this->getDato() . "'>";
            } elseif ($this->isNoMostrar() == false) {
                return $this->getDato();
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
     *            dato a cargar en la variable.
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
     *            dato a cargar en la variable.
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
        $dato = explode(".", $this->getValor());

        if (in_array(strtolower(end($dato)), $this->tiposPermitidos)) {
            $otrosImagen = "";
            $otrosImagen .= " height='" . $this->getAlto() . "' ";
            $otrosImagen .= " width='" . $this->getAncho() . "' ";

            return "<td " . $this->get_extras_td() . "><img " . $otrosImagen . " src='" . $this->getDirectorio() . "/" . $this->getValor() . "'></td> \n";
        } elseif ($this->isNoMostrar() == false) {
            return "<td " . $this->get_extras_td() . ">" . $this->getValor() . "</td> \n";
        }

        return "";
    }

    public function generar_elemento_form_update(): string
    {
        return "<input type='file' class='input-text " . $this->getAtrRequerido() . "' name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled() . " value='" . $this->getValor() . "' " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/> \n";
    }

    public function generar_elemento_form_nuevo(): string
    {
        return "<input type='file' class='input-text " . $this->getAtrRequerido() . "' name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled() . " value='" . $this->getValorPredefinido() . "' " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/> \n";
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
     *            dato a cargar en la variable.
     */
    public function setGrabarSinExtencion($grabarSinExtencion)
    {
        if ($grabarSinExtencion == TRUE or $grabarSinExtencion == 1 or mb_strtoupper($grabarSinExtencion) == 'V') {
            $this->grabarSinExtencion = TRUE;
        }
    }

    /**
     * Retorna el valor del atributo $cargarEnBase
     *
     * @return boolean $cargarEnBase el dato de la variable.
     */
    public function isCargarEnBase()
    {
        return $this->cargarEnBase;
    }

    /**
     * Setter del parametro $cargarEnBase de la clase.
     *
     * @param boolean $cargarEnBase
     *            dato a cargar en la variable.
     */
    public function setCargarEnBase($cargarEnBase)
    {
        $this->cargarEnBase = $cargarEnBase;
    }

    /**
     * Retorna el valor del atributo $nombreArchivo
     *
     * @return string $nombreArchivo el dato de la variable.
     */
    public function getNombreArchivo()
    {
        return $this->nombreArchivo;
    }

    /**
     * Setter del parametro $nombreArchivo de la clase.
     *
     * @param string $nombreArchivo
     *            dato a cargar en la variable.
     */
    public function setNombreArchivo($nombreArchivo)
    {
        $this->nombreArchivo = $nombreArchivo;
    }

    public function realizarCarga(): String
    {
        $valor = "";

        if (isset($_FILES[$this->getCampo()]) and $_FILES[$this->getCampo()]['size'] > 1) {
            // Iniciamos el upload del archivo
            if ($this->getNombreArchivo() != "") {
                $this->setNombreArchivo(str_replace("{{", "\$_REQUEST['", $this->getNombreArchivo()));
                $this->setNombreArchivo(str_replace("}}", "']", $this->getNombreArchivo()));

                $nombre = eval("return " . $this->getNombreArchivo() . ";");
                // $nombre = $data;

                if ($nombre == "") {
                    $nombre = $this->getNombreArchivo();
                }
            } else {
                $nombre = $_FILES[$this->getCampo()]['name'];
            }

            if ($this->isGrabarSinExtencion() == true) {
                $partes_nombre = explode('.', $nombre);
                $nombre = $partes_nombre[0];
            }

            $valor = $nombre;

            $nombre_tmp = $_FILES[$this->getCampo()]['tmp_name'];

            $tamano = $_FILES[$this->getCampo()]['size'];

            // if ($this->getUbicacionArchivo () != "")
            if ($this->getDirectorio() != "") {
                // $estructura = $this->getUbicacionArchivo ();
                $estructura = $this->getDirectorio();
            } else {
                $estructura = "";
            }

            // FIXME urgente!!
            // $tipo = $_FILES[$this->getCampo()]['type'];
            // if (isset ($this['tipoArchivo']) and $this['tipoArchivo'] != "")
            // {
            // $tipo_correcto = preg_match ('/^' . $this['tipoArchivo'] . '$/', $tipo);
            // }

            if ($this->getLimiteArchivo() > 0) {
                $limite = $this->getLimiteArchivo() * 1024;
            } else {
                $limite = 50000 * 1024;
            }

            if ($tamano <= $limite) {

                if ($_FILES[$this->getCampo()]['error'] > 0) {
                    throw new Exception('Error: ' . $_FILES[$this->getCampo()]['error'] . '<br/>' . var_dump($_FILES) . " en linea " . __LINE__);
                } else {

                    if (file_exists($nombre)) {
                        throw new Exception('<br/>El archivo ya existe: ' . $nombre);
                    } else {
                        if (file_exists($estructura)) {
                            move_uploaded_file($nombre_tmp, $estructura . "/" . $nombre) or die(" Error en move_uploaded_file " . var_dump(move_uploaded_file) . " en linea " . __LINE__);
                            chmod($estructura . "/" . $nombre, 0775);
                        } else {
                            mkdir($estructura, 0777, true);
                            move_uploaded_file($nombre_tmp, $estructura . "/" . $nombre) or die(" Error en move_uploaded_file " . var_dump(move_uploaded_file) . " en linea " . __LINE__);
                            chmod($estructura . "/" . $nombre, 0775);
                        }
                    }
                }
                // $imagen = $nombre;
            } else {
                throw new Exception('Tama�o de archivo inv&aacute;lido');
            }
            return $valor;
        } else if ($this->getNombreArchivo() != "") {
            // $valor = $this->getNombreArchivo ();
            return "NULL";
        }
        // Finalizamos el upload del archivo
    }

    /**
     * Retorna el valor del atributo $limiteArchivo
     *
     * @return mixed $limiteArchivo el dato de la variable.
     */
    public function getLimiteArchivo(): int
    {
        return $this->limiteArchivo;
    }

    /**
     * Setter del parametro $limiteArchivo de la clase.
     *
     * @param mixed $limiteArchivo
     *            dato a cargar en la variable.
     */
    public function setLimiteArchivo(int $limiteArchivo)
    {
        $this->limiteArchivo = $limiteArchivo;
    }
}

?>