<?php
namespace Campos;

use Funciones;
use Sitios;
use class_db;

/**
 * Contenedor de la clase campos fecha.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 6 dic. 2018
 * @lenguage PHP
 * @name Campos_feha.php
 * @version 0.1 version inicial del archivo.
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
 * totalHorasPerdidasAqui = 0
 *
 */
// require_once 'class_campo.php';

// require_once '../class_db.php';
// require_once '../funciones.php';

/**
 * Clase que maneja los campos del tipo fecha.
 *
 * @author iberlot
 * @name Campos_fecha
 */
class Campos_fecha extends class_campo
{

    /**
     * Codigo JS para poner en window.onload para cada uno de los campos de fecha *
     */
    private $jsIniciadorCamposFecha = '
    <script>
    $(function(){
        $("#%IDCAMPO%").datepicker({
      		changeMonth: true,
      		changeYear: true,
            regional: "es",
            showAnim: "fade",
            dateFormat: "yy-mm-dd",
            altField: "#display_%IDCAMPO%",
            altFormat: "dd/mm/yy",
			yearRange: "-200:+10"
        });
        $("#display_%IDCAMPO%").focus(function(){$("#%IDCAMPO%").datepicker("show")});
        if("%VALOR%" != "") $("#%IDCAMPO%").datepicker("setDate", "%VALOR%");
    });
    </script>';

    /**
     * Objeto de coneccion a la base de datos
     *
     * @var class_db Class_db
     */
    private $db = null;

    /**
     * Mascara a utilizar en las consultas.
     *
     * @var string
     */
    private $mascara = "dd/mm/YYYY";

    public function __toString(): string
    {
        return "Campo: " . $this->getCampo() . " Valor: " . $this->getValor();
    }

    /**
     * Constructor de la clase.
     * Puede recibir un array con los datos a inicializar. Utiliza el constructor padre y en caso de corresponder carga los propios.
     *
     * @param array $array
     */
    public function __construct(array $array = array(), class_db $db = null)
    {
        if (!isset($db) or empty($db) or $db == null) {
            if (!$this->db = Sitios::openConnection()) {
                global $db;

                if (isset($db) and !empty($db) and $db != null) {
                    $this->db = $db;
                }
            }
        } else {
            $this->db = $db;
        }

        if ($this->db->getDbtype() == "mysql") {
            $this->setMascara("%Y-%m-%d");
        }

        if (isset($array) and !empty($array)) {
            if (array_key_exists('mascara', $array)) {
                $this->setMascara($array['mascara']);
            }

            parent::__construct($array);
        } else {
            parent::__construct();
        }
        $this->setTipo('fecha');
    }

    /**
     *
     * @param String $busqueda
     *            variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
     *
     * @return string
     */
    public function campoFormBuscar(&$busqueda): string
    {
        $retorno = "";
        $disabled = " ";

        if ($this->requerido == TRUE) {
            $requerido = " required ";
        } else {
            $requerido = " ";
        }

        if (isset($_REQUEST['c_' . $this->campo])) {
            $valor = Funciones::limpiarEntidadesHTML($_REQUEST['c_' . $this->campo]);

            // FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
            $busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML($_REQUEST['c_' . $this->campo]);
        }

        if (strlen($valor) > 10) {
            $valor = substr($valor, 0, 10); // sacar hora:min:seg
        }
        if ($valor == '0000-00-00') {
            $valor = "";
        }
        $jsTmp = str_replace('%IDCAMPO%', 'c_' . $this->campo, $this->jsIniciadorCamposFecha);
        $jsTmp = str_replace('%VALOR%', $valor, $jsTmp);

        $retorno .= $jsTmp;
        $retorno .= "<input type='text' style='position:absolute' class='input-fecha' name='c_" . $this->campo . "' id='c_" . $this->campo . "' value='" . ($valor) . "'/> \n";
        $retorno .= "<input type='text' style='position:relative;top:0px;left;0px'  name='display_c_" . $this->campo . "' id='display_c_" . $this->campo . "' class='input-fecha " . $requerido . "' " . $disabled . " " . $this->getAdicionalInput() . " readonly='readonly'/> \n";

        return $retorno;
    }

    /**
     * Arma el string del nombre del campo (tabla.campo AS nombrecampo) para agregar en el SELECT
     *
     * @return string
     */
    public function get_campo_select(): string
    {
        if ($this->isBuscar() == true or $this->isNoListar() == false) {
            if ($this->existeDato("joinTable")) {
                $tablaJoin = $this->prepara_joinTable($this->getJoinTable());

                if ($this->isOmitirJoin() == false) {
                    if ($this->getSelectPersonal() == true) {
                        return $this->getSelectPersonal() . " AS " . substr($tablaJoin . "_" . $this->getCampoTexto(), 0, 30);
                    } else {
                        if ($this->getCampoTexto() != "") {
                            return $this->db->toChar($this->getJoinTable() . "." . $this->getCampoTexto(), substr($tablaJoin, 0, 3) . "_" . $this->getCampoTexto(), $this->mascara);
                        } else {
                            return $this->db->toChar($this->getJoinTable() . "." . $this->getCampo(), substr($tablaJoin, 0, 3) . "_" . $this->getCampo(), $this->mascara);
                        }
                    }
                    // FIXME calculo que habria que armar otra funcion que haga esto
                    // $camposOrder .= "|" . $this->getCampoTexto ();
                } else {
                    if ($this->getSelectPersonal() == true) {
                        return $this->getSelectPersonal() . " AS " . $this->getCampoTexto();
                    } else {
                        // FIXME Hay que encontrar un metodo mejor ya que si hay mas de una tabla con el mismo campo y las primeras tres letras del nombre de la tabla iguales tirara que la columna esta definida de forma ambigua.

                        $camposSelect .= $this->db->toChar($this->getJoinTable() . "." . $this->getCampo(), substr($tablaJoin, 0, 3) . "_" . $this->getCampo(), $this->mascara);
                        $this->setCampo(substr($tablaJoin, 0, 3) . "_" . $this->getCampo());

                        return $camposSelect;
                    }
                }
            } else {
                if ($this->getSelectPersonal() == true) {
                    return $this->getSelectPersonal() . " AS " . $this->getCampo();
                } else {
                    return $this->db->toChar($this->tabla . "." . $this->getCampo(), $this->getCampo(), $this->mascara);
                }
            }
        }
    }

    /**
     * Retorna el valor de campoOrder
     *
     * @return string
     */
    public function getCampoOrder(): string
    {
        if ($this->campoOrder != "") {
            return $this->campoOrder;
        } else {
            if ($this->joinTable == "" or $this->selectPersonal != "") {
                return "TO_CHAR(" . $this->tabla . "." . $this->getCampo() . ", 'yyyymmdd')";
            } else {
                return "TO_CHAR(" . $this->getJoinTable() . "." . $this->getCampoTexto() . ", 'yyyymmdd')";
            }
        }
    }

    /**
     * Arma el where para la busqueda dentro de ese campo.
     *
     * @return string
     */
    public function get_where_buscar(string $valorABuscar): string
    {
        $camposWhereBuscar = "";

        if ($this->buscarUsarCampo != "") {
            $camposWhereBuscar .= "UPPER(" . $this->getBuscarUsarCampo() . ")";
        } else {
            $valorABuscar = str_replace("/", "%", $valorABuscar);
            $valorABuscar = str_replace("-", "%", $valorABuscar);
            $valorABuscar = str_replace(" ", "%", $valorABuscar);

            if ($this->joinTable == "" or $this->selectPersonal != "") {
                $camposWhereBuscar .= $this->db->toChar($this->tabla . "." . $this->getCampo(), "", $this->mascara);
            } else {
                $camposWhereBuscar .= $this->db->toChar($this->getJoinTable . "." . $this->getCampoTexto(), "", $this->mascara);
            }
        }

        $camposWhereBuscar .= " ";

        if ($this->getBuscarOperador() != "" and strtolower($this->getBuscarOperador()) != 'like') {
            $camposWhereBuscar .= $this->buscarOperador . " UPPER('" . $valorABuscar . "')";
        } else {
            $valorABuscar = str_replace(" ", "%", $valorABuscar);
            $camposWhereBuscar .= "LIKE UPPER('%" . $valorABuscar . "%')";
        }

        return $camposWhereBuscar;
    }

    /**
     *
     * {@inheritdoc}
     * @see class_campo::generar_elemento_form_update()
     */
    public function generar_elemento_form_update(): string
    {
        if (strlen($this->getValor()) > 10) {
            $this->setValor(substr($this->getValor(), 0, 10)); // sacar hora:min:seg
        }
        if ($this->getValor() == '0000-00-00') {
            $this->setValor("");
        }

        $jsTmp = str_replace('%IDCAMPO%', $this->getCampo(), $this->jsIniciadorCamposFecha);
        $jsTmp = str_replace('%VALOR%', $this->getValor(), $jsTmp);

        $imprForm = $jsTmp;
        // $imprForm .= "<input type='date' style='position:absolute' name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' value='" . ($this->getValor () != "" ? $this->getValor () : ($this->getValorPredefinido () != "" ? $this->getValorPredefinido () : " ")) . "'/> \n";
        $imprForm .= "<input type='date' style='position:relative;top:0px;left;0px' " . $this->autofocusAttr . " name='display_" . $this->getCampo() . "' id='display_" . $this->getCampo() . "' class='input-fecha " . $this->getAtrRequerido() . "' " . $this->getAtrDisabled() . " " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/> \n";

        return $imprForm;
    }

    /**
     *
     * {@inheritdoc}
     * @see class_campo::generar_elemento_form_update()
     */
    public function generar_elemento_form_nuevo(): string
    {
        if (strlen($this->getValor()) > 10) {
            $this->setValor(substr($this->getValor(), 0, 10)); // sacar hora:min:seg
        }
        if ($this->getValor() == '0000-00-00') {
            $this->setValor("");
        }

        $jsTmp = str_replace('%IDCAMPO%', $this->getCampo(), $this->jsIniciadorCamposFecha);
        $jsTmp = str_replace('%VALOR%', $this->getValor(), $jsTmp);

        $imprForm = $jsTmp;
        // $imprForm .= "<input type='date' style='position:absolute' name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' value='" . ($this->getValor () != "" ? $this->getValor () : ($this->getValorPredefinido () != "" ? $this->getValorPredefinido () : " ")) . "'/> \n";
        // $imprForm .= "<input type='date' style='position:relative;top:0px;left;0px' " . $this->autofocusAttr . " name='display_" . $this->getCampo () . "' id='display_" . $this->getCampo () . "' class='input-fecha " . $this->getAtrRequerido () . "' " . $this->getAtrDisabled () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . "/> \n";
        $imprForm .= "<input type='date' style='position:relative;top:0px;left;0px' " . $this->autofocusAttr . " name='" . $this->getCampo() . "' id='" . $this->getCampo() . "' class='input-fecha " . $this->getAtrRequerido() . "' " . $this->getAtrDisabled() . " " . $this->establecerHint() . " " . $this->getAdicionalInput() . "/> \n";

        return $imprForm;
    }

    /**
     * Retorna el valor del atributo $mascara
     *
     * @return string $mascara el dato de la variable.
     */
    public function getMascara(): string
    {
        return $this->mascara;
    }

    /**
     * Setter del parametro $mascara de la clase.
     *
     * @param string $mascara
     *            dato a cargar en la variable.
     */
    public function setMascara(string $mascara)
    {
        $this->mascara = $mascara;
    }
}
?>