<?php
namespace Campos;

/**
 *
 * @author iberlot
 *
 */
// require_once 'class_campo.php';

// require_once '../funciones.php';
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

/**
 * Clase encargada de manejar todo lo referente a los campos de texto.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 * @since 16 Nov. 2018
 *
 */
class Campos_textarea extends class_campo
{

    // /**
    // * Establece si hay que limpiar o no las entidades html del campo.
    // * Esto es util para lo que es textos formatesados.
    // *
    // * @var boolean
    // */
    // protected $noLimpiar = false;

    /**
     * Maximo de caracteres que mostrara por pantalla.
     *
     * @name maxMostrar
     * @var integer
     */
    protected $maxMostrar = 0;

    /**
     * Establece si el campo va a mostrar las funcioes de edicion y formateo del texto
     *
     * @name textoConFormato
     * @var boolean
     */
    protected $textoConFormato = true;

    /**
     * Script js que se encarga del funcionamiento del dinamic.
     *
     * @var string
     */
    protected $js_editor = "<script>
				  	function abilitarCKE(){
						ClassicEditor.create(
							document.querySelector( '#{CampoACambiar}' ),
							{toolbar: {shouldNotGroupWhenFull: true}}
						);
					}
				</script>";

    /**
     *
     * @return bool $textoConFormato
     */
    public function isTextoConFormato()
    {
        return $this->textoConFormato;
    }

    /**
     *
     * @param boolean $textoConFormato
     */
    public function setTextoConFormato($textoConFormato)
    {
        $this->textoConFormato = $textoConFormato;
    }

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

            if (array_key_exists('maxMostrar', $array)) {
                $this->setMaxMostrar($array['maxMostrar']);
            }
            // XXX esto existe para ofrecer compatibilidad con verciones anteriores
            if (array_key_exists('tmostrar', $array)) {
                $this->setMaxMostrar($array['tmostrar']);
            }
        } else {
            parent::__construct();
        }
        $this->setTipo('textarea');
    }

    /**
     * Retorna el valor de maxMostrar.
     *
     * @return number
     */
    public function getMaxMostrar(): int
    {
        return $this->maxMostrar;
    }

    /**
     *
     * Comprueba y setea el valor de maxMostrar
     *
     * @param number $maxMostrar
     */
    public function setMaxMostrar(int $maxMostrar)
    {
        $this->maxMostrar = $maxMostrar;
    }

    /**
     * Arma un Td con el dato de valor del campo
     *
     * @return string
     */
    public function get_celda_dato(): string
    {
        if ($this->isNoLimpiar() == true) {
            return "<td " . $this->get_extras_td() . " >" . $this->get_spanColorear() . " " . (($this->getMaxMostrar() > 0) ? substr($this->getValor(), 0, $this->getMaxMostrar()) : $this->getValor()) . " " . ($this->get_spanColorear() != "" ? "</span>" : "") . "</td> \n";
        } else {
            return "<td " . $this->get_extras_td() . ">" . $this->get_spanColorear() . " " . (($this->getMaxMostrar() > 0) ? substr($this->getValor(), 0, $this->getMaxMostrar()) : $this->getValor()) . " " . ($this->get_spanColorear() != "" ? "</span>" : "") . "</td> \n";
        }
    }

    public function generar_elemento_form_update(): string
    {
        return "<textarea class='input-textarea " . $this->getAtrRequerido() . "' name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled() . " value='" . $this->getValor() . "' " . $this->establecerMaxLeng() . " " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/>" . $this->getValor() . "</textarea>\n";
    }

    public function generar_elemento_form_nuevo(): string
    {
        return "<textarea class='input-textarea " . $this->getAtrRequerido() . "' name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled() . " value='" . $this->getValorPredefinido() . "' " . $this->establecerMaxLeng() . " " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/></textarea>\n";
    }

    /**
     * Comprueba el valor de un campo y hace el retorno que corresponda.
     *
     * @return string
     */
    public function getMostrarListar()
    {
        if ($this->getDato() != "") {
            if ($this->isNoLimpiar() == true) {
                return substr((html_entity_decode($this->getDato())), 0, $this->getMaxMostrar());
            } else {
                return substr($this->getDato(), 0, $this->getMaxMostrar());
            }
        }
    }

    public function getJs_editor()
    {
        $this->js_editor = str_ireplace('{CampoACambiar}', $this->getCampo(), $this->js_editor);
        return $this->js_editor;
    }
    // /**
    // *
    // * @return boolean
    // */
    // public function isNoLimpiar()
    // {
    // return $this->noLimpiar;
    // }

    // /**
    // *
    // * @param boolean $noLimpiar
    // */
    // public function setNoLimpiar($noLimpiar)
    // {
    // $this->noLimpiar = $noLimpiar;
    // }
}

?>