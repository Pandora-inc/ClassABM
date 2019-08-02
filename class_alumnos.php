<?php
/**
 * Archivo de la clase Alumnos
 *
 * @author lquiroga - lquiroga@gmail.com 
 * 
 * @author iberlot <@> iberlot@usal.edu.ar
 * 
 * 
 * 
 * @since 7 mar. 2019
 * @lenguage PHP
 * @name class_alumnos.php
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
 */

require_once ("/web/html/classesUSAL/class_Personas.php");

class Alumnos extends Personas{    

        protected $id;
	protected $idcentrocosto;
	protected $carrera_descrip;
	protected $carrera;
	protected $carrera_stat;
	protected $fa;
	protected $es;
	protected $ca;
	protected $plan;
	protected $desc_unidad_alumno;
        
    public function __construct($db ,$id , $centrocosto = null){

            $this->db = $db;

            if ($id != null && trim($id) != ''){

                if($centrocosto != null && trim($centrocosto) != ''){

                $this->findByPerson($id ,$centrocosto);
                    
            }else{
 
                $this->findByPerson($id);
           
            }
        }
    }
        
    /**
    * 
    * findByPerson busca alumno por person
    *
    * @param int $person
    * @param int $centrodecosto 
    *        
    **/
    public function findByPerson($person ,$centrocosto = null){            

            $this->set_person($person);

            $anio_actual = date ("Y");

            $parametros = array (
                            $anio_actual,
                            $person
            );

            $query = "      SELECT 
                                carstu.career,
                                carstu.branch,
                                perdoc.typdoc,
                                perdoc.docno,
                                ccalu.idcentrodecosto,
                                ccalu.person,
                                person.lname,
                                person.fname,
                                carstu.career,
                                carstu.plan,
                                carstu.stat,
                                career.descrip
                            FROM
                                appgral.person
                                JOIN appgral.perdoc ON person.person = perdoc.person
                                JOIN studentc.carstu ON person.person = carstu.student
                                JOIN studentc.career ON carstu.career = career.code
                                JOIN contaduria.centrodecosto ON centrodecosto.fa||centrodecosto.ca = career.code and centrodecosto.es = carstu.BRANCH
                                JOIN tesoreria.ccalu ON person.person = ccalu.person and centrodecosto.idcentrodecosto = ccalu.idcentrodecosto
                            WHERE
                                    ccalu.aniocc =:anio
                                AND
                                    person.person =:person ";

        if($centrocosto != null){

            $query .= " AND ccalu.idcentrodecosto = :centrocosto";

            array_push($parametros,$centrocosto);

        }

        $result = $this->db->query ($query, $esParam = true, $parametros);

        $this->loadData($this->db->fetch_array ($result));
    }
        

    /**
    * 
    * En base a un criterio de busqueda (apellido , dni o nombre) devuelve alumno en objeto
    * dentro de un array
    *
    * @param type string $criterio
    *
    * @return array[object] ->Alumnos
    * 
    */      
    public function findByProps($criterio ,$fa = null){

            $anio_actual = date ("Y");

            $criterio1 = $criterio2 = $criterio3 = $criterio;

            $parametros = array (
                $anio_actual,
                strtoupper ($criterio1),
                strtoupper ($criterio2),
                strtoupper ($criterio3)
            );

            if(!$fa){

            $query = "SELECT
                                person.person,
                                person.lname,
                                person.fname
                            FROM
                                appgral.person
                                JOIN appgral.perdoc ON person.person = perdoc.person
                                JOIN tesoreria.ccalu ON person.person = ccalu.person

                            WHERE
                                    ccalu.aniocc =:anio
                                AND (
                                        lname LIKE '%' ||:busq1 || '%'
                                    OR
                                        fname LIKE '%' ||:busq2 || '%'
                                    OR
                                        docno LIKE '%' ||:busq3 || '%'
            )";

            }else{

                $unidades="";

                foreach ($fa as $row){

                    $unidades.=$row.','; 

                }

                $unidades.='00';

            $query = "SELECT ccalu.person, person.lname, person.fname, LPAD(career.facu, 2, '0') FACU ,
                            centrodecosto.idcentrodecosto FROM appgral.person 
                            JOIN appgral.perdoc ON person.person = perdoc.person
                            JOIN studentc.carstu ON person.person = carstu.student
                            JOIN studentc.career ON carstu.career = career.code
                            JOIN tesoreria.ccalu ON person.person = ccalu.person
                            JOIN contaduria.centrodecosto ON centrodecosto.fa||centrodecosto.ca = career.code and centrodecosto.es = carstu.BRANCH
                            JOIN tesoreria.ccalu ON person.person = ccalu.person and centrodecosto.idcentrodecosto = ccalu.idcentrodecosto
                        WHERE
                                ccalu.aniocc =:anio
                            AND (
                                    lname LIKE '%' ||:busq1 || '%'
                                OR
                                    fname LIKE '%' ||:busq2 || '%'
                                OR
                                    docno LIKE '%' ||:busq3 || '%'
                                ) AND FACU IN( $unidades ) group by centrodecosto.idcentrodecosto ,
                                ccalu.person,person.lname, person.fname,FACU";

            }
            
            $result = $this->db->query($query, $esParam = true, $parametros);

            while ($fila = $this->db->fetch_array($result)){

                if(isset($fila['IDCENTRODECOSTO'])){
                    
                    $alumno = new Alumnos($this->db ,$fila['PERSON'] ,$fila['IDCENTRODECOSTO']);
                    
                    $salida[] = $alumno;
                     
                }else{        
                    
                    $alumno = new Alumnos($this->db ,$fila['PERSON'] );
                    
                    $salida[] = $alumno;
                     
                    
                }
            }

            return $salida;
    }
          
    
        /**
         * 
	 * En base al centrocosto seteo la faesca fa-es-ca
	 *
	 * @param int $centrodecosto
         * 
         * @return int ->Faesca
         * 
	 */
        public function obtenerSeterarFaesca($idcentrocosto){
            
        $param  = array(
           $idcentrocosto   
        );
 
        $query      = "SELECT * FROM contaduria.centrodecosto WHERE IDCENTRODECOSTO = :centrocosto";

        $scfaes     =$this->db->query ($query, $esParam = true, $param);
        
       if ($scfaes) {
           
        $arr_asoc = $this->db->fetch_array($scfaes);

            $this->set_fa($arr_asoc['FA']);
            $this->set_es($arr_asoc['ES']);
            $this->set_ca($arr_asoc['CA']);
         
        return ($this->get_fa().$this->get_es().$this->get_ca());
      
       }
                        
     }
         
    /**
     * En base a la facultad obtengo el nombre de la misma
     * @param number $fa
     * @return string
     */
    public function obtener_unidad_por_fa($fa){

        $param  = array(
           $fa   
         );

        $query      = "select SDESC from studentc.facu where LPAD(code, 2, '0') = LPAD(:fa, 2, '0') ";

        $scfaes     =$this->db->query ($query, $esParam = true, $param);

        if ($scfaes){

            $arr_asoc = $this->db->fetch_array($scfaes);

            return ($arr_asoc['SDESC']);

       }

    }
        
    /**
    * En base al person del alumno obtiene la foto
    *
    * @param int $person
    *
    * @return string url de la foto
    */
    public function get_Photo_alumno($person){

           $foto1 = substr ($person, -1, 1);

           $foto2 = substr ($person, -2, 1);

           $foto3 = substr ($person, -3, 1);

           $url_foto = 'http://roma2.usal.edu.ar/FotosPerson/' . $foto1 . '/' . $foto2 . '/' . $foto3 . '/' . $person . '.jpg';

           if (getimagesize ($url_foto)){

                $url_foto = '/FotosPerson/' . $foto1 . '/' . $foto2 . '/' . $foto3 . '/' . $person . '.jpg';
           
           }else{

                $url_foto = '/FotosPerson/sinfoto1.jpg';
           }

        return $url_foto;
    }
                
    /**
    * 
    * MateriasAprxPlanCarrera muestra las materias aprobadas en 
    * base a un plan especifico una carrera y un alumno
    * 
    * @param numeric $person
    * @param numeric $carrera
    * @param numeric $plan
    * @param array $estados
    * @param array $estados
    * @param number $cuatrimestre--> esta serteado en menos dos , por que existen cuatrimestres -1 0 y 1
    * 
    * 
    *  
    *   
    *   FINALPASS,	2
    *   EQUIVAL,	3
    *   POSTPONED,	4
    *   COUREXAM,	5
    *   PREEXAM,	6
    *   FAILED,	7
    *   COURLOST,	8
    *   COURFAIL	9
    * cursadaabandonada 10
    * @return array de materias q no estan en el listado que le pasamos para excluir
    * 
    */
    public function MateriasAprxPlanCarrera($person ,$carrera , $plan ,$estados,$cuatrimestre=-2){

        $query="select s.subject ,C.QUARTER , s.stat  from studentc.stusubj s 
        JOIN STUDENTC.COURSE C  ON  s.course = c.code "
        ."where S.student=:person  and S.career=:carrera "
        ." and S.plan= :plan AND S.STAT IN ($estados) ";
        
       /* $query_debug="select s.subject ,C.QUARTER , s.stat  from studentc.stusubj s 
        JOIN STUDENTC.COURSE C  ON  s.course = c.code "
        . "where S.student=$person  and S.career=$carrera "
        ." and S.plan= $plan AND S.STAT IN ($estados) ";*/
        
       // echo($query_debug);
        
         if($cuatrimestre != -2){
              $query.="AND C.QUARTER = ".$cuatrimestre;
         }
                        
       /* echo("select subject , stat  from studentc.stusubj where student=$person  and career=$carrera "
        ." and plan= $plan AND STAT IN ($estados) ");*/         
         
         $parametros=array(
            $person,
            $carrera,
            $plan
        );

        $subjectMaterias =$this->db->query ($query, $esParam = true, $parametros);

        $subject_x_estado ='';
        
        while ($fila = $this->db->fetch_array($subjectMaterias)){
            
                $subject_x_estado[]= $fila['SUBJECT'];
                
         }
         
         return $subject_x_estado;
     }
                
	/**
	 * loadData
	 * Carga propiedades del objeta que vienen desde la DB
	 *
	 * @param array $fila
	 *        	return objet alumno
	 */
        
        public function loadData($fila){

            $this->set_person ($fila['PERSON']);
            $this->set_nombre ($fila['FNAME'] . ' ' . $fila['LNAME']);
            $this->set_typodoc ($fila['TYPDOC']);
            $this->set_nrodoc ($fila['DOCNO']);
            $this->set_carrera ($fila['CAREER']);
            $this->set_lname ($fila['LNAME']);
            $this->set_fname ($fila['FNAME']);
            $this->set_carrera_descrip ($fila['DESCRIP']);
            $this->set_idcentrocosto ($fila['IDCENTRODECOSTO']);
            $this->set_plan ($fila['PLAN']);
            $this->set_carrera_stat ($fila['STAT']);

            /*seteo la faesca del alumno*/
            if(isset($fila['IDCENTRODECOSTO'])){

                $this->obtenerSeterarFaesca($fila['IDCENTRODECOSTO']);    

            }

            $this->set_foto ($this->get_Photo_alumno ($fila['PERSON']));


            /*en base a la fa obtengo el nomber de la unidad a la cual pertenece el alumno*/
            $this->set_desc_unidad_alumno($this->obtener_unidad_por_fa($this->get_fa()));                
                
	}        
        
	/* SETTER Y GEGTTERS */
	function get_id(){
		return $this->id;
	}
	
        function get_person(){
		return $this->person;
	}
        
	function get_nombre(){
		return $this->nombre;
	}
        
	function get_typodoc(){
		return $this->typodoc;
	}
        
	function get_nrodoc(){
		return $this->nrodoc;
	}
        
        
	function get_carrera(){
		return $this->carrera;
	}
        
	function get_lname(){
		return $this->lname;
	}
        
	function get_conexion(){
		return $this->conexion;
	}
        
	function get_fname(){
		return $this->fname;
	}
        
	function get_carrera_descrip(){
            
		return $this->carrera_descrip;
	}
        
	function get_idcentrocosto(){
		return $this->idcentrocosto;
	}
        
	function get_foto(){
		return $this->foto;
	}
        
	function get_fa(){
		return $this->fa;
	}
        
	function get_es(){
		return $this->es;
	}
        
	function get_ca(){
		return $this->ca;
	}
        
	function get_plan(){
		return $this->plan;
	}
        
            
        function get_desc_unidad_alumno() {
            return $this->desc_unidad_alumno;
        }

        function get_carrera_stat() {
            return $this->carrera_stat;
        }

        /*************/
        
        
        function set_carrera_stat($carrera_stat) {
            $this->carrera_stat = $carrera_stat;
        }

	function set_id($id){
            $this->id = $id;
	}
        
	function set_person($person){
            $this->person = $person;
	}
        
	function set_nombre($nombre){
            $this->nombre = $nombre;
	}
        
	function set_typodoc($typodoc){
            $this->typodoc = $typodoc;
	}
        
	function set_nrodoc($nrodoc){
            $this->nrodoc = $nrodoc;
	}
        
	function set_carrera($carrera){
            $this->carrera = $carrera;
	}
        
	function set_lname($lname){
		$this->lname = $lname;
	}
        
	function set_fname($fname){
		$this->fname = $fname;
	}
        
	function set_conexion($conexion){
		$this->conexion = $conexion;
	}
        
	function set_carrera_descrip($carrera_descrip){
		$this->carrera_descrip = $carrera_descrip;
	}
        
	function set_idcentrocosto($idcentrocosto){
		$this->idcentrocosto = $idcentrocosto;
	}
        
	function set_foto($foto){
		$this->foto = $foto;
	}
        
	function set_fa($fa){
		$this->fa = $fa;
	}
        
	function set_ca($ca){
		$this->ca = $ca;
	}
        
	function set_es($es){
		$this->es = $es;
	}
        
	function set_plan($plan){
		$this->plan = $plan;
	}

        function set_desc_unidad_alumno($desc_unidad_alumno) {
            $this->desc_unidad_alumno = $desc_unidad_alumno;
        }
        
}
?>