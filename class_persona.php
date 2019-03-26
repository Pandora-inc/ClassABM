<?php

/**
 * Archivo principar de la clase.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 * @todo FechaC 19/2/2016 - Lenguaje PHP
 *
 * @name class_persona.php
 *
 */

/**
 * Clase encargada del manejo de todos los datos referentes a la persona.
 *
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @name class_persona
 *
 * @version 0.1 - Version de inicio
 *
 * @package Classes_USAL
 *
 * @category General
 *
 * @todo El usuario que se conecta a la base debe tener los siguientes permisos -
 *       - SELECT :
 *       portal.usuario_web | appgral.apers | appgral.person | appgral.perdoc | appgral.personca | interfaz.estadocredenca | appgral.lnumber
 *       - UPDATE :
 *       appgral.apers | appgral.lnumber | appgral.perdoc
 *       - INSERT :
 *       appgral.apers | appgral.perdoc |
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
class class_persona
{

	/**
	 * Muestra por pantalla diferentes codigos para facilitar el debug
	 *
	 * @var bool
	 */
	public $debug = false;

	/**
	 * Graba log con los errores *
	 */
	public $grabarArchivoLogError = false;

	/**
	 * Graba log con todas las consultas realizadas *
	 */
	public $grabarArchivoLogQuery = false;

	/**
	 * Imprime cuando hay errores *
	 */
	public $mostrarErrores = true;

	/**
	 * Usar exit() en caso de detectar un error .
	 *
	 * Esto es util para etapa de desarrollo *
	 */
	public $dieOnError = false;

	/**
	 * Setear un email para enviar email cuando hay errores sql *
	 */
	public $emailAvisoErrorSql;

	/**
	 *
	 * @var string - Se le pasara en caso de necesitar algun link para acceder
	 */
	public $db_link = "";

	/**
	 * Apellido de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.lname - VARCHAR2(50 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 * @internal Este dato siempre tiene que estar en mayuscula.
	 *           $apellido = strtoupper($apellido);
	 */
	public $apellido = "";

	/**
	 * Nombre de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.fname - VARCHAR2(50 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 * @internal Este campo deberia tener la primer letra en mayuscula y el resto siempre en minuscula
	 *           $realname = ucwords($realname);
	 */
	public $realname = "";

	/**
	 *
	 * @var string Nombre completo de la persona (formado de la forma $apellido . " " . $realname)
	 */
	public $nombreCompleto = "";

	/**
	 *
	 * @var int Numero de person (id de la tabla) de la persona
	 *      PERSON - NUMBER(8,0)
	 */
	public $person = "";

	/**
	 * Numero de documento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 *       <Br>
	 *       Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 1
	 */
	public $docNumero = "";

	/**
	 * Tipo de documento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.perdoc.typdoc - VARCHAR2(10 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 *       <Br>
	 *       Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 1
	 *       <Br>
	 *       El listado con los tipos de documentos y sus descripciones se encuetran en appgral.tdoc
	 */
	public $docTipo = "";

	/**
	 * Numero de cuil de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	 *      <Br>
	 *      Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 0
	 *      y que el campo appgral.perdoc.typdoc = 'CUIL'
	 */
	public $cuil = "";

	/**
	 * Direccion de mail de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'E-MAIL'
	 */
	public $emailPersonal = "";

	/**
	 * Direccion de mail de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'E-MAILP'
	 */
	public $emailProfecional = "";

	/**
	 * Numero de telefono de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMERO'
	 */
	public $personTelPersonal = "";

	/**
	 * Numero de telefono de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMERO2'
	 */
	public $personTelPersonal2 = "";

	/**
	 * Numero de telefono de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMEROCEL'
	 */
	public $personTelCelular = "";

	/**
	 * Numero de telefono de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'TELE' y appgral.apers.shortdes = 'NUMEROP'
	 */
	public $personTelProfecional = "";

	/**
	 * Guarda un listado con las categorias a las que pertenece la persona
	 *
	 * @var array <Br>
	 *      @ubicacionBase appgral.catxperson.categoria - NUMBER(2,0)
	 *      <Br>
	 *      <Br>
	 *      0 Academico <Br>
	 *      1 Administrativo <Br>
	 *      2 Docente <Br>
	 *      3 Alumno <Br>
	 *      4 Proveedor / Externo? <Br>
	 */
	public $categoria = "";

	/**
	 * Direccion donde se encuentra la foto de la persona dentro de la carpeta de fotos
	 * se arma de la siguiente manera
	 *
	 * @version 2 Se modifico para que se usara el person en vez del doc asi no se generan conflictos
	 *          cuando hay cambios en los mismos.
	 * @var string <code>
	 *      <?php
	 *      substr($person, - 1)."/".substr($person, - 2, 1)."/".substr($person, - 3, 1)."/".$person.".jpg";
	 *      ?>
	 *      </code>
	 *
	 * @example para el Person 112469 quedaria:
	 *          9/6/4/112469.jpg
	 */
	public $foto_persona = "";

	/**
	 * Fecha de nacimiento de la persona
	 *
	 * @var string - Date <Br>
	 *      @ubicacionBase appgral.person.birdate - DATE
	 *
	 * @todo el formato correcto para pasar este dato deberia ser 'RRRR-MM-DD' o su equivalente Año-mes-dia.
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $birdate = "";

	/**
	 * Estado civil de la persona
	 *
	 * @var mixed <Br>
	 *      @ubicacionBase appgral.person.marstat - NUMBER(1,0)
	 *      <Br>
	 *      <Br>
	 *      0 = Soltero/a <Br>
	 *      1 = Casado/a <Br>
	 *      2 = Viudo/a <Br>
	 *      3 = Separado/a <Br>
	 *      4 = Divorciado/da <Br>
	 *      5 = Union de hecho
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 */
	public $marstat = "";

	/**
	 * Nacionalidad de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.nation - VARCHAR2(3 BYTE)
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 *       <Br>
	 *       La tabla de referencia al dato es appgral.country.
	 *       Mas espesificamente appgral.country.nation
	 */
	public $nation = "";

	/**
	 * Tipo de nacionalizacion de la persona
	 *
	 * @var int <Br>
	 *      @ubicacionBase appgral.person.tnation - NUMBER(1,0)
	 *      <Br>
	 *      <Br>
	 *      0 = Argentino <Br>
	 *      1 = Extrangero <Br>
	 *      2 = Naturalizado <Br>
	 *      3 = Por Opcion
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 *
	 */
	public $tnation = "";

	/**
	 * Sexo de la persona
	 *
	 * @var boolean <Br>
	 *      @ubicacionBase appgral.person.sex - NUMBER(1,0)
	 *      <Br>
	 *      <Br>
	 *      0 = Mujer <Br>
	 *      1 = Hombre
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $sexo = "";

	/*
	 * *******************************************************************************
	 * VARIABLES REFERENTES A LA CUENTA DE LA PERSONA *
	 * *******************************************************************************
	 */

	/**
	 * Cuenta de la persona (nombre de usuario) <Br>
	 * Hay que tener en cuenta que un usuario puede tener mas de una cuenta por
	 * lo que convendria que este campo sea un array para permitir guardar todas alli.
	 *
	 * @var string <Br>
	 *      @ubicacionBase portal.usuario_web.cuenta - VARCHAR2(120 BYTE)
	 *      <Br>
	 *
	 * @internal Este campo deberia estar siempre en minuscula.
	 *           $cuenta = strtolower($cuenta);
	 */
	public $cuenta = "";

	/**
	 * Id de la Cuenta de la persona.
	 *
	 * @var int <Br>
	 *      @ubicacionBase portal.usuario_web.id - NUMBER(10,0)
	 */
	public $idCuenta = "";

	/**
	 * Direccion de mail relacionada a la cuenta de la persona.
	 *
	 * @var string <Br>
	 *      @ubicacionBase portal.usuario_web.email - VARCHAR2(200 BYTE)
	 */
	public $emailCuenta = "";

	/**
	 * Especifica si la persona es un academico o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.academico - NUMBER(1,0)
	 */
	public $cuentaAcademica = "";

	/**
	 * Especifica si la persona es un administrativo o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.administrativo - NUMBER(1,0)
	 */
	public $cuentaAdministrativa = "";

	/**
	 * Especifica si la persona es un alumno o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.alumno - NUMBER(1,0)
	 */
	public $cuentaAlumno = "";

	/**
	 * Especifica si la persona es un docente o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.docente - NUMBER(1,0)
	 */
	public $cuentaDocente = "";

	/**
	 * Especifica si la persona es un externo o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.externo - NUMBER(1,0)
	 */
	public $cuentaExterno = "";

	/**
	 * Especifica si la cuenta asociada a la persona es generica o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.generico - NUMBER(1,0)
	 */
	public $cuentaGenerica = "";

	/**
	 * Especifica si la cuenta asociada a la persona es del tipo operador o no.
	 *
	 * @var bool <Br>
	 *      @ubicacionBase portal.usuario_web.operador - NUMBER(1,0)
	 */
	public $cuentaOperador = "";

	/**
	 * Frace de seguridad de la cuenta de la persona utilizada
	 * para la recuperacion de contraseñas.
	 *
	 * @var string <Br>
	 *      @ubicacionBase portal.usuario_web.frase - VARCHAR2(250 BYTE)
	 */
	public $fraseDeSeguridad = "";

	/**
	 * Fecha de vencimiento de la cuenta de la persona
	 *
	 * @var string - Date<Br>
	 *      @ubicacionBase portal.usuario_web.fecha_venc - DATE
	 */
	public $vtoDeLaCuenta = "";

	/**
	 * Fecha de alta de la cuenta de la persona
	 *
	 * @var string - Date <Br>
	 *      @ubicacionBase portal.usuario_web.fecha_alta - DATE
	 */
	public $altaDeLaCuenta = "";

	/**
	 * Fecha de baja de la cuenta de la persona
	 *
	 * @var string - Date <Br>
	 *      @ubicacionBase portal.usuario_web.fecha_baja - DATE
	 */
	public $bajaDeLaCuenta = "";

	/*
	 * *******************************************************************************
	 * VARIABLES REFERENTES A LA DIRECCION DE LA PERSONA *
	 * *******************************************************************************
	 */
	/**
	 * Pais de nacimiento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.country - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.country
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $country = "";

	/**
	 * Provincia de nacimiento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.poldiv - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.poldiv
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $poldiv = "";

	/**
	 * Ciudad de nacimiento de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.city - VARCHAR2(10 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.city
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $city = "";

	/**
	 * Pais de residencia de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.rcountry - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.country
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $rcountry = "";

	/**
	 * Provincia de residencia de la persona.
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.rpoldiv - VARCHAR2(3 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.poldiv
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $rpoldiv = "";

	/**
	 * Ciudad de residencia de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.person.rcity - VARCHAR2(10 BYTE)
	 *      <Br>
	 *      <Br>
	 *      La tabla de referencia al dato es appgral.city
	 *
	 * @todo Este campo es obligatorio a la hora de crear personas.
	 */
	public $rcity = "";

	/**
	 * Calle de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CALLE'
	 */
	public $direCalle = "";

	/**
	 * Numero de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'NRO'
	 */
	public $direNumero = "";

	/**
	 * Piso de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'PISO'
	 */
	public $direPiso = "";

	/**
	 * Dto de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'DEPTO'
	 */
	public $direDto = "";

	/**
	 * Codigo postal de la direccion de la persona
	 *
	 * @var string <Br>
	 *      @ubicacionBase appgral.apers.val - VARCHAR2(100 BYTE)
	 *      <Br>
	 *      Siempre que: appgral.apers.pattrib = 'DOMI' y appgral.apers.shortdes = 'CODPOS'
	 */
	public $direCodPos = "";

	/**
	 * Direccion en string que une calle numero y muchas veces depto en un solo campo.
	 *
	 * @todo lo ideal es tratarlo para que quede separado de la forma que corresponde
	 *
	 * @var string <Br>
	 *      @ubicacionBase sueldos.personal.domicilio - VARCHAR2(30 BYTE)
	 */
	public $domicilio = "";

	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES AL AREA DE PERSONAL *
	 * ******************************************************************************
	 */

	/**
	 * Numero de legajo del area de personal de la persona
	 *
	 * @var int @ubicacionBase appgral.catxperson.legajo - NUMBER(8,0)
	 */
	public $legajo = "";

	/**
	 * Fecha en la que ingresa la persona
	 *
	 * @var int @ubicacionBase appgral.catxperson.finicio - DATE
	 */
	public $fIngreso = "";

	/**
	 * Fecha de baja de la persona
	 *
	 * @var int @ubicacionBase appgral.catxperson.fbaja - DATE
	 */
	public $fbaja = "";

	/**
	 * Numero de cuit de la persona
	 *
	 * @var string <Br>
	 * @todo @ubicacionBase appgral.perdoc.docno - VARCHAR2(30 BYTE)
	 *       <Br>
	 *       Hay que tener en cuenta que el campo appgral.perdoc.isKey debe se igual a 0
	 *       y que el campo appgral.perdoc.typdoc = 'CUIT'
	 */
	public $cuit = "";

	/**
	 * Fecha de reingreso
	 *
	 * @var string
	 */
	public $reingreso = "";

	/**
	 * Fecha de inicio en el cargo
	 *
	 * @var string
	 */
	public $inicioCargo = "";

	/**
	 * Actividad de la persona
	 *
	 * @var string
	 */
	public $actividad = "";

	/**
	 * Antiguedad de la persona.
	 *
	 * @var string
	 */
	public $antiguedad = "";

	/**
	 * caja de ahorro
	 *
	 * @var string
	 */
	public $cajaDeAhorro = "";

	/**
	 * Caja jubilatoria
	 *
	 * @var string
	 */
	public $cajaJubilacion = "";

	/**
	 * Cargo
	 *
	 * @var string
	 */
	public $cargo = "";

	/**
	 * Codigo de alta
	 *
	 * @var string
	 */
	public $codigoAlta = "";

	/**
	 * titulo
	 *
	 * @var string
	 */
	public $titulo = "";

	/**
	 * Codigo del titulo
	 *
	 * @var string
	 */
	public $codigoTitulo = "";
	/**
	 * $nroJubilacion.
	 *
	 * @var string
	 */
	public $nroJubilacion = "";
	/**
	 * $nroSindicato.
	 *
	 * @var string
	 */
	public $nroSindicato = "";
	/**
	 * $obraSocial.
	 *
	 * @var string
	 */
	public $obraSocial = "";
	/**
	 * $redito.
	 *
	 * @var string
	 */
	public $redito = "";
	/**
	 * $seguro
	 *
	 * @var string
	 */
	public $seguro = "";
	/**
	 * $sucursalCtaBanco
	 *
	 * @var string
	 */
	public $sucursalCtaBanco = "";
	/**
	 * $tipoCtaBanco
	 *
	 * @var string
	 */
	public $tipoCtaBanco = "";
	/**
	 * $unidadContrato
	 *
	 * @var string
	 */
	public $unidadContrato = "";
	/**
	 * $tipobco
	 *
	 * @var string
	 */
	public $tipobco = "";

	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES A LA CARGA FAMILIAR *
	 * ******************************************************************************
	 */
	/**
	 * $esposa
	 *
	 * @var string
	 */
	public $esposa = "";
	/**
	 * $familiarACargo
	 *
	 * @var string
	 */
	public $familiarACargo = "";
	/**
	 * $FamiliaNumerosa
	 *
	 * @var string
	 */
	public $FamiliaNumerosa = "";
	/**
	 * $hijos
	 *
	 * @var string
	 */
	public $hijos = "";
	/**
	 * $guarderia
	 *
	 * @var string
	 */
	public $guarderia = "";
	/**
	 * $hijoIncapasitado
	 *
	 * @var string
	 */
	public $hijoIncapasitado = "";
	/**
	 * $prenatal
	 *
	 * @var string
	 */
	public $prenatal = "";
	/**
	 * $preescolar
	 *
	 * @var string
	 */
	public $preescolar = "";
	/**
	 * $escuelaMedia
	 *
	 * @var string
	 */
	public $escuelaMedia = "";
	/**
	 * $escuelaPrimaria
	 *
	 * @var string
	 */
	public $escuelaPrimaria = "";

	/*
	 * ******************************************************************************
	 * VARIABLES REFERENTES AL FUNCIONAMIENTO DEL SISTEMA *
	 * ******************************************************************************
	 *
	 *
	 * ACTIVE NUMBER(1,0)
	 * INCOUNTRYSINCE DATE
	 * RELIGION NUMBER(2,0)
	 * QBROTHER NUMBER(2,0)
	 * QSON NUMBER(2,0)
	 */

	/*
	 * ************************************************************************
	 * Aca empiezan las funciones de la clase
	 * ************************************************************************
	 */
	/**
	 * Realiza un listado con todos los persons que cumplan una determinada condicion.
	 *
	 * @name listarPerson
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param mixed[] $datos
	 *        	- Datos extra con los que realizar la busqueda, puede ser cualquier dato de la tabla, con el indice igual al nombre del campo en minuscula.
	 * @return int[] - array con todos los persons resultantes
	 */
	public function listarPerson($db, $datos = "")
	{
		try
		{
			if (isset ($datos['person']) and $datos['person'] != "")
			{
				$where[] = " person = :person ";
				$parametros[] = $datos['person'];
			}

			if (isset ($datos['lname']) and $datos['lname'] != "")
			{
				$where[] = " lname = :lname ";
				$parametros[] = $datos['lname'];
			}
			if (isset ($datos['fname']) and $datos['fname'] != "")
			{
				$where[] = " fname = :fname ";
				$parametros[] = $datos['fname'];
			}
			if (isset ($datos['country']) and $datos['country'] != "")
			{
				$where[] = " country = :country ";
				$parametros[] = $datos['country'];
			}
			if (isset ($datos['poldiv']) and $datos['poldiv'] != "")
			{
				$where[] = " poldiv = :poldiv ";
				$parametros[] = $datos['poldiv'];
			}
			if (isset ($datos['city']) and $datos['city'] != "")
			{
				$where[] = " city = :city ";
				$parametros[] = $datos['city'];
			}
			if (isset ($datos['birdate']) and $datos['birdate'] != "")
			{
				$where[] = " birdate = :birdate ";
				$parametros[] = $datos['birdate'];
			}
			if (isset ($datos['nation']) and $datos['nation'] != "")
			{
				$where[] = " nation = :nation ";
				$parametros[] = $datos['nation'];
			}
			if (isset ($datos['sex']) and $datos['sex'] != "")
			{
				$where[] = " sex = :sex ";
				$parametros[] = $datos['sex'];
			}
			if (isset ($datos['marstat']) and $datos['marstat'] != "")
			{
				$where[] = " marstat = :marstat ";
				$parametros[] = $datos['marstat'];
			}
			if (isset ($datos['address']) and $datos['address'] != "")
			{
				$where[] = " address = :address ";
				$parametros[] = $datos['address'];
			}
			if (isset ($datos['rcountry']) and $datos['rcountry'] != "")
			{
				$where[] = " rcountry = :rcountry ";
				$parametros[] = $datos['rcountry'];
			}
			if (isset ($datos['rpoldiv']) and $datos['rpoldiv'] != "")
			{
				$where[] = " rpoldiv = :rpoldiv ";
				$parametros[] = $datos['rpoldiv'];
			}
			if (isset ($datos['rcity']) and $datos['rcity'] != "")
			{
				$where[] = " rcity = :rcity ";
				$parametros[] = $datos['rcity'];
			}
			if (isset ($datos['telep']) and $datos['telep'] != "")
			{
				$where[] = " telep = :telep ";
				$parametros[] = $datos['telep'];
			}
			if (isset ($datos['active']) and $datos['active'] != "")
			{
				$where[] = " active = :active ";
				$parametros[] = $datos['active'];
			}
			if (isset ($datos['tnation']) and $datos['tnation'] != "")
			{
				$where[] = " tnation = :tnation ";
				$parametros[] = $datos['tnation'];
			}
			if (isset ($datos['incountrysince']) and $datos['incountrysince'] != "")
			{
				$where[] = " incountrysince = :incountrysince ";
				$parametros[] = $datos['incountrysince'];
			}
			if (isset ($datos['religion']) and $datos['religion'] != "")
			{
				$where[] = " religion = :religion ";
				$parametros[] = $datos['religion'];
			}
			if (isset ($datos['qbrother']) and $datos['qbrother'] != "")
			{
				$where[] = " qbrother = :qbrother ";
				$parametros[] = $datos['qbrother'];
			}
			if (isset ($datos['qson']) and $datos['qson'] != "")
			{
				$where[] = " qson = :qson ";
				$parametros[] = $datos['qson'];
			}

			if ($where != "")
			{
				$where = implode (" AND ", $where);

				$where = " AND " . $where;
			}

			$sql = "SELECT person FROM appgral.person" . $this->db_link . " WHERE 1 = 1 " . $where;

			$result = $db->query ($sql, $esParam = true, $parametros);

			$rst = $db->fetch_all ($result);

			return $rst;

			// if (1 == 1)
			// {
			// }
			// else
			// {
			// throw new Exception ('ERROR: No se pudo realizar la insercion en sueldos.valorremu.');
			// }
		}
		catch (Exception $e)
		{
			if ($db->debug == true)
			{
				return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
			}
			else

			{
				return $e->getMessage ();
			}

			if ($db->dieOnError == true)
			{
				exit ();
			}
		}
	}

	/**
	 * Realiza un listado con todos los persons que cumplan una determinada condicion de carrera o facultad.
	 *
	 * @name listarPersonFacu
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param mixed[] $datos
	 *        	- Datos extra con los que realizar la busqueda, puede ser cualquier dato de la tabla, con el indice igual al nombre del campo en minuscula.
	 * @return int[] - array con todos los persons resultantes
	 */
	public function listarPersonFacu($db, $datos = "")
	{
		try
		{
			if (isset ($datos['facu']) and $datos['facu'] != "")
			{
				$where[] = " career.facu = :facu ";
				$parametros[] = $datos['facu'];
			}
			if (isset ($datos['career']) and $datos['career'] != "")
			{
				$where[] = " carstu.career = :career ";
				$parametros[] = $datos['career'];
			}
			if (isset ($datos['inscdate']) and $datos['inscdate'] != "")
			{
				$where[] = " to_char(carstu.inscdate, 'YYYY') = :inscdate ";
				$parametros[] = $datos['inscdate'];
			}

			if ($where != "")
			{
				$where = implode (" AND ", $where);

				$where = " AND " . $where;
			}

			$sql = " SELECT carstu.student person FROM studentc.carstu" . $this->db_link . " LEFT JOIN studentc.career ON carstu.career=career.code LEFT JOIN studentc.facu ON career.facu=facu.code WHERE 1 = 1 " . $where;

			$result = $db->query ($sql, $esParam = true, $parametros);

			$rst = $db->fetch_all ($result);

			return $rst;
		}
		catch (Exception $e)
		{
			if ($db->debug == true)
			{
				return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
			}
			else

			{
				return $e->getMessage ();
			}

			if ($db->dieOnError == true)
			{
				exit ();
			}
		}
	}

	/**
	 * Devuelve un array con los valores de apers usando los SHORTDES como claves.
	 *
	 * @name buscarAppers
	 * @param mixed[] $datosAUsar
	 *        	- Requiere que dentro de los datos enviados este si o si el person de la persona
	 *
	 * @return Array
	 */
	public function buscarAppers($datosAUsar)
	{
		global $db;
		try
		{

			if ($datosAUsar['person'] != "")
			{
				$person = $datosAUsar['person'];

				$sql = "SELECT * FROM appgral.apers" . $this->db_link . " WHERE person = :person";

				$parametros[0] = $person;

				$result = $db->query ($sql, $esParam = true, $parametros);

				while ($recu = $db->fetch_array ($result))
				{
					$persona[$recu['SHORTDES']] = $recu['VAL'];
				}
			}
			else
			{
				throw new Exception ('ERROR: El person es obligatorio.');
			}
			if ($persona != "")
			{
				return $persona;
			}
		}
		catch (Exception $e)
		{
			if ($db->debug == true)
			{
				return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
			}
			else
			{
				return $e->getMessage ();
			}

			if ($db->dieOnError == true)
			{
				exit ();
			}
		}
	}

	/**
	 * Devuelve los datos de la tabla person, la busqueda puede realizarse por nro de person, por nombre, apellido o nombre y apellido
	 * En caso de que no recupere ningun dato devuelve 0
	 *
	 * @name buscarPerson
	 *
	 * @param string[] $datosAUsar
	 *        	- 'person' o 'realname'
	 *
	 * @return array - 'person' 'lname' 'fname' 'country' 'poldiv' 'city' 'birdate' 'nation' 'sex' 'marstat' 'address' 'rcountry' 'rpoldiv' 'rcity' 'telep' 'active' 'tnation' 'incountrysince' 'religion' 'qbrother' 'qson'
	 */
	public function buscarPerson($datosAUsar)
	{
		global $db;

		// Comprovamos si pasaron el person para realizar la busqueda
		if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
		{
			$person = $datosAUsar['person'];

			$sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $person;

			$result = $db->query ($sql, $esParam = true, $parametros);
		}
		// En caso de que hayan mandado el nombre o el apellido
		else if ((isset ($datosAUsar['realname']) and $datosAUsar['realname'] != "") or (isset ($datosAUsar['apellido']) and $datosAUsar['apellido'] != "") or (isset ($datosAUsar['nombreCompleto']) and $datosAUsar['nombreCompleto'] != ""))
		{
			if ((isset ($datosAUsar['realname'])) and ($datosAUsar['realname'] != ""))
			{
				$realname = $datosAUsar['realname'];
			}
			else
			{
				$realname = "";
			}

			if ((isset ($datosAUsar['apellido'])) and ($datosAUsar['apellido'] != ""))
			{
				$apellido = $datosAUsar['apellido'];
			}
			else
			{
				$apellido = "";
			}

			if ((isset ($datosAUsar['nombreCompleto'])) and ($datosAUsar['nombreCompleto'] != ""))
			{
				$nombreCompleto = $datosAUsar['nombreCompleto'];
			}
			else
			{
				$nombreCompleto = "";
			}

			if ($nombreCompleto == "")
			{
				$nombreCompleto = $realname . " " . $apellido;
			}

			$nombreCompleto = strtoupper ($nombreCompleto);
			$nombreCompleto = htmlentities ($nombreCompleto);
			$nombreCompleto = str_replace (" ", "%", $nombreCompleto);
			$nombreCompleto = "%" . $nombreCompleto . "%";

			$sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE (UPPER(lname||fname) LIKE UPPER(:nombreCompleto)) OR (UPPER(fname||lname) LIKE UPPER(:nombreCompleto))";

			// $sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $nombreCompleto;
			$parametros[1] = $nombreCompleto;

			$result = $db->query ($sql, $esParam = true, $parametros);
		}

		$i = 0;

		while ($recu = $db->fetch_array ($result))
		{

			$persona[$i]['person'] = $recu['PERSON'];
			$persona[$i]['lname'] = $recu['LNAME'];
			$persona[$i]['fname'] = $recu['FNAME'];
			$persona[$i]['country'] = $recu['COUNTRY'];
			$persona[$i]['poldiv'] = $recu['POLDIV'];
			$persona[$i]['city'] = $recu['CITY'];
			$persona[$i]['birdate'] = $recu['BIRDATE'];
			$persona[$i]['nation'] = $recu['NATION'];
			$persona[$i]['sex'] = $recu['SEX'];
			$persona[$i]['marstat'] = $recu['MARSTAT'];
			$persona[$i]['address'] = $recu['ADDRESS'];
			$persona[$i]['rcountry'] = $recu['RCOUNTRY'];
			$persona[$i]['rpoldiv'] = $recu['RPOLDIV'];
			$persona[$i]['rcity'] = $recu['RCITY'];
			$persona[$i]['telep'] = $recu['TELEP'];
			$persona[$i]['active'] = $recu['ACTIVE'];
			$persona[$i]['tnation'] = $recu['TNATION'];
			$persona[$i]['incountrysince'] = $recu['INCOUNTRYSINCE'];
			$persona[$i]['religion'] = $recu['RELIGION'];
			$persona[$i]['qbrother'] = $recu['QBROTHER'];
			$persona[$i]['qson'] = $recu['QSON'];

			$i = $i + 1;
		}

		if (isset ($persona) and $persona != "")
		{
			return $persona;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Recibe un array con el person o el numero de documento y devuelve otro con los datos de la tabla perdoc
	 *
	 * @name buscarPerdoc
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'docno' o 'cuil'
	 * @return mixed[] - con los siguientes parametros: person, typdoc, docNumero
	 */
	public function buscarPerdoc($datosAUsar)
	{
		global $db;
		try
		{
			if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
			{
				$person = $datosAUsar['person'];

				$sql = "SELECT * FROM appgral.perdoc" . $this->db_link . " WHERE person = :person";

				$parametros[0] = $person;

				$result = $db->query ($sql, $esParam = true, $parametros);

				if ($result == "")
				{
					$sql = "SELECT * FROM appgral.auditaperdoc" . $this->db_link . " WHERE person = :person";

					$parametros[0] = $person;

					$result = $db->query ($sql, $esParam = true, $parametros);
				}
			}
			else if (isset ($datosAUsar['docNumero']) and $datosAUsar['docNumero'] != "")
			{
				$docNumero = $datosAUsar['docNumero'];
				$docNumero = str_replace ('.', '', $docNumero);
				$docNumero = str_replace (' ', '', $docNumero);

				$sql = "SELECT * FROM appgral.perdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

				$parametros[0] = $docNumero;

				$result = $db->query ($sql, $esParam = true, $parametros);

				if ($result == "")
				{
					$sql = "SELECT * FROM appgral.auditaperdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

					$parametros[0] = $docNumero;

					$result = $db->query ($sql, $esParam = true, $parametros);
				}
			}
			else if (isset ($datosAUsar['cuil']) and $datosAUsar['cuil'] != "")
			{
				$docNumero = substr ($datosAUsar['cuil'], 2, -1);

				$docNumero = str_replace ('.', '', $docNumero);
				$docNumero = str_replace (' ', '', $docNumero);

				$sql = "SELECT * FROM appgral.perdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

				$parametros[0] = $docNumero;

				$result = $db->query ($sql, $esParam = true, $parametros);

				if ($result == "")
				{
					$sql = "SELECT * FROM appgral.auditaperdoc" . $this->db_link . " WHERE LTRIM(LTRIM(docno, '0')) = LTRIM(LTRIM(:docno, '0'))";

					$parametros[0] = $docNumero;

					$result = $db->query ($sql, $esParam = true, $parametros);
				}
			}
			else
			{
				throw new Exception ('O person o el numero de doc deben contener algun valor ! ' . ":" . $datosAUsar['person'] . ":D:" . $datosAUsar['docNumero'] . ":");
			}

			$i = 0;

			while ($recu = $db->fetch_array ($result))
			{
				$persona[$i]['person'] = $recu['PERSON'];
				$persona[$i]['typdoc'] = $recu['TYPDOC'];
				$persona[$i]['docNumero'] = $recu['DOCNO'];

				$i = $i + 1;
			}

			if (isset ($persona) and $persona != "")
			{
				$resultado = $persona;
			}
			else
			{
				return 0;
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Devuelve todos los datos relacionados a la tarjeta de la persona.
	 *
	 * @name buscarTargeta
	 *
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'num_tarj'
	 * @return mixed[] - 'person' 'estadocredencialca' 'email' 'codigoisic' 'nrodechip' 'sca_fecha' 'sca_categoria' 'sca_lote' 'tipo_formulario' 'nrodechip_dec' 'fecha_chip' 'motivo' 'tipo_credencial'
	 */
	public function buscarTargeta($datosAUsar)
	{
		global $db;

		if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
		{
			$person = $datosAUsar['person'];

			$sql = "SELECT * FROM appgral.personca" . $this->db_link . " WHERE person = :person";

			$parametros[0] = $person;

			$result = $db->query ($sql, $esParam = true, $parametros);
		}
		else if (isset ($datosAUsar['num_tarj']) and $datosAUsar['num_tarj'] != "")
		{
			$num_tarj = $datosAUsar['num_tarj'];

			$sql = "SELECT * FROM appgral.personca" . $this->db_link . " WHERE (nrodechip LIKE :num_tarj) OR (nrodechip_dec LIKE :num_tarj)";

			$parametros[0] = $num_tarj;
			$parametros[1] = $num_tarj;

			$result = $db->query ($sql, $esParam = true, $parametros);
		}

		$i = 0;

		while ($recu = $db->fetch_array ($result))
		{
			$personca[$i]['person'] = $recu['PERSON'];
			$personca[$i]['estadocredencialca'] = $recu['ESTADOCREDENCIALCA'];
			$personca[$i]['email'] = $recu['EMAIL'];
			$personca[$i]['codigoisic'] = $recu['CODIGOISIC'];
			$personca[$i]['nrodechip'] = $recu['NRODECHIP'];
			$personca[$i]['sca_fecha'] = $recu['SCA_FECHA'];
			$personca[$i]['sca_categoria'] = $recu['SCA_CATEGORIA'];
			$personca[$i]['sca_lote'] = $recu['SCA_LOTE'];
			$personca[$i]['tipo_formulario'] = $recu['TIPO_FORMULARIO'];
			$personca[$i]['nrodechip_dec'] = $recu['NRODECHIP_DEC'];
			$personca[$i]['fecha_chip'] = $recu['FECHA_CHIP'];
			$personca[$i]['motivo'] = $recu['MOTIVO'];
			$personca[$i]['tipo_credencial'] = $recu['TIPO_CREDENCIAL'];

			if ($recu['TIPO_CREDENCIAL'] == 1)
			{
				$personca[$i]['descTipoCreden'] = "Tarjeta USAL-ISIC-GALICIA";
			}
			elseif ($recu['TIPO_CREDENCIAL'] == 2)
			{
				$personca[$i]['descTipoCreden'] = "Tarjeta blanca";
			}

			$estadocredenca = $recu['ESTADOCREDENCIALCA'];

			$sql = "SELECT * FROM interfaz.estadocredenca" . $this->db_link . " WHERE estadocredenca = :estadocredenca";

			$parametros[0] = $estadocredenca;

			$result = $db->query ($sql, $esParam = true, $parametros);

			$tarjeta = $db->fetch_array ($result);

			$estadoCa = $tarjeta['ESTADOCREDENCA'] . " - " . $tarjeta['DESCRIP'];

			$personca[$i]['estadocredenca'] = $estadoCa;

			$i = $i + 1;
		}

		if ($personca != "")
		{
			return $personca;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Busca los datos de la tabla usuario_web para una persona X.
	 *
	 * @name buscarUsuarioWeb -
	 *
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'docno' o 'altaDeLaCuenta' o 'vtoDeLaCuenta' o 'cuenta' o 'idCuenta'
	 * @return array - 'docno' 'docnoCuenta' 'tipoDocCuenta' 'cuenta' 'nombreCompleto' 'fecha_altaCuenta' 'fecha_vencCuenta' 'fecha_bajaCuenta' 'frase' 'email' 'uid_cCuenta' 'uid_mCuenta' 'fecha_mCuenta' 'academicoCuenta' 'administrativoCuenta' 'alumnoCuenta' 'docenteCuenta' 'genericoCuenta' 'operadorCuenta' 'externoCuenta' 'ultimocambioclaveCuenta' 'ultimoacceso' 'ultimaaplicacion' 'ultimoip' 'person'
	 */
	public function buscarUsuarioWeb($datosAUsar)
	{
		global $db;

		$i = 0;
		$f = 0;

		$sql = "SELECT * FROM portal.usuario_web WHERE 1=:uno ";

		$parametros[$f] = "1";

		if (isset ($datosAUsar['person']) and ($datosAUsar['person'] != ""))
		{
			$person = $datosAUsar['person'];

			$sql = $sql . " AND person = :person";

			$f = $f + 1;

			$parametros[$f] = $person;
		}
		if (isset ($datosAUsar['docNumero']) and ($datosAUsar['docNumero'] != ""))
		{
			$docNumero = $datosAUsar['docNumero'];
			$docNumero = str_replace ('.', '', $docNumero);
			$docNumero = str_replace (' ', '', $docNumero);

			$docNumero = "%" . $docNumero . "%";

			$sql = $sql . " AND LTRIM(LTRIM(nro_doc, '0')) LIKE LTRIM(LTRIM(:nro_doc, '0')) ";

			$f = $f + 1;

			$parametros[$f] = $docNumero;
		}
		if (isset ($datosAUsar['altaDeLaCuenta']) and ($datosAUsar['altaDeLaCuenta'] != ""))
		{
			$altaDeLaCuenta = $datosAUsar['altaDeLaCuenta'];
			$altaDeLaCuenta = htmlentities ($altaDeLaCuenta);

			$sql = $sql . " AND TO_CHAR(fecha_alta, 'yyyy-mm-dd') = :altaDeLaCuenta ";

			$f = $f + 1;

			$parametros[$f] = $altaDeLaCuenta;
		}
		if (isset ($datosAUsar['vtoDeLaCuenta']) and ($datosAUsar['vtoDeLaCuenta'] != ""))
		{
			$vtoDeLaCuenta = $datosAUsar['vtoDeLaCuenta'];
			$vtoDeLaCuenta = htmlentities ($vtoDeLaCuenta);

			$sql = $sql . " AND TO_CHAR(fecha_venc, 'yyyy-mm-dd') = :vtoDeLaCuenta ";

			$f = $f + 1;

			$parametros[$f] = $vtoDeLaCuenta;
		}
		if (isset ($datosAUsar['cuenta']) and ($datosAUsar['cuenta'] != ""))
		{
			$cuenta = $datosAUsar['cuenta'];
			$cuenta = htmlentities ($cuenta);
			$cuenta = "%" . $cuenta . "%";

			$sql = $sql . " AND UPPER(cuenta) LIKE UPPER(:cuenta) ";

			$f = $f + 1;

			$parametros[$f] = $cuenta;
		}
		if (isset ($datosAUsar['idCuenta']) and ($datosAUsar['idCuenta'] != ""))
		{

			$id = $datosAUsar['idCuenta'];

			$sql = $sql . " AND id = :id ";

			$f = $f + 1;

			$parametros[$f] = $id;
		}

		$result = $db->query ($sql, $esParam = true, $parametros);

		while ($recu = $db->fetch_array ($result))
		{
			$persona[$i]['docNumero'] = $recu['NRO_DOC'];
			$persona[$i]['docnoCuenta'] = $recu['NRO_DOC'];
			$persona[$i]['tipoDocCuenta'] = $recu['TIPO_DOCUMENTO'];
			$persona[$i]['cuenta'] = $recu['CUENTA'];
			$persona[$i]['nombreCompleto'] = $recu['NOMBRE'];
			$persona[$i]['fecha_altaCuenta'] = $recu['FECHA_ALTA'];
			$persona[$i]['fecha_vencCuenta'] = $recu['FECHA_VENC'];
			$persona[$i]['fecha_bajaCuenta'] = $recu['FECHA_BAJA'];
			$persona[$i]['frase'] = $recu['FRASE'];
			$persona[$i]['email'] = $recu['EMAIL'];
			$persona[$i]['uid_cCuenta'] = $recu['UID_C'];
			$persona[$i]['uid_mCuenta'] = $recu['UID_M'];
			$persona[$i]['fecha_mCuenta'] = $recu['FECHA_M'];
			$persona[$i]['academicoCuenta'] = $recu['ACADEMICO'];
			$persona[$i]['administrativoCuenta'] = $recu['ADMINISTRATIVO'];
			$persona[$i]['alumnoCuenta'] = $recu['ALUMNO'];
			$persona[$i]['docenteCuenta'] = $recu['DOCENTE'];
			$persona[$i]['genericoCuenta'] = $recu['GENERICO'];
			$persona[$i]['operadorCuenta'] = $recu['OPERADOR'];
			$persona[$i]['externoCuenta'] = $recu['EXTERNO'];
			$persona[$i]['ultimocambioclaveCuenta'] = $recu['ULTIMOCAMBIOCLAVE'];
			$persona[$i]['ultimoacceso'] = $recu['ULTIMOACCESO'];
			$persona[$i]['ultimaaplicacion'] = $recu['ULTIMAAPLICACION'];
			$persona[$i]['ultimoip'] = $recu['ULTIMOIP'];
			$persona[$i]['person'] = $recu['PERSON'];

			$i = $i + 1;
		}

		if ($persona != "")
		{
			return $persona;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Recibe algun parametro de busqueda y en base a eso recupera los datos de esa persona en las diferentes tablas
	 *
	 * @name datosPersona
	 * @param mixed[] $datosAUsar
	 *        	- 'person' o 'docno' o 'realname' o 'apellido' o 'nombreCompleto' o 'num_tarj'
	 * @return mixed[] -
	 */
	public function datosPersona($datosAUsar)
	{
		global $db;

		if (isset ($datosAUsar['person']) and ($datosAUsar['person'] != ""))
		{
			$person = $this->buscarPerson ($datosAUsar);
			$perdoc = $this->buscarPerdoc ($datosAUsar);
			$personca = $this->buscarTargeta ($datosAUsar);
			$apers = $this->buscarAppers ($datosAUsar);
			$cuentaWeb = $this->buscarUsuarioWeb ($perdoc);
		}
		elseif (isset ($datosAUsar['docNumero']) and ($datosAUsar['docNumero'] != ""))
		{
			$perdoc = $this->buscarPerdoc ($datosAUsar);

			for($i = 0; $i < count ($perdoc); $i++)
			{
				$persons = $this->buscarPerson ($perdoc[$i]);
				$person[$i] = $persons[0];

				$personcas = $this->buscarTargeta ($perdoc[$i]);
				$personca[$i] = $personcas[0];

				$cuentaWebs = $this->buscarUsuarioWeb ($perdoc[$i]);
				$cuentaWeb[$i] = $cuentaWebs[0];

				$apers[$i] = $this->buscarAppers ($perdoc[$i]);
			}
		}
		elseif ((isset ($datosAUsar['realname']) and $datosAUsar['realname'] != "") or (isset ($datosAUsar['apellido']) and $datosAUsar['apellido'] != "") or (isset ($datosAUsar['nombreCompleto']) and $datosAUsar['nombreCompleto'] != ""))
		{
			$person = $this->buscarPerson ($datosAUsar);

			for($i = 0; $i < count ($person); $i++)
			{
				$perdocs = $this->buscarPerdoc ($person[$i]);
				$perdoc[$i] = $perdocs[0];

				$personcas = $this->buscarTargeta ($person[$i]);
				$personca[$i] = $personcas[0];

				$cuentaWebs = $this->buscarUsuarioWeb ($perdoc[$i]);
				$cuentaWeb[$i] = $cuentaWebs[0];

				$apers[$i] = $this->buscarAppers ($person[$i]);
			}
		}
		elseif (isset ($datosAUsar['num_tarj']) and $datosAUsar['num_tarj'] != "")
		{
			$personca = $this->buscarTargeta ($datosAUsar);

			for($i = 0; $i < count ($personca); $i++)
			{
				$persons = $this->buscarPerson ($personca[$i]);
				$person[$i] = $persons[0];

				$perdocs = $this->buscarPerdoc ($personca[$i]);
				$perdoc[$i] = $perdocs[0];

				$cuentaWebs = $this->buscarUsuarioWeb ($perdoc[$i]);
				$cuentaWeb[$i] = $cuentaWebs[0];

				$apers[$i] = $this->buscarAppers ($personca[$i]);
			}
		}

		for($i = 0; $i < count ($perdoc); $i++)
		{
			$persona[$i]['person'] = $perdoc[$i]['person'];
			$persona[$i]['typdoc'] = $perdoc[$i]['typdoc'];
			$persona[$i]['docNumero'] = $perdoc[$i]['docNumero'];
		}

		for($i = 0; $i < count ($person); $i++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $person[$i]['person'];
			}
			$persona[$i]['realname'] = $person[$i]['lname'];
			$persona[$i]['apellido'] = $person[$i]['fname'];
			$persona[$i]['country'] = $person[$i]['country'];
			$persona[$i]['poldiv'] = $person[$i]['poldiv'];
			$persona[$i]['city'] = $person[$i]['city'];
			$persona[$i]['birdate'] = $person[$i]['birdate'];
			$persona[$i]['nation'] = $person[$i]['nation'];
			$persona[$i]['sex'] = $person[$i]['sex'];
			$persona[$i]['marstat'] = $person[$i]['marstat'];
			$persona[$i]['address'] = $person[$i]['address'];
			$persona[$i]['rcountry'] = $person[$i]['rcountry'];
			$persona[$i]['rpoldiv'] = $person[$i]['rpoldiv'];
			$persona[$i]['rcity'] = $person[$i]['rcity'];
			$persona[$i]['telep'] = $person[$i]['telep'];
			$persona[$i]['active'] = $person[$i]['active'];
			$persona[$i]['tnation'] = $person[$i]['tnation'];
			$persona[$i]['incountrysince'] = $person[$i]['incountrysince'];
			$persona[$i]['religion'] = $person[$i]['religion'];
			$persona[$i]['qbrother'] = $person[$i]['qbrother'];
			$persona[$i]['qson'] = $person[$i]['qson'];
		}

		for($i = 0; $i < count ($personca); $i++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $personca[$i]['person'];
			}
			$persona[$i]['estadocredencialca'] = $personca[$i]['estadocredencialca'];
			$persona[$i]['email'] = $personca[$i]['email'];
			$persona[$i]['codigoisic'] = $personca[$i]['codigoisic'];
			$persona[$i]['nrodechip'] = $personca[$i]['nrodechip'];
			$persona[$i]['sca_fecha'] = $personca[$i]['sca_fecha'];
			$persona[$i]['sca_categoria'] = $personca[$i]['sca_categoria'];
			$persona[$i]['sca_lote'] = $personca[$i]['sca_lote'];
			$persona[$i]['tipo_formulario'] = $personca[$i]['tipo_formulario'];
			$persona[$i]['nrodechip_dec'] = $personca[$i]['nrodechip_dec'];
			$persona[$i]['fecha_chip'] = $personca[$i]['fecha_chip'];
			$persona[$i]['motivo'] = $personca[$i]['motivo'];
			$persona[$i]['tipo_credencial'] = $personca[$i]['tipo_credencial'];
			$persona[$i]['descTipoCreden'] = $personca[$i]['descTipoCreden'];
			$persona[$i]['estadocredenca'] = $personca[$i]['estadocredenca'];
		}

		for($i = 0; $i < count ($apers); $i++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $apers[$i]['person'];
			}

			if (isset ($apers[$i]['PISO']))
			{
				$persona[$i]['piso'] = $apers[$i]['PISO'];
			}
			if (isset ($apers[$i]['E-MAIL']))
			{
				$persona[$i]['e-mail'] = $apers[$i]['E-MAIL'];
			}
			if (isset ($apers[$i]['CODPOS']))
			{
				$persona[$i]['codpos'] = $apers[$i]['CODPOS'];
			}
			if (isset ($apers[$i]['LOCAL']))
			{
				$persona[$i]['local'] = $apers[$i]['LOCAL'];
			}
			if (isset ($apers[$i]['FAX']))
			{
				$persona[$i]['fax'] = $apers[$i]['FAX'];
			}
			if (isset ($apers[$i]['CALLE']))
			{
				$persona[$i]['calle'] = $apers[$i]['CALLE'];
			}
			if (isset ($apers[$i]['NUMERO']))
			{
				$persona[$i]['numero'] = $apers[$i]['NUMERO'];
			}
			if (isset ($apers[$i]['DEPTO']))
			{
				$persona[$i]['depto'] = $apers[$i]['DEPTO'];
			}
			if (isset ($apers[$i]['NRO']))
			{
				$persona[$i]['nro'] = $apers[$i]['NRO'];
			}
			if (isset ($apers[$i]['PREFIJO']))
			{
				$persona[$i]['prefijo'] = $apers[$i]['PREFIJO'];
			}
			if (isset ($apers[$i]['DESCRIP']))
			{
				$persona[$i]['descrip'] = $apers[$i]['DESCRIP'];
			}
		}

		for($i = 0; $i < count ($cuentaWeb); $i++)
		{
			if (!isset ($persona[$i]['person']) or $persona[$i]['person'] == "")
			{
				$persona[$i]['person'] = $cuentaWeb[$i]['person'];
			}
			if (!isset ($persona[$i]['docNumero']) or $persona[$i]['docNumero'] == "")
			{
				$persona[$i]['docNumero'] = $cuentaWeb[$i]['docNumero'];
			}
			if (!isset ($persona[$i]['nombreCompleto']) or $persona[$i]['nombreCompleto'] == "")
			{
				$persona[$i]['nombreCompleto'] = $cuentaWeb[$i]['nombreCompleto'];
			}

			$persona[$i]['cuenta'] = $cuentaWeb[$i]['cuenta'];
			$persona[$i]['fecha_altaCuenta'] = $cuentaWeb[$i]['fecha_altaCuenta'];
			$persona[$i]['fecha_vencCuenta'] = $cuentaWeb[$i]['fecha_vencCuenta'];
			$persona[$i]['fecha_bajaCuenta'] = $cuentaWeb[$i]['fecha_bajaCuenta'];
			$persona[$i]['frase'] = $cuentaWeb[$i]['frase'];
			$persona[$i]['email'] = $cuentaWeb[$i]['email'];
			$persona[$i]['uid_cCuenta'] = $cuentaWeb[$i]['uid_cCuenta'];
			$persona[$i]['uid_mCuenta'] = $cuentaWeb[$i]['uid_mCuenta'];
			$persona[$i]['fecha_mCuenta'] = $cuentaWeb[$i]['fecha_mCuenta'];
			$persona[$i]['academicoCuenta'] = $cuentaWeb[$i]['academicoCuenta'];
			$persona[$i]['administrativoCuenta'] = $cuentaWeb[$i]['administrativoCuenta'];
			$persona[$i]['alumnoCuenta'] = $cuentaWeb[$i]['alumnoCuenta'];
			$persona[$i]['docenteCuenta'] = $cuentaWeb[$i]['docenteCuenta'];
			$persona[$i]['genericoCuenta'] = $cuentaWeb[$i]['genericoCuenta'];
			$persona[$i]['operadorCuenta'] = $cuentaWeb[$i]['operadorCuenta'];
			$persona[$i]['externoCuenta'] = $cuentaWeb[$i]['externoCuenta'];
			$persona[$i]['ultimocambioclaveCuenta'] = $cuentaWeb[$i]['ultimocambioclaveCuenta'];
			$persona[$i]['ultimoacceso'] = $cuentaWeb[$i]['ultimoacceso'];
			$persona[$i]['ultimaaplicacion'] = $cuentaWeb[$i]['ultimaaplicacion'];
			$persona[$i]['ultimoip'] = $cuentaWeb[$i]['ultimoip'];
		}

		if (count ($persona[0]) > 0)
		{
			return $persona;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * Inicializa las variables con los datos a usar.
	 *
	 * @param mixed[] $datosAUsar
	 */
	public function setPersonaExistente($datosAUsar)
	{
		$persona = $this->datosPersona ($datosAUsar);

		$this->apellido = $persona['apellido'];
		$this->realname = $persona['realname'];
		$this->nombreCompleto = $persona['nombreCompleto'];
		$this->person = $persona['person'];
		$this->docNumero = $persona['docNumero'];
		$this->docTipo = $persona['typdoc'];
		$this->cuil = "";
		$this->emailPersonal = "";
		$this->emailProfecional = "";
		$this->personTelPersonal = "";
		$this->personTelPersonal2 = "";
		$this->personTelCelular = "";
		$this->personTelProfecional = "";
		$this->categoria = "";
		$this->foto_persona = "";
		$this->birdate = $persona['birdate'];
		$this->marstat = $persona['marstat'];
		$this->nation = $persona['nation'];
		$this->tnation = $persona['tnation'];
		$this->sexo = $persona['sex'];
		$this->cuenta = $persona['cuenta'];
		$this->idCuenta = "";
		$this->emailCuenta = "";
		$this->cuentaAcademica = "";
		$this->cuentaAdministrativa = "";
		$this->cuentaAlumno = "";
		$this->cuentaDocente = "";
		$this->cuentaExterno = "";
		$this->cuentaGenerica = "";
		$this->cuentaOperador = "";
		$this->fraseDeSeguridad = "";
		$this->vtoDeLaCuenta = "";
		$this->altaDeLaCuenta = $persona['fecha_altaCuenta'];
		$this->bajaDeLaCuenta = "";
		$this->country = $persona['country'];
		$this->poldiv = $persona['poldiv'];
		$this->city = $persona['city'];
		$this->rcountry = $persona['rcountry'];
		$this->rpoldiv = $persona['rpoldiv'];
		$this->rcity = $persona['rcity'];
		$this->direCalle = "";
		$this->direNumero = "";
		$this->direPiso = "";
		$this->direDto = "";
		$this->direCodPos = "";
		$this->legajo = "";
		$this->fIngreso = "";
		$this->fbaja = "";

		$persona['address'];

		$persona['telep'];
		$persona['active'];

		$persona['incountrysince'];
		$persona['religion'];
		$persona['qbrother'];
		$persona['qson'];
		$persona['person'];
		$persona['estadocredencialca'];
		$persona['email'];
		$persona['codigoisic'];
		$persona['nrodechip'];
		$persona['sca_fecha'];
		$persona['sca_categoria'];
		$persona['sca_lote'];
		$persona['tipo_formulario'];
		$persona['nrodechip_dec'];
		$persona['fecha_chip'];
		$persona['motivo'];
		$persona['tipo_credencial'];
		$persona['descTipoCreden'];
		$persona['estadocredenca'];
		$persona['piso'];
		$persona['e-mail'];
		$persona['codpos'];
		$persona['local'];
		$persona['fax'];
		$persona['calle'];
		$persona['numero'];
		$persona['depto'];
		$persona['nro'];
		$persona['prefijo'];
		$persona['descrip'];

		$persona['fecha_vencCuenta'];
		$persona['fecha_bajaCuenta'];
		$persona['frase'];
		$persona['email'];
		$persona['uid_cCuenta'];
		$persona['uid_mCuenta'];
		$persona['fecha_mCuenta'];
		$persona['academicoCuenta'];
		$persona['administrativoCuenta'];
		$persona['alumnoCuenta'];
		$persona['docenteCuenta'];
		$persona['genericoCuenta'];
		$persona['operadorCuenta'];
		$persona['externoCuenta'];
		$persona['ultimocambioclaveCuenta'];
		$persona['ultimoacceso'];
		$persona['ultimaaplicacion'];
		$persona['ultimoip'];
	}

	/**
	 * Se encargara de las acciones a realizar sobre los campos de la tabla apers
	 *
	 * @param string $accion
	 *        	- Puede ser UPDATE o INSERT dependiendo de esto es la accion que realizara la funcion
	 * @param mixed $pattrib
	 * @param mixed $shortdes
	 * @param mixed $valor
	 * @param int $person
	 *        	- person sobre el cual trabajara
	 * @return boolean
	 */
	function modApers($accion, $pattrib, $shortdes, $valor, $person)
	{
		global $db;

		try
		{
			if (!isset ($valor) or $valor == "")
			{
				throw new Exception ('el dato valor no fue correctamene pasado! ');
			}
			if (!isset ($shortdes) or $shortdes == "")
			{
				throw new Exception ('el dato shortdes no fue correctamene pasado! ');
			}
			if (!isset ($pattrib) or $pattrib == "")
			{
				throw new Exception ('el dato pattrib no fue correctamene pasado! ');
			}
			if (!isset ($person) or $person == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}

			if ($accion == "UPDATE")
			{
				$sql = "UPDATE appgral.apers" . $this->db_link . " SET VAL = :valor WHERE PERSON = :person AND PATTRIB = :pattrib AND SHORTDES = :shortdes";
			}
			elseif ($accion == "INSERT")
			{
				$sql = "INSERT INTO appgral.apers" . $this->db_link . " (val, person, pattrib, shortdes, ordno ) VALUES (:valor, :person, :pattrib, :shortdes, -1 )";
			}

			$parametros[0] = $valor;
			$parametros[1] = $person;
			$parametros[2] = $pattrib;
			$parametros[3] = $shortdes;

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				$resultado = true;
			}
			else
			{
				throw new Exception ('No pudo realizarse la insercion en appgral.apers! ');
			}
		}
		catch (Exception $e)
		{
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Revisarlo e implementarlo en class persona
	 *
	 * Valida el CUIT pasado por parametro.
	 * https://es.wikipedia.org/wiki/Clave_%C3%9Anica_de_Identificaci%C3%B3n_Tributaria
	 *
	 * @param int $cuit
	 */
	public function validarCuit($cuit)
	{
		$cuit = preg_replace ('/[^\d]/', '', (string) $cuit);
		if (strlen ($cuit) != 11)
		{
			return false;
		}
		$acumulado = 0;
		$digitos = str_split ($cuit);
		$digito = array_pop ($digitos);

		for($i = 0; $i < count ($digitos); $i++)
		{
			$acumulado += $digitos[9 - $i] * (2 + ($i % 6));
		}
		$verif = 11 - ($acumulado % 11);
		$verif = $verif == 11 ? 0 : $verif;

		return $digito == $verif;
	}

	/**
	 * Busca el legajo de la persona o el person asociado a un legajo
	 *
	 * @param array $datosAUsar
	 *        	Tiene como indices obligatorios legajo o person.
	 * @return boolean|array - En caso de no encontrar nada devolvera false. Si recupera datos devuelve un array con el person, el legajo, la categoria, la fecha de ingreso y la de baja.
	 */
	public function buscarCatXPerson($datosAUsar)
	{
		global $db;

		$resultado = false;

		$persona = "";

		try
		{
			if ((!isset ($datosAUsar['legajo']) or $datosAUsar['legajo'] == "") and (!isset ($datosAUsar['person']) or $datosAUsar['person'] == ""))
			{
				throw new Exception ('Debe pasarle a la funcion el person o el legajo! ');
			}

			if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
			{
				// if((isset($datosAUsar['legajo']) and $datosAUsar['legajo'] != ""))
				// {
				// $extraWhere = " AND LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0')) ";
				// $parametros[1] = $datosAUsar['legajo'];
				// }
				// else
				// {
				// $extraWhere = "";
				// }
				if ((isset ($datosAUsar['categoria']) and $datosAUsar['categoria'] != ""))
				{
					$extraWhere = " AND LTRIM(LTRIM(categoria, '0')) = LTRIM(LTRIM(:categoria, '0')) ";
					$parametros[1] = $datosAUsar['categoria'];
				}
				else
				{
					$extraWhere = "";
				}
				$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja, legajo FROM appgral.catxperson" . $this->db_link . " WHERE LTRIM(LTRIM(person, '0')) = LTRIM(LTRIM(:person, '0'))" . $extraWhere;

				$parametros[0] = $datosAUsar['person'];

				$result = $db->query ($sql, $esParam = true, $parametros);

				$persona = $db->fetch_array ($result);

				if ($persona != "")
				{
					if (isset ($persona['PERSON']))
					{
						$resultado['person'] = $persona['PERSON'];
					}
					if (isset ($persona['LEGAJO']))
					{
						$resultado['legajo'] = $persona['LEGAJO'];
					}
					if (isset ($persona['CATEGORIA']))
					{
						$resultado['categoria'] = $persona['CATEGORIA'];
					}
					if (isset ($persona['FINICIO']))
					{
						$resultado['fIngreso'] = $persona['FINICIO'];
					}
					if (isset ($persona['FBAJA']))
					{
						$resultado['fbaja'] = $persona['FBAJA'];
					}
				}
				else
				{
					// if((isset($datosAUsar['legajo']) and $datosAUsar['legajo'] != ""))
					// {
					// $parametros[2] = $datosAUsar['legajo'];
					// }
					if ((isset ($datosAUsar['categoria']) and $datosAUsar['categoria'] != ""))
					{
						$parametros[2] = $datosAUsar['categoria'];
					}

					$sqlTime = "SELECT MAX(mtime) person FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(person, '0')) = LTRIM(LTRIM(:person, '0'))";
					$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(person, '0')) = LTRIM(LTRIM(:person, '0')) AND mtime IN (" . $sqlTime . ")" . $extraWhere;

					$parametros[0] = $datosAUsar['person'];
					$parametros[1] = $datosAUsar['person'];

					$result = $db->query ($sql, $esParam = true, $parametros);

					$persona = $db->fetch_array ($result);

					if ($persona != "")
					{
						if (isset ($persona['PERSON']))
						{
							$resultado['person'] = $persona['PERSON'];
						}
						if (isset ($persona['LEGAJO']))
						{
							$resultado['legajo'] = $persona['LEGAJO'];
						}
						if (isset ($persona['CATEGORIA']))
						{
							$resultado['categoria'] = $persona['CATEGORIA'];
						}
						if (isset ($persona['FINICIO']))
						{
							$resultado['fIngreso'] = $persona['FINICIO'];
						}
						if (isset ($persona['FBAJA']))
						{
							$resultado['fbaja'] = $persona['FBAJA'];
						}
					}
				}
			}
			elseif (isset ($datosAUsar['legajo']) and $datosAUsar['legajo'] != "")
			{
				// print_r("1");
				$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja, legajo FROM appgral.catxperson" . $this->db_link . " WHERE LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0'))";

				$parametros[0] = $datosAUsar['legajo'];

				$result = $db->query ($sql, $esParam = true, $parametros);

				$persona = $db->fetch_array ($result);

				if ($persona != "")
				{
					if (isset ($persona['PERSON']))
					{
						$resultado['person'] = $persona['PERSON'];
					}
					if (isset ($persona['LEGAJO']))
					{
						$resultado['legajo'] = $persona['LEGAJO'];
					}
					if (isset ($persona['CATEGORIA']))
					{
						$resultado['categoria'] = $persona['CATEGORIA'];
					}
					if (isset ($persona['FINICIO']))
					{
						$resultado['fIngreso'] = $persona['FINICIO'];
					}
					if (isset ($persona['FBAJA']))
					{
						$resultado['fbaja'] = $persona['FBAJA'];
					}
				}
				else
				{
					$sqlTime = "SELECT MAX(mtime) person FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0'))";
					$sql = "SELECT person, categoria, TO_CHAR(finicio, 'yyyy-mm-dd') finicio, TO_CHAR(fbaja, 'yyyy-mm-dd') fbaja FROM appgral.catxpersont" . $this->db_link . " WHERE LTRIM(LTRIM(legajo, '0')) = LTRIM(LTRIM(:legajo, '0')) AND mtime IN (" . $sqlTime . ")";

					$parametros[0] = $datosAUsar['legajo'];
					$parametros[1] = $datosAUsar['legajo'];

					$result = $db->query ($sql, $esParam = true, $parametros);

					$persona = $db->fetch_array ($result);

					if ($persona != "")
					{
						if (isset ($persona['PERSON']))
						{
							$resultado['person'] = $persona['PERSON'];
						}
						if (isset ($persona['LEGAJO']))
						{
							$resultado['legajo'] = $persona['LEGAJO'];
						}
						if (isset ($persona['CATEGORIA']))
						{
							$resultado['categoria'] = $persona['CATEGORIA'];
						}
						if (isset ($persona['FINICIO']))
						{
							$resultado['fIngreso'] = $persona['FINICIO'];
						}
						if (isset ($persona['FBAJA']))
						{
							$resultado['fbaja'] = $persona['FBAJA'];
						}
					}
				}
			}

			return $resultado;
		}
		catch (Exception $e)
		{
			$resultado = false;

			$this->errores ($e);
		}
	}

	/**
	 * Devuelve el nombre y el apellido para un person dado.
	 *
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param mixed[] $datosAUsar
	 *        	- Es impresindible que contenga el inice "person" de lo contrario devolvera error.
	 * @throws Exception - Tanto si no se pasa el person como si no se puede recuperar valor.
	 * @return string[] - Con los campos LNAME y FNAME.
	 */
	public function getNombreYApellido($db, $datosAUsar)
	{
		try
		{
			if (isset ($datosAUsar['person']) and $datosAUsar['person'] != "")
			{
				$where[] = " person = :person ";
				$parametros[] = $datosAUsar['person'];

				if ($where != "")
				{
					$where = implode (" AND ", $where);

					$where = " AND " . $where;
				}

				$sql = "SELECT lname, fname FROM appgral.person WHERE 1=1 " . $where;

				if ($result = $db->query ($sql, $esParam = true, $parametros))
				{
					$rst = $db->fetch_array ($result);

					return $rst;
				}
				else
				{
					throw new Exception ('ERROR: No se pudo realizar la busqueda en appgral.person.');
				}
			}
			else
			{
				throw new Exception ('ERROR: El person es obligatorio.');
			}
		}
		catch (Exception $e)
		{

			$this->errores ($e);

			if ($db->debug == true)
			{
				return __LINE__ . " - " . __FILE__ . " - " . $e->getMessage ();
			}
			else

			{
				return $e->getMessage ();
			}

			if ($db->dieOnError == true)
			{
				exit ();
			}
		}
	}

	/*
	 * ************************************************************************
	 * Funciones para la creacion de personas
	 *
	 * ************************************************************************
	 */

	/**
	 * Inserta el numero y tipo de documento en la tabla appgral.perdoc
	 *
	 * @param array $arrayDatosPersona
	 *        	Tiene como indices obligatorios docNumero y docTipo.
	 *
	 * @todo adicionalmente updatea el registro en appgral.lnumber para generarle el nuevo person.
	 *
	 * @throws Exception
	 * @return int|boolean - el person en caso de realizarce todo sin problema y false si no.array
	 *
	 *         En caso de que el parametro docTipo sea numerico realiza la siguiente convecion ( 1=LE, 2=LC, 7=DNI )
	 */
	public function nuevoPerdoc($arrayDatosPersona)
	{
		global $db;

		$resultado = true;
		echo "****************************************";
		return;
		// print_r($arrayDatosPersona);

		try
		{
			if (!isset ($arrayDatosPersona['docTipo']) or $arrayDatosPersona['docTipo'] == "")
			{
				throw new Exception ('El tipo de documento no puede ser nulo! ');
			}
			if (!isset ($arrayDatosPersona['docNumero']) or $arrayDatosPersona['docNumero'] == "")
			{
				throw new Exception ('El numero de documento no puede ser nulo! ');
			}

			if (is_numeric ($arrayDatosPersona['docTipo']))
			{
				if (ltrim ($arrayDatosPersona['docTipo'], 0) == 7)
				{
					$arrayDatosPersona['docTipo'] = "DNI";
				}
				elseif (ltrim ($arrayDatosPersona['docTipo'], 0) == 2)
				{
					$arrayDatosPersona['docTipo'] = "LC";
				}
				elseif (ltrim ($arrayDatosPersona['docTipo'], 0) == 4)
				{
					$arrayDatosPersona['docTipo'] = "PAS";
				}
				elseif (ltrim ($arrayDatosPersona['docTipo'], 0) == 1)
				{
					$arrayDatosPersona['docTipo'] = "LE";
				}
				else
				{
					throw new Exception ('El campo tipo es un numero y no pertenece a ninguno de los listados! ');
				}
			}

			$person = $this->buscarPerdoc ($arrayDatosPersona);

			if ($person != "")
			{
				throw new Exception ('El numero de documento tiene un registro en appgral.auditaperdoc con el person $person ! ');
			}

			$sql = "UPDATE appgral.lnumber" . $this->db_link . " SET lnum=lnum+1 WHERE classname = 'intersoft.appgral.schemas.appgral.Person'";

			if (!$db->query ($sql))
			{
				throw new Exception ('error!');
			}

			// Recuperamos el person a utilizar
			$sql = "SELECT (lnum) lnum FROM appgral.lnumber" . $this->db_link . " WHERE classname = 'intersoft.appgral.schemas.appgral.Person'";

			if (!$result = $db->query ($sql))
			{
				throw new Exception ('error!');
			}

			$person = $db->fetch_array ($result);

			$person = $person['LNUM'];

			// Insertamos el documento en Perdoc
			$sql = "INSERT INTO appgral.perdoc" . $this->db_link . " (person, typdoc, docno ) VALUES (:person, :typdoc, :docno )";

			$parametros[0] = $person;
			$parametros[1] = $arrayDatosPersona['docTipo'];
			$parametros[2] = $arrayDatosPersona['docNumero'];

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				return $person;
			}
			else
			{
				throw new Exception ('error!');
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Realiza la insecion en la tabla appgral.catXPerson previamente comprueba que los datos no existan en esa tabla devolviendo un error en caso contrario.
	 *
	 * @param mixed[] $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, categoria, fIngreso, fbaja, legajo
	 *
	 * @global $db - coneccion a la base de datos.
	 * @global $_SESSION - Requiere acceder a las siguirenres variables de session 'person' y 'app'.
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function nuevoCatXPerson($arrayDatosPersona)
	{
		global $db, $_SESSION;

		$resultado = true;

		try
		{
			if ($this->buscarCatXPerson ($arrayDatosPersona) != false)
			{
				throw new Exception ('Ya existe en catXPerson!');
			}

			$i = 0;
			$sqlValores = ") VALUES (SYSDATE";
			$sqlCampos = "";

			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['person']))
				{
					$sqlCampos = $sqlCampos . ", person";

					$sqlValores = $sqlValores . ", :person";

					$parametros[$i] = $arrayDatosPersona['person'];

					$i++;
				}
			}
			if (!isset ($arrayDatosPersona['categoria']) or $arrayDatosPersona['categoria'] == "")
			{
				throw new Exception ('el dato categoria no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['categoria']))
				{
					$sqlCampos = $sqlCampos . ", categoria";

					$sqlValores = $sqlValores . ", :categoria";

					$parametros[$i] = $arrayDatosPersona['categoria'];

					$i++;
				}
			}
			if (!isset ($arrayDatosPersona['fIngreso']) or $arrayDatosPersona['fIngreso'] == "")
			{
				throw new Exception ('el dato fIngreso no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['fIngreso']))
				{
					$sqlCampos = $sqlCampos . ", finicio";

					$sqlValores = $sqlValores . ", TO_DATE(:finicio, 'YYYY-MM-DD')";

					$parametros[$i] = $arrayDatosPersona['fIngreso'];

					$i++;
				}
			}
			if (isset ($arrayDatosPersona['fbaja']) and $arrayDatosPersona['fbaja'] != "")
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['fbaja']))
				{
					$sqlCampos = $sqlCampos . ", fbaja";

					$sqlValores = $sqlValores . ", TO_DATE(:fbaja, 'YYYY-MM-DD')";

					$parametros[$i] = $arrayDatosPersona['fbaja'];

					$i++;
				}
			}
			if (!isset ($arrayDatosPersona['legajo']) or $arrayDatosPersona['legajo'] == "")
			{
				throw new Exception ('el dato legajo no fue correctamene pasado! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($arrayDatosPersona['legajo']))
				{
					$sqlCampos = $sqlCampos . ", legajo";

					$sqlValores = $sqlValores . ", :legajo";

					$parametros[$i] = $arrayDatosPersona['legajo'];

					$i++;
				}
			}
			if (!isset ($_SESSION['person']) or $_SESSION['person'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer el person de la misma! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($_SESSION['person']))
				{
					$sqlCampos = $sqlCampos . ", muid";

					$sqlValores = $sqlValores . ", :muid";

					$parametros[$i] = $_SESSION['person'];

					$i++;
				}
			}
			if (!isset ($_SESSION['app']) or $_SESSION['app'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer la app de la misma! ');
			}
			else
			{
				if ($this->comprobarExisteDato ($_SESSION['app']))
				{
					$sqlCampos = $sqlCampos . ", idaplicacion";

					$sqlValores = $sqlValores . ", :idaplicacion";

					$parametros[$i] = $_SESSION['app'];

					$i++;
				}
			}

			$sql = "INSERT INTO appgral.catxperson" . $this->db_link . " (mtime";
			$sqlValores .= ")";

			$sql = $sql . $sqlCampos . $sqlValores;

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				return $resultado;
			}
			else
			{
				throw new Exception ('Error al insertar en appgral.catxperson!');
			}
			return $resultado;
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
	}

	/**
	 * Realiza el update en la tabla appgral.catXPerson previamente comprueba que los datos no existan en esa tabla devolviendo un error en caso contrario.
	 *
	 * @param array $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, legajo
	 *
	 * @global $db - coneccion a la base de datos.
	 * @global $_SESSION - Requiere acceder a las siguirenres variables de session 'person' y 'app'.
	 *
	 * @throws Exception
	 * @return boolean
	 */
	public function updateCatXPerson($arrayDatosPersona)
	{
		global $db, $_SESSION;
		$extraWhere = '';
		$resultado = true;

		try
		{
			if ($this->buscarCatXPerson ($arrayDatosPersona) == false)
			{
				throw new Exception ('El person ' . $arrayDatosPersona['person'] . 'no existe en catXPerson!');
			}

			$a = 0;
			$campos = "";

			if (isset ($arrayDatosPersona['categoria']) and $this->comprobarExisteDato ($arrayDatosPersona['categoria']))
			{
				$parametros[$a] = $arrayDatosPersona['categoria'];
				$a++;

				$campos .= ", categoria = :categoria";
			}

			if (isset ($arrayDatosPersona['fIngreso']) and $this->comprobarExisteDato ($arrayDatosPersona['fIngreso']))
			{
				$parametros[$a] = $arrayDatosPersona['fIngreso'];
				$a++;

				$campos .= ", finicio = TO_DATE(:finicio, 'YYYY-MM-DD')";
			}

			if (isset ($arrayDatosPersona['fbaja']) and $this->comprobarExisteDato ($arrayDatosPersona['fbaja']))
			{
				$parametros[$a] = $arrayDatosPersona['fbaja'];
				$a++;

				$campos .= ", fbaja = TO_DATE(:fbaja, 'YYYY-MM-DD')";
			}

			if (isset ($arrayDatosPersona['legajo']) and $this->comprobarExisteDato ($arrayDatosPersona['legajo']))
			{
				$parametros[$a] = $arrayDatosPersona['legajo'];
				$a++;

				$campos .= ", legajo = :legajo";
			}

			if (!isset ($_SESSION['person']) or $_SESSION['person'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer el person de la misma! ');
			}
			else
			{
				$parametros[$a] = $_SESSION['person'];
				$a++;

				$campos .= ", muid = :muid";
			}

			if (!isset ($_SESSION['app']) or $_SESSION['app'] == "")
			{
				throw new Exception ('Necesita iniciar session y establecer la app de la misma! ');
			}
			else
			{
				$parametros[$a] = $_SESSION['app'];
				$a++;

				$campos .= ", idaplicacion = :idaplicacion";
			}

			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			else
			{
				$parametros[$a] = $arrayDatosPersona['person'];
				$a++;

				$wer = "AND person = :person";
			}

			if (isset ($arrayDatosPersona['categoria']) and $this->comprobarExisteDato ($arrayDatosPersona['categoria']))
			{
				$extraWhere = " AND LTRIM(LTRIM(categoria, '0')) = LTRIM(LTRIM(:categoria, '0')) ";
				$parametros[$a] = $arrayDatosPersona['categoria'];

				$a++;
			}

			$sql = "UPDATE appgral.catxperson" . $this->db_link . " SET mtime = SYSDATE" . $campos . " WHERE 1=1 " . $wer . $extraWhere;

			if ($db->query ($sql, $esParam = true, $parametros))
			{
				return $resultado;
			}
			else
			{
				throw new Exception ('error!');
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Realiza el insert en la tabla appgral.person
	 *
	 * @param mixed[] $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, apellido, realname, country, poldiv, city, birdate, nation, sexo, marstat, rcountry, rpoldiv, rcity, tnation
	 * @throws Exception
	 *
	 * @global $db - coneccion a la base de datos.
	 *
	 * @return boolean
	 */
	public function inserPerson($arrayDatosPersona)
	{
		global $db;
		$resultado = true;

		try
		{
			if (!isset ($arrayDatosPersona['person']) or $arrayDatosPersona['person'] == "")
			{
				throw new Exception ('el dato person no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['apellido']) or $arrayDatosPersona['apellido'] == "")
			{
				throw new Exception ('el dato apellido no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['realname']) or $arrayDatosPersona['realname'] == "")
			{
				throw new Exception ('el dato realname no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['country']) or $arrayDatosPersona['country'] == "")
			{
				throw new Exception ('el dato country no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['poldiv']) or $arrayDatosPersona['poldiv'] == "")
			{
				throw new Exception ('el dato poldiv no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['city']) or $arrayDatosPersona['city'] == "")
			{
				throw new Exception ('el dato city no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['birdate']) or $arrayDatosPersona['birdate'] == "")
			{
				throw new Exception ('el dato birdate no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['nation']) or $arrayDatosPersona['nation'] == "")
			{
				throw new Exception ('el dato nation no fue correctamene pasado! ');
			}
			else
			{
				$arrayDatosPersona['nation'] = $this->recuNacion ($arrayDatosPersona['nation']);
			}
			if (!isset ($arrayDatosPersona['sexo']) or $arrayDatosPersona['sexo'] == "")
			{
				throw new Exception ('el dato sexo no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['marstat']) or $arrayDatosPersona['marstat'] == "")
			{
				// FIXME verificar si el funcionamiento de esto es correcto - 2017/04/05 iberlot
				// throw new Exception('el dato marstat no fue correctamene pasado! ');

				$arrayDatosPersona['marstat'] = '0';
			}
			if (!isset ($arrayDatosPersona['rcountry']) or $arrayDatosPersona['rcountry'] == "")
			{
				throw new Exception ('el dato rcountry no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['rpoldiv']) or $arrayDatosPersona['rpoldiv'] == "")
			{
				throw new Exception ('el dato rpoldiv no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['rcity']) or $arrayDatosPersona['rcity'] == "")
			{
				throw new Exception ('el dato rcity no fue correctamene pasado! ');
			}
			if (!isset ($arrayDatosPersona['tnation']) or $arrayDatosPersona['tnation'] == "")
			{
				// FIXMENo se por que no reconoce el campo tnation ???
				// throw new Exception('el dato tnation no fue correctamene pasado! ');
				$arrayDatosPersona['tnation'] = 0;
			}

			if (!is_numeric ($arrayDatosPersona['sexo']))
			{
				if ($arrayDatosPersona['sexo'] == 'V')
				{
					$arrayDatosPersona['sexo'] = "1";
				}
				elseif ($arrayDatosPersona['sexo'] == 'M')
				{
					$arrayDatosPersona['sexo'] = "0";
				}
			}

			// Recuperamos el person a utilizar
			$sql = "SELECT * FROM appgral.person" . $this->db_link . " WHERE person = :person";

			$parametros = "";
			$parametros[0] = $arrayDatosPersona['person'];

			$result = $db->query ($sql, $esParam = true, $parametros);

			$persona = $db->fetch_array ($result);

			if ($persona == "" or $persona == NULL)
			{
				$sqlNuevoPer = "INSERT INTO appgral.person" . $this->db_link . "
	(person, lname, fname, country, poldiv, city, birdate, nation, sex, marstat, address, rcountry, rpoldiv, rcity, telep, tnation)
	VALUES
	(:person, upper(:lname), :fname, :country, :poldiv, :city, TO_DATE(:birdate, 'RRRR-MM-DD'), :nation, :sex, :marstat, 'DOMI', :rcountry, :rpoldiv, :rcity, 'TELE', :tnation)";

				$parametros = "";
				$parametros[0] = $arrayDatosPersona['person'];
				$parametros[1] = $arrayDatosPersona['apellido'];
				$parametros[2] = $arrayDatosPersona['realname'];
				$parametros[3] = $arrayDatosPersona['country'];
				$parametros[4] = $arrayDatosPersona['poldiv'];
				$parametros[5] = $arrayDatosPersona['city'];
				$parametros[6] = $arrayDatosPersona['birdate'];
				$parametros[7] = $arrayDatosPersona['nation'];
				$parametros[8] = $arrayDatosPersona['sexo'];
				$parametros[9] = $arrayDatosPersona['marstat'];
				$parametros[10] = $arrayDatosPersona['rcountry'];
				$parametros[11] = $arrayDatosPersona['rpoldiv'];
				$parametros[12] = $arrayDatosPersona['rcity'];
				$parametros[13] = $arrayDatosPersona['tnation'];

				if ($db->query ($sqlNuevoPer, $esParam = true, $parametros))
				{
					return true;
				}
				else
				{
					throw new Exception ('No se pudo realizar la insercion en appgral.person! ');
				}
			}
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		return $resultado;
	}

	/**
	 * Realiza el insert de todos los campos requeridos para la creacion de personas
	 *
	 * @param mixed[] $arrayDatosPersona
	 *        	- Debe contener los siguientes indices de forma obligatoria
	 *        	person, apellido, realname, country, poldiv, city, birdate, nation, sexo, marstat, rcountry, rpoldiv, rcity, tnation
	 *
	 * @throws Exception
	 *
	 * @global $db - coneccion a la base de datos.
	 *
	 * @return number
	 */
	public function nuevaPersona($arrayDatosPersona)
	{
		global $db;
		$resultado = true;

		try
		{
			if (!$person = $this->nuevoPerdoc ($arrayDatosPersona))
			{
				throw new Exception ('No se pudo dar de alta en perdoc ');
			}

			$arrayDatosPersona['person'] = $person;

			$arrayDatosPersona['nation'] = $this->recuNacion ($arrayDatosPersona['nation']);

			if ($arrayDatosPersona['tnation'] == "")
			{
				if ($arrayDatosPersona['nation'] == 'ARG')
				{
					$arrayDatosPersona['tnation'] = 0;
				}
				else
				{
					$arrayDatosPersona['tnation'] = 1;
				}
			}

			if (!$this->inserPerson ($arrayDatosPersona))
			{
				throw new Exception ('No se pudo dar de alta en person ');
			}

			// FIXME hay que verificar si conviene realizarlo asi o de alguna otra manres

			if (isset ($arrayDatosPersona['email']) and $arrayDatosPersona['email'] != "")
			{
				if (!$this->modApers ("INSERT", 'TELE', 'E-MAIL', $arrayDatosPersona['email'], $person))
				{
					throw new Exception ('No se pudo insertar el E-MAIL en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direCalle']) and $arrayDatosPersona['direCalle'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'CALLE', $arrayDatosPersona['direCalle'], $person))
				{
					throw new Exception ('No se pudo insertar la CALLE en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direNumero']) and $arrayDatosPersona['direNumero'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'NRO', $arrayDatosPersona['direNumero'], $person))
				{
					throw new Exception ('No se pudo insertar el NUMERO en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direPiso']) and $arrayDatosPersona['direPiso'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'PISO', $arrayDatosPersona['direPiso'], $person))
				{
					throw new Exception ('No se pudo insertar el PISO en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direDto']) and $arrayDatosPersona['direDto'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'DEPTO', $arrayDatosPersona['direDto'], $person))
				{
					throw new Exception ('No se pudo insertar el DEPARTAMENTO en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['direCodPos']) and $arrayDatosPersona['direCodPos'] != "")
			{
				if (!$this->modApers ("INSERT", 'DOMI', 'CODPOS', $arrayDatosPersona['direCodPos'], $person))
				{
					throw new Exception ('No se pudo insertar el CODIGO POSTAL en appgral.appers ');
				}
			}
			if (isset ($arrayDatosPersona['personTelPersonal']) and $arrayDatosPersona['personTelPersonal'] != "")
			{
				if (!$this->modApers ("INSERT", 'TELE', 'NUMERO', $arrayDatosPersona['personTelPersonal'], $person))
				{
					throw new Exception ('No se pudo insertar el TELEFONO en appgral.appers ');
				}
			}

			$this->nuevoCatXPerson ($arrayDatosPersona);
		}
		catch (Exception $e)
		{
			$db->rollback ();
			$resultado = false;

			$this->errores ($e);
		}
		$db->commit ();
		$resultado = $arrayDatosPersona['person'];

		return $resultado;
	}

	/**
	 * Comprueva la existencia de un dato distinto de cero.
	 *
	 * @param mixed $variable
	 * @return boolean
	 */
	private function comprobarExisteDato($variable)
	{
		if ($variable != "")
		{
			if ($variable != 'NULL')
			{
				if (is_numeric ($variable) and $variable != 0)
				{
					return true;
				}
				elseif (is_numeric ($variable) and $variable == 0)
				{
					return false;
				}
				elseif (!is_numeric ($variable))
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Recupera los datos de appgral.country,
	 *
	 * @param string $nacion
	 *        	- dato a buscar en la tabla appgral.country
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @return string - codigo de la nacion
	 */
	private function recuNacion($nacion, $db = "")
	{
		global $db;

		if (isset ($nacion))
		{
			$sql = "SELECT * FROM appgral.country" . $this->db_link . " WHERE TRIM(upper(country)) = TRIM(upper(:country)) OR TRIM(upper(descrip)) = TRIM(upper(:descrip)) OR TRIM(upper(nation)) = TRIM(upper(:nation))";

			$parametros = "";
			$parametros[0] = $nacion;
			$parametros[1] = $nacion;
			$parametros[2] = $nacion;

			$result = $db->query ($sql, $esParam = true, $parametros);

			$pais = $db->fetch_array ($result);

			$nacion = $pais['COUNTRY'];
		}

		if (!isset ($nacion) or $nacion == "")
		{
			$nacion = 'ARG';
		}

		return $nacion;
	}

	/**
	 * Funcion encargada de la imprecion de errores.
	 *
	 * @param mixed $e
	 */
	private function errores($e)
	{
		if ($this->mostrarErrores == true)
		{
			print_r ($e->getMessage () . ". Linea " . $e->getLine () . " del archivo: " . $e->getFile ());
		}
		if ($this->dieOnError == true)
		{
			exit ();
		}
	}
}
?>