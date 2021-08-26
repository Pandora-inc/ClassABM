<?php

/**
 * Clase manejo de archivos subir,obtener, bajar ,etc
 *
 *
 * TABLAS :
 *          documento.archivo       --->con el id de documento.documen (datos propios del archivo (nombre del aechivo,))
 *          documento.documen       --->Datos del tipo del tipo de registro (tipoarchivo , fechaalta ,person ,etv)
 *
 * @author lquiroga
 */
class Files
{

    protected $idarchivo;

    protected $iddocumen;

    protected $nombrearch;

    protected $estadoarch;

    protected $fechaalta;

    protected $person;

    protected $carpeta_server;

    protected $ext_archi;

    protected $IDFORMULARIO_pertenece;

    protected $db;

    public function __construct(&$db, $IDFORMULARIO_pertenece = NULL, $pre = NULL)
    {
        $this->db = &$db;

        if ($IDFORMULARIO_pertenece != null && $IDFORMULARIO_pertenece != '' && $pre == NULL) {

            $query = 'SELECT documento.documen.* , documento.archivo.* ,' . ' to_CHAR(documento.archivo.FECHAALTA, \'yyyy\') AS carpeta_server' . ' FROM  documento.documen JOIN documento.archivo ' . 'ON documento.documen.IDDOCUMEN =documento.archivo.IDDOCUMEN ' . 'WHERE documento.documen.IDDOCUMEN = :iddocumen';

            $parametros = array(
                $IDFORMULARIO_pertenece
            );

            $result = $this->db->query($query, true, $parametros);

            if ($result) {

                $arr_asoc = $this->db->fetch_array($result);

                $this->loadData($arr_asoc);
            }
        }
        else if ($IDFORMULARIO_pertenece != null && $IDFORMULARIO_pertenece != '' && $pre != NULL) {

            $query = 'SELECT documento.predocumen.* , documento.prearchivo.* ,' . ' to_CHAR(documento.prearchivo.FECHAALTA, \'yyyy\') AS carpeta_server' . ' FROM  documento.predocumen JOIN documento.prearchivo ' . 'ON documento.predocumen.IDDOCUMEN = documento.prearchivo.IDDOCUMEN ' . 'WHERE documento.predocumen.IDDOCUMEN = :iddocumen';

            $parametros = array(
                $IDFORMULARIO_pertenece
            );

            $result = $this->db->query($query, true, $parametros);

            if ($result) {

                $arr_asoc = $this->db->fetch_array($result);

                $this->loadData($arr_asoc);
            }
        }
    }

    /**
     *
     * @param $imagen array
     *            Array con los datos de la imagen , solo 1 imagen
     * @param string $path
     *            path donde se v a subir la img
     *
     *            crear carpeta con anio(si no existe)/arhicvoconiddelmismo.pdf
     *            //INSERTAR EN /u00/RepositorioArchivos/Solitram/
     *
     */
    public function uploadFile($imagen, $path, $id)
    {

        // buscamos los caracteres no permitiro
        $buscar = "/[^a-zA-Z\_\-\.0-9]/";

        $poner = "-";

        $tmp_file = $imagen['tmp_name'];

        $extension = explode('/', $imagen['type']);

        // echo('<br/>-+-->'.$tmp_file.'<br/>');

        if ($extension[1] == 'jpeg') {
            $extension = 'jpg';
        } else {
            $extension = $extension[1];
        }

        $nombre_real = preg_replace($buscar, $poner, $id . '.' . strtolower($extension));

        $e = move_uploaded_file($tmp_file, $path . '/' . $nombre_real);

        if ($e) {

            // echo('<br/>se subio a-+->'.$path.$nombre_real.'<br/>');
            return true;
        } else {

            return false;
            // return $path.'/'.$nombre_real;
        }
    }

    /**
     * Borra el archivo que le pasamos y lo reemplaza por uno del mismo nombre
     *
     * @param array $imagen-->array
     *            con dqatos de la img
     * @param string $path-->ruta
     * @param int $tablacodumento-->puede
     *            ser documento o predocumento
     * @param int $tablaarchivo-->puede
     *            ser archivo o prearchivo
     * @param int $person-->person
     *            de quien sube la img
     *
     * @return boolean
     */
    public function updateFile($imagen, $path, $tablacodumento, $tablaarchivo, $person)
    {

        /* primero elimino el archivo anterior */
        // $file =$path.'/'.$this->get_iddocumen().'.'.$this->get_extension();
        $file = $path . '/' . $this->get_idarchivo() . '.' . $this->get_extension();

        $renombre = $path . '/' . $this->get_idarchivo() . '_temp.' . $this->get_extension();

        // $renombrar = rename ($file, $renombre);
        rename($file, $renombre);

        // Hago update de la tbala document , registro el nuevo person y la nueva fecha alta
        if ($this->updateDocToDocumento($person, $tablacodumento)) {
            // si se hizo el update en tabla documento hago en tabla archivo
            if ($this->updateDocToArchivo($imagen, $person, $tablaarchivo)) {
                // si se hizo el update en archivo , subo la nueva imagen
                $e = $this->uploadFile($imagen, $path, $this->get_idarchivo());

                if (!$e) {
                    // Si no se subio el anterior renombro
                    rename($renombre, $file);
                }
            }
        }

        if ($e) {
            // echo('<br/>se subio a-+->'.$path.$nombre_real.'<br/>');
            return true;
        } else {

            return false;
            // return $path.'/'.$nombre_real;
        }
    }

    /**
     * Actualiza datos del documento en la tbala documento.documento
     *
     * @param int $tipo_adjunto-->id
     *            q identidica al tipo de doc
     * @param int $person-->id
     *            del usuario que esta usando el sistema
     * @return int -->devuelve el id de l doc que se inserto en la base
     */
    public function updateDocToDocumento($person, $tabla)
    {

        /* IDDOCUMEN - TIPOADJUNTO - ESTADODOCUMEN - FECHAALTA - PERSON */
        $datos = array();

        $datos['FECHAALTA'] = 'SYSDATE';

        $datos['PERSON'] = $person;

        $where = array();

        $where['IDDOCUMEN'] = $this->get_iddocumen();

        $insercion = $this->db->realizarUpdate($datos, $tabla, $where);

        if ($insercion) {

            return 1;
        } else {

            return 0;
        }
    }

    /**
     * checkDir
     *
     * @param string $ruta_dir
     *            ruta de la carpeta que quiero chequear
     * @param number $crear
     *            si esta en cero solo chequea si esta en uno chequea si existe y si
     *            si no existe la crea
     * @return boolean
     */
    public function checkDir($ruta_dir, $crear = 0)
    {
        $carpeta = $ruta_dir;

        if (!file_exists($carpeta)) {

            if ($crear == 0) {

                return 0;
            } else {

                mkdir($carpeta, 0777, true);

                return 1;
            }
        } else {

            return 1;
        }
    }

    /**
     * Salva datos del documento en la tbala documento.documento
     *
     * @param int $tipo_adjunto-->id
     *            q identidica al tipo de doc
     * @param int $person-->id
     *            del usuario que esta usando el sistema
     * @return int -->devuelve el id de l doc que se inserto en la base
     */
    public function saveDocToDocumento($tipo_adjunto, $person)
    {

        /* IDDOCUMEN - TIPOADJUNTO - ESTADODOCUMEN - FECHAALTA - PERSON */
        $datos = array();

        $datos['IDDOCUMEN'] = $this->db->insert_id('IDDOCUMEN', 'documento.documen') + 1;

        $datos['TIPOADJUNTO'] = $tipo_adjunto;

        $datos['ESTADODOCUMEN'] = 1;

        $datos['FECHAALTA'] = 'SYSDATE';

        $datos['PERSON'] = $person;

        $insercion = $this->db->realizarInsert($datos, 'documento.documen');

        if ($insercion) {

            return $this->db->insert_id('IDDOCUMEN', 'documento.documen');
        } else {

            return 0;
        }
    }

    /**
     * Salva datos del documento en la tbala documento.arechivo
     *
     * @param int $id_doc-->id
     *            de la tabla documento.documentos
     * @param String $nombrearchivo-->nombre
     *            real del archivo
     * @param int $person-->person
     *            de quien subio el archivo
     * @return bool
     */
    public function saveDocToArchivos($id_doc, $nombrearchivo, $person)
    {
        /* IDARCHIVO - IDDOCUMEN - NOMBREARCH - ESTADOARCH - FECHAALTA - PERSON */
        $datos = array();
        $datanombre = explode(".", $nombrearchivo);
        $extension = end($datanombre);
        $cortar = '.';
        $pos = strpos($nombrearchivo, $cortar);
        $nombrenuevo = substr($nombrearchivo, 0, $pos);

        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }

        // reemplazar por secuencioa
        $datos['IDARCHIVO'] = $this->db->insert_id('IDARCHIVO', 'documento.archivo') + 1;

        $datos['IDDOCUMEN'] = $id_doc;

        $datos['NOMBREARCH'] = $nombrenuevo . '.' . $extension;

        $datos['ESTADOARCH'] = 1;

        $datos['FECHAALTA'] = 'SYSDATE';

        $datos['PERSON'] = $person;

        $insercion = $this->db->realizarInsert($datos, 'documento.archivo');

        if ($insercion) {

            return $this->db->insert_id('IDARCHIVO', 'documento.archivo');
        } else {

            return 0;
        }
    }

    /**
     * Actualiza datos del documento en la tbala documento.documento
     *
     * @param int $tipo_adjunto-->id
     *            q identidica al tipo de doc
     * @param int $person-->id
     *            del usuario que esta usando el sistema
     * @return int -->devuelve el id de l doc que se inserto en la base
     */
    public function updateDocToArchivo($imagen, $person, $tabla)
    {

        // buscamos los caracteres no permitiro
        // $buscar = "/[^a-zA-Z\_\-\.0-9]/";
        // $poner = "-";

        // $tmp_file = $imagen['tmp_name'];
        $extension = explode('/', $imagen['type']);
        // echo('<br/>-+-->'.$tmp_file.'<br/>');

        if ($extension[1] == 'jpeg') {
            $extension = 'jpg';
        }

        /* $nombre_real = preg_replace ($buscar, $poner, $this->get_idarchivo () . '.' . strtolower ($extension)); */

        /* IDDOCUMEN - TIPOADJUNTO - ESTADODOCUMEN - FECHAALTA - PERSON */
        $datos = array();

        $datos['NOMBREARCH'] = $imagen['name'];

        $datos['ESTADOARCH'] = 1;

        $datos['FECHAALTA'] = 'SYSDATE';

        $datos['PERSON'] = $person;

        $where = array();

        $where['IDDOCUMEN'] = $this->get_iddocumen();

        $insercion = $this->db->realizarUpdate($datos, $tabla, $where);

        if ($insercion) {

            return $insercion;
        } else {

            return 0;
        }
    }

    /**
     *
     * @param int $tipo_adjunto-->id
     *            q identidica al tipo de doc
     * @param int $person-->id
     *            del usuario que esta usando el sistema
     * @return int -->devuelve el id de l doc que se inserto en la base
     */
    public function saveDocToPreDocumento($tipo_adjunto, $person)
    {

        /* IDDOCUMEN - TIPOADJUNTO - ESTADODOCUMEN - FECHAALTA - PERSON */
        $datos = array();

        $datos['IDDOCUMEN'] = $this->db->insert_id('IDDOCUMEN', 'documento.predocumen') + 1;

        $datos['TIPOADJUNTO'] = $tipo_adjunto;

        $datos['ESTADODOCUMEN'] = 1;

        $datos['FECHAALTA'] = 'SYSDATE';

        $datos['PERSON'] = $person;

        $insercion = $this->db->realizarInsert($datos, 'documento.predocumen');

        if ($insercion) {

            return $this->db->insert_id('IDDOCUMEN', 'documento.predocumen');
        } else {

            return 0;
        }
    }

    /**
     * Actualiza datos del documento en la tbala documento.prearchivo
     *
     * @param int $tipo_adjunto-->id
     *            q identidica al tipo de doc
     * @param int $person-->id
     *            del usuario que esta usando el sistema
     * @return int -->devuelve el id de l doc que se inserto en la base
     */
    public function saveDocToPreArchivos($id_doc, $nombrearchivo, $person)
    {
        /* IDARCHIVO - IDDOCUMEN - NOMBREARCH - ESTADOARCH - FECHAALTA - PERSON */
        $datos = array();
        $datanombre = explode(".", $nombrearchivo);
        $extension = end($datanombre);
        $cortar = '.';
        $pos = strpos($nombrearchivo, $cortar);
        $nombrenuevo = substr($nombrearchivo, 0, $pos);

        if ($extension == 'jpeg') {
            $extension = 'jpg';
        }

        // reemplazar por secuencioa
        $datos['IDARCHIVO'] = $this->db->insert_id('IDARCHIVO', 'documento.prearchivo') + 1;

        $datos['IDDOCUMEN'] = $id_doc;

        $datos['NOMBREARCH'] = $nombrenuevo . '.' . $extension;

        $datos['ESTADOARCH'] = 1;

        $datos['FECHAALTA'] = 'SYSDATE';

        $datos['PERSON'] = $person;

        $insercion = $this->db->realizarInsert($datos, 'documento.prearchivo');

        if ($insercion) {

            return $this->db->insert_id('IDARCHIVO', 'documento.prearchivo');
        } else {

            return 0;
        }
    }

    /**
     * loadData
     * Carga propiedades del objeta que vienen desde la DB
     *
     * @param array $fila
     *            return objet archivo
     */
    public function loadData($fila)
    {

        // IDARCHIVO - IDDOCUMEN - NOMBREARCH - ESTADOARCH - FECHAALTA - PERSON
        if (isset($fila['IDARCHIVO'])) {
            $this->set_idarchivo($fila['IDARCHIVO']);
        }

        if (isset($fila['IDDOCUMEN'])) {
            $this->set_iddocumen($fila['IDDOCUMEN']);
        }

        if (isset($fila['NOMBREARCH'])) {
            $this->set_nombrearch($fila['NOMBREARCH']);
        }

        if (isset($fila['ESTADOARCH'])) {
            $this->get_estadoarch($fila['ESTADOARCH']);
        }

        if (isset($fila['FECHAALTA'])) {
            $this->set_fechaalta($fila['FECHAALTA']);
        }

        if (isset($fila['PERSON'])) {
            $this->set_person($fila['PERSON']);
        }

        if (isset($fila['CARPETA_SERVER'])) {
            $this->set_carpeta_server($fila['CARPETA_SERVER']);
        }

        if (isset($fila['NOMBREARCH'])) {

            $ext_archi = explode('.', $fila['NOMBREARCH']);
            $this->set_extension($ext_archi[1]);
        }
    }

    /* * ****GETERS***** */
    function get_idarchivo()
    {
        return $this->idarchivo;
    }

    function get_iddocumen()
    {
        return $this->iddocumen;
    }

    function get_nombrearch()
    {
        return $this->nombrearch;
    }

    function get_estadoarch()
    {
        return $this->estadoarch;
    }

    function get_fechaalta()
    {
        return $this->fechaalta;
    }

    function get_person()
    {
        return $this->person;
    }

    function get_IDFORMULARIO_pertenece()
    {
        return $this->IDFORMULARIO_pertenece;
    }

    function get_carpeta_server()
    {
        return $this->carpeta_server;
    }

    function get_db()
    {
        return $this->db;
    }

    function get_extension()
    {
        return $this->extension;
    }

    /**
     * ****SETERS*****
     */
    function set_idarchivo($idarchivo)
    {
        $this->idarchivo = $idarchivo;
    }

    function set_iddocumen($iddocumen)
    {
        $this->iddocumen = $iddocumen;
    }

    function set_nombrearch($nombrearch)
    {
        $this->nombrearch = $nombrearch;
    }

    function set_estadoarch($estadoarch)
    {
        $this->estadoarch = $estadoarch;
    }

    function set_fechaalta($fechaalta)
    {
        $this->fechaalta = $fechaalta;
    }

    function set_person($person)
    {
        $this->person = $person;
    }

    function set_IDFORMULARIO_pertenece($IDFORMULARIO_pertenece)
    {
        $this->IDFORMULARIO_pertenece = $IDFORMULARIO_pertenece;
    }

    function set_db($db)
    {
        $this->db = $db;
    }

    function set_carpeta_server($carpeta_server)
    {
        $this->carpeta_server = $carpeta_server;
    }

    function set_extension($extension)
    {
        $this->extension = $extension;
    }
}
