<?php

/**
 * Archivo de la clase
 *
 * Archivo principal de la clase ABM
 *
 * @author Andres Carizza www.andrescarizza.com.ar
 * @author iberlot <@> ivanberlot@gmail.com
 * @name class_abm.php
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
require_once 'campos/Campos_bit.php';
require_once 'campos/Campos_combo.php';
require_once 'campos/Campos_dbCombo.php';
require_once 'campos/Campos_moneda.php';
require_once 'campos/Campos_numero.php';
require_once 'campos/Campos_password.php';
require_once 'campos/Campos_rownum.php';
require_once 'campos/Campos_textarea.php';
require_once 'campos/Campos_texto.php';
require_once 'campos/Campos_upload.php';
require_once 'campos/class_campo.php';
require_once 'funciones.php';

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
 * 'titulo' => "Contraseña",
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
	public $campoId = array ();

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
	public $adicionalesSelect;

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
	public $textoPreguntarBorrar = "¿Confirma que desea borrar el elemento seleccionado?";

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
	public $iconoEditar = "<a href=\"%s\"><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>";
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
	public $iconoAgregar = "<input type='button' class='btnAgregar' value='Agregar' title='Atajo: ALT+A' accesskey='a' onclick='window.location=\"%s\"'/>";

	/**
	 * Icono de exportar a Excel.
	 */
	public $iconoExportarExcel = "<input type='button' class='btnExcel' title='Exportar a Excel' onclick='javascript:window.open(\"%s\", \"_blank\")'/>";
	// public $iconoExportarExcel = "<input type='button' class='btnExcel' title='Exportar a Excel' onclick='window.location=\"%s\"'/>";

	/**
	 * Icono de exportar a CSV.
	 */
	public $iconoExportarCsv = "<input type='button' class='btnCsv' title='Exportar a CSV' onclick='javascript:window.open(\"%s\", \"_blank\")'/>";

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
	// public $formatoFechaListado = "d/m/Y";
	public $formatoFechaListado = "DD/MM/YYYY";

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
	public $exportar_formatosPermitidos = array (
			'excel',
			'csv'
	);

	/**
	 * El JS que se agrega cuando un campo es requerido *
	 */
	public $jsIniciadorChequeoForm = '
        <script type="text/javascript">
        $(function(){
          $("#formularioAbm").validationEngine({promptPosition:"topLeft"});
        });
        </script>
    ';

	/**
	 * El JS que se agrega cuando un campo es requerido *
	 */
	public $jsHints = '
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
	public $jsMonedaInput = '
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
	public $tituloSolapa = array ();

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
	 * Texto por defecto que se usa cuando el tipo de campo es "bit"
	 */
	public $textoBitTrue = "SI";

	/**
	 * Texto por defecto que se usa cuando el tipo de campo es "bit"
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
	public $estilosBasicos = "<link rel='stylesheet' href='%dirname%/font-awesome/css/font-awesome.min.css'><link rel='stylesheet' type='text/css' href='%dirname%/cssABM/abm.css' />";

	/**
	 * Array de objetos campo
	 *
	 * @var object
	 */
	private $campo;

	// private

	/*
	 * ************************************************************************
	 * Aca empiezan las funciones de la clase
	 * ************************************************************************
	 */

	/**
	 * Devuelve un string con el concat de los campos Id
	 *
	 * @param array $array
	 *        	--> array con todos los campos a utilizar para generar el id compuesto
	 * @param string $tabla
	 *        	--> tabla que contendria el array compuesto
	 * @return string $arrayId --> concatenacion de los campos del array
	 */
	public function convertirIdMultiple($array, $tabla)
	{
		global $db;

		if ($db->dbtype == 'mysql')
		{

			$arrayId = "CONCAT (";

			foreach ($array as &$valor)
			{
				// print_r ("<br>" . $valor . "<br>");

				$arrayId .= $tabla . "." . $valor . ", ";
			}

			$arrayId = substr ($arrayId, 0, -2);

			$arrayId .= ") AS ID";

			return $arrayId;
		}
		elseif ($db->dbtype == 'oracle')
		{

			$tot = count ($array);
			if ($tot < 3)
			{
				$arrayId = "CONCAT (";

				foreach ($array as &$valor)
				{
					$arrayId .= $tabla . "." . $valor . ", ";
				}
			}
			else
			{
				$arrayId = " (";
				foreach ($array as &$valor)
				{
					$arrayId .= $tabla . "." . $valor . "||";
				}
			}
			$arrayId = substr ($arrayId, 0, -2);

			$arrayId .= ") AS ID";

			return $arrayId;
		}
		elseif ($db->dbtype == 'mssql')
		{
			$arrayId = "(";

			foreach ($array as &$valor)
			{
				$arrayId .= "convert(varchar, " . $tabla . "." . $valor . ")+";
			}

			$arrayId = substr ($arrayId, 0, -1);

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
	 *        	--> array con todos los campos a utilizar para generar el id compuesto
	 * @param string $tabla
	 *        	--> tabla que contendria el array compuesto
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @return string $camp --> todos los campos de un id compuesto
	 */
	public function convertirIdMultipleSelect($array, $tabla, $db)
	{
		// global $db;
		$camp = "";

		if ($db->dbtype == 'mysql')
		{
			foreach ($array as &$valor)
			{
				$camp .= ", " . $tabla . "." . $valor;
			}

			return $camp;
		}
		elseif ($db->dbtype == 'oracle')
		{
			$tot = count ($array);

			foreach ($array as &$valor)
			{
				$camp .= ", " . $tabla . "." . $valor;
			}

			return $camp;
		}
		elseif ($db->dbtype == 'mssql')
		{

			foreach ($array as &$valor)
			{
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
		if (isset ($_GET['abm_nuevo']))
		{
			return "alta";
		}
		elseif (isset ($_GET['abm_editar']))
		{
			return "editar";
		}
		elseif (isset ($_GET['abm_borrar']))
		{
			return "dbDelete";
		}
		elseif (isset ($_GET['abm_exportar']))
		{
			return "exportar";
		}
		elseif ($this->formularioEnviado ())
		{
			if (isset ($_GET['abm_modif']))
			{
				return "dbUpdate";
			}
			elseif (isset ($_GET['abm_alta']))
			{
				return "dbInsert";
			}
		}
		else
		{
			return "listado";
		}
	}

	/**
	 * Funcion encargada de generar el formulario de alta.
	 *
	 * @param string $titulo
	 *        	- Titulo a mostrar en el formulario de alta.
	 * @param object $db
	 *        	- objeto encargado del manejo de la base de datos.
	 */
	public function generarFormAlta($titulo = "", $db)
	{
		$this->estilosBasicos = str_ireplace ('%dirname%', dirname (__FILE__), $this->estilosBasicos);
		$this->estilosBasicos = str_ireplace ($_SERVER['DOCUMENT_ROOT'], "", $this->estilosBasicos);

		echo "<HEAD>" . $this->estilosBasicos . "</HEAD>";

		// global $db;

		$_POST = Funciones::limpiarEntidadesHTML ($_POST);

		// genera el query string de variables previamente existentes
		$get = $_GET;
		unset ($get['abm_nuevo']);
		$qsamb = http_build_query ($get);

		if ($qsamb != "")
		{
			$qsamb = "&" . $qsamb;
		}

		// agregar script para inicar FormCheck ?
		foreach ($this->campos as $campo)
		{
			if (isset ($campo['requerido']))
			{
				echo $this->jsIniciadorChequeoForm;
				break;
			}
		}

		// agregar script para inicar los Hints ?
		foreach ($this->campos as $campo)
		{
			if (isset ($campo['hint']) and ($campo['hint'] != ""))
			{
				echo $this->jsHints;
				break;
			}
		}

		echo "<div class='mabm'>";

		if (isset ($_GET['abmsg']))
		{
			echo "<div class='merror'>" . urldecode ($_GET['abmsg']) . "</div>";
		}

		echo $this->jslinksCampoFecha;
		// FIXME El jslinksSelectConBusqueda habria que mostrarlo solo cuando haya un select que lo uso
		echo $this->jslinksSelectConBusqueda;
		echo $this->jsMonedaInput;
		echo "<form enctype='multipart/form-data' method='" . $this->formMethod . "' id='formularioAbm' action='" . $this->formAction . "?abm_alta=1$qsamb' $this->adicionalesForm> \n";
		echo "<input type='hidden' name='abm_enviar_formulario' value='1' /> \n";
		echo "<table class='mformulario' $this->adicionalesTable> \n";

		if (isset ($titulo) or isset ($this->textoTituloFormularioAgregar))
		{
			echo "<thead><tr><th colspan='2'>" . (isset ($this->textoTituloFormularioAgregar) ? $this->textoTituloFormularioAgregar : $titulo) . "&nbsp;</th></tr></thead>";
		}

		echo "<tbody>\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<div id='content'>\n";
		echo "<div id='cuerpo'>\n";
		echo "<div id='contenedor'>\n";

		if ($this->formularioSolapa == true)
		{
			for($e = 1; $e <= $this->cantidadSolapa; $e++)
			{
				echo "<input id='tab-" . $e . "' type='radio' name='radio-set' class='tab-selector-" . $e . " folio' />";
				echo "<label for='tab-" . $e . "' class='tab-label-" . $e . " folio'>" . $this->tituloSolapa[$e - 1] . "</label>";

				$imprForm .= "<div class='content mabm'>";
				$imprForm .= "<div class='content-" . $e . "'>\n";
				$imprForm .= "<section>\n";
				$imprForm .= "<div id='form'>\n";

				$i = 0;

				foreach ($this->campos as $campo)
				{
					if ($campo['enSolapa'] == "")
					{
						$campo['enSolapa'] = 1;
					}

					if ($campo['enSolapa'] == $e)
					{
						if ($campo['noNuevo'] == true)
						{
							continue;
						}
						if ($campo['tipo'] == '' and $campo['formItem'] == '' and !isset ($campo['separador']))
						{
							continue;
						}

						$i++;

						if ($i == 1 and $this->autofocus)
						{
							$autofocusAttr = "autofocus='autofocus'";
						}
						else
						{
							$autofocusAttr = "";
						}

						if ($campo[requerido])
						{
							$requerido = $this->chequeoInputRequerido;
						}
						else
						{
							$requerido = "";
						}

						$imprForm .= "<div class='elementForm'>\n";

						if (isset ($campo['separador']))
						{
							$imprForm .= "<div colspan='2' class='separador'>" . $campo['separador'] . "&nbsp;</div> \n";
						}
						else
						{
							$imprForm .= "<div class='tituloItemsForm'>";
							$imprForm .= "<label for='" . $campo['campo'] . "'>" . ($campo['titulo'] != '' ? $campo['titulo'] : $campo['campo']) . $this->separadorNombreCampo . ($campo[requerido] ? " " . $this->indicadorDeCampoRequerido : "");
							$imprForm .= "</div> \n";

							$imprForm .= "<div class='itemsForm'> \n";

							if ($campo['formItem'] != "" and function_exists ($campo['formItem']))
							{
								call_user_func_array ($campo['formItem'], array (
										$fila
								));
							}
							else
							{
								switch ($campo['tipo'])
								{
									case "texto" :
									case "extra" :
										if ($campo['campo'] == $this->campoId)
										{
											$idVal = $db->insert_id ($this->campoId, $this->tabla . insert_id);
											$idVal = $idVal + 1;

											$imprForm .= "<input type='text' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . $idVal . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										}
										else
										{
											$imprForm .= "<input type='text' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										}
										break;

									case "moneda" :
										$imprForm .= "<input type='number' class='currency' min='0.01' max='250000000.00'  name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "numero" :
										$imprForm .= "<input type='number' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "password" :
										$imprForm .= "<input type='password' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "textarea" :
										$imprForm .= "<textarea name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-textarea $requerido' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " $campo[adicionalInput]>" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "</textarea>\n";
										break;

									case "dbCombo" :
										$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . $campo[adicionalInput] . "> \n";
										if ($campo[incluirOpcionVacia])
										{
											$imprForm .= "<option value=''></option> \n";
										}

										$result = $db->query ($campo['sqlQuery']);

										while ($fila = $db->fetch_array ($result))
										{
											if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == $fila[$campo[campoValor]]) or $campo['valorPredefinido'] == $fila[$campo[campoValor]])
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}

											$combobit = "";

											if (isset ($campo['mostrarValor']) and ($campo['mostrarValor'] == true))
											{
												$combobit .= ' (' . $fila[$campo['campoValor']] . ') ';
											}

											if (isset ($campo['textoMayuscula']) and ($campo['textoMayuscula'] == true))
											{
												$combobit .= substr ($fila[$campo['campoTexto']], 0, 50);
											}
											else
											{
												$combobit .= ucwords (strtolower (substr ($fila[$campo['campoTexto']], 0, 50)));
											}

											$imprForm .= "<option value='" . $fila[$campo['campoValor']] . "' $sel>" . $combobit . "</option> \n";
										}
										$imprForm .= "</select> \n";

										$imprForm .= str_replace ('%IDCAMPO%', $campo['campo'], $this->jsSelectConBusqueda);
										break;

									case "combo" :
										$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . $campo[adicionalInput] . "> \n";
										if ($campo['incluirOpcionVacia'])
										{
											$imprForm .= "<option value=''></option> \n";
										}

										foreach ($campo['datos'] as $valor => $texto)
										{
											if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == $valor) or $campo['valorPredefinido'] == $valor)
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='$valor' $sel>$texto</option> \n";
										}
										$imprForm .= "</select> \n";
										break;

									case "bit" :
										$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' " . (isset ($campo['hint']) and $campo['hint'] != "" ? 'title="' . $campo['hint'] . '"' : "") . " $campo[adicionalInput]> \n";

										if (isset ($campo['ordenInversoBit']))
										{
											if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == false) or $campo['valorPredefinido'] == false)
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";

											if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == true) or $campo['valorPredefinido'] == true)
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='1' $sel>" . ($campo['textoBitTrue'] != "" ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";
										}
										else
										{

											if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == true) or $campo['valorPredefinido'] == true)
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='1' $sel>" . ($campo['textoBitTrue'] != "" ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";

											if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == false) or $campo['valorPredefinido'] == false)
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";
										}

										$imprForm .= "</select> \n";
										break;

									case "fecha" :
										$valor = (isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido'];
										if (strlen ($valor) > 10)
										{
											$valor = substr ($valor, 0, 10); // sacar hora:min:seg
										}
										if ($valor == '0000-00-00')
										{
											$valor = "";
										}
										$jsTmp = str_replace ('%IDCAMPO%', $campo['campo'], $this->jsIniciadorCamposFecha);
										$jsTmp = str_replace ('%VALOR%', $valor, $jsTmp);

										$imprForm .= $jsTmp;
										$imprForm .= "<input type='text' style='position:absolute' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' value='" . ($fila[$campo['campo']] != "" ? $fila[$campo['campo']] : $campo['valorPredefinido']) . "'/> \n";
										$imprForm .= "<input type='text' style='position:relative;top:0px;left;0px' $autofocusAttr name='display_" . $campo['campo'] . "' id='display_" . $campo['campo'] . "' class='input-fecha $requerido' $disabled $campo[adicionalInput] " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " readonly='readonly'/> \n";
										break;

									case "upload" :
										$imprForm .= "<input type='file' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='$requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									default :
										$imprForm .= $campo['nombre'];
										break;
								}
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
			echo $imprForm;
		}
		// en caso de que no sea de tipo solapa
		else
		{
			if (!isset ($imprForm))
			{
				$imprForm = "";
			}

			$imprForm .= "<div class='content mabm'>";
			$imprForm .= "<section>\n";
			$imprForm .= "<div id='form'>\n";

			$i = 0;

			foreach ($this->campos as $campo)
			{
				if (isset ($campo['noNuevo']) and ($campo['noNuevo'] == true))
				{
					continue;
				}
				if ((!isset ($campo['tipo']) or $campo['tipo'] == '') and (!isset ($campo['formItem']) or $campo['formItem'] == '') and !isset ($campo['separador']))
				{
					continue;
				}

				$i++;

				if ($i == 1 and $this->autofocus)
				{
					$autofocusAttr = "autofocus='autofocus'";
				}
				else
				{
					$autofocusAttr = "";
				}

				if (isset ($campo['requerido']))
				{
					$requerido = $this->chequeoInputRequerido;
				}
				else
				{
					$requerido = "";
				}

				$imprForm .= "<div class='elementForm'>\n";

				if (isset ($campo['separador']))
				{
					$imprForm .= "<div colspan='2' class='separador'>" . $campo['separador'] . "&nbsp;</div> \n";
				}
				else
				{
					$imprForm .= "<div class='tituloItemsForm'>";
					$imprForm .= "<label for='" . $campo['campo'] . "'>" . ($campo['titulo'] != '' ? $campo['titulo'] : $campo['campo']) . $this->separadorNombreCampo . (isset ($campo['requerido']) ? " " . $this->indicadorDeCampoRequerido : "");
					$imprForm .= "</div> \n";

					$imprForm .= "<div class='itemsForm'> \n";

					if (isset ($campo['formItem']) and ($campo['formItem'] != "" and function_exists ($campo['formItem'])))
					{
						call_user_func_array ($campo['formItem'], array (
								$fila
						));
					}
					else
					{
						switch ($campo['tipo'])
						{
							case "texto" :
								if (($campo['campo'] == $this->campoId) and (!isset ($campo['valorPredefinido']) or $campo['valorPredefinido'] == ""))
								{
									$idVal = $db->insert_id ($this->campoId, $this->tabla . $this->dbLink);
									$idVal = $idVal + 1;

									$imprForm .= "<input type='text' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . $idVal . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . "  " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								}
								else
								{
									$imprForm .= "<input type='text' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : (isset ($campo['valorPredefinido']) ? $campo['valorPredefinido'] : "")) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . "  " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								}
								break;

							case "moneda" :
								$imprForm .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : (isset ($campo['valorPredefinido']) ? $campo['valorPredefinido'] : "")) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . "  " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "numero" :
								$imprForm .= "<input type='number' class='input-text $requerido ' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : (isset ($campo['valorPredefinido']) ? $campo['valorPredefinido'] : "")) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . "  " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "password" :
								$imprForm .= "<input type='password' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr value='" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " class='input-text $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . "  " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "textarea" :
								$imprForm .= "<textarea name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-textarea $requerido' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . " >" . ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "") ? $_POST[$campo['campo']] : (isset ($campo['valorPredefinido']) ? $campo['valorPredefinido'] : "")) . "</textarea>\n";
								break;

							case "dbCombo" :
								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . " > \n";
								if ($campo['incluirOpcionVacia'])
								{
									$imprForm .= "<option value=''></option> \n";
								}

								if (isset ($campo['sqlQuery']))
								{
									$result = $db->query ($campo['sqlQuery']);
								}
								else
								{
									$result = $db->query ("SELECT " . $campo['campoValor'] . ", " . $campo['campoTexto'] . " FROM " . $campo['joinTable']);
								}

								while ($fila = $db->fetch_array ($result))
								{
									if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == $fila[$campo['campoValor']]) or (isset ($campo['valorPredefinido']) and $campo['valorPredefinido'] == $fila[$campo['campoValor']]))
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}

									$combobit = "";

									if (isset ($campo['mostrarValor']) and ($campo['mostrarValor'] == true))
									{
										$combobit .= ' (' . $fila[$campo['campoValor']] . ') ';
									}

									if (isset ($campo['textoMayuscula']) and ($campo['textoMayuscula'] == true))
									{
										$combobit .= substr ($fila[$campo['campoTexto']], 0, 50);
									}
									else
									{
										$combobit .= ucwords (strtolower (substr ($fila[$campo['campoTexto']], 0, 50)));
									}

									$imprForm .= "<option value='" . $fila[$campo['campoValor']] . "' $sel>" . $combobit . "</option> \n";
									// $imprForm .= "<option value='" . $fila[$campo['campoValor']] . "' $sel>" . $fila[$campo['campoTexto']] . "</option> \n";
								}
								$imprForm .= "</select> \n";

								$imprForm .= str_replace ('%IDCAMPO%', $campo['campo'], $this->jsSelectConBusqueda);
								break;

							case "combo" :
								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . " > \n";
								if (isset ($campo['incluirOpcionVacia']))
								{
									$imprForm .= "<option value=''></option> \n";
								}

								foreach ($campo['datos'] as $valor => $texto)
								{
									if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == $valor) or (isset ($campo['valorPredefinido']) and $campo['valorPredefinido'] == $valor))
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='$valor' $sel>$texto</option> \n";
								}
								$imprForm .= "</select> \n";
								break;

							case "bit" :
								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . " \n";

								if (isset ($campo['ordenInversoBit']) and $campo['ordenInversoBit'] != "")
								{
									if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == false) or $campo['valorPredefinido'] == false)
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";

									if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == true) or $campo['valorPredefinido'] == true)
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='1' $sel>" . ($campo['textoBitTrue'] != "" ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";
								}
								else
								{

									if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == true) or $campo['valorPredefinido'] == true)
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='1' $sel>" . ((isset ($campo['textoBitTrue']) and $campo['textoBitTrue'] != "") ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";

									if ((isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] == false) or $campo['valorPredefinido'] == false)
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='0' $sel>" . ((isset ($campo['textoBitFalse']) and $campo['textoBitFalse'] != "") ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";
								}

								$imprForm .= "</select> \n";
								break;

							case "fecha" :
								$valor = isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "" ? $_POST[$campo['campo']] : $campo['valorPredefinido'];
								if (strlen ($valor) > 10)
								{
									$valor = substr ($valor, 0, 10); // sacar hora:min:seg
								}
								if ($valor == '0000-00-00')
								{
									$valor = "";
								}
								$jsTmp = str_replace ('%IDCAMPO%', $campo['campo'], $this->jsIniciadorCamposFecha);
								$jsTmp = str_replace ('%VALOR%', $valor, $jsTmp);

								$imprForm .= $jsTmp;
								// $imprForm .= "<input type='text' style='position:absolute' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' value='" . ($fila[$campo['campo']] != "" ? $fila[$campo['campo']] : $campo['valorPredefinido']) . "'/> \n";
								$imprForm .= "<input type='text' style='position:absolute' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' value='" . (isset ($_POST[$campo['campo']]) and $_POST[$campo['campo']] != "" ? $_POST[$campo['campo']] : $campo['valorPredefinido']) . "'/> \n";
								$imprForm .= "<input type='text' style='position:relative;top:0px;left;0px' " . $autofocusAttr . " name='display_" . $campo['campo'] . "' id='display_" . $campo['campo'] . "' class='input-fecha " . $requerido . "'  " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . (isset ($disabled) and $disabled != "" ? $disabled : "") . " " . (isset ($campo['hint']) and $campo['hint'] != "" ? 'title="' . $campo['hint'] . '"' : "") . " readonly='readonly'/> \n";
								break;

							case "upload" :
								$imprForm .= "<input type='file' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='$requerido' " . (isset ($campo['hint']) and $campo['hint'] != "" ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							default :
								$imprForm .= $campo['nombre'];
								break;
						}
					}

					$imprForm .= "</div> \n";
				}

				$imprForm .= "</div> \n";
			}
			$imprForm .= "</div>\n";
			$imprForm .= "</section>\n";
			$imprForm .= "</div>\n";
			$imprForm .= "</div>\n";

			echo $imprForm;
		}

		echo "</div>\n";
		echo "</div>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</tbody>\n";

		echo "<tfoot>";
		echo "    <tr>";
		echo "        <th colspan='2'>";
		echo "			<div class ='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"" . ($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'") . "\"/></div> ";

		if ($this->extraBtn == 'true')
		{
			echo "			<div class='divBtnExtra'><input type='button' class='input-button' title='$this->textoBotonExtraTitulo' value='$this->textoBotonExtra' $this->adicionalesExtra /></div>";
		}
		echo "			<div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitNuevo' $this->adicionalesSubmit /></div>";
		echo "		  </th>";
		echo "    </tr>";
		echo "</tfoot>";

		echo "</table> \n";
		echo "</form> \n";
		echo "</div>";
	}

	/**
	 * Genera el formulario de modificacion de un registro
	 *
	 * @version 1.0.2 Se corrigio el uso de $customCompareValor para que quedara entre comillas simples cosa de poder hacer comparaciones de textos.
	 *
	 * @param string $id
	 *        	id por el que debe identificarse el registro a modificar
	 * @param string $titulo
	 *        	en caso de que el formulario deba tener un titulo especial
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 *
	 * @return string
	 */
	public function generarFormModificacion($id, $titulo = "", $db)
	{
		$this->estilosBasicos = str_ireplace ('%dirname%', dirname (__FILE__), $this->estilosBasicos);
		$this->estilosBasicos = str_ireplace ($_SERVER['DOCUMENT_ROOT'], "", $this->estilosBasicos);

		echo "<HEAD>" . $this->estilosBasicos . "</HEAD>";

		// global $db;
		$joinSql = "";

		// por cada campo...
		for($i = 0; $i < count ($this->campos); $i++)
		{

			if ($this->campos[$i]['campo'] == "")
			{
				continue;
			}
			if (isset ($this->campos[$i]['noMostrarEditar']) and $this->campos[$i]['noMostrarEditar'] == true)
			{
				continue;
			}
			if (isset ($this->campos[$i]['tipo']) and $this->campos[$i]['tipo'] == "upload")
			{
				continue;
			}

			// campos para el select
			if (isset ($camposSelect) and $camposSelect != "")
			{
				$camposSelect .= ", ";
			}
			else
			{
				$camposSelect = "";
			}
			// $camposSelect .= $this->campos [$i] ['selectPersonal'] . " AS " . $tablaJoin . "_" . $this->campos [$i] ['campoTexto'];

			if (isset ($this->campos[$i]['joinTable']) and ($this->campos[$i]['joinTable'] != '') and (!isset ($this->campos[$i]['omitirJoin']) or $this->campos[$i]['omitirJoin'] == false))
			{

				if (isset ($this->campos[$i]['selectPersonal']) and $this->campos[$i]['selectPersonal'] != "")
				{
					$tablaJoin = $this->campos[$i]['joinTable'];

					$tablaJoin = explode (".", $tablaJoin);
					$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];
					// $camposOrder .= $this->campos[$i]['selectPersonal'] . " AS " . $this->campos[$i]['campo'];
					$camposSelect .= $this->campos[$i]['selectPersonal'] . " AS " . substr ($tablaJoin . "_" . $this->campos[$i]['campoTexto'], 0, 30) . ", ";
					$camposSelect .= $this->tabla . "." . $this->campos[$i]['campo'];
					// $camposSelect .= $this->campos[$i]['selectPersonal'] . " AS " . substr($tablaJoin . "_" . $this->campos[$i]['campoValor'],0,30);
				}
				else
				{
					$tablaJoin = $this->campos[$i]['joinTable'];

					$tablaJoin = explode (".", $tablaJoin);
					$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];
					$camposSelect .= $this->campos[$i]['joinTable'] . "." . $this->campos[$i]['campoTexto'] . " AS " . substr ($tablaJoin . "_" . $this->campos[$i]['campoTexto'], 0, 30) . ", ";
					$camposSelect .= $this->tabla . "." . $this->campos[$i]['campo'];
					// $camposSelect .= $this->campos[$i]['joinTable'] . "." . $this->campos[$i]['campoValor'] . " AS " . substr($tablaJoin . "_" . $this->campos[$i]['campoValor'],0,30);
				}
			}
			elseif ((isset ($this->campos[$i]['joinTable']) and $this->campos[$i]['joinTable'] != '') and ($this->campos[$i]['omitirJoin'] == true))
			{
				if (isset ($this->campos[$i]['selectPersonal']) and $this->campos[$i]['selectPersonal'] == true)
				{
					$tablaJoin = $this->campos[$i]['joinTable'];

					$tablaJoin = explode (".", $tablaJoin);
					$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];
					// $camposOrder .= $this->campos[$i]['selectPersonal'] . " AS " . $this->campos[$i]['campo'];
					// print_r ($this->campos[$i]['campo']);

					$camposSelect .= $this->campos[$i]['campo'];
					// $camposSelect .= $this->campos[$i]['selectPersonal'] . " AS " . $this->campos[$i]['campo'];
				}
				else
				{
					if (isset ($this->campos[$i]['selectPersonal']) and $this->campos[$i]['selectPersonal'] == true)
					{
						$tablaJoin = $this->campos[$i]['joinTable'];

						$tablaJoin = explode (".", $tablaJoin);
						$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

						$camposSelect .= $this->campos[$i]['campo'];
					}
					else
					{
						$tablaJoin = $this->campos[$i]['joinTable'];

						$tablaJoin = explode (".", $tablaJoin);
						$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

						$camposSelect .= $this->campos[$i]['joinTable'] . "." . $this->campos[$i]['campo'];
					}
				}
			}
			elseif ($this->campos[$i]['tipo'] == 'rownum')
			{
				$camposSelect .= $this->campos[$i]['campo'];
			}
			elseif ($this->campos[$i]['tipo'] == 'fecha')
			{
				$camposSelect .= $db->toChar ($this->tabla . "." . $this->campos[$i]['campo'], $this->campos[$i]['campo']);
				// if ($db->dbtype == 'mysql')
				// {
				// $camposSelect .= "DATE_FORMAT(" . $this->tabla . "." . $this->campos[$i]['campo'] . ",'%Y-%m-%d') AS " . $this->campos[$i]['campo'];
				// }
				// elseif ($db->dbtype == 'oracle')
				// {
				// $camposSelect .= "TO_CHAR(" . $this->tabla . "." . $this->campos[$i]['campo'] . ", 'RRRR-MM-DD') AS " . $this->campos[$i]['campo'];
				// }
				// elseif ($this->dbtype == 'mssql')
				// {
				// $camposSelect .= "CONVERT(VARCHAR(10), " . $this->tabla . "." . $this->campos[$i]['campo'] . ", 120) AS " . $this->campos[$i]['campo'];
				// }
			}
			else
			{
				$camposSelect .= $this->tabla . "." . $this->campos[$i]['campo'];
			}

			// Si existe agregamos los datos del campo select
			if ($this->sqlCamposSelect != "")
			{
				$camposSelect .= ", " . $this->sqlCamposSelect;
			}

			// tablas para sql join
			if (isset ($this->campos[$i]['joinTable']) and $this->campos[$i]['joinTable'] != '' and (!isset ($this->campos[$i]['omitirJoin']) or $this->campos[$i]['omitirJoin'] != true))
			{
				if (isset ($this->campos[$i]['joinCondition']) and $this->campos[$i]['joinCondition'] != '')
				{
					$joinCondition = $this->campos[$i]['joinCondition'];
				}
				else
				{
					$joinCondition = "INNER";
				}

				$joinSql_aux = " $joinCondition JOIN " . $this->campos[$i]['joinTable'] . " ON " . $this->tabla . '.' . $this->campos[$i]['campo'] . '=' . $this->campos[$i]['joinTable'] . '.' . $this->campos[$i]['campoValor'];

				if (isset ($this->campos[$i]['customCompare']) and $this->campos[$i]['customCompare'] != "")
				{
					// $joinSql .= " ".$this->campos [$i] ['customCompare'];
					$joinSql_aux .= " AND " . $this->campos[$i]['customCompareCampo'] . " = " . $this->tabla . '.' . $this->campos[$i]['customCompareValor'];
				}

				$pos = strpos ($joinSql, $joinSql_aux);

				// N�tese el uso de ===. Puesto que == simple no funcionar� como se espera
				// porque la posici�n de 'a' est� en el 1� (primer) caracter.
				if ($pos === false)
				{
					// FIXME Revisar exactamente.
					$joinSql .= $joinSql_aux;
				}
			}
		}
		// hace el select para mostrar los datos del formulario de edicion
		if (isset ($id) and $id != "" and isset ($db))
		{
			$id = $this->limpiarParaSql ($id, $db);
		}

		if (is_array ($this->campoId))
		{
			$camposSelect .= $this->convertirIdMultipleSelect ($this->campoId, $this->tabla, $db);
			$this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);

			$sql = "SELECT $this->campoId, $camposSelect FROM " . $this->tabla . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE " . substr ($this->campoId, 0, -6) . " = '" . $id . "'";
		}
		else
		{
			$sql = "SELECT $this->tabla.$this->campoId AS ID, $camposSelect FROM " . $this->tabla . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE " . $this->tabla . "." . $this->campoId . "='" . $id . "'";
		}

		$result = $db->query ($sql);

		$fila = $db->fetch_array ($result);

		if ($db->num_rows ($result) == 0)
		{
			if (($fila < 0) or ($fila == "") or ($fila == NULL))
			{
				// print_r ("SELECT $this->campoId, $camposSelect FROM " . $this->tabla . " WHERE " . $this->campoId . "='" . $id . "'");
				echo $this->textoElRegistroNoExiste;
				return;
			}
		}

		// genera el query string de variables previamente existentes
		$get = $_GET;
		unset ($get['abm_editar']);
		$qsamb = http_build_query ($get);

		if ($qsamb != "")
		{
			$qsamb = "&" . $qsamb;
		}

		// agregar script para inicar FormCheck ?
		foreach ($this->campos as $campo)
		{
			if (isset ($campo['requerido']))
			{
				echo $this->jsIniciadorChequeoForm;
				break;
			}
		}

		// agregar script para iniciar los Hints ?
		foreach ($this->campos as $campo)
		{
			if (isset ($campo['hint']) and $campo['hint'] != "")
			{
				echo $this->jsHints;
				break;
			}
		}

		// Imprimimos la llamada a los js correspondientes para que funcionen los datepikcer
		echo $this->jslinksCampoFecha;
		// FIXME El jslinksSelectConBusqueda habria que mostrarlo solo cuando haya un select que lo uso
		echo $this->jslinksSelectConBusqueda;

		echo "<div class='mabm'>";
		if (isset ($_GET['abmsg']))
		{
			echo "<div class='merror'>" . urldecode ($_GET['abmsg']) . "</div>";
		}
		echo "<form enctype='multipart/form-data' method='" . $this->formMethod . "' id='formularioAbm' action='" . $this->formAction . "?abm_modif=1&$qsamb' $this->adicionalesForm> \n";
		echo "<input type='hidden' name='abm_enviar_formulario' value='1' /> \n";
		echo "<input type='hidden' name='abm_id' value='" . $id . "' /> \n";
		echo "<table class='mformulario' $this->adicionalesTable> \n";

		if (isset ($titulo) or isset ($this->textoTituloFormularioEdicion))
		{
			echo "<thead><tr><th colspan='2'>" . (isset ($this->textoTituloFormularioEdicion) ? $this->textoTituloFormularioEdicion : $titulo) . "&nbsp;</th></tr></thead>";
		}

		echo "<tbody>\n";
		echo "<tr>\n";
		echo "<td>\n";
		echo "<div id='content'>\n";
		echo "<div id='cuerpo'>\n";
		echo "<div id='contenedor'>\n";

		if ($this->formularioSolapa == true)
		{
			for($e = 1; $e <= $this->cantidadSolapa; $e++)
			{
				echo "<input id='tab-" . $e . "' type='radio' name='radio-set' class='tab-selector-" . $e . " folio' />";
				echo "<label for='tab-" . $e . "' class='tab-label-" . $e . " folio'>" . $this->tituloSolapa[$e - 1] . "</label>";

				$imprForm .= "<div class='content mabm'>";
				$imprForm .= "<div class='content-" . $e . "'>\n";
				$imprForm .= "<section>\n";
				$imprForm .= "<div id='form'>\n";

				$i = 0;

				// por cada campo... arma el formulario
				foreach ($this->campos as $campo)
				{
					if (!isset ($campo['enSolapa']) or $campo['enSolapa'] == "")
					{
						$campo['enSolapa'] = 1;
					}

					if ($campo['enSolapa'] == $e)
					{

						if (isset ($campo['enSolapa']) and $campo['noMostrarEditar'] == true)
						{
							continue;
						}
						if ($campo['tipo'] == '' and $campo['formItem'] == '' and !isset ($campo['separador']))
						{
							continue;
						}

						$i++;

						if ($i == 1 and $this->autofocus)
						{
							$autofocusAttr = "autofocus='autofocus'";
						}
						else
						{
							$autofocusAttr = "";
						}

						if ($campo['noEditar'] == true)
						{
							$disabled = "disabled='disabled'";
						}
						else
						{
							$disabled = "";
						}

						if ($campo['requerido'])
						{
							$requerido = $this->chequeoInputRequerido;
						}
						else
						{
							$requerido = "";
						}

						$imprForm .= "<div class='elementForm'>\n";

						if (isset ($campo['separador']))
						{
							$imprForm .= "<div colspan='2' class='separador'>" . $campo['separador'] . "&nbsp;</div> \n";
						}
						else
						{
							$imprForm .= "<div class='tituloItemsForm'>";
							$imprForm .= "<label for='" . $campo['campo'] . "'>" . ($campo['titulo'] != '' ? $campo['titulo'] : $campo['campo']) . $this->separadorNombreCampo . ($campo[requerido] ? " " . $this->indicadorDeCampoRequerido : "");
							$imprForm .= "</div> \n";

							$imprForm .= "<div class='itemsForm'> \n";

							if ($campo[formItem] != "" and function_exists ($campo['formItem']))
							{
								call_user_func_array ($campo['formItem'], array (
										$fila
								));
							}
							else
							{
								if (($this->campos[$i]['customCompare'] != "") and ($campo['campo'] == $this->campos[$i]['customCompareValor']))
								{
									$customCompareValor = $fila[$campo['campo']];
									// $customCompareValor = $fila[$campo['customCompareValor']];
								}

								switch ($campo['tipo'])
								{
									case "texto" :
										$imprForm .= "<input type='text' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-text $requerido' $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "moneda" :
										$imprForm .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "numero" :
										$imprForm .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "password" :
										$imprForm .= "<input type='password' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-text $requerido' $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									case "textarea" :
										$imprForm .= "<textarea name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr $disabled class='input-textarea $requerido' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " $campo[adicionalInput]>" . $fila[$campo['campo']] . "</textarea>\n";
										break;

									case "dbCombo" :
										$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled $campo[adicionalInput]> \n";
										if ($campo[incluirOpcionVacia])
										{
											$imprForm .= "<option value=''></option> \n";
										}

										$sqlQuery = $campo['sqlQuery'];

										if ($campo['customCompare'] != "")
										{
											$sqlQuery .= " WHERE 1=1 AND " . $campo['customCompareCampo'] . " = '" . $customCompareValor . "'";
											// $sqlQuery .= " WHERE 1=1 AND " . $campo['customCompareCampo'] . " = " . $this->tabla . '.' . $campo['customCompareValor'];

											if ($this->campos[$i]['customOrder'] != "")
											{
												$sqlQuery .= " ORDER BY " . $tabla . '.' . $campo['customOrder'];
											}
										}

										$resultCombo = $db->query ($sqlQuery);
										while ($filaCombo = $db->fetch_array ($resultCombo))
										{
											// $filaCombo = Funciones::limpiarEntidadesHTML ($filaCombo);
											if ($filaCombo[$campo['campoValor']] == $fila[$campo['campo']])
											{
												$selected = "selected";
											}
											else
											{
												$selected = "";
											}

											$combobit = "";

											if (isset ($campo['mostrarValor']) and ($campo['mostrarValor'] == true))
											{
												$combobit .= ' (' . $filaCombo[$campo['campoValor']] . ') ';
											}

											if (isset ($campo['textoMayuscula']) and ($campo['textoMayuscula'] == true))
											{
												$combobit .= substr ($filaCombo[$campo['campoTexto']], 0, 50);
											}
											else
											{
												$combobit .= ucwords (strtolower (substr ($filaCombo[$campo['campoTexto']], 0, 50)));
											}

											$imprForm .= "<option value='" . $filaCombo[$campo['campoValor']] . "' $selected>" . $combobit . "</option> \n";
											// $imprForm .= "<option value='" . $filaCombo[$campo['campoValor']] . "' $selected>" . $filaCombo[$campo['campoTexto']] . "</option> \n";
										}
										$imprForm .= "</select> \n";

										$imprForm .= str_replace ('%IDCAMPO%', $campo['campo'], $this->jsSelectConBusqueda);
										break;

									case "combo" :
										$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " $campo[adicionalInput]> \n";
										if ($campo['incluirOpcionVacia'])
										{
											$imprForm .= "<option value=''></option> \n";
										}

										foreach ($campo['datos'] as $valor => $texto)
										{
											if ($fila[$campo['campo']] == Funciones::limpiarEntidadesHTML ($valor))
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='$valor' $sel>$texto</option> \n";
										}
										$imprForm .= "</select> \n";
										break;

									case "bit" :
										$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " $campo[adicionalInput]> \n";

										if ($campo['ordenInversoBit'])
										{
											if (!$fila[$campo['campo']])
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";

											if ($fila[$campo['campo']])
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='1' $sel>" . ($campo['textoBitTrue'] != "" ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";
										}
										else
										{

											if ($fila[$campo['campo']])
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='1' $sel>" . ($campo['textoBitTrue'] != "" ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";

											if (!$fila[$campo['campo']])
											{
												$sel = "selected='selected'";
											}
											else
											{
												$sel = "";
											}
											$imprForm .= "<option value='0' $sel>" . ($campo['textoBitFalse'] != "" ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";
										}

										$imprForm .= "</select> \n";
										break;

									case "fecha" :
										$valor = $fila[$campo['campo']];
										if (strlen ($valor) > 10)
										{
											$valor = substr ($valor, 0, 10); // sacar hora:min:seg
										}
										if ($valor == '0000-00-00')
										{
											$valor = "";
										}
										$jsTmp = str_replace ('%IDCAMPO%', $campo['campo'], $this->jsIniciadorCamposFecha);
										$jsTmp = str_replace ('%VALOR%', $valor, $jsTmp);

										$imprForm .= $jsTmp;
										$imprForm .= "<input type='text' style='position:absolute' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' value='" . ($fila[$campo['campo']] != "" ? $fila[$campo['campo']] : $campo['valorPredefinido']) . "'/> \n";
										$imprForm .= "<input type='text' style='position:relative;top:0px;left;0px'  $autofocusAttr name='display_" . $campo['campo'] . "' id='display_" . $campo['campo'] . "' class='input-fecha $requerido' $disabled " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " $campo[adicionalInput] readonly='readonly'/> \n";
										break;

									case "upload" :
										$imprForm .= "<input type='file' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='$requerido' " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
										break;

									default :
										$imprForm .= $campo['nombre'];
										break;
								}
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
			echo $imprForm;
		}
		else
		{
			// En caso de que no se requiera la utilizacion de solapas
			if (!isset ($imprForm))
			{
				$imprForm = "";
			}

			$imprForm .= "<div class='content mabm'>";
			$imprForm .= "<section>\n";
			$imprForm .= "<div id='form'>\n";

			$i = 0;

			// por cada campo... arma el formulario
			foreach ($this->campos as $campo)
			{
				if (!isset ($campo['enSolapa']) or $campo['enSolapa'] == "")
				{
					$campo['enSolapa'] = 1;
				}

				if (isset ($campo['noMostrarEditar']) and $campo['noMostrarEditar'] == true)
				{
					continue;
				}
				if ((!isset ($campo['tipo']) or $campo['tipo'] == '') and (!isset ($campo['formItem']) or $campo['formItem'] == '') and (!isset ($campo['separador']) or $campo['separador'] == ""))
				{
					continue;
				}

				$i++;

				if ($i == 1 and $this->autofocus)
				{
					$autofocusAttr = "autofocus='autofocus'";
				}
				else
				{
					$autofocusAttr = "";
				}

				if (isset ($campo['noEditar']) and $campo['noEditar'] == true)
				{
					$disabled = "disabled='disabled'";
				}
				else
				{
					$disabled = "";
				}

				if (isset ($campo['requerido']))
				{
					$requerido = $this->chequeoInputRequerido;
				}
				else
				{
					$requerido = "";
				}

				$imprForm .= "<div class='elementForm'>\n";

				if (isset ($campo['separador']))
				{
					$imprForm .= "<div colspan='2' class='separador'>" . $campo['separador'] . "&nbsp;</div> \n";
				}
				else
				{
					$imprForm .= "<div class='tituloItemsForm'>";
					$imprForm .= "<label for='" . $campo['campo'] . "'>" . ($campo['titulo'] != '' ? $campo['titulo'] : $campo['campo']) . $this->separadorNombreCampo . (isset ($campo['requerido']) ? " " . $this->indicadorDeCampoRequerido : "");
					$imprForm .= "</div> \n";

					$imprForm .= "<div class='itemsForm'> \n";

					if (isset ($campo['formItem']) and $campo['formItem'] != "" and function_exists ($campo['formItem']))
					{
						call_user_func_array ($campo['formItem'], array (
								$fila
						));
					}
					else
					{
						if ((isset ($this->campos[$i]['customCompare']) and $this->campos[$i]['customCompare'] != "") and ($campo['campo'] == $this->campos[$i]['customCompareValor']))
						{
							// $customCompareValor = $fila[$campo['campo']];
							$customCompareValor = $fila[$campo['customCompareValor']];
						}

						switch ($campo['tipo'])
						{
							case "texto" :
								$imprForm .= "<input type='text' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-text $requerido' $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "moneda" :
								$imprForm .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "numero" :
								$imprForm .= "<input type='number' class='input-text $requerido currency' step='0.01' min='0.01' max='250000000.00'  name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "password" :
								$imprForm .= "<input type='password' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-text $requerido' $disabled value='" . $fila[$campo['campo']] . "' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . (($campo['campo'] == $this->campoId and !$this->campoIdEsEditable) ? "readonly='readonly' disabled='disabled'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							case "textarea" :
								$imprForm .= "<textarea name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr $disabled class='input-textarea $requerido' " . ((isset ($campo['maxLen']) and $campo['maxLen'] > 0) ? "maxlength='" . $campo['maxLen'] . "'" : "") . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . ">" . $fila[$campo['campo']] . "</textarea>\n";
								break;

							case "dbCombo" :
								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "> \n";
								if ($campo['incluirOpcionVacia'])
								{
									$imprForm .= "<option value=''></option> \n";
								}

								if (isset ($campo['sqlQuery']))
								{
									$sqlQuery = $campo['sqlQuery'];
								}
								else
								{
									$sqlQuery = "SELECT " . $campo['campoTexto'] . ", " . $campo['campoValor'] . " FROM " . $campo['joinTable'];
								}

								if (isset ($campo['customCompare']) and $campo['customCompare'] != "")
								{
									$sqlQuery .= " WHERE 1=1 AND " . $campo['customCompareCampo'] . " = '" . $customCompareValor . "'";
									// $sqlQuery .= " WHERE 1=1 AND " . $campo['customCompareCampo'] . " = " . $this->tabla . '.' . $campo['customCompareValor'];

									if ($this->campos[$i]['customOrder'] != "")
									{
										$sqlQuery .= " ORDER BY " . $tabla . '.' . $campo['customOrder'];
									}
								}

								$resultCombo = $db->query ($sqlQuery);

								while ($filaCombo = $db->fetch_array ($resultCombo))
								{
									// $filaCombo = Funciones::limpiarEntidadesHTML ($filaCombo);

									if ($filaCombo[$campo['campoValor']] == $fila[$campo['campo']])
									{
										$selected = "selected";
									}
									else
									{
										$selected = "";
									}

									$combobit = "";

									if (isset ($campo['mostrarValor']) and ($campo['mostrarValor'] == true))
									{
										$combobit .= ' (' . $filaCombo[$campo['campoValor']] . ') ';
									}

									if (isset ($campo['textoMayuscula']) and ($campo['textoMayuscula'] == true))
									{
										$combobit .= substr ($filaCombo[$campo['campoTexto']], 0, 50);
									}
									else
									{
										$combobit .= ucwords (strtolower (substr ($filaCombo[$campo['campoTexto']], 0, 50)));
									}

									$imprForm .= "<option value='" . $filaCombo[$campo['campoValor']] . "' $selected>" . $combobit . "</option> \n";
									// $imprForm .= "<option value='" . $filaCombo[$campo['campoValor']] . "' $selected>" . $filaCombo[$campo['campoTexto']] . "</option> \n";
								}
								$imprForm .= "</select> \n";
								break;

							case "dbComboDinamic" :
								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "> \n";

								if ($campo['incluirOpcionVacia'])
								{
									$imprForm .= "<option value=''></option> \n";
								}

								$campoWere = "campoValor=" . $campo['campoValor'] . "&campoTexto=" . $campo['campoTexto'] . "&limitarTamanio=" . $campo['limitarTamaño'] . "&mensaje=" . $campo['mensaje'] . "&where=" . $campo['where'] . "&tabla=" . $campo['tabla'] . "&campoValor=" . $campo['campoValor'] . "&incluirValor=" . $campo['incluirValor'] . "&campo=" . $campo['campo'] . "&campoPadre=\"+$(\"#" . $campo['campoPadre'] . "\").val()";

								$jsSelDin = str_replace ('%CAMPO%', $campo['campo'], $this->jsIniciadorSelectDinamico);
								$jsSelDin = str_replace ('%CAMPOPADRE%', $campo['campoPadre'], $jsSelDin);
								$jsSelDin = str_replace ('%DIREDINAMIC%', $this->direDinamic, $jsSelDin);
								$jsSelDin = str_replace ('%WHERE%', $campoWere, $jsSelDin);
								$jsSelDin = str_replace ('%WHEREINI%', $campo['campo'], $jsSelDin);

								$sqlQuery = $campo['sqlQuery'];

								if ($campo['customCompare'] != "")
								{
									$sqlQuery .= " WHERE 1=1 AND " . $campo['customCompareCampo'] . " = '" . $customCompareValor . "'";

									if ($this->campos[$i]['customOrder'] != "")
									{
										$sqlQuery .= " ORDER BY " . $tabla . '.' . $campo['customOrder'];
									}
								}

								$resultCombo = $db->query ($sqlQuery);

								while ($filaCombo = $db->fetch_array ($resultCombo))
								{
									$filaCombo = Funciones::limpiarEntidadesHTML ($filaCombo);

									if ($filaCombo[$campo['campoValor']] == $fila[$campo['campo']])
									{
										// exit();
										$selected = "selected";
									}
									else
									{
										$selected = "";
									}
									$imprForm .= "<option value='" . $filaCombo[$campo[campoValor]] . "' $selected>" . $filaCombo[$campo['campoTexto']] . "</option> \n";
								}
								$imprForm .= "</select> \n";
								$imprForm .= $jsSelDin;

								$imprForm .= str_replace ('%IDCAMPO%', $campo['campo'], $this->jsSelectConBusqueda);
								break;

							case "combo" :
								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "> \n";

								if (isset ($campo['incluirOpcionVacia']))
								{
									$imprForm .= "<option value=''></option> \n";
								}

								foreach ($campo['datos'] as $valor => $texto)
								{
									if ($fila[$campo['campo']] == Funciones::limpiarEntidadesHTML ($valor))
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='$valor' " . $sel . ">$texto</option> \n";
								}
								$imprForm .= "</select> \n";
								break;

							case "bit" :

								$imprForm .= "<select name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='input-select $requerido' $disabled " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . ((isset ($campo['adicionalInput']) and $campo['adicionalInput'] != "") ? $campo['adicionalInput'] : "") . " > \n";

								if (isset ($campo['ordenInversoBit']) and $campo['ordenInversoBit'] != "")
								{
									if (!$fila[$campo['campo']])
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='0' " . $sel . ">" . ($campo[textoBitFalse] != "" ? $campo[textoBitFalse] : $this->textoBitFalse) . "</option> \n";

									if ($fila[$campo['campo']])
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='1' " . $sel . ">" . ($campo['textoBitTrue'] != "" ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";
								}
								else
								{

									if ($fila[$campo['campo']])
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='1' " . $sel . ">" . ((isset ($campo['textoBitTrue']) and $campo['textoBitTrue'] != "") ? $campo['textoBitTrue'] : $this->textoBitTrue) . "</option> \n";

									if (!$fila[$campo['campo']])
									{
										$sel = "selected='selected'";
									}
									else
									{
										$sel = "";
									}
									$imprForm .= "<option value='0' " . $sel . ">" . ((isset ($campo['textoBitFalse']) and $campo['textoBitFalse'] != "") ? $campo['textoBitFalse'] : $this->textoBitFalse) . "</option> \n";
								}

								$imprForm .= "</select> \n";
								break;

							case "fecha" :
								$valor = $fila[$campo['campo']];
								if (strlen ($valor) > 10)
								{
									$valor = substr ($valor, 0, 10); // sacar hora:min:seg
								}
								if ($valor == '0000-00-00')
								{
									$valor = "";
								}
								$jsTmp = str_replace ('%IDCAMPO%', $campo['campo'], $this->jsIniciadorCamposFecha);
								$jsTmp = str_replace ('%VALOR%', $valor, $jsTmp);

								$imprForm .= $jsTmp;
								$imprForm .= "<input type='text' style='position:absolute' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' value='" . ($fila[$campo['campo']] != "" ? $fila[$campo['campo']] : (isset ($campo['valorPredefinido']) ? $campo['valorPredefinido'] : " ")) . "'/> \n";
								$imprForm .= "<input type='text' style='position:relative;top:0px;left;0px'  " . $autofocusAttr . " name='display_" . $campo['campo'] . "' id='display_" . $campo['campo'] . "' class='input-fecha " . $requerido . "' " . $disabled . " " . ((isset ($campo['hint']) and $campo['hint'] != "") ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "readonly='readonly'/> \n";
								break;

							case "upload" :
								$imprForm .= "<input type='file' name='" . $campo['campo'] . "' id='" . $campo['campo'] . "' $autofocusAttr class='$requerido' " . (isset ($campo['hint']) and $campo['hint'] != "" ? 'title="' . $campo['hint'] . '"' : "") . " " . (isset ($campo['adicionalInput']) ? $campo['adicionalInput'] : "") . "/> \n";
								break;

							default :
								if (isset ($campo['nombre']))
								{
									$imprForm .= $campo['nombre'];
								}
								break;
						}
					}

					$imprForm .= "</div> \n";
				}

				$imprForm .= "</div> \n";
			}
			$imprForm .= "</div>\n";
			$imprForm .= "</section>\n";
			$imprForm .= "</div>\n";

			echo $imprForm;
		}

		echo "</div>\n";
		echo "</div>\n";
		echo "</td>\n";
		echo "</tr>\n";
		echo "</tbody>\n";

		/*
		 * echo "<tfoot>";
		 * echo " <tr>";
		 * echo " <th colspan='2'><div class='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"".($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'")."\"/></div> <div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitModificar' $this->adicionalesSubmit /></div></th>";
		 * echo " </tr>";
		 * echo "</tfoot>";
		 * echo "</table> \n";
		 * echo "</form> \n";
		 * echo "</div>";
		 */
		/**
		 * 2015/11/12
		 * Modificado por @iberlot para poder agregar btns extra
		 */

		echo "<tfoot>";
		echo "    <tr>";
		echo "        <th colspan='2'>";
		echo "			<div class ='divBtnCancelar'><input type='button' class='input-button' title='Atajo: ALT+C' accesskey='c' value='$this->textoBotonCancelar' onclick=\"" . ($this->cancelarOnClickJS != "" ? $this->cancelarOnClickJS : "window.location='$_SERVER[PHP_SELF]?$qsamb'") . "\"/></div> ";
		echo "			<div class='divBtnAceptar'><input type='submit' class='input-submit' title='Atajo: ALT+G' accesskey='G' value='$this->textoBotonSubmitNuevo' $this->adicionalesSubmit /></div>";
		if ($this->extraBtn == 'true')
		{
			echo "			<div class='divBtnExtra'><input type='button' class='input-button' title='$this->textoBotonExtraTitulo' value='$this->textoBotonExtra' $this->adicionalesExtra /></div>";
		}
		echo "		  </th>";
		echo "    </tr>";
		echo "</tfoot>";

		echo "</table> \n";
		echo "</form> \n";
		echo "</div>";
	}

	/**
	 * Funcion que exporta datos a formatos como Excel o CSV
	 *
	 * @param string $formato
	 *        	(uno entre: excel, csv)
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param string $camposWhereBuscar
	 */
	private function exportar($formato, $db, $camposWhereBuscar = "")
	{
		$camposWhereBuscar = htmlspecialchars_decode ($camposWhereBuscar, ENT_QUOTES);
		$camposWhereBuscar = str_replace ("|||", " ", $camposWhereBuscar);

		if (strtolower ($formato) == 'excel')
		{
			header ('Content-type: application/vnd.ms-excel');
			header ("Content-Disposition: attachment; filename={$this->exportar_nombreArchivo}.xls");
			header ("Pragma: no-cache");
			header ("Expires: 0");

			echo "<table border='1'>\n";
			echo "    <tr>\n";
		}
		elseif (strtolower ($formato) == 'csv')
		{
			header ('Content-type: text/csv');
			header ("Content-Disposition: attachment; filename={$this->exportar_nombreArchivo}.csv");
			header ("Pragma: no-cache");
			header ("Expires: 0");
		}

		// contar el total de campos que tienen el parametro "exportar"
		$totalCamposExportar = 0;

		for($i = 0; $i < count ($this->campos); $i++)
		{
			if (!isset ($this->campos[$i]['exportar']) or $this->campos[$i]['exportar'] != true)
			{
				continue;
			}
			$totalCamposExportar++;
		}

		// Por cada campo...
		for($i = 0; $i < count ($this->campos); $i++)
		{
			if (!isset ($this->campos[$i]['exportar']) or $this->campos[$i]['exportar'] != true)
			{
				continue;
			}
			if ($this->campos[$i]['campo'] == "")
			{
				continue;
			}
			if ($this->campos[$i]['tipo'] == "upload")
			{
				continue;
			}

			// campos para el select
			if (isset ($camposSelect) and $camposSelect != "")
			{
				$camposSelect .= ", ";
			}
			else
			{
				$camposSelect = "";
			}

			if ($this->campos[$i]['tipo'] == 'rownum')
			{
				// Si el campo es de tipo rownum le decimos que no le agregue la tabla en la consulta
				$camposSelect .= $this->campos[$i]['campo'];
			}
			else
			{
				// tablas para sql join
				if (isset ($this->campos[$i]['joinTable']) and $this->campos[$i]['joinTable'] != '')
				{
					$tablaJoin = $this->campos[$i]['joinTable'];

					$tablaJoin = explode (".", $tablaJoin);
					$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

					if (isset ($this->campos[$i]['selectPersonal']) and $this->campos[$i]['selectPersonal'] != "")
					{
						$camposSelect .= $this->campos[$i]['selectPersonal'] . " AS " . $this->campos[$i]['campoTexto'];
					}
					else
					{
						$camposSelect .= $this->campos[$i]['joinTable'] . "." . $this->campos[$i]['campoTexto'] . " AS " . $this->campos[$i]['campoTexto'];
					}

					if (!isset ($this->campos[$i]['omitirJoin']) or $this->campos[$i]['omitirJoin'] == false)
					{
						if (isset ($this->campos[$i]['joinCondition']) and $this->campos[$i]['joinCondition'] != '')
						{
							$joinCondition = $this->campos[$i]['joinCondition'];
						}
						else
						{
							$joinCondition = "INNER";
						}

						if (!isset ($joinSql))
						{
							$joinSql = "";
						}

						// $joinSql .= " $joinCondition JOIN " . $this->campos[$i]['joinTable'] . " ON " . $this->tabla . '.' . $this->campos[$i]['campo'] . '=' . $this->campos[$i]['joinTable'] . '.' . $this->campos[$i]['campoValor'];

						// if (isset($this->campos[$i]['customCompare']) and $this->campos[$i]['customCompare'] != "")
						// {
						// // $joinSql .= " ".$this->campos [$i] ['customCompare'];
						// $joinSql .= " AND " . $this->campos[$i]['customCompareCampo'] . " = " . $this->tabla . '.' . $this->campos[$i]['customCompareValor'];
						// }

						$joinSql_aux = " $joinCondition JOIN " . $this->campos[$i]['joinTable'] . " ON " . $this->tabla . '.' . $this->campos[$i]['campo'] . '=' . $this->campos[$i]['joinTable'] . '.' . $this->campos[$i]['campoValor'];

						if (isset ($this->campos[$i]['customCompare']) and $this->campos[$i]['customCompare'] != "")
						{
							// $joinSql .= " ".$this->campos [$i] ['customCompare'];
							$joinSql_aux .= " AND " . $this->campos[$i]['customCompareCampo'] . " = " . $this->tabla . '.' . $this->campos[$i]['customCompareValor'];
						}

						$pos = strpos ($joinSql, $joinSql_aux);

						// N�tese el uso de ===. Puesto que == simple no funcionar� como se espera
						// porque la posici�n de 'a' est� en el 1� (primer) caracter.
						if ($pos === false)
						{
							// FIXME Revisar exactamente.
							$joinSql .= $joinSql_aux;
						}
					}
				}
				else
				{
					$camposSelect .= $this->tabla . "." . $this->campos[$i]['campo'];
				}
			}

			// Encabezados
			if (strtolower ($formato) == 'excel')
			{
				echo "        <th>";
			}

			if (isset ($this->campos[$i]['tituloListado']) and $this->campos[$i]['tituloListado'] != "")
			{
				echo $this->campos[$i]['tituloListado'];
			}
			elseif ($this->campos[$i]['titulo'] != "")
			{
				echo $this->campos[$i]['titulo'];
			}
			else
			{
				echo $this->campos[$i]['campo'];
			}

			// echo (isset($this->campos[$i]['tituloListado']) and $this->campos [$i] ['tituloListado'] != "" ? $this->campos [$i] ['tituloListado'] : ($this->campos [$i] ['titulo'] != '' ? $this->campos [$i] ['titulo'] : $this->campos [$i] ['campo']));

			if (strtolower ($formato) == 'excel')
			{
				echo "</th>\n";
			}
			elseif (strtolower ($formato) == 'csv')
			{
				if ($i < $totalCamposExportar - 1)
				{
					echo $this->exportar_csv_separadorCampos;
				}
			}
		}

		if (strtolower ($formato) == 'excel')
		{
			echo "    </tr>\n";
		}

		// Datos
		if ($this->exportar_sql != "")
		{
			$sql = $this->exportar_sql;
		}
		else if ($this->sqlCamposSelect != "")
		{
			if ($this->orderByPorDefecto != "")
			{
				$orderBy = " ORDER BY " . $this->orderByPorDefecto;
			}
			// $sql = "SELECT " . $this->sqlCamposSelect . " FROM $this->tabla $joinSql WHERE 1=1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
			$sql = "SELECT " . $this->sqlCamposSelect . " FROM " . $this->tabla . " " . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE 1=1 " . $camposWhereBuscar . " " . $this->adicionalesSelect . " " . $orderBy;
		}
		else
		{
			if ($this->orderByPorDefecto != "")
			{
				$orderBy = " ORDER BY " . $this->orderByPorDefecto;
			}

			// if (is_array ($this->campoId))
			// {
			// $this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);
			// }

			// $sql = "SELECT $this->campoId AS ID, $camposSelect FROM $this->tabla $joinSql WHERE 1=1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
			if (is_array ($this->campoId))
			{
				$this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);
			}
			else
			{
				$this->campoId = $this->tabla . "." . $this->campoId . " AS ID ";
			}

			if (!isset ($joinSql))
			{
				$joinSql = "";
			}
			if (!isset ($camposWhereBuscar))
			{
				$camposWhereBuscar = "";
			}
			else
			{
				$camposWhereBuscar = " AND (" . $camposWhereBuscar . ") ";
			}
			if (!isset ($orderBy))
			{
				$orderBy = "";
			}
			// $sql = "SELECT $this->campoId , $camposSelect FROM $this->tabla $joinSql $this->customJoin WHERE 1=1 $camposWhereBuscar $this->adicionalesSelect $orderBy";
			$sql = "SELECT $this->campoId , $camposSelect FROM $this->tabla $this->dbLink $joinSql $this->customJoin WHERE 1=1 AND 2=2 $this->adicionalesSelect $orderBy";
		}

		$result = $db->query ($sql);
		$i = 0;

		while ($fila = $db->fetch_array ($result))
		{
			// print_r("<Br />*******************<Br />");
			$fila = Funciones::limpiarEntidadesHTML ($fila);
			$i++;

			if (strtolower ($formato) == 'excel')
			{
				echo "    <tr>\n";
			}
			elseif (strtolower ($formato) == 'csv')
			{
				echo "\n";
			}

			$c = 0;
			foreach ($this->campos as $campo)
			{
				$c++;
				if (!isset ($campo['exportar']) or $campo['exportar'] != true)
				{
					continue;
				}

				if (isset ($campo['campoOrder']) and $campo['campoOrder'] != "")
				{
					$campo['campo'] = $campo[''];
				}
				else
				{
					if (isset ($campo['joinTable']) and $campo['joinTable'] != '')
					{
						// $campo ['campo'] = $campo ['joinTable'] . '_' . $campo ['campoTexto'];
						$campo['campo'] = $campo['campoTexto'];
					}
				}

				if (strtolower ($formato) == 'excel')
				{

					echo '        <td>';
				}

				if ($campo->getCustomEvalListado () != "")
				{
					/*
					 * echo "-|";
					 * print_r($campo['campo']);
					 * echo "|-";
					 */
					extract ($GLOBALS);
					$id = $fila['ID'];

					if (isset ($campo['campo']) and $campo['campo'] != "")
					{
						$valor = $fila[$campo['campo']];
					}

					if (isset ($campo['parametroUsr']))
					{
						$parametroUsr = $campo['parametroUsr'];
					}

					eval (strip_tags ($campo->getCustomEvalListado ()));
				}

				elseif ($campo['tipo'] == "bit")
				{
					if ($fila[$campo['campo']])
					{
						echo ($campo['textoBitTrue'] != '' ? $campo['textoBitTrue'] : $this->textoBitTrue);
					}
					else
					{
						echo ($campo['textoBitFalse'] != '' ? $campo['textoBitFalse'] : $this->textoBitFalse);
					}
				}
				else
				{

					// si es tipo fecha lo formatea
					if ($campo['tipo'] == "fecha")
					{
						if ($fila[$campo['campo']] != "" and $fila[$campo['campo']] != "0000-00-00" and $fila[$campo['campo']] != "0000-00-00 00:00:00")
						{
							if (strtotime ($fila[$campo['campo']]) !== -1)
							{
								$fila[$campo['campo']] = date ($this->formatoFechaListado, strtotime ($fila[$campo['campo']]));
							}
						}
					}
					elseif ($campo['tipo'] == "moneda")
					{
						// setlocale(LC_MONETARY, 'es_AR');
						// $fila [$campo ['campo']] = money_format('%.2n', $fila [$campo ['campo']]);
						// number_format($número, 2, ',', ' ');
						$fila[$campo['campo']] = number_format ($fila[$campo['campo']], 2, ',', '.');
					}
					elseif ($campo['tipo'] == "numero")
					{
						// setlocale(LC_MONETARY, 'es_AR');
						// $fila [$campo ['campo']] = money_format('%.2n', $fila [$campo ['campo']]);
						// number_format($número, 2, ',', ' ');
						$fila[$campo['campo']] = number_format ($fila[$campo['campo']], $campo['cantidadDecimales'], ',', '.');
					}

					$str = $fila[$campo['campo']];

					// si es formato csv...
					if (strtolower ($formato) == 'csv')
					{
						// quito los saltos de linea que pueda tener el valor
						$str = ereg_replace (chr (13), "", $str);
						$str = ereg_replace (chr (10), "", $str);

						// verifico que no este el caracter separador de campos en el valor
						if (strpos ($str, $this->exportar_csv_separadorCampos) !== false)
						{
							$str = $this->exportar_csv_delimitadorCampos . $str . $this->exportar_csv_delimitadorCampos;
						}
					}

					$str = $this->strip_selected_tags ($str, "br");

					$str = str_ireplace ("\<br", "", $str);
					// $str= Funciones::limpiarEntidadesHTML($str);
					// $str= str_ireplace("Br", "", $str);
					// $str= str_ireplace("lt", "", $str);
					// echo str_ireplace("<Br>", "", $str);

					echo $str;
				}

				if (strtolower ($formato) == 'excel')
				{
					echo "</td>\n";
				}
				elseif (strtolower ($formato) == 'csv')
				{
					if ($c < $totalCamposExportar)
					{
						echo $this->exportar_csv_separadorCampos;
					}
				}
			}

			if (strtolower ($formato) == 'excel')
			{
				echo "    </tr>\n";
			}
		}

		if (strtolower ($formato) == 'excel')
		{
			echo "</table>";
		}

		// exit ();
	}

	/**
	 * Genera el listado ABM con las funciones de editar, nuevo y borrar (segun la configuracion).
	 *
	 * @todo NOTA: Esta funcion solamente genera el listado, se necesita usar la funcion generarAbm() para que funcione el ABM.
	 *
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @param string $titulo
	 *        	Un titulo para mostrar en el encabezado del listado
	 * @param string $sql
	 *        	Query SQL personalizado para el listado. Usando este query no se usa $adicionalesSelect
	 *
	 */
	private function generarListado($db, $titulo, $sql = "")
	{
		$html = "";
		$noMostrar = "";
		$estaBuscando = "";

		$this->estilosBasicos = str_ireplace ('%dirname%', dirname (__FILE__), $this->estilosBasicos);
		$this->estilosBasicos = str_ireplace ($_SERVER['DOCUMENT_ROOT'], "", $this->estilosBasicos);
		$html .= "<HEAD>" . $this->estilosBasicos . "</HEAD>";

		$agregarFormBuscar = false;

		// por cada campo...
		foreach ($this->campo as $campo)
		{
			if (!$campo->existeDato ("campo") or $campo->isNoListar () == true)
			{
				continue;
			}

			if ($campo->isExportar () == true)
			{
				$mostrarExportar = true;
			}

			// para la class de ordenar por columnas
			if ($campo->isNoOrdenar () == false)
			{
				if (isset ($camposOrder) and $camposOrder != "")
				{
					$camposOrder .= "|";
				}
				else
				{
					$camposOrder = "";
				}

				if ($campo->existeDato ("campoOrder"))
				{
					$camposOrder .= $campo->getCampoOrder ();
				}
				else
				{
					if ($campo->getTipo () == 'rownum')
					{
						$camposOrder .= $campo->getCampo ();
					}
					elseif (!$campo->existeDato ("joinTable") or $campo->existeDato ("selectPersonal"))
					{
						$camposOrder .= $this->tabla . "." . $campo->getCampo ();
					}
					else
					{
						$camposOrder .= $campo->getJoinTable () . "." . $campo->getCampoTexto ();
					}
				}
			}

			// XXX creo que lo que sigue deberia ser una funcion es las clases de los campos que retorne el campoSelect.
			// campos para el select
			if ($campo->isBuscar () == true)
			{
				if (isset ($camposSelect) and ($camposSelect != ""))
				{
					$camposSelect .= ", ";
				}
				else
				{
					$camposSelect = "";
				}

				if ($campo->existeDato ("joinTable") and $campo->isOmitirJoin == false)
				{
					$tablaJoin = $campo->getJoinTable ();

					$tablaJoin = explode (".", $tablaJoin);
					$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

					if ($campo->existeDato ("selectPersonal"))
					{
						$camposSelect .= $campo->getSelectPersonal () . " AS " . substr ($tablaJoin . "_" . $campo->getCampoTexto (), 0, 30);
					}
					else
					{
						$camposSelect .= $campo->getJoinTable () . "." . $campo->getCampoTexto () . " AS " . substr ($tablaJoin . "_" . $campo->getCampoTexto (), 0, 30);
					}

					$camposOrder .= "|" . $campo->getCampoTexto ();
				}
				elseif ($campo->existeDato ("joinTable") and $campo->isOmitirJoin () == true)
				{
					$tablaJoin = $campo->getJoinTable ();

					$tablaJoin = explode (".", $tablaJoin);
					$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

					if ($campo->getSelectPersonal () and $campo->getSelectPersonal () == true)
					{
						$camposSelect .= $campo->getSelectPersonal () . " AS " . $campo->getCampoTexto ();
					}
					else
					{
						// FIXME Hay que encontrar un metodo mejor ya que si hay mas de una tabla con el mismo campo y las primeras tres letras del nombre de la tabla iguales tirara que la columna esta definida de forma ambigua.

						$camposSelect .= $campo->getJoinTable () . "." . $campo->getCampo () . " AS " . substr ($tablaJoin, 0, 3) . "_" . $campo->getCampo ();

						// $camposSelect .= $campo->getJoinTable () . "." . $campo->getCampo ();
					}
				}
				else
				{
					if ($campo->getTipo () == 'rownum')
					{
						$camposSelect .= $campo->getCampo ();
					}
					elseif ($campo->getTipo () == 'fecha')
					{
						$camposSelect .= $db->toChar ($this->tabla . "." . $campo->getCampo (), $campo->getCampo (), "dd/mm/YYYY");
					}
					else
					{
						$camposSelect .= $this->tabla . "." . $campo->getCampo ();
					}
				}
			}

			// para el where de buscar
			if ($campo->existeDato ("buscar"))
			{
				$agregarFormBuscar = true;
				// }

				if ((isset ($_REQUEST['c_' . $campo->getCampo ()]) and (trim ($_REQUEST['c_' . $campo->getCampo ()]) != '')) or (isset ($_REQUEST['c_busquedaTotal']) and (trim ($_REQUEST['c_busquedaTotal']) != '')))
				{
					if (isset ($_REQUEST['c_' . $campo->getCampo ()]))
					{
						$valorABuscar = $this->limpiarParaSql ($_REQUEST['c_' . $campo->getCampo ()], $db);

						if (isset ($camposWhereBuscar))
						{
							$camposWhereBuscar .= " AND ";
						}
						else
						{
							$camposWhereBuscar = " ";
						}
					}
					elseif (isset ($_REQUEST['c_busquedaTotal']))
					{
						$valorABuscar = $this->limpiarParaSql ($_REQUEST['c_busquedaTotal'], $db);

						if (isset ($camposWhereBuscar))
						{
							$camposWhereBuscar .= " OR ";
						}
						else
						{
							$camposWhereBuscar = " ";
						}
					}

					$estaBuscando = true;

					if ($campo->existeDato ("buscarUsarCampo"))
					{
						$camposWhereBuscar .= "UPPER(" . $campo->getBuscarUsarCampo () . ")";
					}
					else
					{
						if ($campo->getTipo () == 'fecha')
						{
							$camposWhereBuscar .= $db->toChar ($this->tabla . "." . $campo->getCampo (), "", $this->formatoFechaListado);

							$valorABuscar = str_replace ("/", "%", $valorABuscar);
							$valorABuscar = str_replace ("-", "%", $valorABuscar);
							$valorABuscar = str_replace (" ", "%", $valorABuscar);
						}
						else
						{
							$camposWhereBuscar .= "UPPER(" . $this->tabla . "." . $campo->getCampo () . ")";
						}
					}

					$camposWhereBuscar .= " ";

					if ($campo->existeDato ("buscarOperador") and strtolower ($campo->getBuscarOperador ()) != 'like')
					{
						$camposWhereBuscar .= $campo->buscarOperador . " UPPER('" . $valorABuscar . "')";
					}
					else
					{
						$valorABuscar = str_replace (" ", "%", $valorABuscar);
						$camposWhereBuscar .= "LIKE UPPER('%" . $valorABuscar . "%')";
					}
				}
			}

			// tablas para sql join
			if ($campo->existeDato ("joinTable") and $campo->existeDato ("omitirJoin") == false)
			{
				if ($campo->existeDato ("joinCondition"))
				{
					$joinCondition = $campo->getJoinCondition ();
				}
				else
				{
					$joinCondition = "INNER";
				}

				if (!isset ($joinSql))
				{
					$joinSql = "";
				}

				$joinSql_aux = " $joinCondition JOIN " . $campo->getJoinTable () . " ON " . $this->tabla . '.' . $campo->getCampo () . '=' . $campo->getJoinTable () . '.' . $campo->getCampoValor ();

				if ($campo->existeDato ("customCompare"))
				{
					$joinSql_aux .= " AND " . $campo->getCustomCompareCampo () . " = " . $this->tabla . '.' . $campo->customCompareValor;
				}

				// FIXME Esto es un parche temporal y requiere que se arragle con urgencia
				if ($campo->existeDato ("compareMasJoin"))
				{
					$joinSql_aux .= " AND " . $campo->compareMasJoin;
				}

				$pos = strpos ($joinSql, $joinSql_aux);

				// N�tese el uso de ===. Puesto que == simple no funcionar� como se espera
				// porque la posici�n de 'a' est� en el 1� (primer) caracter.
				if ($pos === false)
				{
					// FIXME Revisar exactamente.
					$joinSql .= $joinSql_aux;
				}
			}
		}

		// hasta aca uso la clase

		$camposSelect .= $this->adicionalesCamposSelect;

		// class para ordenar por columna
		$o = new class_orderby ($this->orderByPorDefecto, $camposOrder);

		if ($o->getOrderBy () != "")
		{
			$orderBy = " ORDER BY " . $o->getOrderBy ();
		}

		if (!isset ($joinSql))
		{
			$joinSql = "";
		}

		if (!isset ($camposWhereBuscar))
		{
			$camposWhereBuscar = "1=1";
		}

		if (!isset ($orderBy))
		{
			$orderBy = "";
		}

		// query del select para el listado
		if ($sql == "" and $this->sqlCamposSelect == "")
		{
			if (is_array ($this->campoId))
			{
				$this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);
			}
			else
			{
				$this->campoId = $this->tabla . "." . $this->campoId . " AS ID ";
			}

			$sql = "SELECT " . $this->campoId . ", " . $camposSelect . " FROM " . $this->tabla . " " . $this->dbLink . " " . $joinSql . " " . $this->customJoin . " WHERE 1=1  AND (" . $camposWhereBuscar . ") " . $this->adicionalesSelect . " " . $orderBy;
		}
		else if ($this->sqlCamposSelect != "")
		{
			$sql = "SELECT " . $this->sqlCamposSelect . " FROM $this->tabla $this->dbLink $joinSql $this->customJoin WHERE 1=1  AND ($camposWhereBuscar) $this->adicionalesSelect $orderBy";
		}
		else
		{
			$sql = $sql . " " . $orderBy;
		}

		// class paginado
		$paginado = new class_paginado ();
		$paginado->registros_por_pagina = $this->registros_por_pagina;
		$paginado->str_registros = $this->textoStrRegistros;
		$paginado->str_registro = $this->textoStrRegistro;
		$paginado->str_total = $this->textoStrTotal;
		$paginado->str_ir_a = $this->textoStrIrA;

		if ($this->mostrarListado)
		{
			$result = $paginado->query ($sql, $db);
		}
		$this->totalFilas = $paginado->total_registros;

		// genera el query string de variables previamente existentes
		$get = $_GET;
		unset ($get['abmsg']);
		$qsamb = http_build_query ($get);

		if ($qsamb != "")
		{
			$qsamb = "&" . $qsamb;
		}

		$html .= "<div class='mabm'>";

		$html .= "\n<script>
		        function abmBorrar(id, obj){
		            var colorAnt = obj.parentNode.parentNode.style.border;
		            obj.parentNode.parentNode.style.border = '3px solid red';";

		$html .= 'if (confirm("' . $this->textoPreguntarBorrar . '")){
		                window.location = "' . $_SERVER['PHP_SELF'] . "?" . $qsamb . "&abm_borrar=" . '" + id;
		            }
		            obj.parentNode.parentNode.style.border = colorAnt;
		            return void(0);
		        }';

		if ($this->colorearFilas)
		{
			$html .= "\n\n
					var colorAntTR;
					\n\n

		            function cambColTR(obj,sw){
		                if(sw){
		                    colorAntTR=obj.style.backgroundColor;";

			if ($this->colorearFilasDegrade == true)
			{
				$html .= "obj.style.background='-webkit-linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* For Safari 5.1 to 6.0 */
				$html .= "obj.style.background='-o-linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* For Opera 11.1 to 12.0 */
				$html .= "obj.style.background='-moz-linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* For Firefox 3.6 to 15 */
				$html .= "obj.style.background='linear-gradient(top, $this->colorearFilasColor,$this->colorearFilasColorSecundario )';"; /* Standard syntax */
			}
			else
			{
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

		if (isset ($_GET['abmsg']))
		{
			$html .= "<div class='merror'>" . urldecode ($_GET['abmsg']) . "</div> \n";
		}

		$html .= "<table class='mlistado' $this->adicionalesTableListado> \n";

		// titulo, botones, form buscar
		$html .= "<thead> \n";
		$html .= "<tr><th colspan='" . (count ($this->campos) + 2) . "'> \n";
		$html .= "<div class='mtitulo'>$titulo</div>";
		$html .= "<div class='mbotonera'> \n";
		$html .= $this->agregarABotoneraListado;

		if ($mostrarExportar and $this->mostrarListado)
		{

			$WBuscar = str_replace (" ", "|||", $camposWhereBuscar);
			$WBuscar = htmlspecialchars ($WBuscar, ENT_QUOTES);
			if (in_array ('excel', $this->exportar_formatosPermitidos))
			{
				$html .= sprintf ($this->iconoExportarExcel, "$_SERVER[PHP_SELF]?abm_exportar=excel&buscar=$WBuscar");
			}
			if (in_array ('csv', $this->exportar_formatosPermitidos))
			{
				$html .= sprintf ($this->iconoExportarCsv, "$_SERVER[PHP_SELF]?abm_exportar=csv");
			}
		}
		if ($this->mostrarNuevo)
		{
			if ($this->direNuevo)
			{
				$html .= sprintf ($this->iconoAgregar, $this->direNuevo);
			}
			else
			{
				$html .= sprintf ($this->iconoAgregar, "$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb");
			}
		}
		$html .= "</div> \n";

		$html .= "</th></tr> \n";

		// formulario de busqueda
		// XXX Hay que convertirlo en una funcion que retorne el string del formulario
		if ((isset ($agregarFormBuscar) and $this->mostrarListado) and $this->busquedaTotal == false)
		{
			$html .= "<tr class='mbuscar'><th colspan='" . (count ($this->campos) + 2) . "'> \n";
			$html .= "<fieldset><legend>$this->textoTituloFormularioBuscar</legend> \n";
			$html .= "<form method='POST' action='$this->formAction?$qsamb' id='formularioBusquedaAbm'> \n";

			$iColumna = 0;
			$maxColumnas = $this->columnasFormBuscar;

			foreach ($this->campo as $campo)
			{
				if ($campo->isBuscar () == false)
				{
					continue;
				}

				// $campo['maxMostrar'] = $campo->getMaxMostrar ();

				if ($campo->isRequerido ())
				{
					$requerido = $this->chequeoInputRequerido;
				}
				else
				{
					$requerido = "";
				}

				if ($campo->isNoEditar ())
				{
					$disabled = "disabled='disabled'";
				}
				else
				{
					$disabled = "";
				}

				$iColumna++;
				$html .= "<div>\n";
				$html .= "<label>" . $campo->obtenerTitulo (true) . "</label>";

				// if ($campo->existeDato ("tipoBuscar"))
				// {
				// $campo['tipo'] = $campo['tipoBuscar'];
				// }

				if ($campo->existeDato ("customFuncionBuscar"))
				{
					call_user_func_array ($campo->getCustomFuncionBuscar (), array ());
				}
				else
				{
					$html .= $campo->campoFormBuscar ($db, $busqueda);
				}

				echo "</div>";
				if ($iColumna == $maxColumnas)
				{
					$iColumna = 0;
					$html .= "<div class='mNuevaLinea'></div>\n";
				}
			}

			$html .= "<div class='mBotonesB'> \n";
			$html .= "<input type='submit' class='mBotonBuscar' value='$this->textoBuscar'/> \n";
			$html .= "<input type='button' class='mBotonLimpiar' value='$this->textoLimpiar' onclick='window.location=\"$this->formAction?$qsamb\"'/> \n";
			$html .= "</div> \n";
			$html .= "</form> \n";
			$html .= "</fieldset> \n";
			$html .= "</th></tr> \n";
		}
		elseif ($this->busquedaTotal == true)
		{
			$formBuscar = "<tr class='mbuscar'><th colspan='" . (count ($this->campos) + 2) . "'> \n";
			$formBuscar .= "<fieldset><legend>$this->textoTituloFormularioBuscar</legend> \n";
			$formBuscar .= "<form method='POST' action='$this->formAction?$qsamb' id='formularioBusquedaAbm'> \n";
			$formBuscar .= "<div>\n";
			$formBuscar .= "<label>B&uacute;squeda</label>";
			if (isset ($_REQUEST['c_busquedaTotal']))
			{
				// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
				$busqueda = '&c_busquedaTotal=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_busquedaTotal']);

				$formBuscar .= "<input type='text' class='input-text' name='c_busquedaTotal' value='" . Funciones::limpiarEntidadesHTML ($_REQUEST['c_busquedaTotal']) . "' /> \n";
			}
			else
			{
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

			$html .= $formBuscar;
		}

		if (isset ($busqueda))
		{
			$paginado->sumarBusqueda = $busqueda;
		}
		// fin formulario de busqueda

		if ($paginado->total_registros > 0)
		{

			// columnas del encabezado
			if ($this->mostrarEncabezadosListado)
			{
				$html .= '<tr class="tablesorter-headerRow"> ';
				foreach ($this->campo as $campo)
				{

					if ($campo->isNoMostrar () == true)
					{
						$noMostrar = " style='display: none;' ";
					}
					else
					{
						$noMostrar = " ";
					}

					if ($campo->isNoListar () == true)
					{
						continue;
					}
					if ($campo->existeDato ("separador"))
					{
						continue;
					}
					// if (isset ($campo['tipo']) and ($campo['tipo'] == "upload"))
					// {
					// continue;
					// }

					$styleTh = "";

					if ($campo->isCentrarColumna () == true)
					{
						$styleTh .= "text-align:center;";
					}
					if ($campo->existeDato ("anchoColumna"))
					{
						$styleTh .= "width:$campo->getAnchoColumna();";
					}

					if ($campo->getCampo () == "" or $campo->isNoOrdenar () == true)
					{
						$html .= "<th " . ($styleTh != "" ? "style='$styleTh'" : "") . $noMostrar . ">" . (($campo->existeDato ("tituloListado")) ? $campo->getTituloListado () : (($campo->existeDato ("titulo")) ? $campo->getTitulo () : $campo->getCampo ())) . "</th> \n";
					}
					else
					{
						if ($campo->existeDato ("campoOrder"))

						{
							$campoOrder = $campo->getCampoOrder ();
						}
						else
						{
							if ($campo->existeDato ("joinTable") and $campo->isOmitirJoin () == false)
							{
								$campoOrder = $campo->getCampoTexto ();
							}
							elseif ($campo->existeDato ("joinTable") and $campo->isOmitirJoin () == true)
							{
								$campoOrder = $this->tabla . '.' . $campo->getCampo ();
								// $campoOrder = $campo['joinTable'] . '.' . $campo->getCampo();
							}
							else
							{
								$campoOrder = $this->tabla . '.' . $campo->getCampo ();
							}
						}

						if ($campo->existeDato ("titulo"))
						{
							$linkas = $o->linkOrderBy ($campo->getTitulo (), $campoOrder);
						}
						elseif ($campo->existeDato ("tituloListado"))
						{
							$linkas = $o->linkOrderBy ($campo->getTituloListado (), $campoOrder);
						}
						else
						{
							$linkas = $o->linkOrderBy ($campo->getCampo (), $campoOrder);
						}
						// echo "<th " . ($styleTh != "" ? "style='$styleTh'" : "") . " $noMostrar >" . $o->linkOrderBy(((isset($campo->getTituloListado()) and $campo->getTituloListado() != "") ? $campo->getTituloListado() : ($campo->getTitulo() != '' ? $campo->getTitulo() : $campo->getCampo())), $campoOrder) . "</th> \n";

						$html .= "<th " . ($styleTh != "" ? "style='$styleTh'" : "") . " $noMostrar >" . $linkas . "</th> \n";
					}
				}
				if ($this->mostrarEditar)
				{
					$html .= "<th class='mtituloColEditar' " . $noMostrar . ">" . $this->textoEditarListado . "</th> \n";
				}
				if ($this->mostrarBorrar)
				{
					$html .= "<th class='mtituloColBorrar' " . $noMostrar . ">" . $this->textoBorrarListado . "</th> \n";
				}
				$html .= "</tr> \n";
			} // fin columnas del encabezado
			$html .= "</thead> \n";
			// filas de datos
			$i = 0;
			while ($fila = $db->fetch_array ($result))
			{
				if (!isset ($rallado))
				{
					$rallado = "";
				}
				$fila = Funciones::limpiarEntidadesHTML ($fila);

				$i++;
				$rallado = !$rallado;

				$html .= "<tr class='rallado$rallado' ";
				if ($this->colorearFilas)
				{
					$html .= " onmouseover=\"cambColTR(this,1)\" onmouseout=\"cambColTR(this,0)\" ";
				}
				if (isset ($this->evalEnTagTR))
				{
					eval ($this->evalEnTagTR);
				}
				$html .= "> \n";

				foreach ($this->campo as $campo)
				{
					if ($campo->isNoMostrar () == true)
					{
						$noMostrar = " style='display: none;' ";
					}
					else
					{
						$noMostrar = " ";
					}

					if ($campo->isNoListar () == true)
					{
						continue;
					}

					// if (isset ($campo['tipo']) and ($campo['tipo'] == "upload"))
					// {
					// continue;
					// }

					if ($campo->getSeparador ())
					{
						continue;
					}

					if ($campo->existeDato ("campoOrder"))

					{
						$campo->setCampo ($campo->getCampoOrder ());
					}
					else
					{
						if ($campo->existeDato ("joinTable") and !$campo->existeDato ("omitirJoin"))
						{
							$tablaJoin = $campo->getJoinTable ();
							$tablaJoin = explode (".", $tablaJoin);
							$tablaJoin = $tablaJoin[count ($tablaJoin) - 1];

							$campo->setCampo ($tablaJoin . "_" . $campo->getCampoTexto ());
						}
					}

					if ($campo->existeDato ("centrarColumna"))
					{
						$centradoCol = 'align="center"';
					}
					else
					{
						$centradoCol = '';
					}

					if ($campo->existeDato ("colorearValores") and (is_array ($campo->getColorearValores ())))
					{
						if (array_key_exists ($fila[$campo->getCampo ()], $campo->getColorearValores ()))
						{
							// XXX revisar la implementacion de las funciones que retornan arrays en generarListado()
							$spanColorear = "<span class='" . ($campo->isColorearConEtiqueta () ? "label" : "") . "' style='" . ($campo->isColorearConEtiqueta () ? "background-" : "") . "color:" . $campo->getColorearValores ()[$fila[$campo->getCampo ()]] . "'>";
							$spanColorearFin = "</span>";
						}
						else
						{
							$spanColorear = "";
							$spanColorearFin = "";
						}
					}
					else
					{
						$spanColorear = "";
						$spanColorearFin = "";
					}

					if ($campo->getCustomEvalListado () != "")
					{
						$id = $fila['ID'];

						extract ($GLOBALS);

						if (isset ($fila['ID']))
						{
							$id = $fila['ID'];
						}
						else
						{
							$fila['ID'] = $id;
						}

						if ($campo->existeDato ("campo"))
						{
							$valor = $fila[$campo->getCampo ()];
						}

						if ($campo->existeDato ("parametroUsr"))
						{
							$parametroUsr = $campo->getParametroUsr ();
						}

						eval ($campo->getCustomEvalListado ());
					}
					elseif ($campo->existeDato ("customFuncionListado"))
					{
						call_user_func_array ($campo->getCustomFuncionListado (), array (
								$fila
						));
					}
					elseif ($campo->existeDato ("customPrintListado"))
					{
						if (is_array ($this->campoId))
						{
							$this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);

							$this->campoId = substr ($this->campoId, 0, -6);
						}

						if ($campo->existeDato ("incluirCampo"))
						{
							$campo->steIncluirCampo (explode (",", $campo->getIncluirCampo ()));

							$cant = count ($campo->getIncluirCampo ());

							for($j = 0; $j < $cant; $j++)
							{
								$campo->setCustomPrintListado (str_ireplace ("{" . trim ($campo->getIncluirCampo ()[$j]) . "}", $fila[trim ($campo->getIncluirCampo ()[$j])], $campo->getCustomPrintListado ()));
							}
						}

						$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear";

						$campo->setCustomPrintListado (str_ireplace ('{id}', $fila['ID'], $campo->getCustomPrintListado ()));

						if (isset ($fila[$campo->getCampo ()]))
						{
							$html .= sprintf ($campo->getCustomPrintListado (), $fila[$campo->getCampo ()]);
						}
						else
						{
							$html .= sprintf ($campo->getCustomPrintListado ());
						}
						$html .= $spanColorearFin . "</td> \n";
					}
					else
					{
						// FIXME Debe crearse un metodo polimorfico que arme la celda de cada campo como corresponda y remplace lo siguiente
						if ($campo->getTipo () == "bit")
						{
							if ($fila[$campo->getCampo ()])
							{
								$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear" . (($campo->existeDato ("textoBitTrue")) ? $campo->getTextoBitTrue () : $this->textoBitTrue) . $spanColorearFin . "</td> \n";
							}
							else
							{
								$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear" . (($campo->existeDato ("textoBitTrue")) ? $campo->getTextoBitFalse () : $this->textoBitFalse) . $spanColorearFin . "</td> \n";
							}
						}
						// si es tipo combo le decimos que muestre el texto en vez del valor
						elseif ($campo->getTipo () == "combo")
						{
							if (isset ($fila[$campo->getCampo ()]))
							{
								// XXX verificar acomodar y documentar $campo['datos']
								$datos = $campo->getDatos ();
								$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear" . $datos[$fila[$campo->getCampo ()]] . "$spanColorearFin</td> \n";
							}
						}
						elseif ($campo->getTipo () == "moneda")
						{
							setlocale (LC_MONETARY, 'es_AR');
							$html .= "<td style='text-align: right;' " . $noMostrar . ">$spanColorear" . money_format ('%.2n', $fila[$campo->getCampo ()]) . "$spanColorearFin</td> \n";
						}
						elseif ($campo->getTipo () == "numero")
						{
							if ($fila[$campo->getCampo ()] != "" and $fila[$campo->getCampo ()] > 0)
							{
								$html .= "<td style='text-align: right;' " . $noMostrar . ">$spanColorear" . number_format ($fila[$campo->getCampo ()], $campo['cantidadDecimales'], ',', '.') . "$spanColorearFin</td> \n";
							}
							else
							{
								$html .= "<td style='text-align: right;' $noMostrar>$spanColorear" . number_format (0, $campo['cantidadDecimales'], ',', '.') . "$spanColorearFin</td> \n";
							}
						}
						elseif ($campo->getTipo () == "textarea")
						{
							if ($campo->isNoLimpiar () == true)
							{
								$html .= "<td $centradoCol " . $noMostrar . ">" . substr (($spanColorear . html_entity_decode ($fila[$campo->getCampo ()]) . $spanColorearFin), 0, $campo->getMaxMostrar ()) . "</td> \n";
							}
							else
							{
								if (isset ($fila[$campo->getCampo ()]))
								{
									$html .= "<td $centradoCol " . $noMostrar . ">" . substr (($spanColorear . $fila[$campo->getCampo ()] . $spanColorearFin), 0, $campo->getMaxMostrar ()) . "</td> \n";
								}
								else
								{
									$html .= "<td $centradoCol " . $noMostrar . ">" . substr (($spanColorear . $fila[$campo->getCampoTexto ()] . $spanColorearFin), 0, $campo->getMaxMostrar ()) . "</td> \n";
								}
							}
						}
						elseif ($campo->getTipo () == "upload")
						{
							$dato = explode (".", $fila[$campo->getCampo ()]);
							if (in_array (strtolower (end ($dato)), array (
									'jpg',
									'jpeg',
									'bmp',
									'png'
							)))
							{
								$otrosImagen = "";
								$otrosImagen .= " height='" . $campo['alto'] . "' ";
								$otrosImagen .= " width='" . $campo['ancho'] . "' ";

								$html .= "<td $centradoCol " . $noMostrar . "><img " . $otrosImagen . " src='" . $campo['directorio'] . "/" . $fila[$campo->getCampo ()] . "'></td> \n";
							}
							elseif ($campo['mostrar'] == true)
							{
								$html .= "<td $centradoCol " . $noMostrar . ">" . $fila[$campo->getCampo ()] . "</td> \n";
							}
						}
						else
						{
							// si es tipo fecha lo formatea
							if ($campo->getTipo () == "fecha")
							{
								if ($fila[$campo->getCampo ()] != "" and $fila[$campo->getCampo ()] != "0000-00-00" and $fila[$campo->getCampo ()] != "0000-00-00 00:00:00")
								{
									if (strtotime ($fila[$campo->getCampo ()]) !== -1)
									{
										// FIXME Urgente arreglar el formateo de fecha y que pasa con strtotime -1

										// $fila[$campo['campo']] = date ($this->formatoFechaListado, strtotime ($fila[$campo['campo']]));
										// $fila[$campo['campo']] = date ($this->formatoFechaListado, $fila[$campo['campo']]);
										// $fila[$campo['campo']] = $fila[$campo['campo']];
									}
								}
							}

							// XXX definir y documentar el atributo noLimpiar
							if ($campo->isNoLimpiar () == true)
							{
								$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear" . html_entity_decode ($fila[$campo->getCampo ()]) . "$spanColorearFin</td> \n";
							}
							else
							{
								if (isset ($fila[$campo->getCampo ()]))
								{
									$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear" . $fila[$campo->getCampo ()] . "$spanColorearFin</td> \n";
								}
								else
								{
									$html .= "<td $centradoCol " . $noMostrar . ">$spanColorear" . $fila[$campo->getCampoTexto ()] . "$spanColorearFin</td> \n";
								}
							}
						}
					}
				}

				// FIXME - Deberia dar la opcion a una comprovacion individual sobre si mostrar o no el editar por cada fila.
				if ($this->mostrarEditar)
				{
					$this->iconoEditar = str_ireplace ('{id}', $fila['ID'], $this->iconoEditar);
					$this->iconoEditar = str_ireplace ('/img/', $this->directorioImagenes, $this->iconoEditar);

					// echo "<td class='celdaEditar'>" . $this->iconoEditar . $fila['ID'] . "</td> \n";

					$html .= "<td class='celdaEditar' " . $noMostrar . ">" . sprintf ($this->iconoEditar, $_SERVER['PHP_SELF'] . "?abm_editar=" . $fila['ID'] . $qsamb) . "</td> \n";
				}
				if ($this->mostrarBorrar)
				{
					$this->iconoBorrar = str_ireplace ('{id}', $fila['ID'], $this->iconoBorrar);
					$this->iconoBorrar = str_ireplace ('/img/', $this->directorioImagenes, $this->iconoBorrar);

					$html .= "<td class='celdaBorrar' " . $noMostrar . ">" . sprintf ($this->iconoBorrar, "abmBorrar('" . $fila['ID'] . "', this)") . "</td> \n";
				}
				$html .= "</tr> \n";
			}

			$html .= "<tfoot> \n";
			$html .= "<tr> \n";
			$html .= "<th colspan='" . (count ($this->campos) + 2) . "'>";

			if (!$this->mostrarTotalRegistros)
			{
				$paginado->mostrarTotalRegistros = false;
			}

			$paginado->mostrar_paginado ();
			$html .= "</th> \n";
			$html .= "</tr> \n";
			$html .= "</tfoot> \n";
		}
		else
		{
			$html .= "<td colspan='" . (count ($this->campos) + 2) . "' " . $noMostrar . "><div class='noHayRegistros'>" . ($estaBuscando ? $this->textoNoHayRegistrosBuscando : $this->textoNoHayRegistros) . "</div></td>";
		}

		$html .= "</table> \n";
		$html .= "</div>";

		if ($this->mostrarNuevo)
		{
			// genera el query string de variables previamente existentes
			$get = $_GET;
			unset ($get['abmsg']);
			unset ($get[$o->variableOrderBy]);
			$qsamb = http_build_query ($get);
			if ($qsamb != "")
			{
				$qsamb = "&" . $qsamb;
			}
		}

		// FIXME esto debe retornarse y no mostrarse por pantalla
		echo $html;
	}

	/**
	 * Genera el listado ABM con las funciones de editar, nuevo y borrar (segun la configuracion)
	 *
	 * @param string $sql
	 *        	Query SQL personalizado para el listado. Usando este query no se usa $adicionalesSelect
	 * @param string $titulo
	 *        	Un titulo para mostrar en el encabezado del listado
	 */
	public function generarAbm($sql = "", $titulo, $db = "")
	{
		// en caso de que no se pase el parametro de conexion a la base
		if (!isset ($db) or empty ($db))
		{
			global $db;
		}

		$this->cargar_campos ($this->campos);

		$estado = $this->getEstadoActual ();

		if (!isset ($abmsg))
		{
			$abmsg = "";
		}

		switch ($estado)
		{
			case "listado" :
				$this->generarListado ($db, $titulo, $sql);
				break;

			case "alta" :
				if (!$this->mostrarNuevo)
				{
					die ("Error"); // chequeo de seguridad, necesita estar activado mostrarNuevo
				}

				$this->generarFormAlta ("Nuevo", $db);
				break;

			case "editar" :
				if (!$this->mostrarEditar)
				{
					die ("Error"); // chequeo de seguridad, necesita estar activado mostrarEditar
				}
				$this->generarFormModificacion ($_GET['abm_editar'], "Editar", $db);
				break;

			case "dbInsert" :
				if (!$this->mostrarNuevo)
				{
					die ("Error"); // chequeo de seguridad, necesita estar activado mostrarNuevo
				}

				$r = $this->dbRealizarAlta ($db);

				if ($r != 0)
				{

					// el error 1062 es "Duplicate entry"
					if ($db->errorNro () == 1062 and $this->textoRegistroDuplicado != "")
					{
						$abmsg = "&abmsg=" . urlencode ($this->textoRegistroDuplicado);
					}
					else
					{
						$abmsg = "&abmsg=" . urlencode ($db->error ());
					}
				}

				unset ($_POST['abm_enviar_formulario']);
				unset ($_GET['abm_alta']);
				unset ($_GET['abmsg']);

				if ($r == 0 && $this->redireccionarDespuesInsert != "")
				{
					$this->redirect (sprintf ($this->redireccionarDespuesInsert, $db->insert_id ($this->campoId, $this->tabla)));
				}
				else
				{
					$qsamb = http_build_query ($_GET); // conserva las variables que existian previamente

					$this->redirect ("$_SERVER[PHP_SELF]?$qsamb$abmsg");
				}

				break;

			case "dbUpdate" :
				if (!$this->mostrarEditar)
				{
					die ("Error"); // chequeo de seguridad, necesita estar activado mostrarEditar
				}

				$r = $this->dbRealizarModificacion ($_POST['abm_id'], $db);
				if ($r != 0)
				{
					// el error 1062 es "Duplicate entry"
					if ($db->errorNro () == 1062 and $this->textoRegistroDuplicado != "")
					{
						$abmsg = "&abmsg=" . urlencode ($this->textoRegistroDuplicado);
					}
					else
					{
						$abmsg = "&abmsg=" . urlencode ($db->error ());
					}
				}

				unset ($_POST['abm_enviar_formulario']);
				unset ($_GET['abm_modif']);
				unset ($_GET['abmsg']);
				if ($r == 0 && $this->redireccionarDespuesUpdate != "")
				{
					$this->redirect (sprintf ($this->redireccionarDespuesUpdate, $_POST['abm_id']));
					// $this->redirect (sprintf ($this->redireccionarDespuesUpdate, $_POST[$fila['ID']]));
				}
				else
				{
					$qsamb = http_build_query ($_GET); // conserva las variables que existian previamente
					$this->redirect ("$_SERVER[PHP_SELF]?$qsamb$abmsg");
				}

				break;

			case "dbDelete" :
				if (!$this->mostrarBorrar)
				{
					die ("Error"); // chequeo de seguridad, necesita estar activado mostrarBorrar
				}

				$r = $this->dbBorrarRegistro ($_GET['abm_borrar'], $db);
				if ($r != 0)
				{
					$abmsg = "&abmsg=" . urlencode ($db->error ());
				}

				unset ($_GET['abm_borrar']);

				if ($r == 0 && $this->redireccionarDespuesDelete != "")
				{
					$this->redirect (sprintf ($this->redireccionarDespuesDelete, $_GET['abm_borrar']));
				}
				else
				{
					$qsamb = http_build_query ($_GET); // conserva las variables que existian previamente
					$this->redirect ("$_SERVER[PHP_SELF]?$qsamb$abmsg");
				}

				break;

			case "exportar" :
				$this->exportar_verificar ($camposWhereBuscar);
				break;

			default :
				$this->generarListado ($db, $titulo, $sql);
				break;
		}
	}

	/**
	 * Procesa los datos del formulario de alta para realizar la insercion
	 *
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 *
	 * @return void|string En caso de haber algun problema devuelve un error
	 */
	private function dbRealizarAlta($db)
	{
		if (!$this->formularioEnviado ())
		{
			return;
		}

		if (isset ($_POST))
		{
			$_POST = @$this->limpiarParaSql ($_POST, $db);
		}

		foreach ($this->campos as $campo)
		{
			if (isset ($campo['joinTable']))
			{
				// $tablas[] = $campo['joinTable'];

				$tablas[] = $this->tabla;
			}
			else
			{
				$tablas[] = $this->tabla;
			}
		}

		$tablas = array_unique ($tablas);
		/*
		 * FIXME Verificar para que funcione correctamente, hoy no lo hace
		 */
		foreach ($tablas as $tabla)
		{

			$camposSql = "";
			$valoresSql = "";
			$sql = "";

			$sql = "INSERT INTO " . $tabla . $this->dbLink . "  \n";

			foreach ($this->campos as $campo)
			{
				/*
				 * FIXME Cuando es un JOIN deberia verificar si existe en la otra tabla y no lo hace genera mal las consultas
				 */
				if (!isset ($campo['joinTable']) or $campo['joinTable'] == "")
				{
					$campo['joinTable'] = $this->tabla;
				}

				if (isset ($campo['joinTable']) and $campo['joinTable'] != $tabla and $campo['tipo'] != 'extra' and $campo['tipo'] != 'dbCombo')
				{
					if (isset ($campo['campo']) and ($campo['campo'] === $this->campoId))
					{
						$hayID = true;
					}

					if ($campo['noNuevo'] == true)
					{
						continue;
					}

					// if ($campo['tipo'] == '' or $campo['tipo'] == 'upload')
					if ($campo['tipo'] == '')
					{
						continue;
					}

					if ($campo['tipo'] == 'upload' and $campo['cargarEnBase'] != true)
					{
						continue;
					}
					elseif ($campo['tipo'] == 'upload' and $campo['cargarEnBase'] == true)
					{
						if (isset ($campo['grabarSinExtencion']) and $campo['grabarSinExtencion'] == TRUE)
						{
							$partes_nombre = explode ('.', $_FILES[$campo['campo']]['name']);
							$valor = $partes_nombre[0];
						}
						else
						{
							$valor = $_FILES[$campo['campo']]['name'];
						}

						// Iniciamos el upload del archivo

						if (isset ($_FILES[$campo['campo']]) and $_FILES[$campo['campo']]['size'] > 1)
						{
							$nombre_tmp = $_FILES[$campo['campo']]['tmp_name'];
							$tipo = $_FILES[$campo['campo']]['type'];
							$tamano = $_FILES[$campo['campo']]['size'];

							if (isset ($campo['nombreArchivo']) and $campo['nombreArchivo'] != "")
							{
								$nombre = $campo['nombreArchivo'];
							}
							else
							{
								$nombre = $_FILES[$campo['campo']]['name'];
							}

							if (isset ($campo['ubicacionArchivo']) and $campo['ubicacionArchivo'] != "")
							{
								$estructura = $campo['ubicacionArchivo'];
							}
							else
							{
								$estructura = "";
							}

							if (isset ($campo['tipoArchivo']) and $campo['tipoArchivo'] != "")
							{
								// $tipo_correcto = preg_match ('/^image\/(pjpeg|jpeg|gif|png|txt|doc|pdf|xls|sql|html|htm|php|sql)$/', $tipo);
								$tipo_correcto = preg_match ('/^' . $campo['tipoArchivo'] . '$/', $tipo);
							}

							if (isset ($campo['limiteArchivo']) and $campo['limiteArchivo'] != "")
							{
								$limite = $campo['limiteArchivo'] * 1024;
							}
							else
							{
								$limite = 50000 * 1024;
							}

							if ($tamano <= $limite)
							{

								if ($_FILES[$campo['campo']]['error'] > 0)
								{
									echo 'Error: ' . $_FILES[$campo['campo']]['error'] . '<br/>' . var_dump ($_FILES) . " en linea " . __LINE__;
								}
								else
								{

									if (file_exists ($nombre))
									{
										echo '<br/>El archivo ya existe: ' . $nombre;
									}
									else
									{
										if (file_exists ($estructura))
										{
											move_uploaded_file ($nombre_tmp, $estructura . "/" . $nombre) or die (" Error en move_uploaded_file " . var_dump (move_uploaded_file) . " en linea " . __LINE__);
											chmod ($estructura . "/" . $nombre, 0775);
										}
										else
										{
											mkdir ($estructura, 0777, true);
											move_uploaded_file ($nombre_tmp, $estructura . "/" . $nombre) or die (" Error en move_uploaded_file " . var_dump (move_uploaded_file) . " en linea " . __LINE__);
											chmod ($estructura . "/" . $nombre, 0775);
										}
									}
								}
							}
							else
							{
								echo 'Archivo inv&aacute;lido';
							}
						}

						// Finalizamos el upload del archivo
					}
					else
					{
						$valor = $_POST[$campo['campo']];
					}

					// chequeo de campos requeridos
					if ($campo['requerido'] and trim ($valor) == "")
					{
						// genera el query string de variables previamente existentes
						$get = $_GET;
						unset ($get['abmsg']);
						unset ($get['abm_alta']);
						$qsamb = http_build_query ($get);
						if ($qsamb != "")
						{
							$qsamb = "&" . $qsamb;
						}

						$this->redirect ("$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb&abmsg=" . urlencode (sprintf ($this->textoCampoRequerido, $campo['titulo'])));
					}

					if ($camposSql != "")
					{
						$camposSql .= ", \n";
					}

					if ($valoresSql != "")
					{
						$valoresSql .= ", \n";
					}

					if (isset ($campo['customFuncionValor']) and $campo['customFuncionValor'] != "")
					{
						$valor = call_user_func_array ($campo['customFuncionValor'], array (
								$valor
						));
					}

					$camposSql .= $campo['campo'];

					if (trim ($valor) == '')
					{
						$valoresSql .= " NULL";
					}
					else
					{
						// Se agrega la comparativa para que en caso de sel bases de oracle haga la conversion del formato de fecha
						// if ($campo['tipo'] == 'fecha' and $db->dbtype == 'oracle')
						if ($campo['tipo'] == 'fecha')
						{
							// $valoresSql .= "TO_DATE('" . $valor . "', 'RRRR-MM-DD')";
							$valoresSql .= $db->toDate ($valor, 'RRRR-MM-DD');
						}
						else
						{
							$valoresSql .= " '" . $valor . "' ";
						}
					}
				}
				else
				{
					if (isset ($campo['campo']) and ($campo['campo'] === $this->campoId))
					{
						$hayID = true;
					}
					elseif (isset ($campo['campo']) and isset ($this->campoId) and is_array ($campo['campo']) and (in_array ($campo['campo'], $this->campoId)))
					{
						$hayID = true;
					}

					if (isset ($campo['noNuevo']) and $campo['noNuevo'] == true)
					{
						continue;
					}

					if ($campo['tipo'] == '')
					{
						continue;
					}

					if ($campo['tipo'] == 'upload' and isset ($campo['cargarEnBase']) and $campo['cargarEnBase'] != true)
					{
						continue;
					}
					elseif ($campo['tipo'] == 'upload' and $campo['cargarEnBase'] == true)
					{
						if (isset ($campo['grabarSinExtencion']) and $campo['grabarSinExtencion'] == TRUE)
						{
							$partes_nombre = explode ('.', $_FILES[$campo['campo']]['name']);
							$valor = $partes_nombre[0];
						}
						else
						{
							$valor = $_FILES[$campo['campo']]['name'];
						}

						// Iniciamos el upload del archivo
						if (isset ($campo['nombreArchivo']) and $campo['nombreArchivo'] != "")
						{
							$campo['nombreArchivo'] = str_replace ("{{", "\$_REQUEST['", $campo['nombreArchivo']);
							$campo['nombreArchivo'] = str_replace ("}}", "']", $campo['nombreArchivo']);

							$nombre = eval ($campo['nombreArchivo']);
							$nombre = $data;
							if ($nombre == "")
							{
								$nombre = $campo['nombreArchivo'];
							}
							$valor = $nombre;
							if (isset ($partes_nombre))
							{
								$nombre = $nombre . "." . end ($partes_nombre);
							}
						}

						if (isset ($_FILES[$campo['campo']]) and $_FILES[$campo['campo']]['size'] > 1)
						{
							$nombre_tmp = $_FILES[$campo['campo']]['tmp_name'];
							$tipo = $_FILES[$campo['campo']]['type'];
							$tamano = $_FILES[$campo['campo']]['size'];

							if (!isset ($campo['nombreArchivo']) or $campo['nombreArchivo'] == "")
							{
								$nombre = $_FILES[$campo['campo']]['name'];
							}

							if (isset ($campo['ubicacionArchivo']) and $campo['ubicacionArchivo'] != "")
							{
								$estructura = $campo['ubicacionArchivo'];
							}
							else
							{
								$estructura = "";
							}

							if (isset ($campo['tipoArchivo']) and $campo['tipoArchivo'] != "")
							{
								$tipo_correcto = preg_match ('/^' . $campo['tipoArchivo'] . '$/', $tipo);
							}

							if (isset ($campo['limiteArchivo']) and $campo['limiteArchivo'] != "")
							{
								$limite = $campo['limiteArchivo'] * 1024;
							}
							else
							{
								$limite = 50000 * 1024;
							}

							if ($tamano <= $limite)
							{

								if ($_FILES[$campo['campo']]['error'] > 0)
								{
									echo 'Error: ' . $_FILES[$campo['campo']]['error'] . '<br/>' . var_dump ($_FILES) . " en linea " . __LINE__;
								}
								else
								{

									if (file_exists ($nombre))
									{
										echo '<br/>El archivo ya existe: ' . $nombre;
									}
									else
									{
										if (file_exists ($estructura))
										{
											move_uploaded_file ($nombre_tmp, $estructura . "/" . $nombre) or die (" Error en move_uploaded_file " . var_dump (move_uploaded_file) . " en linea " . __LINE__);
											chmod ($estructura . "/" . $nombre, 0775);
										}
										else
										{
											mkdir ($estructura, 0777, true);
											move_uploaded_file ($nombre_tmp, $estructura . "/" . $nombre) or die (" Error en move_uploaded_file " . var_dump (move_uploaded_file) . " en linea " . __LINE__);
											chmod ($estructura . "/" . $nombre, 0775);
										}
									}
								}
								// $imagen = $nombre;
							}
							else
							{
								echo 'Archivo inv&aacute;lido';
							}
						}

						// Finalizamos el upload del archivo
					}
					else
					{
						$valor = $_POST[$campo['campo']];
					}

					// chequeo de campos requeridos
					if (isset ($campo['requerido']) and trim ($valor) == "")
					{
						// genera el query string de variables previamente existentes
						$get = $_GET;
						unset ($get['abmsg']);
						unset ($get['abm_alta']);
						$qsamb = http_build_query ($get);
						if ($qsamb != "")
						{
							$qsamb = "&" . $qsamb;
						}

						$this->redirect ("$_SERVER[PHP_SELF]?abm_nuevo=1$qsamb&abmsg=" . urlencode (sprintf ($this->textoCampoRequerido, $campo['titulo'])));
					}

					if ($camposSql != "")
					{
						$camposSql .= ", \n";
					}

					if ($valoresSql != "")
					{
						$valoresSql .= ", \n";
					}

					if (isset ($campo['customFuncionValor']) and $campo['customFuncionValor'] != "")
					{
						$valor = call_user_func_array ($campo['customFuncionValor'], array (
								$valor
						));
					}

					$camposSql .= $campo['campo'];

					if (trim ($valor) == '')
					{
						$valoresSql .= " NULL";
					}
					else
					{
						// Se agrega la comparativa para que en caso de sel bases de oracle haga la conversion del formato de fecha
						// if ($campo['tipo'] == 'fecha' and $db->dbtype == 'oracle')
						if ($campo['tipo'] == 'fecha')
						{
							// $valoresSql .= "TO_DATE('" . $valor . "', 'RRRR-MM-DD')";
							$valoresSql .= $db->toDate ($valor, 'RRRR-MM-DD');
						}
						else
						{
							$valoresSql .= " '" . $valor . "' ";
						}
					}
				}
			}

			if (strpos ($camposSql, $this->campoId) == false)
			{
				if ($camposSql != "")
				{
					$camposSql .= ", \n";
				}

				if ($valoresSql != "")
				{
					$valoresSql .= ", \n";
				}

				if ($hayID == false)
				{
					$camposSql .= $this->campoId;

					$idVal = $db->insert_id ($this->campoId, $this->tabla . insert_id);
					$idVal = $idVal + 1;
					$valoresSql .= " '" . $idVal . "' ";
				}
			}

			$camposSql = trim ($camposSql, ", \n");
			$valoresSql = trim ($valoresSql, ", \n");

			$sql .= " (" . $camposSql . ")";

			$sql .= $this->adicionalesInsert;

			$sql .= " VALUES \n (" . $valoresSql . ")";

			if ($camposSql != "")
			{
				// print_r ($sql);
				// echo "<Br /><Br />";
				// exit ();
				$db->query ($sql);

				if (isset ($this->callbackFuncInsert))
				{
					call_user_func_array ($this->callbackFuncInsert, array (
							$id,
							$this->tabla
					));
				}
			}
		}
		return $db->errorNro ();
	}

	/**
	 * Arma la consulta y realiza el update en la tabla.
	 *
	 * @param int $id
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @return void|string
	 */
	private function dbRealizarModificacion($id, $db)
	{
		if (trim ($id) == '')
		{
			die ('Parametro id vacio en dbRealizarModificacion');
		}
		if (!$this->formularioEnviado ())
		{
			return;
		}

		$id = $this->limpiarParaSql ($id, $db);

		$_POST = $this->limpiarParaSql ($_POST, $db);

		foreach ($this->campos as $campo)
		{
			if (isset ($campo['joinTable']) and $campo['tipo'] != 'dbCombo')
			{
				$tablas[] = $campo['joinTable'];
			}
			else
			{
				$tablas[] = $this->tabla;
			}
		}

		$tablas = array_unique ($tablas);

		foreach ($tablas as $tabla)
		{

			$sql = "";
			$camposSql = "";

			$sql = "UPDATE " . $tabla . $this->dbLink . " SET \n";
			// por cada campo...
			foreach ($this->campos as $campo)
			{
				if (!isset ($campo['joinTable']) or $campo['joinTable'] == "")
				{
					$campo['joinTable'] = $this->tabla;
				}

				if ($campo['joinTable'] == $tabla or $campo['tipo'] == 'dbCombo')
				{
					if (isset ($campo['noEditar']) or isset ($campo['noMostrarEditar']))
					{
						continue;
					}
					// if (!isset ($campo['tipo']) or $campo['tipo'] == '' or $campo['tipo'] == 'upload')
					// {
					// continue;
					// }

					if (!isset ($campo['tipo']) or ($campo['tipo'] == 'upload' and $campo['cargarEnBase'] != true))
					{
						continue;
					}
					elseif ($campo['tipo'] == 'upload' and $campo['cargarEnBase'] == true)
					{
						if (isset ($campo['grabarSinExtencion']) and $campo['grabarSinExtencion'] == TRUE)
						{
							$partes_nombre = explode ('.', $_FILES[$campo['campo']]['name']);
							$valor = $partes_nombre[0];
						}
						else
						{
							$valor = $_FILES[$campo['campo']]['name'];
						}

						// Iniciamos el upload del archivo
						if (isset ($campo['nombreArchivo']) and $campo['nombreArchivo'] != "")
						{
							$campo['nombreArchivo'] = str_replace ("{{", "\$_REQUEST['", $campo['nombreArchivo']);
							$campo['nombreArchivo'] = str_replace ("}}", "']", $campo['nombreArchivo']);

							$nombre = eval ($campo['nombreArchivo']);
							$nombre = $data;
							if ($nombre == "")
							{
								$nombre = $campo['nombreArchivo'];
							}
							$valor = $nombre;
							if (isset ($partes_nombre))
							{
								$nombre = $nombre . "." . end ($partes_nombre);
							}
						}

						if (isset ($_FILES[$campo['campo']]) and $_FILES[$campo['campo']]['size'] > 1)
						{
							$nombre_tmp = $_FILES[$campo['campo']]['tmp_name'];
							$tipo = $_FILES[$campo['campo']]['type'];
							$tamano = $_FILES[$campo['campo']]['size'];

							if (!isset ($campo['nombreArchivo']) or $campo['nombreArchivo'] == "")
							{
								$nombre = $_FILES[$campo['campo']]['name'];
							}

							if (isset ($campo['ubicacionArchivo']) and $campo['ubicacionArchivo'] != "")
							{
								$estructura = $campo['ubicacionArchivo'];
							}
							else
							{
								$estructura = "";
							}

							if (isset ($campo['tipoArchivo']) and $campo['tipoArchivo'] != "")
							{
								$tipo_correcto = preg_match ('/^' . $campo['tipoArchivo'] . '$/', $tipo);
							}

							if (isset ($campo['limiteArchivo']) and $campo['limiteArchivo'] != "")
							{
								$limite = $campo['limiteArchivo'] * 1024;
							}
							else
							{
								$limite = 50000 * 1024;
							}

							if ($tamano <= $limite)
							{

								if ($_FILES[$campo['campo']]['error'] > 0)
								{
									echo 'Error: ' . $_FILES[$campo['campo']]['error'] . '<br/>' . var_dump ($_FILES) . " en linea " . __LINE__;
								}
								else
								{

									if (file_exists ($nombre))
									{
										echo '<br/>El archivo ya existe: ' . $nombre;
									}
									else
									{
										if (file_exists ($estructura))
										{
											move_uploaded_file ($nombre_tmp, $estructura . "/" . $nombre) or die (" Error en move_uploaded_file " . var_dump (move_uploaded_file) . " en linea " . __LINE__);
											chmod ($estructura . "/" . $nombre, 0775);
										}
										else
										{
											mkdir ($estructura, 0777, true);
											move_uploaded_file ($nombre_tmp, $estructura . "/" . $nombre) or die (" Error en move_uploaded_file " . var_dump (move_uploaded_file) . " en linea " . __LINE__);
											chmod ($estructura . "/" . $nombre, 0775);
										}
									}
								}
								// $imagen = $nombre;
							}
							else
							{
								echo 'Archivo inv&aacute;lido';
							}
						}

						// Finalizamos el upload del archivo
					}
					else
					{
						$valor = $_POST[$campo['campo']];
					}
					// chequeo de campos requeridos
					if (isset ($campo['requerido']) and trim ($valor) == "")
					{
						// genera el query string de variables previamente existentes
						$get = $_GET;
						unset ($get['abmsg']);
						unset ($get['abm_modif']);
						$qsamb = http_build_query ($get);
						if ($qsamb != "")
						{
							$qsamb = "&" . $qsamb;
						}

						$this->redirect ("$_SERVER[PHP_SELF]?abm_editar=$id$qsamb&abmsg=" . urlencode (sprintf ($this->textoCampoRequerido, $campo['titulo'])));
					}

					if ($camposSql != "")
					{
						$camposSql .= ", \n";
					}

					if (isset ($campo['customFuncionValor']) and $campo['customFuncionValor'] != "")
					{
						$valor = call_user_func_array ($campo['customFuncionValor'], array (
								$valor
						));
					}

					if (trim ($valor) == '')
					{
						$camposSql .= $campo['campo'] . " = NULL";
					}
					else
					{
						if ($campo['tipo'] == 'fecha')
						{
							// $camposSql .= $campo['campo'] . " = TO_DATE('" . $valor . "', 'yyyy-mm-dd')";
							$camposSql .= $campo['campo'] . " = " . $db->toDate ($valor, 'yyyy-mm-dd');
						}
						else
						{
							$camposSql .= $campo['campo'] . " = '" . $valor . "'";
						}
					}
				}
			}

			$sql .= $camposSql;

			if (is_array ($this->campoId))
			{
				$this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);

				$this->campoId = substr ($this->campoId, 0, -6);
			}

			/*
			 * FIXME - no tengo idea de donde sale $this->adicionalesUpdate asi que se elimino para que no tire error
			 * hay que verificar bien si deberia agregarse y hacerlo.
			 * Si no me equivoco deveria funcionar exactamente ingual que adicionalesSelect
			 * $sql .= $this->adicionalesUpdate . " WHERE " . $this->campoId . "='" . $id . "' " . $this->adicionalesWhereUpdate;
			 */

			$sql .= " WHERE " . $this->campoId . "='" . $id . "' " . $this->adicionalesWhereUpdate;

			// ////////////////////////////////
			if ($camposSql != "")
			{
				$stid = $db->query ($sql);
				if ($db->affected_rows ($stid) == 1)
				{
					$fueAfectado = true;

					// si cambio la id del registro
					if ($this->campoIdEsEditable and isset ($_POST[$this->campoId]) and $id != $_POST[$this->campoId])
					{
						$id = $_POST[$this->campoId];
					}
				}

				// upload
				if ($id !== false)
				{
					foreach ($this->campos as $campo)
					{
						if (!$campo['tipo'] == 'upload')
						{
							continue;
						}

						if (isset ($campo['uploadFunction']))
						{
							$r = call_user_func_array ($campo['uploadFunction'], array (
									$id,
									$this->tabla
							));
						}
					}
				}

				if (isset ($this->callbackFuncUpdate))
				{
					call_user_func_array ($this->callbackFuncUpdate, array (
							$id,
							$this->tabla,
							$fueAfectado
					));
				}
			}
			// ///////////////////
		}
		return $db->errorNro ();
	}

	/**
	 * Elimina un registro con un id dado
	 *
	 * @param int $id
	 *        	- id del registro a eliminar.
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 *
	 * @return int devuelve codigo de error en caso de ser necesario
	 */
	private function dbBorrarRegistro($id, $db)
	{
		$id = $this->limpiarParaSql ($id, $db);

		if (isset ($this->callbackFuncDelete))
		{
			call_user_func_array ($this->callbackFuncDelete, array (
					$id,
					$this->tabla
			));
		}

		if (is_array ($this->campoId))
		{
			$this->campoId = $this->convertirIdMultiple ($this->campoId, $this->tabla);

			$this->campoId = substr ($this->campoId, 0, -6);
		}

		$sql = "DELETE FROM " . $this->tabla . $this->dbLink . " WHERE " . $this->campoId . "='" . $id . "' " . $this->adicionalesWhereDelete;

		$db->query ($sql);

		return $db->errorNro ();
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
		$estado = $this->getEstadoActual ();
		if ($estado == "exportar" and $this->mostrarListado)
		{
			$this->exportar ($_GET['abm_exportar'], $db, $_GET['buscar']);
		}
	}

	/**
	 * Retorna true si el formulario fue enviado y estan disponibles los datos enviados
	 *
	 * @return boolean
	 */
	private function formularioEnviado()
	{
		if (isset ($_POST['abm_enviar_formulario']))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Escapa de un array todos los caracteres especiales de una cadena para su uso en una sentencia SQL
	 *
	 * @example $_REQUEST = limpiarParaSql($_REQUEST, $db);
	 *
	 * @param String[] $param
	 * @param object $db
	 *        	- Objeto encargado de la interaccion con la base de datos.
	 * @return String[] - Depende del parametro recibido, un array con los datos remplazados o un String
	 */
	private function limpiarParaSql($param, $db)
	{
		global $db;
		if (is_array ($param))
		{
			$result = array_map (array (
					$this,
					__FUNCTION__
			), $param);
		}
		else
		{
			$result = $db->real_escape_string ($param);
		}

		return $result;
		// return is_array($param) ? array_map (array ($this, __FUNCTION__ ), $param) : $db->real_escape_string ($param);
	}

	/**
	 * Eliminamos cualquier etiqueta html que pueda haber
	 *
	 * @param string $text
	 *        	- texto a analizar.
	 * @param array $tags
	 *        	- Etiquetas a elininar en el texto dado.
	 * @return string
	 */
	private function strip_selected_tags($text, $tags = array())
	{
		$args = func_get_args ();
		$text = array_shift ($args);
		$tags = func_num_args () > 2 ? array_diff ($args, array (
				$text
		)) : (array) $tags;
		foreach ($tags as $tag)
		{
			while (preg_match ('/<' . $tag . '(|\W[^>]*)>(.*)<\/' . $tag . '>/iusU', $text, $found))
			{
				$text = str_replace ($found[0], $found[2], $text);
			}
		}

		return preg_replace ('/(<(' . join ('|', $tags) . ')(|\W.*)\/>)/iusU', '', $text);
	}

	/**
	 * Redirecciona a $url
	 *
	 * @param string $url
	 *
	 */
	private function redirect($url)
	{
		if ($this->metodoRedirect == "header")
		{
			header ("Location:$url");
			exit ();
		}
		else
		{
			echo "<HTML><HEAD><META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=$url\"></HEAD></HTML>";
			exit ();
		}
	}

	/**
	 * Carga los datos pasados por medio de un array a un array de la clase con un listado de objetos campo
	 *
	 * @param String[] $campos
	 */
	private function cargar_campos($campos)
	{
		foreach ($campos as $camp)
		{
			switch (strtolower ($camp['tipo']))
			{
				case "texto" :
					$this->campo[] = new Campos_texto ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "bit" :
					$this->campo[] = new Campos_bit ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "combo" :
					$this->campo[] = new Campos_combo ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "dbcombo" :
					$this->campo[] = new Campos_dbCombo ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "password" :
					$this->campo[] = new Campos_password ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "upload" :
					$this->campo[] = new Campos_upload ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "moneda" :
					$this->campo[] = new Campos_moneda ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "numero" :
					$this->campo[] = new Campos_numero ($camp);
					$i = Funciones::endKey ($this->campo);
					break;

				case "rownum" :
					$this->campo[] = new Campos_rownum ($camp);
					$i = Funciones::endKey ($this->campo);
					break;
			}
		}
		// print_r ($this->campo);
	}

	private function generaWhereBuscar()
	{
		$retorno = Array ();

		if ((isset ($_REQUEST['c_' . $this->campos[$i]['campo']]) and (trim ($_REQUEST['c_' . $this->campos[$i]['campo']]) != '')) or (isset ($_REQUEST['c_busquedaTotal']) and (trim ($_REQUEST['c_busquedaTotal']) != '')))
		{
			if (isset ($_REQUEST['c_' . $this->campos[$i]['campo']]))
			{
				$valorABuscar = $this->limpiarParaSql ($_REQUEST['c_' . $this->campos[$i]['campo']], $db);

				if (isset ($camposWhereBuscar))
				{
					$camposWhereBuscar .= " AND ";
				}
				else
				{
					$camposWhereBuscar = " ";
				}
			}
			elseif (isset ($_REQUEST['c_busquedaTotal']))
			{
				$valorABuscar = $this->limpiarParaSql ($_REQUEST['c_busquedaTotal'], $db);

				if (isset ($camposWhereBuscar))
				{
					$camposWhereBuscar .= " OR ";
				}
				else
				{
					$camposWhereBuscar = " ";
				}
			}

			$estaBuscando = true;

			// quita la variable de paginado, ya que estoy buscando y no se aplica
			// unset($_REQUEST['r']);
			// unset($_POST['r']);
			// unset($_GET['r']);

			if (isset ($this->campos[$i]['buscarUsarCampo']) and ($this->campos[$i]['buscarUsarCampo'] != ""))
			{
				$camposWhereBuscar .= "UPPER(" . $this->campos[$i]['buscarUsarCampo'] . ")";
			}
			else
			{
				if ($this->campos[$i]['tipo'] == 'fecha')
				{
					// $camposWhereBuscar .= $db->toChar ($this->tabla . "." . $this->campos[$i]['campo'], "", "DD/MM/YYYY");
					$camposWhereBuscar .= $db->toChar ($this->tabla . "." . $this->campos[$i]['campo'], "", $this->formatoFechaListado);
					// $camposWhereBuscar .= "TO_CHAR(" . $this->tabla . "." . $this->campos[$i]['campo'] . ", 'DD/MM/YYYY')";
					// $camposWhereBuscar .= "TO_CHAR(" . $this->tabla . "." . $this->campos[$i]['campo'] . ", 'YYYY-MM-DD')"; // @iberlot 2016/10/18 se cambia para que funcionen los nuevos parametros de busqueda

					$valorABuscar = str_replace ("/", "%", $valorABuscar);
					$valorABuscar = str_replace ("-", "%", $valorABuscar);
					$valorABuscar = str_replace (" ", "%", $valorABuscar);
				}
				else
				{
					$camposWhereBuscar .= "UPPER(" . $this->tabla . "." . $this->campos[$i]['campo'] . ")";
				}
			}

			$camposWhereBuscar .= " ";

			if (isset ($this->campos[$i]['buscarOperador']) and (($this->campos[$i]['buscarOperador'] != '')) and strtolower ($this->campos[$i]['buscarOperador']) != 'like')
			{
				$camposWhereBuscar .= $this->campos[$i]['buscarOperador'] . " UPPER('" . $valorABuscar . "')";
			}
			else
			{
				$valorABuscar = str_replace (" ", "%", $valorABuscar);
				$camposWhereBuscar .= "LIKE UPPER('%" . $valorABuscar . "%')";
			}
		}

		return $camposWhereBuscar;
	}
}
?>
