<?php

/**
 * Archivo de la clase Caja
 * 
 * 
 * @author lquiroga - lquiroga@gmail.com
 * 
 * @since 25 jun. 2019
 * @lenguage PHP
 * @name class_caja.php
 * @version 0.1 version inicial del archivo.
 * 

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
 * 
 * Manejo de caja del sistema tesoreria
 */

class CajaTesoreria {    
    
    protected $db;
    protected $id;
    protected $numero;
    protected $caja;
    protected $usuarioActual;
    protected $fondo;
    protected $fechaApertura;
    protected $fechaCierre;
    protected $turno;
    protected $idSesion;
    protected $idSucursal;
    protected $nombreSucursal;
    protected $telefono;
    protected $permisos;
    
    function __construct($db ,$idsesion = null) {
        
        $this->db = $db;
        
        if($idsesion != null){
            
            $query="select * from SESIONCAJA JOIN "
                    . " SESIONDETALLE ON SESIONCAJA.IDSESION = SESIONDETALLE.IDSESION"
                    . " WHERE SESIONCAJA.IDSESION = :idsesion ";
            
                $parametros = array (
                            $idsesion
                 );

            $result = $this->db->query ($query, true ,$parametros);

            $this->loadData($this->db->fetch_array ($result));
        }
        
    }

    /**
     * iniciarSesionCaja Inicia la sesion de la caja en la tabla SESIONCAJA
     * 
     * @param array $datos 
     * @return numeric->id de registro insertado
     */
    function iniciarSesionCaja($datos ,$fecha){
          /* INSERTO UN REGISTRO EN LA TABLA DE SESIONES */
    $query = "INSERT INTO SESIONCAJA"
            . "(IDSESION,IDPERSON,IDSESIONUSUARIO , FECHAAPERTURA,NROCAJA,TURNO,FONDOAPERTURA ,NOMBREUSUARIO ,IDSUCURSAL )VALUES("
            . "tesoreria.sesioncaja_seq.nextval , :idperson , :idsesionusuario ,TO_DATE('$fecha', 'dd/mm/yy'), :nrocaja , :turno,"
            . ":fondoapertura , :nombreusuario ,:idsucursal)";
    
    $fecha_id_sesion                    = date_format($fecha, 'dmy');   
  
    $idsesionusuario                    = $data['person'] . $fecha_id_sesion;
    
     

    $insert                         = $this->db->query($query, $esParam = true, $datos);

    //obtengo el id insertado en la linea de arriba
    $idsesion_caja              =  $this->db->insert_id('IDSESION', 'SESIONCAJA');
    //guardo en la sesion el id de la caja

        
    return $idsesion_caja;
    
    }
    
    
    /**
     * Carga datos en la tabla SESIONDETALLE
     *
     * @param type $datos array
     * @return bool
     * 
     */
    function detalleSesionCaja($datos){
        
        $monto_efectivo         = $datos[0];
        $monto_credito          = $datos[1];
        $monto_debito           = $datos[2];
        $monto_cheques          = $datos[3];
        $idsesion               = $datos[4];
        $accion                 = $datos[5];

        $sql = "INSERT INTO SESIONDETALLE(
                ID ,IDSESION,EFECTIVO,CHEQUE,CREDITO,DEBITO,ACCION)VALUES(
                SESIONDETALLE_SEQ.nextval , :idsesion, :efectivo, :cheque, :credito, :debito, :accion)";

        $params = array(
            $idsesion, //idperson
            $monto_efectivo,
            $monto_cheques,
            $monto_credito,
            $monto_debito,
            $accion
        );
     
        return $this->db->query($sql, $esParam = true, $params);
        
    }
    
    
    /**
     * Obtengo los movimientos por estado de una caja por usuario fecha y numero de caja
     * 
     * @return type
     */
    function getBalanceCaja(){
        
    //selecciono todos los recibos que esten cancelados , impresos o duplicados del dia de hoy de la cajera
    $query ="select ESTADO , IMPORTE , RECARGO , FTERMINO FROM movimientos "
            ."WHERE ESTADO IN (2,3,4)
            AND NROCAJERA = ".$this->getUsuarioActual()." 
            AND to_char(FECHATRANS,'dd/mm/yy') =  '".$this->getFechaApertura()."' AND idcaja = '".$this->getNumero()."'";    

    //recorro los recibos
    $datos_caja                         = $this->db->query($query);     
    
    return $this->db->fetch_array($datos_caja) ;
    
    }

    /**
     * En base a la sucursal de la caja obtiene permisos del menu
     * 
     * @param int $id_sucursal
     */
    function getMenuCaja($id_sucursal){
        
          //selecciono todos los recibos que esten cancelados , impresos o duplicados del dia de hoy de la cajera
    $query =" select IDITEMMENU ,DESCRIPCION ,IDHTML from tesoreria.PERMISOSMENUTESORERIA 
        join TESORERIA.OPCIONESMENUCAJATESORERIA ON PERMISOSMENUTESORERIA.IDITEMMENU = OPCIONESMENUCAJATESORERIA.ID 
        where IDSUCURSAL = :idsucursal";    

     $params = array(
            $id_sucursal);
             
    //recorro los recibos
    $permisos_caja                      = $this->db->query($query, true, $params);    
    
     while ($fila = $this->db->fetch_array ($permisos_caja)) {

            $salida[] = $fila;

        }

     return $salida;

    }
    
    
    
                
	/**
	 * loadData
	 * Carga propiedades del objeta que vienen desde la DB
         * 
         *  IDSESION
         *  IDPERSON
         *  FECHAAPERTURA
         *  FECHACIERRE
         *  NROCAJA
         *  TURNO
         *  FONDOAPERTURA
         *  FONDOCIERRE
         *  NOMBREUSUARIO
         *  IDSESIONUSUARIO
         *  DIFERENCIACIERRE
         *  IDSUCURSAL
         *
	 * @param array $fila
	 *        	return objet alumno
	 */        
        public function loadData($fila){
            
            $this->setId            ($fila['IDSESION']);
            $this->setNumero        ($fila['NROCAJA']);
            $this->setCaja          ($fila['NROCAJA']);
            $this->setUsuarioActual ($fila['IDPERSON']);
            $this->setFondo         ($fila['FONDOAPERTURA']);
            $this->setFechaApertura ($fila['FECHAAPERTURA']);
            $this->setFechaCierre   ($fila['FECHACIERRE']);
            $this->setTurno         ($fila['TURNO']);
            $this->setIdSesion      ($fila['IDSESION']);
            $this->setIdSucursal    ($fila['IDSUCURSAL']); 
           /* $this->setNombreSucursal($fila['']);
            $this->setTelefono      ($fila['']);
*/
        }

/***************GETTERS******************/

    function getPermisos() {
        return $this->permisos;
    }
    function getId() {
        return $this->id;
    }

     function getNumero() {
        return $this->numero;
    }

     function getCaja() {
        return $this->caja;
    }

     function getUsuarioActual() {
        return $this->usuarioActual;
    }

     function getFondo() {
        return $this->fondo;
    }

     function getFechaApertura() {
        return $this->fechaApertura;
    }

     function getFechaCierre() {
        return $this->fechaCierre;
    }

     function getTurno() {
        return $this->turno;
    }

     function getIdSesion() {
        return $this->idSesion;
    }

     function getIdSucursal() {
        return $this->idSucursal;
    }

     function getNombreSucursal() {
        return $this->nombreSucursal;
    }

     function getTelefono() {
        return $this->telefono;
    }



    /***************SETTERS******************/
    
     function setId($id) {
        $this->id = $id;
    }
     function setPermisos($permisos) {
        $this->permisos = $permisos;
    }

     function setNumero($numero) {
        $this->numero = $numero;
    }

     function setCaja($caja) {
        $this->caja = $caja;
    }

     function setUsuarioActual($usuarioActual) {
        $this->usuarioActual = $usuarioActual;
    }

     function setFondo($fondo) {
        $this->fondo = $fondo;
    }

     function setFechaApertura($fechaApertura) {
        $this->fechaApertura = $fechaApertura;
    }

     function setFechaCierre($fechaCierre) {
        $this->fechaCierre = $fechaCierre;
    }

     function setTurno($turno) {
        $this->turno = $turno;
    }

     function setIdSesion($idSesion) {
        $this->idSesion = $idSesion;
    }

     function setIdSucursal($idSucursal) {
        $this->idSucursal = $idSucursal;
    }

     function setNombreSucursal($nombreSucursal) {
        $this->nombreSucursal = $nombreSucursal;
    }

     function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

}