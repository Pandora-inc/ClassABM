<?php
/**
 *
 * Este archivo se encargara de contener e inicializar todas las variables que va a utilizar el sistema
 *
 * @author iberlot
 * @version 2.0
 *
 */
$notaPorCambio = "";
$idModulo = "";
$idAplicacion = "";
$enviarMailSector = "";
$enviarMail = "";
$errorEnvio = "";
$msjSolicitante = "";
$msjDesarrollos = "";
$viejoESTADO = "";
$viejoDERIVADO = "";
$viejoCOMPROMETIDO_PARA = "";
$viejoCATEGORIAREQ = "";
$viejoTIPOREQUERIMIENTO = "";
$enviarMailSector = "";
$enviarMail = "";
$errorEnvio = "";
$consReque = "";
$uni1 = "";
$ConsAnoReq = "";
$uni2 = "";
$ConsSop = "";
$uni3 = "";
$ConsEst = "";
$uni4 = "";
$ConsCat = "";
$uni5 = "";
$ConsFechaInn = "";
$uni6 = "";
$ConsTipo = "";
$uni7 = "";
$uni8 = "";
$uni9 = "";
$reqNro = "";
$anio = "";
$uni10 = "";

$nuevoReqSolic = "";
$nuevoReqArea = "";
$Email = "";
$nuevoReqRefer = "";
$nuevoReqTelUsr = "";
$nuevoReqDesCorta = "";
$nuevoReqPriori = "";
$estado = "";
$nuevoReqFechaEntr = "";
$nuevoReqCateg = "";
$nuevoReqTipo = "";
$nuevoReqDetalle = "";
$nuevoReqFecha = "";
$nuevoReqPrioriSist = "";
$nuevoReqAccion = "";
$nuevoReqSector = "";
$nuevoReqUsrSist = "";
$nuevoReqAplic = "";
$nuevoReqModulo = "";
$nuevoReqRelac = "";
$nuevoReqArchivo = "";
$nuevoReqCerrar = "";
$jumpMenu = "";
$msjArchivo = "";

$row2 = "";
$DocNro = "";
$refer = "";
$requeRel = "";
$directorio = "";
$dir = "";
$nota = "";

$nombreUsrSist = "";
$usrMail = "";

$raiz = '';
$raizext = '';

$row = "";
$opciones = "";
$archModulo = "";
$personaNombre = "";
$EmailPersona = "";

$fechaActual = date ("Y-m-d");
$anio = date ("Y");

$nuevafecha = strtotime ('+2 Year', strtotime ($fechaActual));
$nuevafecha = date ('Y-m-d', $nuevafecha);

$app = '';
$cuenta = '';
$foto = '';
$personApps = '';

$depen = '';
$dia = '';
$mes = '';

$color = '';
$bgcolor = "";

$justificador = "";
$fechcorr = "";
$selected = "";

$fecha = date ('Y-m-d');
$nuevafecha = strtotime ('+7 day', strtotime ($fecha));
$nuevafecha = date ('Y-m-d', $nuevafecha);

$anio = date ("Y");

$nombre_dependencia = "";
$cod_dependencia = "";
$ndependencia = "";
$baja = "";
$cod = "";

$ultimocambioclave = "";
$ultimoacceso = "";
$ultimaaplicacion = "";
$ultimoip = "";

$sqlLegajo = "";
$sqlApellido = "";
$sqlNombre = "";
$actividad = "";

$sqlLegajo = "";
$sqlApellido = "";
$sqlNombre = "";
$sqlDni = "";

$Unidad = "";
$Edificio = "";
$Sede = "";

$agregar = "";

$bajaCuenta = "";
$crearCuentas = "";

$sqlNCuenta = "";

$okNaper = "";
$okNper = "";
$okNperDoc = "";

/**
 * ******************************************************************************
 * VARIABLES DE CONFIGURACION *
 * ******************************************************************************
 */

/**
 *
 * @var string Nombre del servidor principal a conectarse
 */
$ServidorOracle = "";

/**
 *
 * @var string Nombre del segundo servidor a conectarse
 */
$ServidorOracle2 = "";

/**
 *
 * @var string Usuario de conexion a la db de la aplicacion
 */
$UsuarioOracle = "";

/**
 *
 * @var string Password de la conexion a la aplicacion
 */
$PasswordOracle = "";

/**
 *
 * @var string Titulo de la aplicacion
 */
$Titulo;

/**
 *
 * @var string Directorio raiz de la aplicacion en formato corto
 * @example $raiz = "/cgi-bin/Aplicacion/";
 */
$raiz = "";

/**
 *
 * @var string Directorio raiz de la aplicacion en formato completo tipo direccion
 * @example $directorio = "http://Servidor/cgi-bin/Aplicacion/";
 */
$directorio = "";

/**
 *
 * @var string Directorio donde se guardan las fotos
 */
$dirFotos = "";

/**
 * ******************************************************************************
 * VARIABLES GLOBALES Y REFERENTES AL USUARIO *
 * ******************************************************************************
 */

/**
 * Nombre de usuario que esta usando el sistema
 *
 * @global string $_SESSION['usuario']
 * @name $usuario
 */
$usuario = "";

/**
 * Aplicaciones a las que tiene acceso el usuario
 *
 * @global string $_SESSION['personApps']
 * @name $personApps
 */
$personApps = "";

/**
 *
 * @var mix Ip desde la que se esta conectando el usuario
 */
$ipUsuario = "";

/**
 * La URI que se empleó para acceder a la página.
 *
 * @example '/index.html'.
 * @global string $_SERVER['REQUEST_URI']
 * @name $actual_link
 */
$actual_link = "";

/**
 * ******************************************************************************
 * VARIABLES REFERENTES A LA PERSONA *
 * ******************************************************************************
 */

/**
 *
 * @var string Apellido de la persona
 */
$apellido = "";

/**
 *
 * @var string Nombre de la persona
 */
$realname = "";

/**
 *
 * @var string Nombre completo de la persona (formado de la forma $apellido . " " . $realname)
 */
$nombreCompleto = "";

/**
 *
 * @var int Numero de person (id de la tabla) de la persona
 */
$person = "";

/**
 *
 * @var string Numero de documento de la persona
 */
$docNumero = "";

/**
 *
 * @var string Tipo de documento de la persona
 */
$docTipo = "";

/**
 *
 * @var string Cuenta de la persona (nombre de usuario)
 */
$cuenta = "";

/**
 *
 * @var string Id de la Cuenta de la persona
 */
$idCuenta = "";

/**
 *
 * @var string Direccion de mail de la persona
 */
$email = "";

/**
 *
 * @var int Numero de telefono de la persona
 */
$personTel = "";

/**
 *
 * @var bool Especifica si la persona es un academico o no
 */
$esAcademico = "";

/**
 *
 * @var bool Especifica si la persona es un administrativo o no
 */
$esAdministrativo = "";

/**
 *
 * @var bool Especifica si la persona es un alumno o no
 */
$esAlumno = "";

/**
 *
 * @var bool Especifica si la persona es un docente o no
 */
$esDocente = "";

/**
 *
 * @var bool Especifica si la persona es un externo o no
 */
$esExterno = "";

/**
 *
 * @var bool Especifica si la cuenta asociada a la persona es generica o no
 */
$esGenerica = "";

/**
 *
 * @var string Frace de seguridad de la cuenta de la persona utilizada para la recuperacion de contraseñas
 */
$fraseDeSeguridad = "";

/**
 *
 * @var date Fecha de vencimiento de la cuenta de la persona
 */
$vtoDeLaCuenta = "";

/**
 *
 * @var date Fecha de alta de la cuenta de la persona
 */
$altaDeLaCuenta = "";

/**
 *
 * @var date Fecha de baja de la cuenta de la persona
 */
$bajaDeLaCuenta = "";

/**
 *
 * @var string Direccion deonde se encuentra la foto de la persona dentro de la carpeta de fotos
 *      se arma de la siguiente manera
 *     
 *      <code>
 *      <?php
 *      substr($docNumero, - 1)."/".substr($docNumero, - 2, 1)."/".substr($docNumero, - 3, 1)."/".substr('00000000000'.$docNumero, - 10).".jpg";
 *      ?>
 *      </code>
 *     
 * @example para el DNI 31234567 quedaria:
 * @example 7/6/5/0031234567.jpg
 *         
 */
$foto_persona = "";

/**
 *
 * @var date Fecha de nacimiento de la persona
 */
$birdate = "";

/**
 *
 * @var mix Estado civil de la persona
 */
$marstat = "";

/**
 *
 * @var varchar Pais de nacimiento de la persona
 */
$nation = "";

/**
 *
 * @var varchar Pais de residencia de la persona
 */
$tnation = "";

/**
 *
 * @var mix Sexo de la persona
 */
$sexo = "";

/**
 * ******************************************************************************
 * VARIABLES REFERENTES A LA DIRECCION DE LA PERSONA *
 * ******************************************************************************
 */

/**
 *
 * @var string Pais de nacimiento de la persona
 */
$country = "";

/**
 *
 * @var string Provincia de nacimiento de la persona
 */
$poldiv = "";

/**
 *
 * @var string Ciudad de nacimiento de la persona
 */
$city = "";

/**
 *
 * @var string Pais de residencia de la persona
 */
$rcountry = "";

/**
 *
 * @var string Provincia de residencia de la persona
 */
$rpoldiv = "";

/**
 *
 * @var string Ciudad de residencia de la persona
 */
$rcity = "";

/**
 *
 * @var string Calle de la direccion de la persona
 */
$direCalle = "";

/**
 *
 * @var string Numero de la direccion de la persona
 */
$direNumero = "";

/**
 *
 * @var string Piso de la direccion de la persona
 */
$direPiso = "";

/**
 *
 * @var string Dto de la direccion de la persona
 */
$direDto = "";

/**
 *
 * @var string Codigo postal de la direccion de la persona
 */
$direCosPos = "";
// $ = "";
// $ = "";
/**
 * ******************************************************************************
 * VARIABLES GENERALES REFERENTES AL FUCIONAMIENTO DEL SISTEMA *
 * ******************************************************************************
 */

/**
 * COMPROBAR SU USO PARA PODER DEFINIRLA
 */
$recu = "";

/**
 *
 * @var int numero de renglones devueltos por una consulta
 */
$nrows = "";

/**
 *
 * @var string variable en la que se define y guarda la declaracion de las consultas a la base de datos
 */
$stmt = "";

/**
 *
 * @var mix Variable generica que se utiliza para guardar temporalmente los valores devueltos por una consulta
 */
$row = "";

/**
 *
 * @var string ulizada principalmente cuando queremos realizar una consulta dentro de otra
 */
$stmt2 = "";

/**
 *
 * @var mix Variable generica que se utiliza para guardar temporalmente los valores devueltos por una consulta
 */
$row2 = "";

/**
 *
 * @var string ulizada en una tercera capa de consultas sobre los resultados de otras
 */
$stmt3 = "";

/**
 *
 * @var mix Variable generica que se utiliza para guardar temporalmente los valores devueltos por una consulta
 */
$row3 = "";

/**
 *
 * @var string ulizada en una cuarta capa de consultas sobre los resultados de otras
 */
$stmt4 = "";

/**
 *
 * @var string variable en la que se define la coneccion a la base de datos de oracle
 */
$linkOracle = "";

/**
 *
 * @var string variable en la que se amacena el usuario de creacion
 */
$uid_c = "";

/**
 *
 * @var string variable en la que se amacena el usuario de modificacion
 */
$uid_m = "";

/**
 *
 * @var sysdate variable en la que se define la fecha de modificacion
 */
$fecha_m = "";

/**
 *
 * @var bool Variable generica que se utiliza en caso de la modificacion de datos del abm
 */
$modificar = "";

/**
 *
 * @var string variable generica en la que se amacenan los datos que se mostraran en los select dinamicos
 */
$opcionez = "";

/**
 * ******************************************************************************
 * VARIABLES PROPIAS DEL SISTEMA DE CUENTAS WEB *
 * ******************************************************************************
 */

/**
 * Usada en la definicion de las clases de los <tr>
 */
$claseLinea = "";

/**
 * Identifica si eliminar o no los puntos de acceso
 */
$eliminarPuntoAcceso = "";

/**
 * ******************************************************************************
 * VARIABLES PROPIAS DEL SISTEMA DE REQUERIMIENTOS *
 * ******************************************************************************
 */

/**
 *
 * @var array $sqlWhere array con booleanos para el armado de sql's parametrizados
 */
$sqlWhere['detalleReq'] = 0;
$sqlWhere['derivadoReq'] = 0;
$sqlWhere['usuarioReq'] = 0;
$sqlWhere['prioridadReq'] = 0;
$sqlWhere['fechaEntregaReq'] = 0;
$sqlWhere['categoriaReq'] = 0;
$sqlWhere['fechaSolisitudReq'] = 0;
$sqlWhere['tipoReq'] = 0;
$sqlWhere['estadoReq'] = 0;
$sqlWhere['soporteReq'] = 0;
$sqlWhere['reqAccion'] = 0;
$sqlWhere['nombreReq'] = 0;
$sqlWhere['AnoReq'] = 0;
?>