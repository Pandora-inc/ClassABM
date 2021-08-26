<?php
/**
 * Archivo de la clase.
 *
 * Archivo principal de la clase ABM
 *
 * @author Andres Carizza www.andrescarizza.com.ar
 * @author iberlot <@> ivanberlot@gmail.com
 * @name class_abm2.php
 */

/*
 * Querido programador:
 * Cuando escribi este codigo, solo Dios y yo sabiamos como funcionaba.
 * Ahora, Solo Dios lo sabe!!!
 * Asi que, si esta tratando de 'optimizar' esta rutina y fracasa (seguramente),
 * por favor, incremente el siguiente contador como una advertencia para el
 * siguiente colega:
 * totalHorasPerdidasAqui = 1055
 */
// require_once 'campos/Campos_bit.php';
// require_once 'campos/Campos_combo.php';
// require_once 'campos/Campos_dbCombo.php';
// require_once 'campos/Campos_moneda.php';
// require_once 'campos/Campos_numero.php';
// require_once 'campos/Campos_password.php';
// require_once 'campos/Campos_rownum.php';
// require_once 'campos/Campos_textarea.php';
// require_once 'campos/Campos_texto.php';
// require_once 'campos/Campos_upload.php';
// require_once 'campos/Campos_fecha.php';
// require_once 'campos/class_campo.php';
// require_once 'funciones.php';
// require_once 'class_db.php';
// require_once 'class_sitio.php';
// require_once 'class_paginado.php';
// require_once 'class_orderby.php';
use Campos\Campos_bit;
use Campos\Campos_combo;
use Campos\Campos_dbCombo;
use Campos\Campos_fecha;
use Campos\Campos_moneda;
use Campos\Campos_numero;
use Campos\Campos_password;
use Campos\Campos_rownum;
use Campos\Campos_textarea;
use Campos\Campos_texto;
use Campos\Campos_upload;

require_once __DIR__ . '/autoload.php';

/*
 * Clase que genera automaticamente un listado y los formularios que modifican o agregan datos en una tabla de BD.
 * @uses class_paginado.php, class_orderby.php, class_db.php
 * @author Andres Carizza www.andrescarizza.com.ar
 * @author iberlot <@> ivanberlot@gmail.com
 * @version 3.95
 * (A partir de la version 3.0 cambia la forma de aplicar los estilos css)
 * (A partir de la version 3.4 cambia de usar MooTools a usar JQuery)
 * (A partir de la version 3.5.8 cambia a UTF-8)
 * (A partir de la version 3.9 Se actualizaron las funciones obsoletas)
 * (A partir de la version 3.9.4 Se modifico el datepicker para que sea mas facil elegir el mes y el ano)
 * (A partir de la version 3.9.5 Se corrigio la implementacion de joins para que en caso de haber mas de uno de la misma tabla solo la incluyera una vez.
 * Datos para array de campos: (ver ejemplo de uso mas abajo) Se recomienda ver la documetacion de la clase class_campo, ya que apartir de la version 4 se remplara todas las llamadas a los atributos de los campos por el uso de esta.
 * [campoTexto]
 * [centrarColumna]
 * [customEvalListado]
 * [incluirOpcionVacia]
 * [joinCondition]
 * [joinTable]
 * [noEditar]
 * [noNuevo]
 * [noOrdenar]
 * [separador]
 * [sqlQuery]
 * adicionalesSelect = Agrega algo al final de la consulta Ejemplo: " AND User = 2" (aplicable siempre y cuando no sea un select custom)
 *
 * Ejemplo de uso:
 * $abm = new class_abm();
 * $abm->tabla = "usuarios";
 * $abm->registros_por_pagina = 40;
 * $abm->textoTituloFormularioAgregar = "Agregar usuario";
 * $abm->textoTituloFormularioEdicion = "Editar usuario";
 * $abm->adicionalesSelect = " AND gastos IS NOT NULL "; tiene que tener el espacio al princiio
 * $abm->campoId[0] = "USUARIO";
 * $abm->campoId[1] = "ACTIVO";
 * $abm->campos = array(
 * array (
 * 'campo' => 'ROWNUM',
 * 'tipo' => 'rownum',
 * 'exportar' => true,
 * 'titulo' => 'Nro',
 * 'noEditar' => true,
 * 'noNuevo' => true
 * ),
 * array(
 * 'campo' => "usuario",
 * 'tipo' => "texto",
 * 'titulo' => "Usuario",
 * 'maxLen' => 30,
 * "customPrintListado" => "<a href='ver_usuario.php?id={id}' target='_blank' title='Ver usuario'>%s</a>",
 * "buscar" => true
 * ),
 * array(
 * 'campo' => "pass",
 * 'tipo' => "texto",
 * 'titulo' => "Contrase�a",
 * 'maxLen' => 30
 * ),
 * array(
 * 'campo' => "activo",
 * 'tipo' => "bit",
 * 'titulo' => "Activo",
 * "centrarColumna" => true,
 * "valorPredefinido" => "0"
 * ),
 * array(
 * 'campo' => "nivel",
 * 'tipo' => "combo",
 * 'titulo' => "Admin",
 * 'datos' => array("admin"=>"Si", ""=>"No"),
 * 'customEvalListado' => 'echo "<td align=\"center\">"; if($valor=="admin"){echo "Si";}else{echo "No";}; echo "</td>";'
 * ),
 * array(
 * 'campo' => "paisId",
 * 'tipo' => "dbCombo",
 * "sqlQuery" => "SELECT * FROM paises ORDER BY pais",
 * "campoValor" => "id",
 * "campoTexto" => "pais",
 * "joinTable" => "paises",
 * "joinCondition" => "LEFT",
 * 'titulo' => "Pais",
 * "incluirOpcionVacia" => true
 * "mostrarValor" => true // Muestra el valor del campo en el combo
 * "textoMayuscula" // Pone el texto del combo en mayusculas
 * 'valorPredefinido' => $_REQUEST['idAutor']
 * ),
 * array(
 * 'campo' => "email",
 * 'tipo' => "textarea",
 * 'titulo' => "Email",
 * 'maxLen' => 70,
 * 'noOrdenar' => true
 * ),
 * array(
 * 'separador' => "Un separador"
 * ),
 * array(
 * 'campo' => "donde",
 * 'tipo' => "combo",
 * 'titulo' => "Donde nos conociste?",
 * 'tituloListado' => "Donde",
 * 'datos' => array("google"=>"Por Google", "amigo"=>"Por un amigo", "publicidad"=>"Por una publicidad", "otro"=>"Otro"),
 * 'colorearValores' => array('google'=>'#4990D7', 'amigo'=>'#EA91EA'),
 * ),
 * array (
 * 'campo' => 'GASTOS',
 * 'tipo' => 'moneda',
 * 'exportar' => true,
 * 'titulo' => 'GASTOS',
 * 'noEditar' => false,
 * 'buscar' => true
 * ),
 * array (
 * 'campo' => 'VALOR',
 * 'tipo' => 'numero',
 * 'exportar' => true,
 * 'titulo' => 'VALOR',
 * 'noEditar' => false,
 * 'buscar' => true
 * ),
 * array(
 * 'campo' => "ultimoLogin",
 * 'tipo' => "texto",
 * 'titulo' => "Ultimo login",
 * "noEditar" => true,
 * "noListar" => true,
 * "noNuevo" => true
 * )
 * );
 * $abm->generarAbm("", "Administrar usuarios");
 * Ejemplo para incluir una columna adicional personalizada en el listado:
 * array('campo' => "",
 * 'tipo' => "",
 * 'titulo' => "Fotos",
 * 'customEvalListado' => 'echo "<td align=\"center\"><a href=\"admin_productos_fotos.php?productoId=$fila[ID]\"><img src=\"img/camara.png\" border=\"0\" /></a></td>";'
 * )
 * noMostrar = No muestra el dato en el listado (lo unico que hace es esconderlo por mecio de css con la propiedad display none
 * @example noMostrar
 * array(
 * 'campo' => 'ultimoLogin',
 * 'tipo' => 'texto',
 * 'titulo' => 'Ultimo login',
 * 'noMostrar' => true
 * )
 * @example customPrintListado
 * customPrintListado = sprintf para imprimir en el listado. %s ser&aacute; el valor del campo y {id} se remplaza por el Id del registro definido para la tabla. Ej: <a href='ver_usuario.php?id={id}' target='_blank' title='Ver usuario'>%s</a>
 * incluirCampo = Campo a remplazar en la formula de customPrintListado. Cuando haya mas de un campo a incluir deberan separarse con coma. No hay que olvidar encerrar los campor con llaves para que el sistema los reconosca.
 * array(
 * 'campo' => '',
 * 'tipo' => '',
 * 'exportar' => true,
 * 'titulo' => 'Opciones',
 * 'incluirCampo' => 'INICIO, FINAL',
 * 'centrarColumna' => true,
 * 'customPrintListado" => '<a href=# onclick="consul({INICIO},{FINAL})" ><img src="/images/icons16/edit_button.png"></a>',
 * 'buscar' => false
 * )
 */

// FIXME - Cuando se realiza una busqueda hay que recetear el paginado.

// FIXME - Hay que modificar el sistema de consulta del paginado para que sea mas eficiente.

/**
 * Clase que genera automaticamente un listado y los formularios que modifican o agregan datos en una tabla de BD.
 *
 * @name class_abm
 *
 * @uses class_paginado.php, class_orderby.php, class_db.php
 * @author Andres Carizza www.andrescarizza.com.ar
 * @author iberlot <@> ivanberlot@gmail.com
 *
 * @version 3.96
 * @since 3.0 cambia la forma de aplicar los estilos css.
 * @since 3.4 cambia de usar MooTools a usar JQuery.
 * @since 3.5.8 cambia a UTF-8.
 * @since 3.9 Se actualizaron las funciones obsoletas.
 * @since 3.9.4 Se modifico el datepicker para que sea mas facil elegir el mes y el ano.
 * @since 3.9.5 Se corrigio la implementacion de joins para que en caso de haber mas de uno de la misma tabla solo la incluyera una vez.
 * @since 3.9.6 Se corrigio el armado de la consulta del listado para que en el caso de ser del tipo fecha use la funcion to_char de la clase db para armar la consulta.
 */
class class_abm
{

    /*
     * *******************************************************
     * VARIABLES RELACIONADAS AL ABM *
     * *******************************************************
     */

    /**
     * Nombre de la tabla en la base de datos.
     * Va a ser la tabla prinsipal con la que se va a armar la cosulta.
     *
     * @var string
     * @example /web/html/classes/examples/amb_example.html abm->tabla = "Capitulo";
     */
    public $tabla;

    /**
     * Campo ID de la tabla.
     *
     * - Es el campo que se va a utilizar como indice del abm.
     * - Se registra como un array ya que hay casos donde se necesita mas de un campo para ser el indice.
     * - En esos casos realiza una concatenacion de ellos para realizar la consulta.
     *
     * @example a $abm->campoId = "idCapitulo";
     * @var string[]
     */
    public $campoId = array();

    /**
     * Permite editar el campo que corresponde al ID
     * Por lo general no permite editarlo porque suele ser autoincremental, por defecto ni se muestra en el abm pq al usuario no le interesa, pero se puede forzar que sea editable con este parametro
     *
     * @example a $abm->campoIdEsEditable = TRUE;
     * @var boolean
     */
    public $campoIdEsEditable = false;

    /**
     * Cantidad de registros que se van a ver por pagina.
     *
     * - El valor por defecto es 30.
     *
     * @example a $abm->registros_por_pagina = 50;
     * @var int
     */
    public $registros_por_pagina = 30;

    /**
     * Campo order by por defecto para los select.
     *
     * Es el nombre del campo por el cual se van a ordenar el listado en el primer momento.
     *
     * @example a $abm->orderByPorDefecto = "nrOrden";
     * @var string
     */
    public $orderByPorDefecto;

    /**
     * Directorio donde se guardan las imagenes.
     * - En el se van a ir a buscar las imagenes de los iconos y del sistema.
     * - No tiene relacion con los campos que contengan imagenes.
     * - El valor por defecto es '/img/'.
     *
     * @var string
     * @example a $abm->directorioImagenes = "/imagenes/";
     */
    public $directorioImagenes = '/img/';

    /**
     * Redireccionar a $redireccionarDespuesInsert despues de hacer un Insert.
     *
     * (si el ID de la tabla no fuera un numero usar %s)
     *
     * @var string
     * @example a $abm->redireccionarDespuesInsert = "renameFold.php?tipo=capitulo&accion=1&id=%d";
     */
    public $redireccionarDespuesInsert;

    /**
     * Redireccionar a $redireccionarDespuesUpdate despues de hacer un Update.
     *
     * (si el ID de la tabla no fuera un numero usar %s)
     *
     * @var string
     * @example a $abm->redireccionarDespuesUpdate = "renameFold.php?tipo=capitulo&accion=2&id=%d";
     */
    public $redireccionarDespuesUpdate;

    /**
     * Redireccionar a $redireccionarDespuesDelete despues de hacer un Delete.
     *
     * (si el ID de la tabla no fuera un numero usar %s)
     *
     * @var string
     * @example a $abm->redireccionarDespuesDelete = "renameFold.php?tipo=capitulo&accion=3&id=%d";
     */
    public $redireccionarDespuesDelete;

    /**
     * JOIN personalizado para agregar a la consulta
     *
     * @var string
     * @example a $abm->customJoin = 'LEFT JOIN pais ON persona.idpais=pais.idpais';
     */
    public $customJoin = "";

    /**
     * Valor del atributo method del formulario
     *
     * Aunque hasta la version 3.9.5 no hay comprobaciones deberia poder tomar unicamente los valores GET o POST.
     *
     * @var String
     * @example a $abm->formMethod = 'GET';
     */
    public $formMethod = "POST";

    /**
     * Agrega el atributo autofocus al primer campo del formulario de alta o modificacion
     *
     * @var boolean
     * @example a $abm->autofocus = FALSE;
     */
    public $autofocus = true;

    /**
     * Para poder agregar codigo HTML en la botonera del listado, antes de los iconos "Exportar" y "Agregar"
     *
     * @var string
     * @example a $abm->agregarABotoneraListado = '&lt;a href="contact/" class="cloud-contact-sales-button button" track-type="sales" track-name="contact" track-metadata-position="nav">Contactar con Ventas&lt;/a>';
     */
    public $agregarABotoneraListado;

    /**
     * Metodo que usa para hacer los redirect "header" (si no se envio contenido antes) o "html" de lo contrario
     *
     * @var string
     * @example a $abm->metodoRedirect = 'header';
     */
    public $metodoRedirect = "html";

    /**
     * Texto que muestra el boton submit del formulario Nuevo
     *
     * @var string
     * @example a $abm->textoBotonSubmitNuevo = 'Enviar';
     */
    public $textoBotonSubmitNuevo = "Guardar";

    /**
     * Texto que muestra el boton submit del formulario Modificar
     *
     * @var string
     * @example a $abm->textoBotonSubmitModificar = 'Enviar';
     */
    public $textoBotonSubmitModificar = "Guardar";

    /**
     * Texto que muestra el boton de Cancelar
     *
     * @var string
     * @example a $abm->textoBotonCancelar = 'Anular';
     */
    public $textoBotonCancelar = "Cancelar";

    /**
     * Habilitacion del boton Extra.
     * - Este boton va a ir junto a los botones de Enviar y cancelar de los formularios de Alta y Modificacion.
     *
     * @var boolean
     * @example a $abm->extraBtn = TRUE;
     */
    public $extraBtn = 'false';

    /**
     * Texto del titulo del boton Extra.
     * Esto es el texto que suele aparecer sobre los botones cuando se pasa el puntero por encima.
     * - Este boton va a ir junto a los botones de Enviar y cancelar de los formularios de Alta y Modificacion.
     * - Es necesario que el valor de $extraBtn sea verdadero para que este funcione.
     *
     * @var string
     * @example a $abm->textoBotonExtraTitulo = 'Asociar';
     */
    public $textoBotonExtraTitulo = "";

    /**
     * Texto que muestra el boton Extra
     * - Este boton va a ir junto a los botones de Enviar y cancelar de los formularios de Alta y Modificacion.
     * - Es necesario que el valor de $extraBtn sea verdadero para que este funcione.
     * - El valor por defecto es "Extra"
     *
     * @var string
     * @example a $abm->textoBotonExtra = 'Asociar';
     */
    public $textoBotonExtra = "Extra";

    /**
     * Adicionales al boton Extra, aca va a ir cualquier atributo extra que se le quiera agregar a la etiqueta button.
     * - Este boton va a ir junto a los botones de Enviar y cancelar de los formularios de Alta y Modificacion.
     * - Es necesario que el valor de $extraBtn sea verdadero para que este funcione.
     *
     * @var string
     * @example a $abm->adicionalesExtra = 'onclick="window.location="abmCapitulos.php?abm_extra=1&r=40&idLibro=2""';
     *
     */
    public $adicionalesExtra;

    /**
     * Texto que muestra cuando la base de datos retorna registro duplicado al hacer un insert.
     * Si se deja el string vacio entonces muestra el mensaje de error del motor de bd.
     * - El valor por defecto es "Uno de los datos est&aacute; duplicado y no puede guardarse en la base de datos";
     *
     * @var string
     * @example a $abm->textoRegistroDuplicado = 'registro duplicado';
     */
    public $textoRegistroDuplicado = "Uno de los datos est&aacute; duplicado y no puede guardarse en la base de datos";

    /**
     * Para asignar una accion diferente al boton de Cancelar del formulario de Edicion y Nuevo.
     *
     * Este texto va a ir dentro de la etiqueta onclick.
     *
     * @var string
     * @example a $abm->cancelarOnClickJS = 'myFunction()';
     */
    public $cancelarOnClickJS = "";

    /**
     * Texto para mostrar en caso de que no exista el registro
     *
     * @var string $textoElRegistroNoExiste
     */
    public $textoElRegistroNoExiste = "El registro no existe. <A HREF='javascript:history.back()'>[Volver]</A>";

    /**
     * Texto para mostrar en caso de que no haya registros para mostrar
     *
     * @var string
     */
    public $textoNoHayRegistros = "No hay registros para mostrar";

    /**
     * Texto para mostrar en caso de que la busqueda no devuelva ningun valor
     *
     * @var string $textoNoHayRegistrosBuscando
     */
    public $textoNoHayRegistrosBuscando = "No hay resultados para la b&uacute;squeda";

    /**
     * Titulo del formulario de edicion *
     */
    public $textoTituloFormularioEdicion;

    /**
     * Titulo del formulario de agregar *
     */
    public $textoTituloFormularioAgregar;

    /**
     * Agregado al final del JOIN para espesificar un extra en el WHERE de la consulta
     *
     * @var string
     */
    public $customCompare = "";

    /**
     * Titulo del formulario de busqueda *
     */
    public $textoTituloFormularioBuscar = "B&uacute;squeda";

    /**
     * Muestra los encabezados de las columnas en el listado *
     */
    public $mostrarEncabezadosListado = true;

    /**
     * Muestra el total de registros al final del listado *
     */
    public $mostrarTotalRegistros = true;

    /**
     * Pagina a donde se redireccionan los formularios.
     * No setear a menos que seapas lo que estas haciendo.
     *
     * @var string
     */
    public $formAction = "";

    /**
     * para agregar atributos al tag *
     */
    public $adicionalesForm;

    /**
     * para agregar atributos al tag *
     */
    public $adicionalesTable;

    /**
     * para agregar atributos al tag *
     */
    public $adicionalesTableListado;

    /**
     * para agregar atributos al tag *
     */
    public $adicionalesSubmit;

    /**
     * Condicion adicional para agreegar en el WHERE
     *
     * @example AND userId=2 *busquedaTotal
     */
    public $adicionalesWhereUpdate;

    /**
     * Condicion adicional para agreegar en el WHERE solo para el DELETE
     *
     * @example AND userId=2 *
     */
    public $adicionalesWhereDelete;

    /**
     * Condicion adicional para agreegar en el WHERE solo para el INSERT
     *
     * @example , userId=2 *
     */
    public $adicionalesInsert;

    /**
     * Condicion adicional para agreegar en el WHERE solo para el SELECT
     *
     * aplicable siempre y cuando no sea un select custom
     *
     * @example AND userId=2
     */
    public $adicionalesSelect = "";

    /**
     * Esto es ultil cuando se necesita traer un campo para usar durante el listado y no esta como visible
     *
     * @example , campo2, campo3, campo4
     */
    public $adicionalesCamposSelect;

    /**
     * Genera el query del listado usando ese string.
     * Esto es util por ejemplo cuando se necesitan hacer sub select *
     *
     * @var string
     * @example SELECT $sqlCamposSelect FROM...
     */
    public $sqlCamposSelect;

    /**
     * Funcion que se ejecuta antes al borrar un registro.
     * (donde borrarUsuario es una funcion que debe recibir los parametros $id y $tabla) *
     *
     * @example callbackFuncDelete = "borrarUsuario"
     */
    public $callbackFuncDelete;

    /**
     * Funcion que se ejecuta despues al actualizar un registro.
     * (donde actualizarDatosUsuario es una funcion que debe recibir los parametros $id, $tabla, $fueAfectado)
     *
     * @example callbackFuncUpdate = "actualizarDatosUsuario"
     */
    public $callbackFuncUpdate;

    /**
     * Funcion que se ejecuta despues de insertar un registro.
     * (donde crearCarpetaUsuario es una funcion que debe recibir los parametros $id y $tabla)
     *
     * @example callbackFuncInsert = "crearCarpetaUsuario"
     */
    public $callbackFuncInsert;

    /**
     * Cantidad de filas total que retorno el query de listado.
     * NOTA: Tiene que haberse llamado antes la funcion que genera el ABM. *
     */
    public $totalFilas;

    /**
     * Para ejecutar PHP en cada tag <TR {aca}>.
     * Esta disponible el array $fila.
     *
     * @example if($fila["nivel"]=="admin")echo "style='background:red'"; *
     */
    public $evalEnTagTR;

    /**
     * texto del confirm() antes de borrar (escapar las comillas dobles si se usan) *
     */
    public $textoPreguntarBorrar = "Confirma que desea borrar el elemento seleccionado?";

    /**
     * Muestra el boton Editar en el listado
     */
    public $mostrarEditar = true;

    /**
     * Muestra el boton Nuevo en el listado
     */
    public $mostrarNuevo = true;

    /**
     * Muestra el boton Borrar en el listado
     */
    public $mostrarBorrar = true;

    /**
     * Muestra los datos del listado
     */
    public $mostrarListado = true;

    /**
     * El titulo de la columna Editar del listado *
     */
    public $textoEditarListado = "Editar";

    /**
     * El titulo de la columna Borrar del listado *
     */
    public $textoBorrarListado = "Borrar";

    /**
     * Texto del boton submit del formulario de busqueda *
     */
    public $textoBuscar = "Buscar";

    /**
     * Texto del boton limpiar del formulario de busqueda *
     */
    public $textoLimpiar = "Limpiar";

    /**
     * La palabra (plural) que pone al lado del total del registros en el pie de la tabla del listado *
     */
    public $textoStrRegistros = "registros";

    /**
     * La palabra (singular) que pone al lado del total del registros en el pie de la tabla del listado *
     */
    public $textoStrRegistro = "registro";

    /**
     * El palabra "Total" que pone al lado del total del registros en el pie de la tabla del listado *
     */
    public $textoStrTotal = "Total";

    /**
     * Texto para el title de los links de los numeros de pagina *
     */
    public $textoStrIrA = "Ir a la p&aacute;gina";

    /**
     * Cantidad de columnas de inputs en el formulario de busqueda *
     */
    public $columnasFormBuscar = 1;

    /**
     * Icono editar del listado.
     */
    public $iconoEditar = "<a onclick=\"%s\"><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";

    // public $iconoEditar = "<a href=\"%s\"><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
    // public $iconoEditar = "<a href=\"%s\"><img src='/img/editar.gif' title='Editar' alt='Editar' border='0' /></a>";

    /**
     * Icono borrar del listado.
     */
    public $iconoBorrar = "<a href=\"javascript:void(0)\" onclick=\"%s\"><i class='fa fa-times' aria-hidden='true'></i></a>";

    // public $iconoBorrar = "<a href=\"javascript:void(0)\" onclick=\"%s\"><img src='/img/eliminar.gif' title='Eliminar' alt='Eliminar' border='0' /></a>";

    /**
     * Icono de Agregar para crear un registro nuevo.
     * Ademas del icono modifica la accion a realizar
     *
     * @example $abm->iconoAgregar = '<a href="#" onclick="agregarConcepto()"><img src="/img/add.png"></img></a>';
     *          Esto agregaria el icono add.png en lugar del boton y ejecutaria agregarConcepto() en vez de redireccionar al link de nuevo.
     */
    // public $iconoAgregar = "<input type='button' class='btnAgregar' value='Agregar' title='Atajo: ALT+A' accesskey='a' onclick='window.location=\"%s\"'/>";
    public $iconoAgregar = "<input type='button' class='btnAgregar' value='Agregar' title='Atajo: ALT+A' accesskey='a' onclick=\"%s\"/>";

    /**
     * Icono de exportar a Excel.
     */
    // public $iconoExportarExcel = "<input type='button' class='btnExcel' title='Exportar a Excel' onclick='javascript:window.open(\"%s\", \"_blank\")'/>";
    public $iconoExportarExcel = "<a href=\"javascript:void(0)\" onclick=\"%s\" title='Exportar a Excel'><i class='fa fa-file-excel-o' aria-hidden='true'></i></a> &nbsp;";

    // public $iconoExportarExcel = "<input type='button' class='btnExcel' title='Exportar a Excel' onclick='window.location=\"%s\"'/>";

    /**
     * Icono de exportar a CSV.
     */
    // public $iconoExportarCsv = "<input type='button' class='btnCsv' title='Exportar a CSV' onclick='javascript:window.open(\"%s\", \"_blank\")'/>";
    public $iconoExportarCsv = "<a href=\"javascript:void(0)\" onclick=\"%s\" title='Exportar a CSV'><i class='fa fa-file-text-o' aria-hidden='true'></i></a> &nbsp;";

    /**
     * Direccion a la que se tiene que dirigir en caso de que el formulario para agregar un nuevo registro no sea el standar
     */
    public $direNuevo = "";

    /**
     * Texto sprintf para el mensaje de campo requerido *
     */
    public $textoCampoRequerido = "El campo \"%s\" es requerido.";

    /**
     * Lo que agrega al lado del nombre del campo para indicar que es requerido *
     */
    public $indicadorDeCampoRequerido = "<div class='indRequerido'></div>";

    /**
     * Aparece despues del nombre del campo en los formularios de Alta y Modificacion.
     * Ej: ":" *
     */
    public $separadorNombreCampo = "";

    /**
     * Coleccion de links necesarios para que el datepiker funcione correctamente
     */
    public $jslinksCampoFecha = '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> <script src="//code.jquery.com/jquery-1.10.2.js"></script> <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>';

    /**
     * Coleccion de links necesarios para que el Select Con Busqueda funcione correctamente
     */
    public $jslinksSelectConBusqueda = '
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>';

    /**
     * Codigo JS para poner en window.onload para cada uno de los campos de select con busqueda *
     */
    public $jsSelectConBusqueda = '
	<script>
		$(document).ready(function() { $("#%IDCAMPO%").select2(); });
	</script>
	';

    /**
     * Direccion donde tiene que ir a buscar el archivo encargado de procesar los selects dinamicos
     */
    public $direDinamic = 'dinamic/selectDinamico.php';

    /**
     * Codigo JS para poner en window.onload para cada uno de los campos de select dinamicos *
     */
    public $jsIniciadorSelectDinamico = '
    <script>
			$(document).ready(function(){
				$("#%CAMPOPADRE%").change(function(){
					$.ajax({
						url:"%DIREDINAMIC%",
						type: "POST",
						data:"%WHERE%,
						success: function(opciones){
							$("#%CAMPO%").html(opciones);
						}
					})

			console.log("change");
				});
				$(window).load(function(){
					$.ajax({
						url:"%DIREDINAMIC%",
						type: "POST",
						data:"%WHEREINI%",
						success: function(opciones){
							$("#%CAMPO%").html(opciones);
						}
					})
				});
			});
		</script>
    ';

    /**
     * Adicional para el atributo class de los input para el chequeo de los campos requeridos *
     */
    public $chequeoInputRequerido = 'validate[required]';

    /**
     * Formato de fecha a utilizar en los campos tipo fecha del listado.
     * Usa la funcion date() de PHP *
     */
    public $formatoFechaListado = "DD/MM/YYYY";

    // public $formatoFechaListado = "d/m/Y";

    /**
     * Indica si colorea las filas del listado cuando se pasa por arriba con el puntero *
     */
    public $colorearFilas = true;

    /**
     * Color de la fila del listado cuando se para el puntero por arriba (ver $colorearFilas) *
     */
    public $colorearFilasColor = '#f5fcff';

    /**
     * Define si se va a colorear la fila con un degrade
     *
     * @var boolean
     */
    public $colorearFilasDegrade = true;

    /**
     * Segundo color del degrade de la fila del listado cuando se para el puntero por arriba (ver $colorearFilas) *
     */
    public $colorearFilasColorSecundario = '#d5eefb';

    /**
     * Nombre que le pone al archivo que exporta (no incluir la extension) *
     */
    public $exportar_nombreArchivo = "exportar";

    /**
     * El caracter separador de campos cuando exporta CSV *
     */
    public $exportar_csv_separadorCampos = ",";

    /**
     * El caracter delimitador de campos cuando exporta CSV *
     */
    public $exportar_csv_delimitadorCampos = "\"";

    /**
     * Usar este query sql para la funcion de exportar *
     */
    public $exportar_sql;

    /**
     * Los formatos en los que se puede exportar, o sea los botones que muestra (siempre y cuando haya campos con exportar=true) *
     */
    public $exportar_formatosPermitidos = array(
        'excel',
        'csv'
    );

    /**
     * El JS que se agrega cuando un campo es requerido *
     */
    private $jsIniciadorChequeoForm = '
        <script type="text/javascript">
        $(function(){
          $("#formularioAbm").validationEngine({promptPosition:"topLeft"});
        });
        </script>
    ';

    /**
     * El JS que se agrega cuando un campo es requerido *
     */
    private $jsHints = '
        <script type="text/javascript">
        $( document ).tooltip({
            position: {
                my: "center bottom-20",
                at: "center top",
                using: function( position, feedback ) {
                    $( this ).css( position );
                    $( "<div>" )
                        .addClass( "arrow" )
                        .addClass( feedback.vertical )
                        .addClass( feedback.horizontal )
                        .appendTo( this );
                }
            }
        });
        </script>
    ';

    /**
     * codigo Js a insertar en los form de alta y modificacion
     */
    private $jsMonedaInput = '
        <script type="text/javascript">
			(function($) {
				  $.fn.currencyInput = function() {
				    this.each(function() {
				      var wrapper = $("<div class=\'currency-input\' />");
				      $(this).wrap(wrapper);
				      $(this).before("<span class=\'currency-symbol\'>$</span>");
				      $(this).change(function() {
				        var min = parseFloat($(this).attr("min"));
				        var max = parseFloat($(this).attr("max"));
				        var value = this.valueAsNumber;
				        if(value < min)
				          value = min;
				        else if(value > max)
				          value = max;
				        $(this).val(value.toFixed(2));
				      });
				    });
				  };
				})(jQuery);

				$(document).ready(function() {
				  $(\'input.currency\').currencyInput();
				});
        </script>';

    /**
     * Definicion de la funcion que se encarga de abrir el dialod de edicion
     *
     * @var string
     */
    // private $jsUpdateForm = '<script type="text/javascript">
    // function f_editar(direccion)
    // {
    // $.ajax({
    // url: direccion,
    // type: "POST",
    // data:"",
    // dataType: \'text\',
    // success: function(tablaPerson)
    // {
    // vex.dialog.open(
    // {
    // message: \'\',
    // input: [tablaPerson].join(\'\')
    // })
    // }
    // });
    // }
    // </script>';
    // url: "verEnvios.php",
    // data: {'do':'show', 'doc': tipo+nro,'fac':facultad,'sede':sede, 'carr':carrera},

    // $.ajax({
    // type: "POST",
    // url: direccion,
    // data: datos,
    // success: function (data) {
    // vex.dialog.open({
    // message: "Haga clic en aceptar para confirmar la modificacion en la cuenta corriente del alumno.",
    // input: [data].join(),
    // buttons: []
    // });

    // }
    // })
    private $jsUpdateForm = '<script type="text/javascript">
	function f_editar(direccion){
	$.ajax({
	url: direccion,
	type: "POST",
	dataType: "html",
	success: function (data) {
		vex.dialog.confirm({
		    message: \'\',
            input: [data] .join(\'\'),
		    callback: function (value) {
		        if (value) {
		 			document.getElementById(\'formularioAbm\').submit();
		            console.log(\'Successfully destroyed the planet.\');
		        } else {
		            console.log(\'Chicken.\');
					location.reload();
		        }
			}
		});

		abilitarCKE();

		}
	})
}
		</script>';

    // className: 'vex-dialog-button-primary', text: 'Checkout', click: function
    // vex.dialog.buttons.YES.text = \'Okiedokie\';
    // vex.dialog.buttons.NO.text = \'Aahw hell no\';
    private $jsAltaForm = '<script type="text/javascript">
	function f_alta(direccion){
	$.ajax({
	url: direccion,
	type: "POST",
	dataType: "html",
	success: function (data) {
		vex.dialog.confirm({
		    message: \'\',
            input: [data] .join(\'\'),
		    callback: function (value) {
		        if (value) {
		 			document.getElementById(\'formularioAbm\').submit();
		            console.log(\'Successfully destroyed the planet.\');
		        } else {
		            console.log(\'Chicken.\');
					location.reload();
		        }
		    }
		});

		abilitarCKE();
		}
	})
}
		</script>';

    // // $html .= " <div class ='divBtnCancelar'>
    // <input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c'
    // value='$this->textoBotonCancelar' onclick=\"" . ($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'") . "\"/></div> ";
    // // $html .= " <div class='divBtnAceptar'>
    // <input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G'
    // value='$this->textoBotonSubmitNuevo' $this->adicionalesSubmit /></div>";

    /**
     * Establece si los formularios del abm se separaran en solapas o no
     *
     * @var boolean
     */
    public $formularioSolapa = false;

    /**
     * En caso de dividirse el formulario en solapas cuantas deberian ser
     *
     * @var int
     */
    public $cantidadSolapa = 0;

    /**
     * El texto identificado que llevara cada solapa.
     * Hay que recordar que el index del array sera siempre uno menos que el id de la solapa.
     *
     * @var array
     */
    public $tituloSolapa = array();

    /**
     * establece si agregar o no un campo de busqueda general
     * para poder buscar un texto x en todos los campos de la consulta.
     *
     * @var boolean
     */
    public $busquedaTotal = false;

    /**
     * Cantidad maxima de registros sobre los que consultar.
     *
     * @var integer
     */
    public $limitarCantidad = 2000;

    /*
     * *******************************************************
     * VARIABLES RELACIONADAS AL CAMPO *
     * *******************************************************
     */

    /**
     * Los campos de la BD y preferencias para cada uno.
     * (Ver el ejemplo de la class)
     */
    public $campos;

    /**
     * Texto por defecto que se usa cuando el tipo de campo es "bit".
     *
     * @var string
     */
    public $textoBitTrue = "SI";

    /**
     * Texto por defecto que se usa cuando el tipo de campo es "bit"
     *
     * @var string
     */
    public $textoBitFalse = "NO";

    /**
     * Nombre del link en caso de que sea requerido para las conecciones
     *
     * @var string
     */
    public $dbLink = "";

    /**
     * Direccion donde se encuentran las hojas de estilos vasicas para el sistema
     *
     * @var string
     */
    private $estilosBasicos = "<link rel='stylesheet' href='%dirname%/font-awesome/css/font-awesome.min.css'>
<link rel='stylesheet' type='text/css' href='%dirname%/cssABM/abm.css' />
<link rel='stylesheet' href='%dirname%/cssABM/css-vex/vex.css' />
<link rel='stylesheet' href='%dirname%/cssABM/css-vex/vex-theme-os.css' />
";

    private $jsBasicos = "
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
<script src='%dirname%/jsABM/vex.combined.min.js'></script>
<script>vex.defaultOptions.className = 'vex-theme-os'</script>
<script src='%dirname%/jsABM/ckeditor5/ckeditor.js'></script>";

    // <script src='https://cdn.ckeditor.com/ckeditor5/24.0.0/classic/ckeditor.js'></script>";

    /**
     * Array de objetos campo
     *
     * @var object
     */
    private $campo;

    /**
     * Objeto de coneccion a la base de datos
     *
     * @var object Class_db
     */
    private $db;

    /**
     * Determina si hay que agregar o no el formulario de busqueda.
     *
     * @var boolean
     */
    private $agregarFormBuscar = false;

    /**
     * Paginacion del formulario
     *
     * @var class_paginado
     */
    private $paginado;

    /*
     * ************************************************************************
     * Aca empiezan las funciones de la clase
     * ************************************************************************
     */

    /*
     * ************************************************************************
     * Aca empiezan las funciones de la clase
     * ************************************************************************
     */
    /**
     * Constructos de la clase.
     *
     * @param class_db $db
     *            objeto de coneccion a la base de datos, el mismo es opcional.
     */
    public function __construct(&$db = null)
    {
        if (!isset($db) or empty($db) or $db == null) {
            if (Sitios::getDb() == null) {
                if (!$this->db = Sitios::openConnection()) {
                    global $db;

                    if (isset($db) and !empty($db) and $db != null) {
                        $this->db = &$db;
                    }
                }
            } else {
                $this->db = Sitios::getDb();
            }
        } else {
            $this->db = &$db;
        }

        $this->paginado = new class_paginado();
        $this->paginado->registros_por_pagina = $this->registros_por_pagina;
        $this->paginado->str_registros = $this->textoStrRegistros;
        $this->paginado->str_registro = $this->textoStrRegistro;
        $this->paginado->str_total = $this->textoStrTotal;
        $this->paginado->str_ir_a = $this->textoStrIrA;
    }

    /**
     * Devuelve un string con el concat de los campos Id
     *
     * @param array $array
     *            --> array con todos los campos a utilizar para generar el id compuesto
     * @param string $tabla
     *            --> tabla que contendria el array compuesto
     * @return string $arrayId --> concatenacion de los campos del array
     */
    public function convertirIdMultiple($array, $tabla)
    {
        if ($this->db->getDbtype() == 'mysql') {

            $arrayId = "CONCAT (";

            foreach ($array as &$valor) {
                // print_r ("<br>" . $valor . "<br>");

                $arrayId .= $tabla . "." . $valor . ", ";
            }

            $arrayId = substr($arrayId, 0, -2);

            $arrayId .= ") AS ID";

            return $arrayId;
        } elseif ($this->db->getDbtype() == 'oracle') {

            $tot = count($array);
            if ($tot < 3) {
                $arrayId = "CONCAT (";

                foreach ($array as &$valor) {
                    $arrayId .= $tabla . "." . $valor . ", ";
                }
            } else {
                $arrayId = " (";
                foreach ($array as &$valor) {
                    $arrayId .= $tabla . "." . $valor . "||";
                }
            }
            $arrayId = substr($arrayId, 0, -2);

            $arrayId .= ") AS ID";

            return $arrayId;
        } elseif ($this->db->getDbtype() == 'mssql') {
            $arrayId = "(";

            foreach ($array as &$valor) {
                $arrayId .= "convert(varchar, " . $tabla . "." . $valor . ")+";
            }

            $arrayId = substr($arrayId, 0, -1);

            $arrayId .= ") AS ID";

            return $arrayId;
        }
    }

    /**
     * Devuelve un string con los campos Id de forma individual
     *
     * @author iberlot <@> ivanberlot@gmail.com
     * @name convertirIdMultipleSelect
     *
     * @param array $array
     *            --> array con todos los campos a utilizar para generar el id compuesto
     * @param string $tabla
     *            --> tabla que contendria el array compuesto
     * @return string $camp --> todos los campos de un id compuesto
     */
    public function convertirIdMultipleSelect($array, $tabla)
    {
        $camp = "";

        if ($this->db->getDbtype() == 'mysql') {
            foreach ($array as &$valor) {
                $camp .= ", " . $tabla . "." . $valor;
            }

            return $camp;
        } elseif ($this->db->getDbtype() == 'oracle') {
            // $tot = count ($array);

            foreach ($array as &$valor) {
                $camp .= ", " . $tabla . "." . $valor;
            }

            return $camp;
        } elseif ($this->db->getDbtype() == 'mssql') {

            foreach ($array as &$valor) {
                $camp .= ", " . $tabla . "." . $valor;
            }

            return $camp;
        }
    }

    /**
     * Para saber que formulario esta mostrando (listado, alta, editar, dbDelete, dbUpdate, dbInsert), esto es util cuando queremos hacer diferentes en la pagina segun el estado.
     */
    public function getEstadoActual()
    {
        if (isset($_GET['abm_nuevo'])) {
            return "alta";
        } elseif (isset($_GET['abm_editar'])) {
            return "editar";
        } elseif (isset($_GET['abm_borrar'])) {
            return "dbDelete";
        } elseif (isset($_GET['abm_exportar'])) {
            return "exportar";
        } elseif ($this->formularioEnviado()) {
            if (isset($_GET['abm_modif'])) {
                return "dbUpdate";
            } elseif (isset($_GET['abm_alta'])) {
                return "dbInsert";
            }
        } else {
            return "listado";
        }
    }

    /**
     * Funcion encargada de generar el formulario de alta.
     *
     * @param string $titulo
     *            - Titulo a mostrar en el formulario de alta.
     */
    private function generarFormAlta($titulo = "")
    {
        $html = "";
        $get = $_GET;
        unset($get['abm_nuevo']);
        $qsamb = http_build_query($get);
        $fila = "";

        if ($qsamb != "") {
            $qsamb = "&" . $qsamb;
        }

        // agregar script para inicar FormCheck ?
        foreach ($this->campo as &$campo) {
            if ($campo->isRequerido() == true) {
                $html .= $this->jsIniciadorChequeoForm;
                break;
            }
        }

        // agregar script para inicar los Hints ?
        foreach ($this->campo as &$campo) {
            if ($campo->getHint() != "") {
                $html .= $this->jsHints;
                break;
            }
        }

        $html .= "<div class='mabm'>";

        if (isset($_GET['abmsg'])) {
            $html .= "<div class='merror'>" . urldecode($_GET['abmsg']) . "</div>";
        }

        $html .= $this->jslinksCampoFecha;
        // FIXME El jslinksSelectConBusqueda habria que mostrarlo solo cuando haya un select que lo uso
        $html .= $this->jslinksSelectConBusqueda;
        $html .= $this->jsMonedaInput;

        // foreach ($this->campo as &$campo)
        // {
        // if ($campo instanceof Campos_dbCombo)
        // {
        // if ($campo->isEsDinamico () == true)
        // {
        // $html .= $campo->getJs_dinamic ();
        // }
        // }
        // }

        $html .= "<form enctype='multipart/form-data' method='" . $this->formMethod . "' id='formularioAbm' action='" . $this->formAction . "?abm_alta=1$qsamb' $this->adicionalesForm> \n";
        $html .= "<input type='hidden' name='abm_enviar_formulario' value='1' /> \n";
        $html .= "<table class='mformulario' $this->adicionalesTable> \n";

        if (isset($titulo) or isset($this->textoTituloFormularioAgregar)) {
            $html .= "<thead><tr><th colspan='2'>" . (isset($this->textoTituloFormularioAgregar) ? $this->textoTituloFormularioAgregar : $titulo) . "&nbsp;</th></tr></thead>";
        }

        $html .= "<tbody>\n";
        $html .= "<tr>\n";
        $html .= "<td>\n";
        $html .= "<div id='content'>\n";
        $html .= "<div id='cuerpo'>\n";
        $html .= "<div id='contenedor'>\n";

        if ($this->formularioSolapa == true) {
            for ($e = 1; $e <= $this->cantidadSolapa; $e ++) {
                $html .= "<input id='tab-" . $e . "' type='radio' name='radio-set' class='tab-selector-" . $e . " folio' />";
                $html .= "<label for='tab-" . $e . "' class='tab-label-" . $e . " folio'>" . $this->tituloSolapa[$e - 1] . "</label>";

                $imprForm .= "<div class='content mabm'>";
                $imprForm .= "<div class='content-" . $e . "'>\n";
                $imprForm .= "<section>\n";
                $imprForm .= "<div id='form'>\n";

                $i = 0;

                // foreach ($this->campos as $campo)
                foreach ($this->campo as &$campo) {
                    if ($campo->getEnSolapa() > 0) {
                        $campo->setEnSolapa(1);
                    }

                    if ($campo->getEnSolapa() == $e) {
                        if ($campo->isNoNuevo() == true) {
                            continue;
                        }
                        if ($campo->getTipo() == '' and $campo->getFormItem() == '' and $campo->getSeparador() == "") {
                            continue;
                        }

                        $i ++;

                        $imprForm .= "<div class='elementForm'>\n";

                        if ($campo->getSeparador() != "") {
                            $imprForm .= "<div colspan='2' class='separador'>" . $campo->getSeparador() . "&nbsp;</div> \n";
                        } else {
                            $imprForm .= "<div class='tituloItemsForm'>";
                            $imprForm .= "<label for='" . $campo->getCampo() . "'>" . ($campo->getTitulo() != '' ? $campo->getTitulo() : $campo->getCampo()) . $this->separadorNombreCampo . ($campo->isRequerido() ? " " . $this->indicadorDeCampoRequerido : "");
                            $imprForm .= "</div> \n";

                            $imprForm .= "<div class='itemsForm'> \n";

                            if ($campo->getFormItem() != "" and function_exists($campo->getFormItem())) {
                                call_user_func_array($campo->getFormItem(), array(
                                    $fila
                                ));
                            } else {

                                $imprForm .= $campo->generar_elemento_form_nuevo();
                            }

                            $imprForm .= "</div> \n";
                        }

                        $imprForm .= "</div> \n";
                    }
                }
                $imprForm .= "</div>\n";
                $imprForm .= "</section>\n";
                $imprForm .= "</div>\n";
                $imprForm .= "</div>\n";
            }
            $html .= $imprForm;
        } // en caso de que no sea de tipo solapa
        else {
            if (!isset($imprForm)) {
                $imprForm = "";
            }

            $imprForm .= "<div class='content mabm'>";
            $imprForm .= "<section>\n";
            $imprForm .= "<div id='form'>\n";

            $i = 0;

            foreach ($this->campo as &$campo) {
                if ($campo->isNoNuevo() == true) {
                    continue;
                }
                if (($campo->getTipo() == '') and ($campo->getFormItem() == '') and $campo->getSeparador() == "") {
                    continue;
                }

                $i ++;

                $imprForm .= "<div class='elementForm'>\n";

                if ($campo->getSeparador()) {
                    $imprForm .= "<div colspan='2' class='separador'>" . $campo->getSeparador() . "&nbsp;</div> \n";
                } else {
                    $imprForm .= "<div class='tituloItemsForm'>";
                    $imprForm .= "<label for='" . $campo->getCampo() . "'>" . ($campo->getTitulo() != '' ? $campo->getTitulo() : $campo->getCampo()) . $this->separadorNombreCampo . ($campo->isRequerido() == true ? " " . $this->indicadorDeCampoRequerido : "");
                    $imprForm .= "</div> \n";

                    $imprForm .= "<div class='itemsForm'> \n";

                    if ($campo->getFormItem() != "" and function_exists($campo->getFormItem())) {
                        call_user_func_array($campo->getFormItem(), array(
                            $fila
                        ));
                    } else {
                        $imprForm .= $campo->generar_elemento_form_nuevo();
                    }

                    $imprForm .= "</div> \n";
                }

                $imprForm .= "</div> \n";
            }
            $imprForm .= "</div>\n";
            $imprForm .= "</section>\n";
            $imprForm .= "</div>\n";
            $imprForm .= "</div>\n";

            $html .= $imprForm;
        }

        $html .= "</div>\n";
        $html .= "</div>\n";
        $html .= "</td>\n";
        $html .= "</tr>\n";
        $html .= "</tbody>\n";

        if ($this->extraBtn == 'true') {

            $html .= "<tfoot>";
            $html .= " <tr>";
            $html .= " <th colspan='2'>";
            // $html .= " <div class ='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"" . ($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'") . "\"/></div> ";

            $html .= " <div class='divBtnExtra'><input type='button' class='input-button' title='$this->textoBotonExtraTitulo' value='$this->textoBotonExtra' $this->adicionalesExtra /></div>";

            // $html .= " <div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitNuevo' $this->adicionalesSubmit /></div>";
            $html .= " </th>";
            $html .= " </tr>";
            $html .= "</tfoot>";
        }

        $html .= "</table> \n";
        $html .= "</form> \n";
        $html .= "</div>";

        echo $html;
    }

    /**
     * Genera el formulario de modificacion de un registro
     *
     * @version 1.0.2 Se corrigio el uso de $customCompareValor para que quedara entre comillas simples cosa de poder hacer comparaciones de textos.
     *
     * @param string $id
     *            id por el que debe identificarse el registro a modificar
     * @param string $titulo
     *            en caso de que el formulario deba tener un titulo especial
     *
     * @return string
     */
    private function generarFormModificacion($id, $titulo = "")
    {
        $html = "";
        $camposSelect = "";

        $joinSql = "";

        // por cada campo...
        foreach ($this->campo as &$campo) {
            if ($campo->getCampo() == "") {
                continue;
            }

            if ($campo->isNoMostrarEditar() == true) {
                continue;
            }

            // if ($campo->getTipo () == "upload")
            if ($campo instanceof Campos_upload) {
                continue;
            }

            if ($campo instanceof Campos_dbCombo) {
                if ($campo->isEsDinamico() == true) {
                    $campo->preparar_script_dinamic();

                    $html .= $campo->getJs_dinamic();
                }
            }

            // campos para el select
            if (isset($camposSelect) and $camposSelect != "") {
                $camposSelect .= ", ";
            } else {
                $camposSelect = "";
            }
            $camposSelect .= $campo->get_campo_select();

            // Si existe agregamos los datos del campo select
            if ($this->sqlCamposSelect != "") {
                $camposSelect .= ", " . $this->sqlCamposSelect;
            }

            // tablas para sql join
            if ($campo->existeDato('joinTable') and (!$campo->existeDato('omitirJoin') or $campo->isOmitirJoin() == false)) {
                if ($campo->existeDato('joinCondition')) {
                    $joinCondition = $campo->getJoinCondition();
                } else {
                    $joinCondition = "INNER";
                }

                $joinSql_aux = $joinCondition . " JOIN " . $campo->getJoinTable() . " ON " . $this->tabla . "." . $campo . "=" . $campo->getJoinTable() . "." . $campo->getCampoValor();

                if ($campo->existeDato('customCompare')) {
                    $joinSql_aux .= " AND " . $campo->getCustomCompareCampo() . " = " . $this->tabla . '.' . $campo->getCustomCompareValor();
                }

                $pos = strpos($joinSql, $joinSql_aux);

                // Notese el uso de ===. Puesto que == simple no funcionara como se espera
                // porque la posicion de 'a' esta en el 1a (primer) caracter.
                if ($pos === false) {
                    // FIXME Revisar exactamente.
                    $joinSql .= " " . $joinSql_aux;
                }
            }
        }
        // hace el select para mostrar los datos del formulario de edicion
        if (isset($id) and $id != "" and isset($this->db)) {
            $id = $this->limpiarParaSql($id);
        }

        if (is_array($this->campoId)) {
            $camposSelect .= $this->convertirIdMultipleSelect($this->campoId, $this->tabla);
            $this->campoId = $this->convertirIdMultiple($this->campoId, $this->tabla);

            $sql = "SELECT $this->campoId, $camposSelect FROM " . $this->tabla . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE upper(" . substr($this->campoId, 0, -6) . ") = upper('" . $id . "')";
        } else {
            $sql = "SELECT $this->tabla.$this->campoId AS ID, $camposSelect FROM " . $this->tabla . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE upper(" . $this->tabla . "." . $this->campoId . ") = upper('" . $id . "')";
        }

        $result = $this->db->query($sql);

        $fila = $this->db->fetch_array($result);

        if ($this->db->num_rows($result) == 0) {
            if (($fila < 0) or ($fila == "") or ($fila == NULL)) {
                $html .= $this->textoElRegistroNoExiste;
                return;
            }
        }

        $fila = array_merge(array_change_key_case($fila, CASE_UPPER), array_change_key_case($fila, CASE_LOWER));

        // genera el query string de variables previamente existentes
        $get = $_GET;
        unset($get['abm_editar']);
        $qsamb = http_build_query($get);

        if ($qsamb != "") {
            $qsamb = "&" . $qsamb;
        }

        // agregar script para inicar FormCheck ?
        foreach ($this->campo as &$campo) {
            if ($campo->existeDato('requerido') and ($campo->isRequerido() == true)) {
                $html .= $this->jsIniciadorChequeoForm;
                break;
            }
        }

        // agregar script para iniciar los Hints ?
        foreach ($this->campo as &$campo) {
            if ($campo->existeDato('hint')) {
                $html .= $this->jsHints;
                break;
            }
        }

        foreach ($this->campo as &$campo) {

            if (isset($fila[$campo->getCampo()])) {
                $campo->setValor($fila[$campo->getCampo()]);
            } elseif (isset($fila[$campo->getCampoTexto()])) {
                $campo->setValor($fila[$campo->getCampoTexto()]);
            }

            if ($campo->existeDato("joinTable") and $campo->isOmitirJoin() == false) {
                $tablaJoin = $campo->getJoinTable();
                $tablaJoin = explode(".", $tablaJoin);
                $tablaJoin = $tablaJoin[count($tablaJoin) - 1];

                $campo->setDato($campo->getCampo());
                if ($campo->existeDato("campoTexto")) {
                    $campo->setCampo($tablaJoin . "_" . $campo->getCampoTexto());
                } else {
                    $campo->setCampo($tablaJoin . "_" . $campo->getCampo());
                }

                if (array_key_exists(substr($campo->getCampo(), 0, 30), $fila)) {
                    $campo->setValor($fila[substr($campo->getCampo(), 0, 30)]);
                }
            }
        }

        // Imprimimos la llamada a los js correspondientes para que funcionen los datepikcer
        $html .= $this->jslinksCampoFecha;
        // FIXME El jslinksSelectConBusqueda habria que mostrarlo solo cuando haya un select que lo uso
        $html .= $this->jslinksSelectConBusqueda;

        $html .= "<div class='mabm'>";
        if (isset($_GET['abmsg'])) {
            $html .= "<div class='merror'>" . urldecode($_GET['abmsg']) . "</div>";
        }
        $html .= "<form enctype='multipart/form-data' method='" . $this->formMethod . "' id='formularioAbm' action='" . $this->formAction . "?abm_modif=1&$qsamb' $this->adicionalesForm> \n";
        $html .= "<input type='hidden' name='abm_enviar_formulario' value='1' /> \n";
        $html .= "<input type='hidden' name='abm_id' value='" . $id . "' /> \n";
        $html .= "<table class='mformulario' $this->adicionalesTable> \n";

        if (isset($titulo) or isset($this->textoTituloFormularioEdicion)) {
            $html .= "<thead><tr><th colspan='2'>" . (isset($this->textoTituloFormularioEdicion) ? $this->textoTituloFormularioEdicion : $titulo) . "&nbsp;</th></tr></thead>";
        }

        $html .= "<tbody>\n";
        $html .= "<tr>\n";
        $html .= "<td>\n";
        $html .= "<div id='content'>\n";
        $html .= "<div id='cuerpo'>\n";
        $html .= "<div id='contenedor'>\n";

        if ($this->formularioSolapa == true) {
            for ($e = 1; $e <= $this->cantidadSolapa; $e ++) {
                $html .= "<input id='tab-" . $e . "' type='radio' name='radio-set' class='tab-selector-" . $e . " folio' />";
                $html .= "<label for='tab-" . $e . "' class='tab-label-" . $e . " folio'>" . $this->tituloSolapa[$e - 1] . "</label>";

                $imprForm .= "<div class='content mabm'>";
                $imprForm .= "<div class='content-" . $e . "'>\n";
                $imprForm .= "<section>\n";
                $imprForm .= "<div id='form'>\n";

                $i = 0;

                // por cada campo... arma el formulario
                foreach ($this->campo as &$campo) {

                    if (!$campo->existeDato('enSolapa')) {
                        $campo->setEnSolapa(1);
                    }

                    if ($campo->getEnSolapa() == $e) {
                        if ($campo->IsNoMostrarEditar() == true) {
                            continue;
                        }

                        if ($campo->getTipo() == '' and (!$campo->existeDato('formItem')) and (!$campo->existeDato('separador'))) {
                            continue;
                        }

                        $i ++;

                        if ($i == 1 and $this->autofocus) {
                            $campo->setAutofocus(TRUE);
                        }

                        $imprForm .= "<div class='elementForm'>\n";

                        if ($campo->existeDato('separador')) {
                            $imprForm .= "<div colspan='2' class='separador'>" . $campo->getSeparador() . "&nbsp;</div> \n";
                        } else {
                            $imprForm .= "<div class='tituloItemsForm'>";
                            $imprForm .= "<label for='" . $campo->getCampo() . "'>" . ($campo->existeDato('titulo') ? $campo->getTitulo() : $campo->getCampo()) . $this->separadorNombreCampo . ($campo->isRequerido() ? " " . $this->indicadorDeCampoRequerido : "");
                            $imprForm .= "</div> \n";

                            $imprForm .= "<div class='itemsForm'> \n";

                            if ($campo->existeDato('formItem') and function_exists($campo->getFormItem())) {
                                call_user_func_array($campo->getFormItem(), array(
                                    $fila
                                ));
                            } else {
                                if ($campo->existeDato('customCompare') and ($campo->getCampo() == $campo->getCustomCompareValor())) {
                                    // FIXME verificar si esta bien el nombre de la variable o como deberia encararse
                                    // Por el momento voy a eliminarlo y mas tarde vemos como lo solucionamos.
                                    // $customCompareValor = $fila[$campo->getCampo()];
                                }

                                $imprForm .= $campo->generar_elemento_form_update();
                            }

                            $imprForm .= "</div> \n";
                        }

                        $imprForm .= "</div> \n";
                    }
                }
                $imprForm .= "</div>\n";
                $imprForm .= "</section>\n";
                $imprForm .= "</div>\n";
                $imprForm .= "</div>\n";
            }
            $html .= $imprForm;
        } else {

            // En caso de que no se requiera la utilizacion de solapas
            if (!isset($imprForm)) {
                $imprForm = "";
            }

            $imprForm .= "<div class='content mabm'>";
            $imprForm .= "<section>\n";
            $imprForm .= "<div id='form'>\n";

            $i = 0;

            // por cada campo... arma el formulario
            foreach ($this->campo as &$campo) {

                if ($campo->isNoMostrarEditar() == true) {
                    continue;
                }

                if ($campo->getFormItem() != "" and $campo->getSeparador() != "") {
                    continue;
                }

                $i ++;

                if ($i == 1 and $this->autofocus) {
                    $campo->setAutofocus(true);
                } else {
                    $campo->setAutofocus(false);
                }

                $imprForm .= "<div class='elementForm'>\n";

                if ($campo->getSeparador() != "") {
                    $imprForm .= "<div colspan='2' class='separador'>" . $campo->getSeparador() . "&nbsp;</div> \n";
                } else {
                    $imprForm .= "<div class='tituloItemsForm'>";
                    $imprForm .= "<label for='" . $campo->getCampo() . "'>" . ($campo->getTitulo() != '' ? $campo->getTitulo() : $campo->getCampo()) . $this->separadorNombreCampo . ($campo->isRequerido() == TRUE ? " " . $this->indicadorDeCampoRequerido : "");
                    $imprForm .= "</div> \n";

                    $imprForm .= "<div class='itemsForm'> \n";

                    if ($campo->getFormItem() != "" and function_exists($campo->getFormItem())) {
                        call_user_func_array($campo->getFormItem(), array(
                            $fila
                        ));
                    } else {
                        $imprForm .= $campo->generar_elemento_form_update();
                    }

                    $imprForm .= "</div> \n";
                }

                $imprForm .= "</div> \n";
            }
            $imprForm .= "</div>\n";
            $imprForm .= "</section>\n";
            $imprForm .= "</div>\n";

            $html .= $imprForm;
        }

        $html .= "</div>\n";
        $html .= "</div>\n";
        $html .= "</td>\n";
        $html .= "</tr>\n";
        $html .= "</tbody>\n";

        /**
         * 2015/11/12
         * Modificado por @iberlot para poder agregar btns extra
         */
        if ($this->extraBtn == 'true') {
            $html .= "<tfoot>";
            $html .= "    <tr>";
            $html .= "        <th colspan='2'>";
            // $html .= " <div class ='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"" . ($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'") . "\"/></div> ";
            // $html .= " <div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitNuevo' $this->adicionalesSubmit /></div>";

            $html .= "			<div class='divBtnExtra'><input type='button' class='input-button' title='$this->textoBotonExtraTitulo' value='$this->textoBotonExtra' $this->adicionalesExtra /></div>";

            $html .= "		  </th>";
            $html .= "    </tr>";
            $html .= "</tfoot>";
        }
        $html .= "</table> \n";
        $html .= "</form> \n";
        $html .= "</div>";

        echo $html;
    }

    /**
     * Funcion que exporta datos a formatos como Excel o CSV
     *
     * @param string $formato
     *            (uno entre: excel, csv)
     * @param string $camposWhereBuscar
     */
    private function exportar($formato, $camposWhereBuscar = "")
    {
        $joinSql = "";
        $camposSelect = "";

        $camposWhereBuscar = htmlspecialchars_decode($camposWhereBuscar, ENT_QUOTES);
        $camposWhereBuscar = str_replace("|||", " ", $camposWhereBuscar);

        if (strtolower($formato) == 'excel') {
            header('Content-type: application/vnd.ms-excel');
            header("Content-Disposition: attachment; filename={$this->exportar_nombreArchivo}.xls");
            header("Pragma: no-cache");
            header("Expires: 0");

            echo "<table border='1'>\n";
            echo "    <tr>\n";
        } elseif (strtolower($formato) == 'csv') {
            header('Content-type: text/csv');
            header("Content-Disposition: attachment; filename={$this->exportar_nombreArchivo}.csv");
            header("Pragma: no-cache");
            header("Expires: 0");
        }

        // contar el total de campos que tienen el parametro "exportar"
        $totalCamposExportar = 0;

        for ($i = 0; $i < count($this->campo); $i ++) {
            if (!isset($this->campos[$i]['exportar']) or $this->campos[$i]['exportar'] != true) {
                continue;
            }
            $totalCamposExportar ++;
        }

        // FIXME WTF con essto
        // Por cada campo...
        for ($i = 0; $i < count($this->campo); $i ++) {

            // XXX probar que no explote nada
            // if ($campo->existeDato('exportar') != true) {
            if ($this->campos[$i]['campo']->existeDato('exportar') != true) {
                continue;
            }
            if ($this->campos[$i]['campo'] == "") {
                continue;
            }
            if ($this->campos[$i]['tipo'] == "upload") {
                continue;
            }

            // campos para el select
            if (isset($camposSelect) and $camposSelect != "") {
                $camposSelect .= ", ";
            }

            if ($this->campos[$i]['tipo'] == 'rownum') {
                // Si el campo es de tipo rownum le decimos que no le agregue la tab$campomposSelect .= $this->campos[$i]['campo'];
            } else {
                // tablas para sql join
                if (isset($this->campos[$i]['joinTable']) and $this->campos[$i]['joinTable'] != '') {
                    $tablaJoin = $this->campos[$i]['joinTable'];

                    $tablaJoin = explode(".", $tablaJoin);
                    $tablaJoin = $tablaJoin[count($tablaJoin) - 1];

                    if (isset($this->campos[$i]['selectPersonal']) and $this->campos[$i]['selectPersonal'] != "") {
                        $camposSelect .= $this->campos[$i]['selectPersonal'] . " AS " . $this->campos[$i]['campoTexto'];
                    } else {
                        $camposSelect .= $this->campos[$i]['joinTable'] . "." . $this->campos[$i]['campoTexto'] . " AS " . $this->campos[$i]['campoTexto'];
                    }

                    if (!isset($this->campos[$i]['omitirJoin']) or $this->campos[$i]['omitirJoin'] == false) {
                        if (isset($this->campos[$i]['joinCondition']) and $this->campos[$i]['joinCondition'] != '') {
                            $joinCondition = $this->campos[$i]['joinCondition'];
                        } else {
                            $joinCondition = "INNER";
                        }

                        // $joinSql .= " $joinCondition JOIN " . $this->campos[$i]['joinTable'] . " ON " . $this->tabla . '.' . $this->campos[$i]['campo'] . '=' . $this->campos[$i]['joinTable'] . '.' . $this->campos[$i]['campoValor'];

                        // if (isset($this->campos[$i]['customCompare']) and $this->campos[$i]['customCompare'] != "")
                        // {
                        // // $joinSql .= " ".$this->campos [$i] ['customCompare'];
                        // $joinSql .= " AND " . $this->campos[$i]['customCompareCampo'] . " = " . $this->tabla . '.' . $this->campos[$i]['customCompareValor'];
                        // }

                        $joinSql_aux = " $joinCondition JOIN " . $this->campos[$i]['joinTable'] . " ON " . $this->tabla . '.' . $this->campos[$i]['campo'] . '=' . $this->campos[$i]['joinTable'] . '.' . $this->campos[$i]['campoValor'];

                        if (isset($this->campos[$i]['customCompare']) and $this->campos[$i]['customCompare'] != "") {
                            // $joinSql .= " ".$this->campos [$i] ['customCompare'];
                            $joinSql_aux .= " AND " . $this->campos[$i]['customCompareCampo'] . " = " . $this->tabla . '.' . $this->campos[$i]['customCompareValor'];
                        }

                        $pos = strpos($joinSql, $joinSql_aux);

                        // Natese el uso de ===. Puesto que == simple no funcionara como se espera
                        // porque la posician de 'a' esta en el 1a (primer) caracter.
                        if ($pos === false) {
                            // FIXME Revisar exactamente.
                            $joinSql .= " " . $joinSql_aux;
                        }
                    }
                } else {
                    $camposSelect .= $this->tabla . "." . $this->campos[$i]['campo'];
                }
            }

            // Encabezados
            if (strtolower($formato) == 'excel') {
                echo "        <th>";
            }

            if (isset($this->campos[$i]['tituloListado']) and $this->campos[$i]['tituloListado'] != "") {
                echo $this->campos[$i]['tituloListado'];
            } elseif ($this->campos[$i]['titulo'] != "") {
                echo $this->campos[$i]['titulo'];
            } else {
                echo $this->campos[$i]['campo'];
            }

            // echo (isset($this->campos[$i]['tituloListado']) and $this->campos [$i] ['tituloListado'] != "" ? $this->campos [$i] ['tituloListado'] : ($this->campos [$i] ['titulo'] != '' ? $this->campos [$i] ['titulo'] : $this->campos [$i] ['campo']));

            if (strtolower($formato) == 'excel') {
                echo "</th>\n";
            } elseif (strtolower($formato) == 'csv') {
                if ($i < $totalCamposExportar - 1) {
                    echo $this->exportar_csv_separadorCampos;
                }
            }
        }

        if (strtolower($formato) == 'excel') {
            echo "    </tr>\n";
        }

        // Datos
        if ($this->exportar_sql != "") {
            $sql = $this->exportar_sql;
        } else if ($this->sqlCamposSelect != "") {
            if ($this->orderByPorDefecto != "") {
                $orderBy = " ORDER BY " . $this->orderByPorDefecto;
            }
            // $sql = "SELECT " . $this->sqlCamposSelect . " FROM $this->tabla $joinSql WHERE 1=1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
            $sql = "SELECT " . $this->sqlCamposSelect . " FROM " . $this->tabla . " " . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE 1=1 " . $camposWhereBuscar . " " . $this->adicionalesSelect . " " . $orderBy;
        } else {
            if ($this->orderByPorDefecto != "") {
                $orderBy = " ORDER BY " . $this->orderByPorDefecto;
            }

            // if (is_array ($this->campoId))
            // {
            // $this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);
            // }

            // $sql = "SELECT $this->campoId AS ID, $camposSelect FROM $this->tabla $joinSql WHERE 1=1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
            if (is_array($this->campoId)) {
                $this->campoId = $this->convertirIdMultiple($this->campoId, $this->tabla);
            } else {
                $this->campoId = $this->tabla . "." . $this->campoId . " AS ID ";
            }

            if (!isset($joinSql)) {
                $joinSql = "";
            }
            if (!isset($camposWhereBuscar)) {
                $camposWhereBuscar = "";
            } else {
                $camposWhereBuscar = " AND (" . $camposWhereBuscar . ") ";
            }
            if (!isset($orderBy)) {
                $orderBy = "";
            }
            // $sql = "SELECT $this->campoId , $camposSelect FROM $this->tabla $joinSql $this->customJoin WHERE 1=1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
            $sql = "SELECT $this->campoId , $camposSelect FROM $this->tabla $this->dbLink $joinSql $this->customJoin WHERE 1=1 AND 2=2 $this->adicionalesSelect $orderBy";
        }

        $result = $this->db->query($sql);
        $i = 0;

        while ($fila = $this->db->fetch_array($result)) {
            $fila = array_merge(array_change_key_case($fila, CASE_UPPER), array_change_key_case($fila, CASE_LOWER));

            // print_r("<Br />*******************<Br />");
            $fila = Funciones::limpiarEntidadesHTML($fila);
            $i ++;

            if (strtolower($formato) == 'excel') {
                echo "    <tr>\n";
            } elseif (strtolower($formato) == 'csv') {
                echo "\n";
            }

            $c = 0;
            // foreach ($this->campos as $campo)
            foreach ($this->campo as &$campo) {
                $c ++;
                if ($campo->getExportar() != true) {
                    continue;
                }

                // FIXME campoOrder???
                if ($campo->existeDato('campoOrder')) {
                    // XXX no estoy seguro de que esto sea asi, requiere estudi detallado
                    // $campo->setCampo ($campo['']);
                    $campo->setCampo($campo);
                } else {
                    if ($campo->existeDato("joinTable") and $campo->getJoinTable() != '') {
                        // $campo ['campo'] = $campo ['joinTable'] . '_' . $campo ['campoTexto'];
                        $campo->setCampo($campo->getCampoTexto());
                    }
                }

                if (strtolower($formato) == 'excel') {

                    echo '        <td>';
                }

                if ($campo->getCustomEvalListado() != "") {

                    extract($GLOBALS);
                    // $id = $fila['ID'];

                    if ($campo->getCampo() != "") {
                        // FIXME aparentemente esto no se usa nunca, lo comento y dejo la nota para revisarlo
                        // $valor = $fila[$campo->getCampo()];
                    }

                    eval(strip_tags($campo->getCustomEvalListado()));
                } // elseif ($campo->getTipo () == "bit")
                elseif ($campo instanceof Campos_bit) {
                    if ($fila[$campo->getCampo()]) {
                        echo ($campo->getTextoBitTrue() != '' ? $campo->getTextoBitTrue() : $this->textoBitTrue);
                    } else {
                        echo ($campo->getTextoBitFalse() != '' ? $campo->getTextoBitFalse() : $this->textoBitFalse);
                    }
                } else {

                    // si es tipo fecha lo formatea
                    // if ($campo->getTipo () == "fecha")
                    if ($campo instanceof Campos_fecha) {
                        if ($fila[$campo->getCampo()] != "" and $fila[$campo->getCampo()] != "0000-00-00" and $fila[$campo->getCampo()] != "0000-00-00 00:00:00") {
                            if (strtotime($fila[$campo->getCampo()]) !== -1) {
                                $fila[$campo->getCampo()] = date($this->formatoFechaListado, strtotime($fila[$campo->getCampo()]));
                            }
                        }
                    } // elseif ($campo->getTipo () == "moneda")
                    elseif ($campo instanceof Campos_moneda) {
                        // setlocale(LC_MONETARY, 'es_AR');
                        // $fila [$campo ['campo']] = money_format('%.2n', $fila [$campo ['campo']]);
                        // number_format($n�mero, 2, ',', ' ');
                        $fila[$campo->getCampo()] = number_format($fila[$campo->getCampo()], 2, ',', '.');
                    } // elseif ($campo->getTipo () == "numero")
                    elseif ($campo instanceof Campos_numero) {
                        // setlocale(LC_MONETARY, 'es_AR');
                        // $fila [$campo ['campo']] = money_format('%.2n', $fila [$campo ['campo']]);
                        // number_format($numero, 2, ',', ' ');
                        $fila[$campo->getCampo()] = number_format($fila[$campo->getCampo()], $campo->getCantidadDecimales(), ',', '.');
                    }

                    $str = $fila[$campo->getCampo()];

                    // si es formato csv...
                    if (strtolower($formato) == 'csv') {
                        // quito los saltos de linea que pueda tener el valor
                        $str = ereg_replace(chr(13), "", $str);
                        $str = ereg_replace(chr(10), "", $str);

                        // verifico que no este el caracter separador de campos en el valor
                        if (strpos($str, $this->exportar_csv_separadorCampos) !== false) {
                            $str = $this->exportar_csv_delimitadorCampos . $str . $this->exportar_csv_delimitadorCampos;
                        }
                    }

                    $str = $this->strip_selected_tags($str, "br");

                    $str = str_ireplace("\<br", "", $str);
                    // $str= Funciones::limpiarEntidadesHTML($str);
                    // $str= str_ireplace("Br", "", $str);
                    // $str= str_ireplace("lt", "", $str);
                    // echo str_ireplace("<Br>", "", $str);

                    echo $str;
                }

                if (strtolower($formato) == 'excel') {
                    echo "</td>\n";
                } elseif (strtolower($formato) == 'csv') {
                    if ($c < $totalCamposExportar) {
                        echo $this->exportar_csv_separadorCampos;
                    }
                }
            }

            if (strtolower($formato) == 'excel') {
                echo "    </tr>\n";
            }
        }

        if (strtolower($formato) == 'excel') {
            echo "</table>";
        }

        // exit ();
    }

    /**
     * Genera el formulario de busqueda a agregar en el listado.
     *
     * @param string $qsamb
     * @return string
     */
    private function generarFormBusqueda($qsamb = null)
    {

        // formulario de busqueda
        // XXX Hay que convertirlo en una funcion que retorne el string del formulario
        if (($this->mostrarListado) and $this->busquedaTotal == false) {
            $formBuscar = "<tr class='mbuscar'><th colspan='" . (count($this->campo) + 2) . "'> \n";
            $formBuscar .= "<fieldset><legend>$this->textoTituloFormularioBuscar</legend> \n";
            $formBuscar .= "<form method='POST' action='$this->formAction?$qsamb' id='formularioBusquedaAbm'> \n";

            $iColumna = 0;
            $maxColumnas = $this->columnasFormBuscar;

            foreach ($this->campo as &$campo) {
                if ($campo->isBuscar() == false) {
                    continue;
                }

                // $campo['maxMostrar'] = $campo->getMaxMostrar ();

                // if ($campo->isRequerido ())
                // {
                // $requerido = $this->chequeoInputRequerido;
                // }
                // else
                // {
                // $requerido = "";
                // }

                $iColumna ++;
                $formBuscar .= "<div>\n";
                $formBuscar .= "<label>" . $campo->obtenerTitulo(true) . "</label>";

                // if ($campo->existeDato ("tipoBuscar"))
                // {
                // $campo->getTipo() = $campo['tipoBuscar'];
                // }

                if ($campo->existeDato("customFuncionBuscar")) {
                    call_user_func_array($campo->getCustomFuncionBuscar(), array());
                } else {
                    // FIXME y aca que onda??? revisar y dejar como corresponda.
                    // $formBuscar .= $campo->campoFormBuscar($busqueda);
                    $formBuscar .= $campo->campoFormBuscar();
                }

                echo "</div>";
                if ($iColumna == $maxColumnas) {
                    $iColumna = 0;
                    $formBuscar .= "<div class='mNuevaLinea'></div>\n";
                }
            }

            $formBuscar .= "<div class='mBotonesB'> \n";
            $formBuscar .= "<input type='submit' class='mBotonBuscar' value='$this->textoBuscar'/> \n";
            $formBuscar .= "<input type='button' class='mBotonLimpiar' value='$this->textoLimpiar' onclick='window.location=\"$this->formAction?$qsamb\"'/> \n";
            $formBuscar .= "</div> \n";
            $formBuscar .= "</form> \n";
            $formBuscar .= "</fieldset> \n";
            $formBuscar .= "</th></tr> \n";
        } elseif ($this->busquedaTotal == true) {
            $formBuscar = "<tr class='mbuscar'><th colspan='" . (count($this->campo) + 2) . "'> \n";
            $formBuscar .= "<fieldset><legend>$this->textoTituloFormularioBuscar</legend> \n";
            $formBuscar .= "<form method='POST' action='$this->formAction?$qsamb' id='formularioBusquedaAbm'> \n";
            $formBuscar .= "<div>\n";
            $formBuscar .= "<label>B&uacute;squeda</label>";

            if (isset($_REQUEST['c_busquedaTotal'])) {
                // FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
                $this->paginado->setSumarBusqueda('&c_busquedaTotal=' . Funciones::limpiarEntidadesHTML($_REQUEST['c_busquedaTotal']));

                $formBuscar .= "<input type='text' class='input-text' name='c_busquedaTotal' value='" . Funciones::limpiarEntidadesHTML($_REQUEST['c_busquedaTotal']) . "' /> \n";
            } else {
                $formBuscar .= "<input type='text' class='input-text' name='c_busquedaTotal' value='' /> \n";
            }
            $formBuscar .= "</div>";
            // $formBuscar .= "<div class='mNuevaLinea'></div>\n";
            $formBuscar .= "<div class='mBotonesB'> \n";
            $formBuscar .= "<input type='submit' class='mBotonBuscar' value='" . $this->textoBuscar . "'/> \n";
            $formBuscar .= "<input type='button' class='mBotonLimpiar' value='" . $this->textoLimpiar . "' onclick='window.location=\"$this->formAction?$qsamb\"'/> \n";
            $formBuscar .= "</div> \n";
            $formBuscar .= "</form> \n";
            $formBuscar .= "</fieldset> \n";
            $formBuscar .= "</th></tr> \n";
        }

        return $formBuscar;
    }

    /**
     * Genera el listado ABM con las funciones de editar, nuevo y borrar (segun la configuracion).
     *
     * @todo NOTA: Esta funcion solamente genera el listado, se necesita usar la funcion generarAbm() para que funcione el ABM.
     *
     * @param string $titulo
     *            Un titulo para mostrar en el encabezado del listado
     * @param string $sql
     *            Query SQL personalizado para el listado. Usando este query no se usa $adicionalesSelect
     *
     */
    private function generarListado($titulo, $sql = "")
    {
        $html = "";
        $rallado = "";
        $joinSql = "";
        $camposSelect = "";
        $camposOrder = "";
        $estaBuscando = "";
        $camposWhereBuscar = "";

        $this->estilosBasicos = str_ireplace('%dirname%', dirname(__FILE__), $this->estilosBasicos);
        $this->estilosBasicos = str_ireplace($_SERVER['DOCUMENT_ROOT'], "", $this->estilosBasicos);
        $this->jsBasicos = str_ireplace('%dirname%', dirname(__FILE__), $this->jsBasicos);
        $this->jsBasicos = str_ireplace($_SERVER['DOCUMENT_ROOT'], "", $this->jsBasicos);
        $html .= "<HEAD>" . $this->estilosBasicos . "</HEAD>";

        // por cada campo...
        foreach ($this->campo as &$campo) {
            // print_r ("recorre el campo " . $campo . "<Br />");

            if (!$campo->existeDato("campo") or $campo->isNoListar() == true) {
                continue;
            }

            if ($campo->isExportar() == true) {
                $mostrarExportar = true;
            }

            // para la class de ordenar por columnas
            if ($campo->isNoOrdenar() == false) {
                if (isset($camposOrder) and $camposOrder != "") {
                    $camposOrder .= "|";
                }

                $camposOrder .= $campo->getCampoOrder();
            }

            if (isset($camposSelect) and ($camposSelect != "")) {
                $camposSelect .= ", ";
            }

            $camposSelect .= $campo->get_campo_select();

            // para el where de buscar
            if ($campo->existeDato("buscar")) {
                $this->agregarFormBuscar = true;
                // }

                if ((isset($_REQUEST['c_' . $campo->getCampo()]) and (trim($_REQUEST['c_' . $campo->getCampo()]) != '')) or (isset($_REQUEST['c_busquedaTotal']) and (trim($_REQUEST['c_busquedaTotal']) != ''))) {
                    if (isset($_REQUEST['c_' . $campo->getCampo()])) {
                        $valorABuscar = $this->limpiarParaSql($_REQUEST['c_' . $campo->getCampo()]);

                        if (isset($camposWhereBuscar)) {
                            $camposWhereBuscar .= " AND ";
                        }
                    } elseif (isset($_REQUEST['c_busquedaTotal'])) {
                        $valorABuscar = $this->limpiarParaSql($_REQUEST['c_busquedaTotal']);

                        if (isset($camposWhereBuscar)) {
                            $camposWhereBuscar .= " OR ";
                        } else {
                            $camposWhereBuscar = " ";
                        }
                    }

                    $estaBuscando = true;

                    $camposWhereBuscar .= $campo->get_where_buscar($valorABuscar);
                }
            }
            // print_r ("recorre el " . $campo . "<Br />");
            // tablas para sql join
            if ($campo->existeDato("joinTable") and $campo->existeDato("omitirJoin") == false) {
                // print_r ("<Br />" . "<Br />" . $campo->getJoinTable () . "<Br />" . "<Br />");

                if ($campo->existeDato("joinCondition")) {
                    $joinCondition = $campo->getJoinCondition();
                } else {
                    $joinCondition = "INNER";
                }

                $joinSql_aux = $joinCondition . " JOIN " . $campo->getJoinTable() . " ON " . $this->tabla . "." . $campo->getCampo() . "=" . $campo->getJoinTable() . "." . $campo->getCampoValor();

                if (isset($this->customCompare) and $this->customCompare != "") {
                    $joinSql_aux .= " AND " . $campo->getCustomCompareCampo() . " = " . $this->tabla . '.' . $campo->customCompareValor;
                }

                // print_r ($campo);
                // FIXME Esto es un parche temporal y requiere que se arragle con urgencia
                if ($campo->existeDato("compareMasJoin")) {
                    // print_r ("pepino1");
                    $joinSql_aux .= " AND " . $campo->getCompareMasJoin();
                }

                $pos = strpos($joinSql, $joinSql_aux);

                // Natese el uso de ===. Puesto que == simple no funcionara como se espera
                // porque la posician de 'a' esta en el 1a (primer) caracter.
                if ($pos === false) {
                    // FIXME Revisar exactamente.
                    $joinSql .= " " . $joinSql_aux;
                }
            }
        }
        $camposSelect .= $this->adicionalesCamposSelect;

        // class para ordenar por columna
        $o = new class_orderby($this->orderByPorDefecto, $camposOrder);

        if ($o->getOrderBy() != "") {
            $orderBy = " ORDER BY " . $o->getOrderBy();
        }

        if (!isset($joinSql)) {
            $joinSql = "";
        }

        if (!isset($camposWhereBuscar) or $camposWhereBuscar == "") {
            $camposWhereBuscar = "1=1";
        }

        if (!isset($orderBy)) {
            $orderBy = "";
        }

        // query del select para el listado
        if ($sql == "" and $this->sqlCamposSelect == "") {
            if (is_array($this->campoId)) {
                $this->campoId = $this->convertirIdMultiple($this->campoId, $this->tabla);
            } else {
                $this->campoId = $this->tabla . "." . $this->campoId . " AS ID ";
            }

            $sql = "SELECT " . $this->campoId . ", " . $camposSelect . " FROM " . $this->tabla . " " . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE 1=1  AND (" . $camposWhereBuscar . ") " . $this->adicionalesSelect . " " . $orderBy;
        } else if ($this->sqlCamposSelect != "") {
            $sql = "SELECT " . $this->sqlCamposSelect . " FROM $this->tabla $this->dbLink $joinSql $this->customJoin WHERE 1=1  AND ($camposWhereBuscar) $this->adicionalesSelect $orderBy";
        } else {
            $sql = $sql . " " . $orderBy;
        }

        // print_r ("=000=");
        // // class paginado
        // $paginado = new class_paginado ();
        // $paginado->registros_por_pagina = $this->registros_por_pagina;
        // $paginado->str_registros = $this->textoStrRegistros;
        // $paginado->str_registro = $this->textoStrRegistro;
        // $paginado->str_total = $this->textoStrTotal;
        // $paginado->str_ir_a = $this->textoStrIrA;

        if ($this->mostrarListado) {
            $result = $this->paginado->query($sql, $this->db);
        }
        $this->totalFilas = $this->paginado->total_registros;

        $get = $_GET;
        unset($get['abmsg']);
        $qsamb = http_build_query($get);

        if ($qsamb != "") {
            $qsamb = "&" . $qsamb;
        }

        $html .= "<div class='mabm'>";
        $html .= $this->jsUpdateForm;

        if ($this->isMostrarNuevo() == true and $this->getDireNuevo() == "") {
            $html .= $this->jsAltaForm;
        }

        $html .= "\n<script>
		        function abmBorrar(id, obj){
		            var colorAnt = obj.parentNode.parentNode.style.border;
		            obj.parentNode.parentNode.style.border = '3px solid red';";

        $html .= 'if (confirm ("' . $this->textoPreguntarBorrar . '"))
		{
		                window.location = "' . $_SERVER['PHP_SELF'] . "?" . $qsamb . "&abm_borrar=" . '" + id;
		}
		            obj.parentNode.parentNode.style.border = colorAnt;
		return void (0);
	}';

        if ($this->colorearFilas) {
            $html .= "\n\n
					var colorAntTR;
					\n\n

		            function cambColTR(obj,sw){
		                if(sw){
		                    colorAntTR=obj.style.backgroundColor;";

            if ($this->colorearFilasDegrade == true) {
                $html .= "obj.style.background='-webkit-linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* For Safari 5.1 to 6.0 */
                $html .= "obj.style.background='-o-linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* For Opera 11.1 to 12.0 */
                $html .= "obj.style.background='-moz-linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* For Firefox 3.6 to 15 */
                $html .= "obj.style.background='linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* Standard syntax */
            } else {
                $html .= "obj.style.background='$this->colorearFilasColor';";
            }
            $html .= "
		                }else{
		                    obj.style.background=colorAntTR;
		                }
		            }
		            ";
        }

        $html .= "</script>";

        if (isset($_GET['abmsg'])) {
            $html .= "<div class='merror'>" . urldecode($_GET['abmsg']) . "</div> \n";
        }

        $html .= "<table class='mlistado' $this->adicionalesTableListado> \n";

        // titulo, botones, form buscar
        $html .= "<thead> \n";
        $html .= "<tr><th colspan='" . (count($this->campo) + 2) . "'> \n";
        $html .= "<div class='mtitulo'>$titulo</div>";
        $html .= "<div class='mbotonera'> \n";
        $html .= $this->agregarABotoneraListado;

        if ($mostrarExportar and $this->mostrarListado) {

            $WBuscar = str_replace(" ", "|||", $camposWhereBuscar);
            $WBuscar = htmlspecialchars($WBuscar, ENT_QUOTES);
            if (in_array('excel', $this->exportar_formatosPermitidos)) {
                $html .= sprintf($this->iconoExportarExcel, "$_SERVER[PHP_SELF]?abm_exportar=excel&buscar=$WBuscar");
            }
            if (in_array('csv', $this->exportar_formatosPermitidos)) {
                $html .= sprintf($this->iconoExportarCsv, "$_SERVER[PHP_SELF]?abm_exportar=csv");
            }
        }
        if ($this->mostrarNuevo) {
            if ($this->direNuevo) {
                $html .= sprintf($this->iconoAgregar, $this->direNuevo);
            } else {
                $html .= sprintf($this->iconoAgregar, "f_alta( '" . $_SERVER['PHP_SELF'] . "?abm_nuevo=1" . $qsamb . "')");
                // $html .= sprintf ($this->iconoAgregar, "$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb");
            }
        }
        $html .= "</div> \n";

        $html .= "</th></tr> \n";

        // XXX aca va la busqueda
        if ($this->agregarFormBuscar == true) {
            $html .= $this->generarFormBusqueda($qsamb);
        }

        // fin formulario de busqueda

        if ($this->paginado->total_registros > 0) {

            // columnas del encabezado
            if ($this->mostrarEncabezadosListado) {
                $html .= '<tr class="tablesorter-headerRow"> ';
                foreach ($this->campo as &$campo) {

                    if ($campo->isNoListar() == true) {
                        continue;
                    }
                    if ($campo->existeDato("separador")) {
                        continue;
                    }
                    // if (isset ($campo->getTipo()) and ($campo->getTipo() == "upload"))
                    // {
                    // continue;
                    // }

                    $styleTh = "";

                    if ($campo->isCentrarColumna() == true) {
                        $styleTh .= "text-align:center;";
                    }
                    if ($campo->existeDato("anchoColumna")) {
                        $styleTh .= "width:$campo->getAnchoColumna();";
                    }

                    if ($campo->getCampo() == "" or $campo->isNoOrdenar() == true) {
                        $html .= "<th " . ($styleTh != "" ? "style='$styleTh'" : "") . $campo->get_no_mostrar() . " " . $campo->get_prioridad_campo() . ">" . (($campo->existeDato("tituloListado")) ? $campo->getTituloListado() : (($campo->existeDato("titulo")) ? $campo->getTitulo() : $campo->getCampo())) . "</th> \n";
                    } else {
                        if ($campo->existeDato("campoOrder")) {
                            $campoOrder = $campo->getCampoOrder();
                        } else {

                            if ($campo->existeDato("joinTable") and $campo->isOmitirJoin() == false) {
                                $campoOrder = $campo->getCampoTexto();
                            } elseif ($campo->existeDato("joinTable") and $campo->isOmitirJoin() == true) {
                                $campoOrder = $this->tabla . '.' . $campo->getCampo();
                                // $campoOrder = $campo->getJoinTable() . '.' . $campo->getCampo();
                            } else {
                                $campoOrder = $this->tabla . '.' . $campo->getCampo();
                            }
                        }

                        if ($campo->existeDato("titulo")) {
                            $linkas = $o->linkOrderBy($campo->getTitulo(), $campoOrder);
                        } elseif ($campo->existeDato("tituloListado")) {
                            $linkas = $o->linkOrderBy($campo->getTituloListado(), $campoOrder);
                        } else {
                            $linkas = $o->linkOrderBy($campo->getCampo(), $campoOrder);
                        }
                        // echo "<th " . ($styleTh != "" ? "style='$styleTh'" : "") . " $campo->get_no_mostrar() >" . $o->linkOrderBy(((isset($campo->getTituloListado()) and $campo->getTituloListado() != "") ? $campo->getTituloListado() : ($campo->getTitulo() != '' ? $campo->getTitulo() : $campo->getCampo())), $campoOrder) . "</th> \n";

                        $html .= "<th " . ($styleTh != "" ? "style='$styleTh'" : "") . " " . $campo->get_no_mostrar() . " " . $campo->get_prioridad_campo() . " >" . $linkas . "</th> \n";
                    }
                }
                if ($this->mostrarEditar) {
                    $html .= "<th class='mtituloColEditar' " . $campo->get_no_mostrar() . ">" . $this->textoEditarListado . "</th> \n";
                }
                if ($this->mostrarBorrar) {
                    $html .= "<th class='mtituloColBorrar' " . $campo->get_no_mostrar() . ">" . $this->textoBorrarListado . "</th> \n";
                }
                $html .= "</tr> \n";
            } // fin columnas del encabezado
            $html .= "</thead> \n";
            // filas de datos
            $i = 0;

            while ($fila = $this->db->fetch_array($result)) {
                $fila = array_merge(array_change_key_case($fila, CASE_UPPER), array_change_key_case($fila, CASE_LOWER));

                $fila = Funciones::limpiarEntidadesHTML($fila);

                $i ++;
                $rallado = !$rallado;

                $html .= $this->armar_fila_listado($fila, $rallado);
            }

            $html .= "<tfoot> \n";
            $html .= "<tr> \n";
            $html .= "<th colspan='" . (count($this->campo) + 2) . "'>";

            if (!$this->mostrarTotalRegistros) {
                $this->paginado->mostrarTotalRegistros = false;
            }

            $html .= $this->paginado->get_paginado();
            $html .= "</th> \n";
            $html .= "</tr> \n";
            $html .= "</tfoot> \n";
        } else {
            $html .= "<td colspan='" . (count($this->campo) + 2) . "' " . $campo->get_no_mostrar() . "><div class='noHayRegistros'>" . ($estaBuscando ? $this->textoNoHayRegistrosBuscando : $this->textoNoHayRegistros) . "</div></td>";
        }

        $html .= "</table> \n";
        $html .= "</div>";

        if ($this->mostrarNuevo) {
            // genera el query string de variables previamente existentes
            $get = $_GET;
            unset($get['abmsg']);
            unset($get[$o->variableOrderBy]);
            $qsamb = http_build_query($get);
            if ($qsamb != "") {
                $qsamb = "&" . $qsamb;
            }
        }

        // FIXME esto debe retornarse y no mostrarse por pantalla
        foreach ($this->campo as &$campo) {
            $edi = false;

            if ($campo instanceof Campos_dbCombo) {
                if ($campo->isEsDinamico() == true) {
                    $html .= $campo->getJs_dinamic();
                }
            } elseif ($campo instanceof Campos_textarea) {
                if ($campo->isTextoConFormato() == true && $edi == false) {
                    $html .= $campo->getJs_editor();

                    $edi = true;
                }
            }
        }
        echo $html . $this->jsBasicos;
    }

    /**
     * Arma una fila con los datos de los campos.
     *
     * @param array $fila
     * @param string $rallado
     * @return string
     */
    private function armar_fila_listado($fila, &$rallado)
    {
        // print_r ("==");
        // print_r ($fila);
        // print_r ("==");
        $filaListado = "<tr class='rallado$rallado' ";
        if ($this->colorearFilas) {
            $filaListado .= " onmouseover=\"cambColTR(this,1)\" onmouseout=\"cambColTR(this,0)\" ";
        }
        if (isset($this->evalEnTagTR)) {
            eval($this->evalEnTagTR);
        }
        $filaListado .= "> \n";

        // print_r ($fila);
        foreach ($this->campo as &$campo) {

            // print_r ($fila[$campo->getCampo ()]);
            // print_r ($campo->getCampo ());
            // print_r ($campo->getCampoTexto ());
            // print_r (strtoupper ($campo->getCampoTexto ()));
            // print_r ("<Br/>");
            if (isset($fila[strtoupper($campo->getCampo())])) {
                $campo->setValor($fila[strtoupper($campo->getCampo())]);
            } elseif (isset($fila[strtoupper($campo->getCampoTexto())])) {
                // print_r ($campo->getCampoTexto ());
                // print_r ($fila[strtoupper ($campo->getCampoTexto ())]);
                // print_r ("<Br/>");
                $campo->setValor($fila[strtoupper($campo->getCampoTexto())]);
            }

            // if ($campo->getTipo () == "bit")
            if ($campo instanceof Campos_bit) {
                if ($this->textoBitTrue != "SI") {
                    $campo->setTextoBitTrue($this->textoBitTrue);
                }

                if ($this->textoBitFalse != "NO") {
                    $campo->setTextoBitFalse($this->textoBitFalse);
                }
            }

            if ($campo->isNoListar() == true) {
                continue;
            }

            // if (isset ($campo->getTipo()) and ($campo->getTipo() == "upload"))
            // {
            // continue;
            // }
            if ($campo->getSeparador()) {
                continue;
            }

            if ($campo->existeDato("campoOrder")) {
                $campo->setCampo($campo->getCampoOrder());
            } else {
                if ($campo->existeDato("joinTable") and $campo->isOmitirJoin() == false) {
                    // print_r ($campo);

                    $tablaJoin = $campo->getJoinTable();
                    $tablaJoin = explode(".", $tablaJoin);
                    $tablaJoin = $tablaJoin[count($tablaJoin) - 1];

                    $campo->setDato($campo->getCampo());
                    if ($campo->existeDato("campoTexto")) {
                        $campo->setCampo($tablaJoin . "_" . $campo->getCampoTexto());
                    } else {
                        $campo->setCampo($tablaJoin . "_" . $campo->getCampo());
                    }

                    if (array_key_exists(substr(strtoupper($campo->getCampo()), 0, 30), $fila)) {
                        $campo->setValor($fila[substr(strtoupper($campo->getCampo()), 0, 30)]);
                    }

                    // print_r ($campo);
                }
            }

            // if ($campo->existeDato ("joinTable") and $campo->isOmitirJoin () == false)
            // {
            // $tablaJoin = $campo->getJoinTable ();
            // $tablaJoin = explode (".", $tablaJoin);
            // $tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

            // $campo->setDato ($campo->getCampo ());
            // if ($campo->existeDato ("campoTexto"))
            // {
            // $campo->setCampo ($tablaJoin . "_" . $campo->getCampoTexto ());
            // }
            // else
            // {
            // $campo->setCampo ($tablaJoin . "_" . $campo->getCampo ());
            // }

            // if (array_key_exists (substr ($campo->getCampo (), 0, 30), $fila))
            // {
            // $campo->setValor ($fila[substr ($campo->getCampo (), 0, 30)]);
            // }
            // // print_r ($campo->getCampo ());
            // // print_r (" | ");
            // // print_r (substr ($campo->getCampo (), 0, 30));
            // // print_r (" | ");
            // // print_r ($campo->getCampo ());
            // // print_r (" | ");
            // // print_r ($campo->getValor ());
            // // print_r ("<Br />");
            // }

            // if ($campo->existeDato ("colorearValores") and (is_array ($campo->getColorearValores ())))
            // {

            // // XXX lo que sigue lo lleve mas para arriba porque me parece que aca adentro ni pincha ni corta.
            // // if ($campo->existeDato ("joinTable") and $campo->isOmitirJoin () == false)
            // // {
            // // $tablaJoin = $campo->getJoinTable ();
            // // $tablaJoin = explode (".", $tablaJoin);
            // // $tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

            // // if ($campo->existeDato ("campoTexto"))
            // // {
            // // $campo->setCampo ($tablaJoin . "_" . $campo->getCampoTexto ());
            // // }
            // // else
            // // {
            // // $campo->setCampo ($tablaJoin . "_" . $campo->getCampo ());
            // // }
            // // }
            // // print_r ($campo->getCampo ());
            // if ($campo->getCampo () != "" and array_key_exists ($fila[$campo->getCampo ()], $campo->getColorearValores ()))
            // {
            // // XXX revisar la implementacion de las funciones que retornan arrays en generarListado()
            // $spanColorear = "<span class='" . ($campo->isColorearConEtiqueta () ? "label" : "") . "' style='" . ($campo->isColorearConEtiqueta () ? "background-" : "") . "color:" . $campo->getColorearValores ()[$fila[$campo->getCampo ()]] . "'>";
            // $spanColorearFin = "</span>";
            // }
            // else
            // {
            // $spanColorear = "";
            // $spanColorearFin = "";
            // }
            // }
            // else
            // {
            // $spanColorear = "";
            // $spanColorearFin = "";
            // }

            // FIXME todo esto y lo que sigue deberia estar dentro de la funcion del campo
            if ($campo->getCustomEvalListado() != "") {
                $id = $fila['ID'];

                extract($GLOBALS);

                if (isset($fila['ID'])) {
                    $id = $fila['ID'];
                } else {
                    $fila['ID'] = $id;
                }

                // if ($campo->existeDato ("campo"))
                // {
                // $valor = $fila[$campo->getCampo ()];
                // }

                // if ($campo->existeDato ("parametroUsr"))
                // {
                // $parametroUsr = $campo->getParametroUsr ();
                // }

                // print_r (get_defined_vars ());
                // $porciones = explode (" ", $filaListado);
                // print_r ($porciones);
                // exit ();
                // if ($campo->existeDato ("incluirCampo"))
                // {
                // $camposIncuidos = explode (",", $campo->getIncluirCampo ());

                // $cant = count ($camposIncuidos);

                // for($j = 0; $j < $cant; $j++)
                // {
                // print_r ($fila);
                // $campo->setCustomEvalListado (str_ireplace ("{" . trim ($camposIncuidos[$j]) . "}", $fila[trim ($camposIncuidos[$j])], $campo->getCustomEvalListado ()));
                // }
                // }
                ob_start();
                // print_r ($fila);
                eval($campo->getCustomEvalListado());

                $filaListado .= ob_get_contents();

                ob_end_clean();
            } elseif ($campo->existeDato("customFuncionListado")) {
                call_user_func_array($campo->getCustomFuncionListado(), array(
                    $fila
                ));
            } elseif ($campo->existeDato("customPrintListado")) {

                $campo->setValor($campo->getCustomPrintListado());

                if (is_array($this->campoId)) {
                    $this->campoId = $this->convertirIdMultiple($this->campoId, $this->tabla);

                    $this->campoId = substr($this->campoId, 0, -6);
                }

                if ($campo->existeDato("incluirCampo")) {
                    $camposIncuidos = explode(",", $campo->getIncluirCampo());

                    $cant = count($camposIncuidos);

                    for ($j = 0; $j < $cant; $j ++) {
                        // $campo->setCustomPrintListado (str_ireplace ("{" . trim ($camposIncuidos[$j]) . "}", $fila[trim ($camposIncuidos[$j])], $campo->getCustomPrintListado ()));

                        $campo->setValor(str_ireplace("{" . trim($camposIncuidos[$j]) . "}", $fila[trim($camposIncuidos[$j])], $campo->getValor()));
                    }
                }

                $filaListado .= "<td " . $campo->get_centrar_columna() . " " . $campo->get_no_mostrar() . ">" . " ";

                $campo->setValor(str_ireplace('{id}', $fila['ID'], $campo->getValor()));

                if (isset($fila[$campo->getCampo()])) {
                    $filaListado .= sprintf($campo->getValor(), $fila[$campo->getCampo()]);
                } else {
                    $filaListado .= sprintf($campo->getValor());
                }
                $filaListado .= "</td> \n";
            } else {
                // si es tipo fecha lo formatea
                // if ($campo->getTipo () == "fecha")
                if ($campo instanceof Campos_fecha) {
                    // print_r ($campo);
                    if ($fila[$campo->getCampo()] != "" and $fila[$campo->getCampo()] != "0000-00-00" and $fila[$campo->getCampo()] != "0000-00-00 00:00:00") {
                        if (strtotime($fila[$campo->getCampo()]) !== -1) {
                            // print_r ($campo);
                            // FIXME Urgente arreglar el formateo de fecha y que pasa con strtotime -1

                            // $fila[$campo->getCampo()] = date ($this->formatoFechaListado, strtotime ($fila[$campo->getCampo()]));
                            // $fila[$campo->getCampo()] = date ($this->formatoFechaListado, $fila[$campo->getCampo()]);
                            // $fila[$campo->getCampo()] = $fila[$campo->getCampo()];
                        }
                    }
                }

                $filaListado .= $campo->get_celda_dato();
            }
        }

        // FIXME - Deberia dar la opcion a una comprovacion individual sobre si mostrar o no el editar por cada fila.
        if ($this->mostrarEditar) {
            $this->iconoEditar = str_ireplace('{id}', $fila['ID'], $this->iconoEditar);
            $this->iconoEditar = str_ireplace('/img/', $this->directorioImagenes, $this->iconoEditar);

            // echo "<td class='celdaEditar'>" . $this->iconoEditar . $fila['ID'] . "</td> \n";
            // $html .= "<td class='celdaEditar' " . $campo->get_no_mostrar() . ">" . sprintf ($this->iconoEditar, $_SERVER['PHP_SELF'] . "?abm_editar=" . $fila['ID'] . $qsamb) . "</td> \n";
            // $html .= "<td class='celdaEditar' " . $campo->get_no_mostrar () . ">" . sprintf ($this->iconoEditar, "f_editar( '" . $_SERVER['PHP_SELF'] . "?abm_editar=" . $fila['ID'] . $qsamb . "')") . "</td> \n";
            $filaListado .= "<td class='celdaEditar' " . $campo->get_no_mostrar() . ">" . sprintf($this->iconoEditar, "f_editar( '" . $_SERVER['PHP_SELF'] . "?abm_editar=" . $fila['ID'] . "')") . "</td> \n";
            // $html .= "<td class='celdaEditar' " . $campo->get_no_mostrar() . "><a href='#' title='editar' onclick='f_editar()'>" . $this->iconoEditar . "</a></td> \n";
        }
        if ($this->mostrarBorrar) {
            $this->iconoBorrar = str_ireplace('{id}', $fila['ID'], $this->iconoBorrar);
            $this->iconoBorrar = str_ireplace('/img/', $this->directorioImagenes, $this->iconoBorrar);

            $filaListado .= "<td class='celdaBorrar' " . $campo->get_no_mostrar() . ">" . sprintf($this->iconoBorrar, "abmBorrar('" . $fila['ID'] . "', this)") . "</td> \n";
        }
        $filaListado .= "</tr> \n";

        return $filaListado;
    }

    /**
     * Genera el listado ABM con las funciones de editar, nuevo y borrar (segun la configuracion)
     *
     * @param string $sql
     *            Query SQL personalizado para el listado. Usando este query no se usa $adicionalesSelect
     * @param string $titulo
     *            Un titulo para mostrar en el encabezado del listado
     */
    public function generarAbm($sql = "", $titulo)
    {
        $abmsg = "";

        if (!empty($this->campos)) {
            $this->cargar_campos($this->campos);
        } elseif (empty($this->campo)) {
            throw new Exception("No paso ningun campo con el que trabajar.");
        }
        // print_r ($this->campo);

        $estado = $this->getEstadoActual();

        switch ($estado) {
            case "listado":
                $this->generarListado($titulo, $sql);

                break;

            case "alta":
                if (!$this->mostrarNuevo) {
                    die("Error"); // chequeo de seguridad, necesita estar activado mostrarNuevo
                }

                $this->generarFormAlta("Nuevo");
                break;

            case "editar":
                if (!$this->mostrarEditar) {
                    die("Error"); // chequeo de seguridad, necesita estar activado mostrarEditar
                }
                $this->generarFormModificacion($_GET['abm_editar'], "Editar");
                break;

            case "dbInsert":
                if (!$this->mostrarNuevo) {
                    die("Error"); // chequeo de seguridad, necesita estar activado mostrarNuevo
                }

                $r = $this->dbRealizarAlta();

                if ($r != 0) {

                    // el error 1062 es "Duplicate entry"
                    if ($this->db->errorNro() == 1062 and $this->textoRegistroDuplicado != "") {
                        $abmsg = "&abmsg=" . urlencode($this->textoRegistroDuplicado);
                    } else {
                        $abmsg = "&abmsg=" . urlencode($this->db->error());
                    }
                }

                unset($_POST['abm_enviar_formulario']);
                unset($_GET['abm_alta']);
                unset($_GET['abmsg']);

                if ($r == 0 && $this->redireccionarDespuesInsert != "") {
                    $this->redirect(sprintf($this->redireccionarDespuesInsert, $this->db->insert_id($this->campoId, $this->tabla)));
                } else {
                    $qsamb = http_build_query($_GET); // conserva las variables que existian previamente

                    $this->redirect("$_SERVER[PHP_SELF]?$qsamb$abmsg");
                }

                break;

            case "dbUpdate":
                if (!$this->mostrarEditar) {
                    die("Error"); // chequeo de seguridad, necesita estar activado mostrarEditar
                }

                $r = $this->dbRealizarModificacion($_POST['abm_id']);
                if ($r != 0) {
                    // el error 1062 es "Duplicate entry"
                    if ($this->db->errorNro() == 1062 and $this->textoRegistroDuplicado != "") {
                        $abmsg = "&abmsg=" . urlencode($this->textoRegistroDuplicado);
                    } else {
                        $abmsg = "&abmsg=" . urlencode($this->db->error());
                    }
                }

                unset($_POST['abm_enviar_formulario']);
                unset($_GET['abm_modif']);
                unset($_GET['abmsg']);
                if ($r == 0 && $this->redireccionarDespuesUpdate != "") {
                    $this->redirect(sprintf($this->redireccionarDespuesUpdate, $_POST['abm_id']));
                    // $this->redirect (sprintf ($this->redireccionarDespuesUpdate, $_POST[$fila['ID']]));
                } else {
                    $qsamb = http_build_query($_GET); // conserva las variables que existian previamente
                    $this->redirect("$_SERVER[PHP_SELF]?$qsamb$abmsg");
                }

                break;

            case "dbDelete":
                if (!$this->mostrarBorrar) {
                    die("Error"); // chequeo de seguridad, necesita estar activado mostrarBorrar
                }

                $r = $this->dbBorrarRegistro($_GET['abm_borrar']);
                if ($r != 0) {
                    $abmsg = "&abmsg=" . urlencode($this->db->error());
                }

                unset($_GET['abm_borrar']);

                if ($r == 0 && $this->redireccionarDespuesDelete != "") {
                    $this->redirect(sprintf($this->redireccionarDespuesDelete, $_GET['abm_borrar']));
                } else {
                    $qsamb = http_build_query($_GET); // conserva las variables que existian previamente
                    $this->redirect("$_SERVER[PHP_SELF]?$qsamb$abmsg");
                }

                break;

            case "exportar":
                // FIXME esto esta mal, lo modifico y dejo la nota para revisarlo.
                // $this->exportar_verificar($camposWhereBuscar);
                $this->exportar_verificar();
                break;

            default:
                $this->generarListado($titulo, $sql);
                break;
        }
    }

    /**
     * Procesa los datos del formulario de alta para realizar la insercion
     *
     * @return void|string En caso de haber algun problema devuelve un error
     */
    private function dbRealizarAlta()
    {
        if (!$this->formularioEnviado()) {
            return;
        }

        if (isset($_POST)) {
            $_POST = @$this->limpiarParaSql($_POST);
        }

        // foreach ($this->campos as $campo)
        foreach ($this->campo as &$campo) {
            if ($campo->existeDato("joinTable")) {
                // $tablas[] = $campo->getJoinTable();

                $tablas[] = $this->tabla;
            } else {
                $tablas[] = $this->tabla;
            }
        }

        $tablas = array_unique($tablas);
        /*
         * FIXME Verificar para que funcione correctamente, hoy no lo hace
         */
        foreach ($tablas as $tabla) {

            $camposSql = "";
            $valoresSql = "";
            $sql = "";

            $sql = " INSERT INTO " . $tabla . $this->dbLink . "  \n";

            // foreach ($this->campos as $campo)
            foreach ($this->campo as &$campo) {
                /*
                 * FIXME Cuando es un JOIN deberia verificar si existe en la otra tabla y no lo hace genera mal las consultas
                 */
                if (!$campo->existeDato("joinTable") or $campo->getJoinTable() == "") {
                    $campo->setJoinTable($this->tabla);
                }

                // if ($campo->existeDato ("joinTable") and $campo->getJoinTable () != $tabla and $campo->getTipo () != 'extra' and $campo->getTipo () != 'dbCombo')
                // if ($campo->existeDato ("joinTable") and $campo->getJoinTable () != $tabla and $campo->getTipo () != 'extra' and ($campo instanceof Campos_dbCombo))
                // {

                if (!is_array($this->campoId)) {
                    if ($campo->getCampo() === $this->campoId) {
                        $hayID = true;
                    } elseif ($campo->getCampo() != "" and isset($this->campoId) and is_array($campo->getCampo()) and (in_array($campo->getCampo(), $this->campoId))) {
                        $hayID = true;
                    }
                }

                if ($campo->isNoNuevo() == true) {
                    continue;
                }

                if ($campo->getTipo() == '') {
                    continue;
                }

                if (($campo instanceof Campos_upload) and $campo->isCargarEnBase() != true) {
                    $campo->realizarCarga();

                    continue;
                } elseif ($campo instanceof Campos_upload) {
                    $valor = $campo->realizarCarga();
                } else {
                    $campo->setValor($_POST[$campo->getCampo()]);
                    $valor = $campo->getValor();
                }

                // chequeo de campos requeridos
                if ($campo->isRequerido() and trim($valor) == "") {
                    // genera el query string de variables previamente existentes
                    $get = $_GET;
                    unset($get['abmsg']);
                    unset($get['abm_alta']);
                    $qsamb = http_build_query($get);
                    if ($qsamb != "") {
                        $qsamb = "&" . $qsamb;
                    }

                    $this->redirect("$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb&abmsg=" . urlencode(sprintf($this->textoCampoRequerido, $campo->getTitulo())));
                }

                if ($camposSql != "") {
                    $camposSql .= ", \n";
                }

                if ($valoresSql != "") {
                    $valoresSql .= ", \n";
                }

                if ($campo->getCustomFuncionValor() != "") {
                    $valor = call_user_func_array($campo->getCustomFuncionValor(), array(
                        $valor
                    ));
                }

                $camposSql .= $campo->getCampo();

                if (trim($valor) == '') {
                    $valoresSql .= " NULL";
                } else {
                    // Se agrega la comparativa para que en caso de sel bases de oracle haga la conversion del formato de fecha
                    // if ($campo->getTipo() == 'fecha' and $this->db->dbtype == 'oracle')
                    // if ($campo->getTipo () == 'fecha')
                    if ($campo instanceof Campos_fecha) {
                        // $valoresSql .= "TO_DATE('" . $valor . "', 'RRRR-MM-DD')";
                        $valoresSql .= $this->db->toDate($valor, 'RRRR-MM-DD');
                    } else {
                        $valoresSql .= " '" . $valor . "' ";
                    }
                }
                // }
                // else
                // {
                // if (!is_array ($this->campoId))
                // {
                // if ($campo->getCampo () === $this->campoId)
                // {
                // $hayID = true;
                // }
                // elseif ($campo->getCampo () != "" and isset ($this->campoId) and is_array ($campo->getCampo ()) and (in_array ($campo->getCampo (), $this->campoId)))
                // {
                // $hayID = true;
                // }
                // }

                // if ($campo->isNoNuevo () == true)
                // {
                // continue;
                // }

                // if ($campo->getTipo () == '')
                // {
                // continue;
                // }

                // if (($campo instanceof Campos_upload) and $campo->isCargarEnBase () != true)
                // {
                // $campo->realizarCarga ();

                // continue;
                // }
                // elseif ($campo instanceof Campos_upload)
                // {
                // $valor = $campo->realizarCarga ();
                // }
                // else
                // {
                // $valor = $_POST[$campo->getCampo ()];
                // }

                // // chequeo de campos requeridos
                // if ($campo->isRequerido () == true and trim ($valor) == "")
                // {
                // // genera el query string de variables previamente existentes
                // $get = $_GET;
                // unset ($get['abmsg']);
                // unset ($get['abm_alta']);
                // $qsamb = http_build_query ($get);
                // if ($qsamb != "")
                // {
                // $qsamb = "&" . $qsamb;
                // }

                // $this->redirect ("$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb&abmsg=" . urlencode (sprintf ($this->textoCampoRequerido, $campo->getTitulo ())));
                // }

                // if ($camposSql != "")
                // {
                // $camposSql .= ", \n";
                // }

                // if ($valoresSql != "")
                // {
                // $valoresSql .= ", \n";
                // }

                // if ($campo->getCustomFuncionValor () != "")
                // {
                // $valor = call_user_func_array ($campo->getCustomFuncionValor (), array (
                // $valor
                // ));
                // }

                // $camposSql .= $campo->getCampo ();

                // if (trim ($valor) == '')
                // {
                // $valoresSql .= " NULL";
                // }
                // else
                // {
                // // Se agrega la comparativa para que en caso de sel bases de oracle haga la conversion del formato de fecha
                // // if ($campo->getTipo() == 'fecha' and $this->db->dbtype == 'oracle')
                // // if ($campo->getTipo () == 'fecha')
                // if ($campo instanceof Campos_fecha)
                // {
                // // $valoresSql .= "TO_DATE('" . $valor . "', 'RRRR-MM-DD')";
                // $valoresSql .= $this->db->toDate ($valor, 'RRRR-MM-DD');
                // }
                // else
                // {
                // $valoresSql .= " '" . $valor . "' ";
                // }
                // }
                // }
            }

            if (!is_array($this->campoId) and strpos($camposSql, $this->campoId) == false) {
                if ($camposSql != "") {
                    $camposSql .= ", \n";
                }

                if ($valoresSql != "") {
                    $valoresSql .= ", \n";
                }

                if ($hayID == false) {
                    $camposSql .= $this->campoId;

                    $idVal = $this->db->insert_id($this->campoId, $this->tabla . insert_id);
                    $idVal = $idVal + 1;
                    $valoresSql .= " '" . $idVal . "' ";
                }
            }

            $camposSql = trim($camposSql, ", \n");
            $valoresSql = trim($valoresSql, ", \n");

            $sql .= " (" . $camposSql . ")";

            $sql .= $this->adicionalesInsert;

            $sql .= " VALUES \n (" . $valoresSql . ")";

            if ($camposSql != "") {
                // print_r ($sql);
                // echo "<Br /><Br />";
                // exit ();
                $this->db->query($sql);

                if (isset($this->callbackFuncInsert)) {
                    // call_user_func_array($this->callbackFuncInsert, array(
                    // $id,
                    // $this->tabla
                    // ));
                    // XXX lo remplazo para ver si funciona de la misma manera.
                    call_user_func_array($this->callbackFuncInsert, array(
                        $this->campoId,
                        $this->tabla
                    ));
                }
            }
        }
        return $this->db->errorNro();
    }

    /**
     * Arma la consulta y realiza el update en la tabla.
     *
     * @param int $id
     * @return void|string
     */
    private function dbRealizarModificacion($id)
    {
        if (!$this->formularioEnviado()) {
            return false;
        }

        if (trim($id) == '') {
            throw new Exception('Parametro id vacio en dbRealizarModificacion');
        }

        $id = $this->limpiarParaSql($id);

        $_POST = $this->limpiarParaSql($_POST);

        // foreach ($this->campos as $campo)
        foreach ($this->campo as &$campo) {
            // if ($campo->existeDato ("joinTable") and strtolower ($campo->getTipo ()) != strtolower ('dbCombo'))

            if ($campo->existeDato("joinTable") and !($campo instanceof Campos_dbCombo)) {
                $tablas[] = $campo->getJoinTable();
            } else {
                $tablas[] = $this->tabla;
            }
        }

        $tablas = array_unique($tablas);

        foreach ($tablas as $tabla) {

            $sql = "";
            $camposSql = "";

            $sql = "UPDATE " . $tabla . $this->dbLink . " SET \n";

            // por cada campo...
            // foreach ($this->campos as $campo)
            foreach ($this->campo as &$campo) {
                if (!$campo->existeDato("joinTable")) {
                    $campo->setJoinTable($this->tabla);
                }

                // if ($campo->getJoinTable () == $tabla or $campo->getTipo () == 'dbCombo')
                if ($campo->getJoinTable() == $tabla or ($campo instanceof Campos_dbCombo)) {

                    if ($campo->isNoEditar() == TRUE or $campo->isNoMostrarEditar() == TRUE) {
                        continue;
                    }
                    // if (!isset ($campo->getTipo()) or $campo->getTipo() == '' or $campo->getTipo() == 'upload')
                    // {
                    // continue;
                    // }

                    // if (!$campo->getTipo () or ($campo->getTipo () == 'upload' and $campo->isCargarEnBase () != true))
                    if (!$campo->getTipo() or (($campo instanceof Campos_upload) and $campo->isCargarEnBase() != true)) {
                        continue;
                    } elseif (($campo instanceof Campos_upload) and $campo->isCargarEnBase() == true) {
                        // if (isset ($campo['grabarSinExtencion']) and $campo['grabarSinExtencion'] == TRUE)
                        if ($campo->isGrabarSinExtencion() == TRUE) {
                            $partes_nombre = explode('.', $_FILES[$campo->getCampo()]['name']);
                            $valor = $partes_nombre[0];
                        } else {
                            $valor = $_FILES[$campo->getCampo()]['name'];
                        }

                        // Iniciamos el upload del archivo
                        if ($campo->getNombreArchivo() != "") {
                            $campo->setNombreArchivo(str_replace("{{", "\$_REQUEST['", $campo->getNombreArchivo()));
                            $campo->setNombreArchivo(str_replace("}}", "']", $campo->getNombreArchivo()));

                            $nombre = eval($campo->getNombreArchivo());
                            // FIXME revisar que este data no deberia ir aca, lo comento por si llega a explotar algo.
                            // $nombre = $data;
                            if ($nombre == "") {
                                $nombre = $campo->getNombreArchivo();
                            }
                            $valor = $nombre;
                            if (isset($partes_nombre)) {
                                $nombre = $nombre . "." . end($partes_nombre);
                            }
                        }

                        if (isset($_FILES[$campo->getCampo()]) and $_FILES[$campo->getCampo()]['size'] > 1) {
                            $nombre_tmp = $_FILES[$campo->getCampo()]['tmp_name'];
                            $tamano = $_FILES[$campo->getCampo()]['size'];
                            // FIXME esto va a utilizarse cuando se agregue el control de tipo de archivo
                            // Debe ser manejado por la clase del tipo de campo.
                            // $tipo = $_FILES[$campo->getCampo()]['type'];

                            if ($campo->getNombreArchivo() == "") {
                                $nombre = $_FILES[$campo->getCampo()]['name'];
                            }

                            if ($campo->getUbicacionArchivo() != "") {
                                $estructura = $campo->getUbicacionArchivo();
                            } else {
                                $estructura = "";
                            }

                            // FIXME urgente!!
                            // if (isset ($campo['tipoArchivo']) and $campo['tipoArchivo'] != "")
                            // {
                            // $tipo_correcto = preg_match ('/^' . $campo['tipoArchivo'] . '$/', $tipo);
                            // }

                            if ($campo->getLimiteArchivo() != "") {
                                $limite = $campo->getLimiteArchivo() * 1024;
                            } else {
                                $limite = 50000 * 1024;
                            }

                            if ($tamano <= $limite) {

                                if ($_FILES[$campo->getCampo()]['error'] > 0) {
                                    echo 'Error: ' . $_FILES[$campo->getCampo()]['error'] . '<br/>' . var_dump($_FILES) . " en linea " . __LINE__;
                                } else {

                                    if (file_exists($nombre)) {
                                        echo '<br/>El archivo ya existe: ' . $nombre;
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
                                echo 'Archivo inv&aacute;lido';
                            }
                        }

                        // Finalizamos el upload del archivo
                    } else {
                        // if ($campo->getCampo () == "")
                        // {
                        // print_r ($campo);
                        // }

                        $valor = $_POST[$campo->getCampo()];
                    }
                    // chequeo de campos requeridos
                    if ($campo->isRequerido() == true and trim($valor) == "") {
                        // genera el query string de variables previamente existentes
                        $get = $_GET;
                        unset($get['abmsg']);
                        unset($get['abm_modif']);
                        $qsamb = http_build_query($get);
                        if ($qsamb != "") {
                            $qsamb = "&" . $qsamb;
                        }

                        $this->redirect("$_SERVER[PHP_SELF]?abm_editar=$id$qsamb&abmsg=" . urlencode(sprintf($this->textoCampoRequerido, $campo->getTitulo())));
                    }

                    if ($camposSql != "") {
                        $camposSql .= ", \n";
                    }

                    if ($campo->getCustomFuncionValor() != "") {
                        $valor = call_user_func_array($campo->getCustomFuncionValor(), array(
                            $valor
                        ));
                    }

                    if ($campo instanceof Campos_dbCombo) {
                        $valor = $_POST[$campo->nombreJoinLargo()];
                    }

                    if (trim($valor) == '') {
                        $camposSql .= $campo->getCampo() . " = NULL";
                    } else {
                        // if ($campo->getTipo () == 'fecha')
                        if ($campo instanceof Campos_fecha) {
                            // $camposSql .= $campo->getCampo() . " = TO_DATE('" . $valor . "', 'yyyy-mm-dd')";
                            $camposSql .= $campo->getCampo() . " = " . $this->db->toDate($valor, $campo->getMascara());
                        } // elseif ($campo->getTipo () == 'numero' or is_numeric ($valor))
                        elseif (($campo instanceof Campos_numero) or is_numeric($valor)) {
                            $camposSql .= $campo->getCampo() . " = " . $valor . "";
                        } else {
                            $camposSql .= $campo->getCampo() . " = '" . $valor . "'";
                        }
                    }
                }
            }

            $sql .= $camposSql;

            if (is_array($this->campoId)) {
                $this->campoId = $this->convertirIdMultiple($this->campoId, $this->tabla);

                $this->campoId = substr($this->campoId, 0, -6);
            }

            /*
             * FIXME - no tengo idea de donde sale $this->adicionalesUpdate asi que se elimino para que no tire error
             * hay que verificar bien si deberia agregarse y hacerlo.
             * Si no me equivoco deveria funcionar exactamente ingual que adicionalesSelect
             * $sql .= $this->adicionalesUpdate . " WHERE " . $this->campoId . "='" . $id . "' " . $this->adicionalesWhereUpdate;
             */

            if (is_numeric($id)) {
                $sql .= " WHERE " . $this->campoId . "=" . $id . " " . $this->adicionalesWhereUpdate;
            } else {
                $sql .= " WHERE " . $this->campoId . "='" . $id . "' " . $this->adicionalesWhereUpdate;
            }

            // ////////////////////////////////
            if ($camposSql != "") {
                $stid = $this->db->query($sql);
                if ($this->db->affected_rows($stid) == 1) {
                    $fueAfectado = true;

                    // si cambio la id del registro
                    if ($this->campoIdEsEditable and isset($_POST[$this->campoId]) and $id != $_POST[$this->campoId]) {
                        $id = $_POST[$this->campoId];
                    }
                }

                // upload
                if ($id !== false) {
                    // foreach ($this->campos as $campo)
                    foreach ($this->campo as &$campo) {
                        // if (!$campo->getTipo () == 'upload')
                        if (!($campo instanceof Campos_upload)) {
                            continue;
                        }

                        if ($campo->getUploadFunction()) {
                            // FIXME revisar para que es esto y corregirlo ya que no se usa.
                            // $r = call_user_func_array($campo->getUploadFunction(), array(
                            // $id,
                            // $this->tabla
                            // ));
                            // Lo remplazo con lo siguiente ya que me parece que funciona de la misma manera y no necesito registrar el resultado.
                            call_user_func_array($campo->getUploadFunction(), array(
                                $id,
                                $this->tabla
                            ));
                        }
                    }
                }

                if (isset($this->callbackFuncUpdate)) {
                    call_user_func_array($this->callbackFuncUpdate, array(
                        $id,
                        $this->tabla,
                        $fueAfectado
                    ));
                }
            }
            // ///////////////////
        }
        return $this->db->errorNro();
    }

    /**
     * Elimina un registro con un id dado
     *
     * @param int $id
     *            - id del registro a eliminar.
     *
     * @return int devuelve codigo de error en caso de ser necesario
     */
    private function dbBorrarRegistro($id)
    {
        $id = $this->limpiarParaSql($id);

        $parametros = array(
            $id
        );

        if (isset($this->callbackFuncDelete)) {
            call_user_func_array($this->callbackFuncDelete, array(
                $id,
                $this->tabla
            ));
        }

        if (is_array($this->campoId)) {
            $this->campoId = $this->convertirIdMultiple($this->campoId, $this->tabla);

            $this->campoId = substr($this->campoId, 0, -6);
        }

        // $sql = "DELETE FROM " . $this->tabla . $this->dbLink . " WHERE " . $this->campoId . "='" . $id . "' " . $this->adicionalesWhereDelete;
        $sql = "DELETE FROM " . $this->tabla . $this->dbLink . " WHERE " . $this->campoId . " = :id " . $this->adicionalesWhereDelete;

        $this->db->query($sql, true, $parametros);

        return $this->db->errorNro();
    }

    /**
     * Verifica el query string para ver si hay que llamar a la funcion de exportar
     * Esta funcion debe llamarse despues de setear los valores de la classe y antes de que se envie cualquier
     * salida al navegador, de otra manera no se podrian enviar los Headers
     * Nota: El nombre de la funcion quedo por compatibilidad
     *
     * @param string $camposWhereBuscar
     */
    public function exportar_verificar($camposWhereBuscar = "")
    {
        $estado = $this->getEstadoActual();
        if ($estado == "exportar" and $this->mostrarListado) {
            $this->exportar($_GET['abm_exportar'], $_GET['buscar']);
        }
    }

    /**
     * Retorna true si el formulario fue enviado y estan disponibles los datos enviados
     *
     * @return boolean
     */
    private function formularioEnviado()
    {
        if (isset($_POST['abm_enviar_formulario'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Escapa de un array todos los caracteres especiales de una cadena para su uso en una sentencia SQL
     *
     * @example $_REQUEST = limpiarParaSql($_REQUEST);
     *
     * @param String[] $param
     * @return String[] - Depende del parametro recibido, un array con los datos remplazados o un String
     */
    private function limpiarParaSql($param)
    {
        if (is_array($param)) {
            $result = array_map(array(
                $this,
                __FUNCTION__
            ), $param);
        } else {
            $result = $this->db->real_escape_string($param);
        }

        return $result;
        // return is_array($param) ? array_map (array ($this, __FUNCTION__ ), $param) : $this->db->real_escape_string ($param);
    }

    /**
     * Eliminamos cualquier etiqueta html que pueda haber
     *
     * @param string $text
     *            - texto a analizar.
     * @param array $tags
     *            - Etiquetas a elininar en el texto dado.
     * @return string
     */
    private function strip_selected_tags(string $text, array $tags = array())
    {
        $found = array();

        $args = func_get_args();
        $text = array_shift($args);
        $tags = func_num_args() > 2 ? array_diff($args, array(
            $text
        )) : (array) $tags;
        foreach ($tags as $tag) {
            while (preg_match('/<' . $tag . '(|\W[^>]*)>(.*)<\/' . $tag . '>/iusU', $text, $found)) {
                $text = str_replace($found[0], $found[2], $text);
            }
        }

        return preg_replace('/(<(' . join('|', $tags) . ')(|\W.*)\/>)/iusU', '', $text);
    }

    /**
     * Redirecciona a $url
     *
     * @param string $url
     *
     */
    private function redirect($url)
    {
        if ($this->metodoRedirect == "header") {
            header("Location:$url");
            exit();
        } else {
            echo "<HTML><HEAD><META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$url\"></HEAD></HTML>";
            exit();
        }
    }

    /**
     * Carga los datos pasados por medio de un array a un array de la clase con un listado de objetos campo
     *
     * @param String[] $campos
     */
    private function cargar_campos($campos)
    {
        foreach ($campos as $camp) {
            if (!$camp['tipo']) {
                $camp['tipo'] = "texto";
            }

            switch (strtolower($camp['tipo'])) {
                // case "" :
                case "texto":
                    $this->campo[] = new Campos_texto($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "bit":
                    $this->campo[] = new Campos_bit($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "combo":
                    $this->campo[] = new Campos_combo($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "dbcombo":
                    $this->campo[] = new Campos_dbCombo($camp, $this->db);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "password":
                    $this->campo[] = new Campos_password($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "upload":
                    $this->campo[] = new Campos_upload($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "moneda":
                    $this->campo[] = new Campos_moneda($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "numero":
                    $this->campo[] = new Campos_numero($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "rownum":
                    $this->campo[] = new Campos_rownum($camp);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "fecha":
                    $this->campo[] = new Campos_fecha($camp, $this->db);
                    $i = Funciones::endKey($this->campo);
                    break;

                case "textarea":
                    $this->campo[] = new Campos_textarea($camp);
                    $i = Funciones::endKey($this->campo);
                    break;
            }

            $this->campo[$i]->setTabla($this->tabla);
        }
    }

    /**
     * Genera el where de la busqueda????
     *
     * FIXME revisar esta funcion porque ni se usa ni funciona ya que en ningun momento recorre los campos, ademas hay que actualizarla al uso de clases.
     *
     * @return string
     */
    private function generaWhereBuscar($camposWhereBuscar = "")
    {
        // $retorno = Array ();
        // FIXME por ahora inicializo el $i en nulo y despues vemos
        $i = "";

        if ((isset($_REQUEST['c_' . $this->campos[$i]['campo']]) and (trim($_REQUEST['c_' . $this->campos[$i]['campo']]) != '')) or (isset($_REQUEST['c_busquedaTotal']) and (trim($_REQUEST['c_busquedaTotal']) != ''))) {
            if (isset($_REQUEST['c_' . $this->campos[$i]['campo']])) {
                $valorABuscar = $this->limpiarParaSql($_REQUEST['c_' . $this->campos[$i]['campo']]);

                if (isset($camposWhereBuscar)) {
                    $camposWhereBuscar .= " AND ";
                } else {
                    $camposWhereBuscar = " ";
                }
            } elseif (isset($_REQUEST['c_busquedaTotal'])) {
                $valorABuscar = $this->limpiarParaSql($_REQUEST['c_busquedaTotal']);

                if (isset($camposWhereBuscar)) {
                    $camposWhereBuscar .= " OR ";
                } else {
                    $camposWhereBuscar = " ";
                }
            }

            // $estaBuscando = true;

            // quita la variable de paginado, ya que estoy buscando y no se aplica
            // unset($_REQUEST['r']);
            // unset($_POST['r']);
            // unset($_GET['r']);

            if (isset($this->campos[$i]['buscarUsarCampo']) and ($this->campos[$i]['buscarUsarCampo'] != "")) {
                $camposWhereBuscar .= "UPPER(" . $this->campos[$i]['buscarUsarCampo'] . ")";
            } else {
                if ($this->campos[$i]['tipo'] == 'fecha') {
                    // $camposWhereBuscar .= $this->db->toChar ($this->tabla . "." . $this->campos[$i]['campo'], "", "DD/MM/YYYY");
                    $camposWhereBuscar .= $this->db->toChar($this->tabla . "." . $this->campos[$i]['campo'], "", $this->formatoFechaListado);
                    // $camposWhereBuscar .= "TO_CHAR(" . $this->tabla . "." . $this->campos[$i]['campo'] . ", 'DD/MM/YYYY')";
                    // $camposWhereBuscar .= "TO_CHAR(" . $this->tabla . "." . $this->campos[$i]['campo'] . ", 'YYYY-MM-DD')"; // @iberlot 2016/10/18 se cambia para que funcionen los nuevos parametros de busqueda

                    $valorABuscar = str_replace("/", "%", $valorABuscar);
                    $valorABuscar = str_replace("-", "%", $valorABuscar);
                    $valorABuscar = str_replace(" ", "%", $valorABuscar);
                } else {
                    $camposWhereBuscar .= "UPPER(" . $this->tabla . "." . $this->campos[$i]['campo'] . ")";
                }
            }

            $camposWhereBuscar .= " ";
            if ($this->campos[$i]->existeDato('buscarOperador') and strtolower($this->campos[$i]['buscarOperador']) != 'like') {
                $camposWhereBuscar .= $this->campos[$i]['buscarOperador'] . " UPPER('" . $valorABuscar . "')";
            } else {
                $valorABuscar = str_replace(" ", "%", $valorABuscar);
                $camposWhereBuscar .= "LIKE UPPER('%" . $valorABuscar . "%')";
            }
        }

        return $camposWhereBuscar;
    }

    /*
     * **********************************************************************************
     * GETTERS AND SETTERS
     * **********************************************************************************
     */

    /**
     * Retorna el valor del atributo $campoIdEsEditable
     *
     * @return boolean $campoIdEsEditable el dato de la variable.
     */
    public function isCampoIdEsEditable()
    {
        return $this->campoIdEsEditable;
    }

    /**
     * Retorna el valor del atributo $mostrarEditar
     *
     * @return boolean $mostrarEditar el dato de la variable.
     */
    public function isMostrarEditar()
    {
        return $this->mostrarEditar;
    }

    /**
     * Retorna el valor del atributo $mostrarNuevo
     *
     * @return boolean $mostrarNuevo el dato de la variable.
     */
    public function isMostrarNuevo()
    {
        return $this->mostrarNuevo;
    }

    /**
     * Retorna el valor del atributo $mostrarBorrar
     *
     * @return boolean $mostrarBorrar el dato de la variable.
     */
    public function isMostrarBorrar()
    {
        return $this->mostrarBorrar;
    }

    /**
     * Retorna el valor del atributo $mostrarListado
     *
     * @return boolean $mostrarListado el dato de la variable.
     */
    public function isMostrarListado()
    {
        return $this->mostrarListado;
    }

    /**
     * Setter del parametro $campoIdEsEditable de la clase.
     *
     * @param boolean $campoIdEsEditable
     *            dato a cargar en la variable.
     */
    public function setCampoIdEsEditable(bool $campoIdEsEditable)
    {
        $this->campoIdEsEditable = $campoIdEsEditable;
    }

    /**
     * Setter del parametro $mostrarEditar de la clase.
     *
     * @param boolean $mostrarEditar
     *            dato a cargar en la variable.
     */
    public function setMostrarEditar(bool $mostrarEditar)
    {
        $this->mostrarEditar = $mostrarEditar;
    }

    /**
     * Setter del parametro $mostrarNuevo de la clase.
     *
     * @param boolean $mostrarNuevo
     *            dato a cargar en la variable.
     */
    public function setMostrarNuevo(bool $mostrarNuevo)
    {
        $this->mostrarNuevo = $mostrarNuevo;
    }

    /**
     * Setter del parametro $mostrarBorrar de la clase.
     *
     * @param boolean $mostrarBorrar
     *            dato a cargar en la variable.
     */
    public function setMostrarBorrar(bool $mostrarBorrar)
    {
        $this->mostrarBorrar = $mostrarBorrar;
    }

    /**
     * Setter del parametro $mostrarListado de la clase.
     *
     * @param boolean $mostrarListado
     *            dato a cargar en la variable.
     */
    public function setMostrarListado(bool $mostrarListado)
    {
        $this->mostrarListado = $mostrarListado;
    }

    /**
     * Retorna el valor del atributo $busquedaTotal
     *
     * @return boolean $busquedaTotal el dato de la variable.
     */
    public function isBusquedaTotal()
    {
        return $this->busquedaTotal;
    }

    /**
     * Setter del parametro $busquedaTotal de la clase.
     *
     * @param boolean $busquedaTotal
     *            dato a cargar en la variable.
     */
    public function setBusquedaTotal($busquedaTotal)
    {
        $this->busquedaTotal = $busquedaTotal;
    }

    /**
     * Retorna el valor del atributo $adicionalesSelect
     *
     * @return mixed $adicionalesSelect el dato de la variable.
     */
    public function getAdicionalesSelect(): string
    {
        return $this->adicionalesSelect;
    }

    /**
     * Setter del parametro $adicionalesSelect de la clase.
     *
     * @param string $adicionalesSelect
     *            dato a cargar en la variable.
     */
    public function setAdicionalesSelect(string $adicionalesSelect)
    {
        $this->adicionalesSelect = $adicionalesSelect;
    }

    /**
     * Agrega el valor pasado al parametro $adicionalesSelect de la clase.
     *
     * @param string $adicionalesSelect
     *            dato a cargar en la variable.
     */
    public function addAdicionalesSelect(string $adicionalesSelect)
    {
        if (strpos(substr($adicionalesSelect, 0, 7), "AND ") === false) {
            $this->adicionalesSelect .= " AND " . $adicionalesSelect;
        } else {
            $this->adicionalesSelect .= " " . $adicionalesSelect;
        }
    }

    /**
     * Retorna el valor del atributo $textoBitTrue
     *
     * @return string $textoBitTrue el dato de la variable.
     */
    public function getTextoBitTrue(): string
    {
        return $this->textoBitTrue;
    }

    /**
     * Setter del parametro $textoBitTrue de la clase.
     *
     * @param string $textoBitTrue
     *            dato a cargar en la variable.
     */
    public function setTextoBitTrue(string $textoBitTrue)
    {
        $this->textoBitTrue = $textoBitTrue;
    }

    /**
     * Retorna el valor del atributo $textoBitFalse
     *
     * @return string $textoBitFalse el dato de la variable.
     */
    public function getTextoBitFalse(): string
    {
        return $this->textoBitFalse;
    }

    /**
     * Setter del parametro $textoBitFalse de la clase.
     *
     * @param string $textoBitFalse
     *            dato a cargar en la variable.
     */
    public function setTextoBitFalse(string $textoBitFalse)
    {
        $this->textoBitFalse = $textoBitFalse;
    }

    /**
     * Retorna el valor del atributo $orderByPorDefecto
     *
     * @return string $orderByPorDefecto el dato de la variable.
     */
    public function getOrderByPorDefecto(): string
    {
        return $this->orderByPorDefecto;
    }

    /**
     * Setter del parametro $orderByPorDefecto de la clase.
     *
     * @param string $orderByPorDefecto
     *            dato a cargar en la variable.
     */
    public function setOrderByPorDefecto(string $orderByPorDefecto)
    {
        $this->orderByPorDefecto = $orderByPorDefecto;
    }

    /**
     * Retorna el valor del atributo $campoId
     *
     * @return multitype:string $campoId el dato de la variable.
     */
    public function getCampoId()
    {
        return $this->campoId;
    }

    /**
     * Setter del parametro $campoId de la clase.
     *
     * @param multitype:string $campoId
     *            dato a cargar en la variable.
     */
    public function setCampoId($campoId)
    {
        $this->campoId = $campoId;
    }

    /**
     * Agrega un item al array de campos ID
     *
     * @param string $campoId
     */
    public function addCampoId(string $campoId)
    {
        if (!isset($this->campoId)) {
            $this->campoId = array();
        }

        $this->campoId[] = $campoId;
    }

    /**
     * Retorna el valor del atributo $tabla
     *
     * @return string $tabla el dato de la variable.
     */
    public function getTabla(): string
    {
        return $this->tabla;
    }

    /**
     * Setter del parametro $tabla de la clase.
     *
     * @param string $tabla
     *            dato a cargar en la variable.
     */
    public function setTabla(string $tabla)
    {
        $this->tabla = $tabla;
    }

    /**
     * Retorna el valor del atributo $registros_por_pagina
     *
     * @return int $registros_por_pagina el dato de la variable.
     */
    public function getRegistros_por_pagina(): int
    {
        return $this->registros_por_pagina;
    }

    /**
     * Setter del parametro $registros_por_pagina de la clase.
     *
     * @param int $registros_por_pagina
     *            dato a cargar en la variable.
     */
    public function setRegistros_por_pagina(int $registros_por_pagina)
    {
        $this->registros_por_pagina = $registros_por_pagina;
    }

    /**
     * Retorna el valor del atributo $redireccionarDespuesInsert
     *
     * @return string $redireccionarDespuesInsert el dato de la variable.
     */
    public function getRedireccionarDespuesInsert(): string
    {
        return $this->redireccionarDespuesInsert;
    }

    /**
     * Setter del parametro $redireccionarDespuesInsert de la clase.
     *
     * @param string $redireccionarDespuesInsert
     *            dato a cargar en la variable.
     */
    public function setRedireccionarDespuesInsert(string $redireccionarDespuesInsert)
    {
        $this->redireccionarDespuesInsert = $redireccionarDespuesInsert;
    }

    /**
     * Retorna el valor del atributo $redireccionarDespuesUpdate
     *
     * @return string $redireccionarDespuesUpdate el dato de la variable.
     */
    public function getRedireccionarDespuesUpdate(): string
    {
        return $this->redireccionarDespuesUpdate;
    }

    /**
     * Setter del parametro $redireccionarDespuesUpdate de la clase.
     *
     * @param string $redireccionarDespuesUpdate
     *            dato a cargar en la variable.
     */
    public function setRedireccionarDespuesUpdate(string $redireccionarDespuesUpdate)
    {
        $this->redireccionarDespuesUpdate = $redireccionarDespuesUpdate;
    }

    /**
     * Retorna el valor del atributo $redireccionarDespuesDelete
     *
     * @return string $redireccionarDespuesDelete el dato de la variable.
     */
    public function getRedireccionarDespuesDelete(): string
    {
        return $this->redireccionarDespuesDelete;
    }

    /**
     * Setter del parametro $redireccionarDespuesDelete de la clase.
     *
     * @param string $redireccionarDespuesDelete
     *            dato a cargar en la variable.
     */
    public function setRedireccionarDespuesDelete(string $redireccionarDespuesDelete)
    {
        $this->redireccionarDespuesDelete = $redireccionarDespuesDelete;
    }

    /**
     * Retorna el valor del atributo $customJoin
     *
     * @return string $customJoin el dato de la variable.
     */
    public function getCustomJoin(): string
    {
        return $this->customJoin;
    }

    /**
     * Setter del parametro $customJoin de la clase.
     *
     * @param string $customJoin
     *            dato a cargar en la variable.
     */
    public function setCustomJoin(string $customJoin)
    {
        $this->customJoin = $customJoin;
    }

    /**
     * Retorna el valor del atributo $adicionalesWhereDelete
     *
     * @return string $adicionalesWhereDelete el dato de la variable.
     */
    public function getAdicionalesWhereDelete(): string
    {
        return $this->adicionalesWhereDelete;
    }

    /**
     * Setter del parametro $adicionalesWhereDelete de la clase.
     *
     * @param mixed $adicionalesWhereDelete
     *            dato a cargar en la variable.
     */
    public function setAdicionalesWhereDelete(string $adicionalesWhereDelete)
    {
        $this->adicionalesWhereDelete = $adicionalesWhereDelete;
    }

    /**
     * Setter del parametro $adicionalesWhereDelete de la clase.
     *
     * @param string $adicionalesWhereDelete
     *            dato a cargar en la variable.
     */
    public function addAdicionalesWhereDelete(string $adicionalesWhereDelete)
    {
        if (strpos(substr($adicionalesWhereDelete, 0, 7), "AND ") === false) {
            $this->adicionalesWhereDelete .= " AND " . $adicionalesWhereDelete;
        } else {
            $this->adicionalesWhereDelete .= " " . $adicionalesWhereDelete;
        }
    }

    /**
     * Setter del parametro $campos de la clase.
     *
     * @param mixed $campos
     *            dato a cargar en la variable.
     */
    public function setCampos($campos)
    {
        $this->campos = $campos;
    }

    /**
     * Retorna el valor del atributo $campos
     *
     * @return mixed $campos el dato de la variable.
     */
    public function getCampos()
    {
        return $this->campos;
    }

    /**
     * El ultimo campo cargado en el array
     *
     * @return mixed $campos el dato de la variable.
     */
    public function getUltimoCampo()
    {
        return $this->campo[Funciones::endKey($this->campo)];
    }

    /**
     *
     * @param array $campo
     */
    public function addCampos(array $campos)
    {
        $this->campos[] = $campos;
    }

    public function addNuevoCampos($tipo = "")
    {}

    /**
     * Crea un nuevo elemento en el array de campos vasado en el tipo pasado y retorna el id en el array.
     *
     * @param string $tipo
     * @return int
     */
    public function crearCampoTipo($tipo = "")
    {
        if (!$tipo) {
            $tipo = "texto";
        }
        // error_log($tipo);
        switch (strtolower($tipo)) {
            // case "" :
            case "texto":
                $this->campo[] = new Campos_texto();
                $i = Funciones::endKey($this->campo);
                break;

            case "bit":
                $this->campo[] = new Campos_bit();
                $i = Funciones::endKey($this->campo);
                break;

            case "combo":
                $this->campo[] = new Campos_combo();
                $i = Funciones::endKey($this->campo);
                break;

            case "dbcombo":
                $this->campo[] = new Campos_dbCombo(array(), $this->db);
                $i = Funciones::endKey($this->campo);
                break;

            case "password":
                $this->campo[] = new Campos_password();
                $i = Funciones::endKey($this->campo);
                break;

            case "upload":
                $this->campo[] = new Campos_upload();
                $i = Funciones::endKey($this->campo);
                break;

            case "moneda":
                $this->campo[] = new Campos_moneda();
                $i = Funciones::endKey($this->campo);
                break;

            case "numero":
                $this->campo[] = new Campos_numero();
                $i = Funciones::endKey($this->campo);
                break;

            case "rownum":
                $this->campo[] = new Campos_rownum();
                $i = Funciones::endKey($this->campo);
                break;

            case "fecha":
                $this->campo[] = new Campos_fecha(array(), $this->db);
                $i = Funciones::endKey($this->campo);
                break;

            case "textarea":
                // error_log("Entra aca");
                $this->campo[] = new Campos_textarea();
                // $this->campo[] = new Campos_texto();
                $i = Funciones::endKey($this->campo);
                break;

            default:
                throw new Exception("Tipo de dato desconocido.");
                break;
        }
        $this->campo[$i]->setTabla($this->getTabla());

        return $i;
    }

    /**
     * Retorna el valor del atributo $direNuevo
     *
     * @return string $direNuevo el dato de la variable.
     */
    public function getDireNuevo(): string
    {
        return $this->direNuevo;
    }

    /**
     * Setter del parametro $direNuevo de la clase.
     *
     * @param string $direNuevo
     *            dato a cargar en la variable.
     */
    public function setDireNuevo(string $direNuevo)
    {
        $this->direNuevo = $direNuevo;
    }

    /**
     * Retorna el valor del atributo $estilosBasicos
     *
     * @return string $estilosBasicos el dato de la variable.
     */
    public function getEstilosBasicos(): string
    {
        return $this->estilosBasicos;
    }

    /**
     * Setter del parametro $estilosBasicos de la clase.
     *
     * @param string $estilosBasicos
     *            dato a cargar en la variable.
     */
    public function setEstilosBasicos(string $estilosBasicos)
    {
        $this->estilosBasicos = $estilosBasicos;
    }

    /**
     * Setter del parametro $estilosBasicos de la clase.
     *
     * @param string $estilosBasicos
     *            dato a cargar en la variable.
     */
    public function addEstilosBasicos(string $estilosBasicos)
    {
        $this->estilosBasicos .= $estilosBasicos;
    }

    /**
     * Retorna el valor del atributo $jsBasicos
     *
     * @return string $jsBasicos el dato de la variable.
     */
    public function getJsBasicos(): string
    {
        return $this->jsBasicos;
    }

    /**
     * Setter del parametro $jsBasicos de la clase.
     *
     * @param string $jsBasicos
     *            dato a cargar en la variable.
     */
    public function setJsBasicos(string $jsBasicos)
    {
        $this->jsBasicos = $jsBasicos;
    }

    /**
     * Setter del parametro $jsBasicos de la clase.
     *
     * @param string $jsBasicos
     *            dato a cargar en la variable.
     */
    public function addJsBasicos(string $jsBasicos)
    {
        $this->jsBasicos .= $jsBasicos;
    }
}
?>