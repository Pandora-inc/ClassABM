<?php
/**
 * @author iberlot
 * @version 1.0
 * @package  clase Persona
 * @category Edicion
 *
 * Clase que devuelve todos los datos de la persona.
 *
 * @link config/includes.php - Archivo con todos los includes del sistema
 *
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
 * totalHorasPerdidasAqui = 106
 *
 */
class class_persona {
	
	/**
	 * Apellido de la persona
	 * 
	 * @var string 
	 * <Br>
	 * @ubicacionBase appgral.person.lname - VARCHAR2(50 BYTE)
	 * 
	 * @internal Este dato siempre tiene que estar en mayuscula. 
	 * 		$apellido = strtoupper($apellido);
	 */
	public $apellido = "";
	
	/**
	 * Nombre de la persona
	 * @var string   
	 * <Br>
	 * @ubicacionBase appgral.person.fname - VARCHAR2(50 BYTE)
	 * 
	 * @internal Este campo deberia tener la primer letra en mayuscula y el resto siempre en minuscula
	 * 		$realname = ucwords($realname); 
	 */
	public $realname = "";
	
	/**
	 * @var string Nombre completo de la persona (formado de la forma $apellido . " " . $realname)
	 */
	public $nombreCompleto = "";
	
	/**
	 * @var int Numero de person (id de la tabla) de la persona
	 * PERSON - NUMBER(8,0)
	 */
	public $person = "";
	
	/**
	 * Numero de documento de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	 * <Br>
	 * Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 1
	 */
	public $docNumero = "";
	
	/**
	 * Tipo de documento de la persona
	 * @var string   
	 * <Br>
	 * @ubicacionBase appgral.perdoc.typdoc - VARCHAR2(10 BYTE)
	 * <Br>
	 * Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 1
	 * <Br>
	 * El listado con los tipos de documentos y sus descripciones se encuetran en appgral.tdoc
	 */
	public $docTipo = "";

	/**
	 * Numero de cuil de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	 * <Br>
	 * Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 0
	 * y que el campo appgral.perdoc.typdoc = 'CUIL'
	 */
	public $cuil = "";
	
	/**
	 * Direccion de mail de la persona
	 * @var string 
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'E-MAIL'
	 */
	public $emailPersonal = "";

	/**
	 * Direccion de mail de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'E-MAILP'
	 */
	public $emailProfecional = "";
	
	/**
	 * Numero de telefono de la persona
	 * @var int 
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE) 
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMERO'
	 */
	public $personTelPersonal = "";

	/**
	 * Numero de telefono de la persona 
	 * @var int 
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMERO2'
	 */
	public $personTelPersonal2 = "";

	/**
	 * Numero de telefono de la persona
	 * @var int  
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMEROCEL'
	 */
	public $personTelCelular = "";

	/**
	 * Numero de telefono de la persona
	 * @var int  
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMEROP'
	 */
	public $personTelProfecional = "";
	
	/**
	 * Guarda un listado con las categorias a las que pertenece la persona
	 * @var array   
	 * <Br>
	 * @ubicacionBase appgral.catxperson.categoria - NUMBER(2,0)
	 * <Br> 
	 * <Br>  
	 * 		0 Academico <Br> 
	 * 		1 Administrativo <Br> 
	 * 		2 Docente <Br> 		
	 * 		3 Alumno <Br> 
	 * 		4 Proveedor / Externo? <Br> 
	 */
	public $categoria = "";
	
	/**
	 * Direccion donde se encuentra la foto de la persona dentro de la carpeta de fotos
	 *      se arma de la siguiente manera
	 *      
	 * @version 2 Se modifico para que se usara el person en vez del doc asi no se generan conflictos
	 * 		cuando hay cambios en los mismos.
	 * @var string 
	 *
	 *      <code>
	 *      <?php
	 *      substr($person, - 1)."/".substr($person, - 2, 1)."/".substr($person, - 3, 1)."/".$person.".jpg";
	 *      ?>
	 *      </code>
	 *
	 * @example para el Person 112469 quedaria:
	 * 		9/6/4/112469.jpg
	 */
	public $foto_persona = "";
	
	/**
	 * Fecha de nacimiento de la persona
	 * @var date  
	 * <Br>
	 * @ubicacionBase appgral.person.birdate - DATE
	 */
	public $birdate = "";
	
	/**
	 * Estado civil de la persona
	 * @var mix  
	 * <Br>
	 * @ubicacionBase appgral.person.marstat - NUMBER(1,0)
	 * <Br> 
	 * <Br>
	 * 		0 = Soltero/a <Br> 
	 * 		1 = Casado/a <Br> 
	 * 		2 = Viudo/a <Br> 
	 * 		3 = Separado/a <Br> 
	 * 		4 = Divorciado/da <Br> 
	 * 		5 = Union de hecho 
	 */
	public $marstat = "";
	
	/**
	 * Nacionalidad de la persona
	 * @var varchar 
	 * <Br>
	 * @ubicacionBase appgral.person.nation - VARCHAR2(3 BYTE)
	 * <Br>
	 * La tabla de referencia al dato es appgral.country.  
	 * Mas espesificamente appgral.country.nation
	 */
	public $nation = "";
	
	/**
	 * Tipo de nacionalizacion de la persona
	 * @var int  
	 * <Br>
	 * @ubicacionBase appgral.person.tnation - NUMBER(1,0)
	 * <Br> 
	 * <Br>
	 * 		0 = Argentino <Br> 
	 * 		1 = Extrangero <Br> 
	 * 		2 = Naturalizado <Br> 
	 * 		3 = Por Opcion 
	 */
	public $tnation = "";
	
	/**
	 * Sexo de la persona
	 * @var bit  
	 * <Br>
	 * @ubicacionBase appgral.person.sex - NUMBER(1,0)
	 * <Br>
	 * <Br>
	 * 		0 = Mujer <Br> 
	 * 		1 = Hombre
	 */
	public $sexo = "";
	

	/*
	 ********************************************************************************
	 * VARIABLES REFERENTES A LA CUENTA DE LA PERSONA                               *
	 ********************************************************************************
	 */

	/**
	 * Cuenta de la persona (nombre de usuario) <Br>
	 * Hay que tener en cuenta que un usuario puede tener mas de una cuenta por 
	 * lo que convendria que este campo sea un array para permitir guardar todas alli.
	 * 
	 * @var string 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.cuenta - VARCHAR2(120 BYTE)
	 * <Br>
	 * 
	 * @internal Este campo deberia estar siempre en minuscula.
	 * 		$cuenta = strtolower($cuenta);
	 */
	public $cuenta = "";
	
	/**
	 * Id de la Cuenta de la persona. 
	 * @var int 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.id - NUMBER(10,0)
	 */
	public $idCuenta = "";
	
	/**
	 * Direccion de mail relacionada a la cuenta de la persona.
	 * @var string  
	 * <Br>
	 * @ubicacionBase portal.usuario_web.email - VARCHAR2(200 BYTE)
	 */
	public $emailCuenta = "";
	
	/**
	 * Especifica si la persona es un academico o no. 
	 * @var bool 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.academico - NUMBER(1,0)
	 */
	public $cuentaAcademica = "";
	
	/**
	 * Especifica si la persona es un administrativo o no.
	 * @var bool 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.administrativo - NUMBER(1,0)
	 */
	public $cuentaAdministrativa = "";
	
	/**
	 * Especifica si la persona es un alumno o no. 
	 * @var bool 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.alumno - NUMBER(1,0)
	 */
	public $cuentaAlumno = "";
	
	/**
	 * Especifica si la persona es un docente o no. 
	 * @var bool 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.docente - NUMBER(1,0)
	 */
	public $cuentaDocente = "";
	
	/**
	 * Especifica si la persona es un externo o no.
	 * @var bool  
	 * <Br>
	 * @ubicacionBase portal.usuario_web.externo - NUMBER(1,0)
	 */
	public $cuentaExterno = "";
	
	/**
	 * Especifica si la cuenta asociada a la persona es generica o no. 
	 * @var bool 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.generico - NUMBER(1,0)
	 */
	public $cuentaGenerica = "";
	
	/**
	 * Especifica si la cuenta asociada a la persona es del tipo operador o no. 
	 * @var bool 
	 * <Br>
	 * @ubicacionBase portal.usuario_web.operador - NUMBER(1,0)
	 */
	public $cuentaOperador = "";
	
	/**
	 * Frace de seguridad de la cuenta de la persona utilizada 
	 * 		para la recuperacion de contraseñas.
	 * @var string  
	 * <Br>
	 * @ubicacionBase portal.usuario_web.frase - VARCHAR2(250 BYTE)
	 */
	public $fraseDeSeguridad = "";
	
	/**
	 * Fecha de vencimiento de la cuenta de la persona
	 * @var date  
	 * <Br>
	 * @ubicacionBase portal.usuario_web.fecha_venc - DATE
	 */
	public $vtoDeLaCuenta = "";
	
	/**
	 * Fecha de alta de la cuenta de la persona
	 * @var date  
	 * <Br>
	 * @ubicacionBase portal.usuario_web.fecha_alta - DATE
	 */
	public $altaDeLaCuenta = "";
	
	/**
	 * Fecha de baja de la cuenta de la persona
	 * @var date  
	 * <Br>
	 * @ubicacionBase portal.usuario_web.fecha_baja - DATE
	 */
	public $bajaDeLaCuenta = "";
	
	/*
	 ********************************************************************************
	 * VARIABLES REFERENTES A LA DIRECCION DE LA PERSONA                            *
	 ********************************************************************************
	 */
	/**
	 * Pais de nacimiento de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.person.country - VARCHAR2(3 BYTE)
	 * <Br>
	 * <Br>
	 * La tabla de referencia al dato es appgral.country
	 */
	public $country = "";
	
	/**
	 * Provincia de nacimiento de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.person.poldiv - VARCHAR2(3 BYTE)
	 * <Br>
	 * <Br>
	 * La tabla de referencia al dato es appgral.poldiv
	 */
	public $poldiv = "";
	
	/**
	 * Ciudad de nacimiento de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.person.city - VARCHAR2(10 BYTE)
	 * <Br>
	 * <Br>
	 * La tabla de referencia al dato es appgral.city
	 */
	public $city = "";

	/**
	 * Pais de residencia de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.person.rcountry - VARCHAR2(3 BYTE)
	 * <Br>
	 * <Br>
	 * La tabla de referencia al dato es appgral.country
	 */
	public $rcountry = "";
	
	/**
	 * Provincia de residencia de la persona.
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.person.rpoldiv - VARCHAR2(3 BYTE)
	 * <Br>
	 * <Br>
	 * La tabla de referencia al dato es appgral.poldiv
	 */
	public $rpoldiv = "";
	
	/**
	 * Ciudad de residencia de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.person.rcity - VARCHAR2(10 BYTE)
	 * <Br>
	 * <Br>
	 * La tabla de referencia al dato es appgral.city
	 */
	public $rcity = "";
		
	/**
	 * Calle de la direccion de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CALLE'
	 */
	public $direCalle = "";
	
	/**
	 * Numero de la direccion de la persona
	 * @var string 
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'NRO'
	 */
	public $direNumero = "";
	
	/**
	 * Piso de la direccion de la persona
	 * @var string  
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'PISO'
	 */
	public $direPiso = "";
	
	/**
	 * Dto de la direccion de la persona 
	 * @var string 
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'DEPTO'
	 */
	public $direDto = "";
	
	/**
	 * Codigo postal de la direccion de la persona
	 * @var string 
	 * <Br>
	 * @ubicacionBase appgral.apers.val  - VARCHAR2(100 BYTE)
	 * <Br>
	 * Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CODPOS'
	 */
	public $direCodPos = "";
	

	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES AL AREA DE PERSONAL                                     *
	 * ******************************************************************************
	 */
	
	/**
	 * Numero de legajo del area de personal de la persona
	 * @var int 
	 * @ubicacionBase appgral.catxperson.legajo - NUMBER(8,0)
	 */
	public $legajo = "";

	/**	
	 * Fecha en la que ingresa la persona
	 * @var int
	 * @ubicacionBase appgral.catxperson.finicio - DATE
	 */
	public $fIngreso = "";

	/**
	 * Fecha de baja de la persona
	 * @var int
	 * @ubicacionBase appgral.catxperson.fbaja - DATE
	 */
	public $fbaja = "";
	
	
	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES AL FUNCIONAMIENTO DEL SISTEMA                           *
	 * ******************************************************************************
	 *
	 *
	ACTIVE	NUMBER(1,0)
	INCOUNTRYSINCE	DATE
	RELIGION	NUMBER(2,0)
	QBROTHER	NUMBER(2,0)
	QSON	NUMBER(2,0)
	*/		
	
}

?>