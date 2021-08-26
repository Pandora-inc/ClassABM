<?php
namespace Campos;

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
class Campos_password extends class_campo
{

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
        $this->setTipo('password');
    }

    public function generar_elemento_form_update(): string
    {
        return "<input type='password' class='input-text " . $this->getAtrRequerido() . "' name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled() . " value='" . $this->getValor() . "' " . $this->establecerMaxLeng() . " " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/> \n";
    }

    public function generar_elemento_form_nuevo(): string
    {
        return "<input type='password' class='input-text " . $this->getAtrRequerido() . "' name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled() . " value='' " . $this->establecerMaxLeng() . " " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/> \n";
    }
}

?>