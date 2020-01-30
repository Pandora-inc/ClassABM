<?php
/**
 * Archivo contenedor de la clase campo.
 *
 * @author iberlot <@> iberlot@usal.edu.ar
 * @since 19 mar. 2018
 */
/**
 *
 * @name class_campo.php
 * @version 0.1 version inicial del archivo.
 * @version 0.2 Tipado de funciones y parametros valido en php. A partir de esta version se deshabilita su uso en php5.
 */
require_once '/web/html/classes/funciones.php';

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
 * totalHorasPerdidasAqui = 15
 *
 *
 */

/**
 * Clase que agrupa las definiciones atributos y funciones relacionadas con los campos de un abm.
 *
 * @uses class_abm
 * @author iberlot
 * @name class_campo
 * @version 0.1 version inicial de la clase.
 */
class class_campo
{
	/**
	 * El atributo campo hace referencia al nombre del campo de la tabla.
	 * Es el identificador con el cual se lo va a llamar y con el que se va a armar la consulta.
	 *
	 * @name campo
	 * @var string
	 * @access protected
	 * @link /u00/html/classes/examples/campo.html - Ejemplo de definicion de un campo para la utilizacion de la clase abm.
	 */
	protected $campo = '';

	/**
	 * Tipo de elemento de formulario.
	 * Puede tomar uno de los siguientes valores: texto, bit, textarea, combo, dbCombo,
	 * password, upload, moneda, numero, rownum
	 * Recordar que tiene que respetar mayusculas y minusculas.
	 *
	 * @name tipo
	 * @access protected
	 * @var string
	 */
	protected $tipo = '';

	/**
	 * Tabla en la que se encuentra el campo.
	 *
	 * @name tabla
	 * @access protected
	 * @var string
	 */
	protected $tabla = '';

	/**
	 * Valor que tiene en este momento el campo
	 *
	 * @access protected
	 * @var mixed
	 */
	protected $valor;

	/**
	 * Incluye ese campo en la exportacion.
	 * Si al menos uno de los campos lo incluye entonces aparecen los iconos de exportar.
	 *
	 * ATENCION: Leer referencia de la funcion exportar_verificar()
	 *
	 * @name exportar
	 * @access protected
	 * @var boolean
	 */
	protected $exportar = true;

	/**
	 * Texto para el campo en los formularios y listado.
	 *
	 * @name titulo
	 * @access protected
	 * @var string
	 */
	protected $titulo = '';

	/**
	 * Texto para el campo en los formularios y listado al pasar el mouse por encima.
	 *
	 * @name tituloMouseOver
	 * @access protected
	 * @var string
	 */
	protected $tituloMouseOver = '';

	/**
	 * Centrar los datos de la columna en el listado.
	 *
	 * @name centrarColumna
	 * @access protected
	 * @var boolean
	 */
	protected $centrarColumna = false;

	/**
	 * Codig PHP para ejecutar en cada celda del listado sin imprimir ni siquiera los tags td.
	 * Las variables utilizables por defecto son $id y $valor.
	 *
	 * @example @link examples/customEvalListado.html
	 * @name customEvalListado
	 * @access protected
	 * @var string
	 */
	protected $customEvalListado = '';

	/**
	 *
	 * Parametro a pasarle de forma manual a customEvalListado.
	 * Solo se utiliza si customEvalListado tiene datos.
	 *
	 * No muestra el dato en el listado (lo unico que hace es esconderlo por mecio de css con la propiedad display none.
	 *
	 *
	 * @name parametroUsr
	 * @access protected
	 * @var string
	 */
	protected $parametroUsr = '';

	/**
	 * No muestra el dato en el listado (lo unico que hace es esconderlo por mecio de css con la propiedad display none, su valor por defecto es false.
	 *
	 * @name noMostrar
	 * @access protected
	 * @var boolean
	 */
	protected $noMostrar = false;

	/**
	 * No permite editar el campo en el formulario de edicion.
	 *
	 * @name noEditar
	 * @var boolean
	 */
	protected $noEditar = false;

	/**
	 * no mostrar el campo en el listado.
	 *
	 * @name noListar
	 * @access protected
	 * @var boolean
	 */
	protected $noListar = false;

	/**
	 * establece si hay que limpiar o no las etiquetas html.
	 *
	 * @access protected
	 * @var boolean
	 */
	protected $noLimpiar = false;

	/**
	 * No incuye ni muestra ese campo en el formulario de alta.
	 *
	 * @access protected
	 * @name noNuevo
	 * @var boolean
	 */
	protected $noNuevo = false;

	/**
	 * Si esta seteado usa este titulo en el listado.
	 *
	 * @name tituloListado
	 * @access protected
	 * @var string
	 */
	protected $tituloListado = '';

	/**
	 * Colorea el texto de esta columna en el listado segun el valor.
	 *
	 * @example Array("Hombre" => "blue", "Mujer" => "#FF00AE")
	 *
	 * @name colorearValores
	 * @access protected
	 * @var array
	 */
	protected $colorearValores = array ();

	/**
	 * String con el texto para mostrar en el separador.
	 * El separador aparece en los formularios de edicion y alta. Es un TH colspan='2' para separar la informacion visualmente.
	 *
	 * @name separador
	 * @access protected
	 * @var string
	 */
	protected $separador = '';

	/**
	 * Maximo de caracteres que permite ingresar el input del formulario.
	 *
	 * @name maxLen
	 * @var integer
	 * @access protected
	 */
	protected $maxLen = 0;

	/**
	 * No permite ordenar por ese campo haciendo click en el titulo de la columna.
	 *
	 * @name noOrdenar
	 * @access protected
	 * @var boolean
	 */
	protected $noOrdenar = false;

	/**
	 * Campo de la tabla izquierda.
	 * Es el que tiene el valor que va en <option value='{ac&aacute;}'>
	 *
	 * @todo Obligatorio para el tipo de campo dbCombo.
	 *
	 * @name campoValor
	 * @access protected
	 * @var string
	 */
	protected $campoValor = '';

	/**
	 * Campo de la tabla izquierda que tiene el texto que se muestra en el listado y que va en <option value=''>{ac&aacute;}</option>
	 *
	 * @todo Obligatorio para el tipo de campo dbCombo.
	 *
	 * @name campoTexto
	 * @access protected
	 * @var string
	 */
	protected $campoTexto = '';

	/**
	 * Tabla para hacer join en el listado (es la misma tabla de sqlQuery).
	 *
	 * @todo Obligatorio para el tipo de campo dbCombo.
	 *
	 * @name joinTable
	 * @access protected
	 * @var string
	 */
	protected $joinTable = '';

	/**
	 * Indica si un campo en particular imprime o no su join
	 * por defecto es true pero se puede usar para imprimir joins personalizados
	 *
	 * @access protected
	 * @var boolean
	 */
	protected $omitirJoin = false;

	/**
	 * Para hacer join en el listado.
	 * Valores posibles:
	 * - INNER: devuelve registros que tienen valores coincidentes en ambas tablas
	 * - LEFT: Devuelve todos los registros de la tabla de la izquierda y los registros coincidentes de la tabla de la derecha
	 * - RIGHT: devuelva todos los registros de la tabla correcta y los registros coincidentes de la tabla izquierda
	 * - FULL: devuelve todos los registros cuando hay una coincidencia en la tabla izquierda o derecha
	 *
	 * @todo El valor por defecto es INNER.
	 *
	 * @name joinCondition
	 * @access protected
	 * @var string
	 */
	protected $joinCondition = 'INNER';

	/**
	 * Condicion a agregar al final del join
	 *
	 * XXX esto es un parche temporal y hay que corregirlo.
	 *
	 * @access protected
	 * @var string
	 */
	protected $compareMasJoin = '';

	/**
	 * Valor predefinido para un campo en el formulario de alta.
	 *
	 * @name valorPredefinido
	 * @access protected
	 * @var string
	 */
	protected $valorPredefinido = '';

	/**
	 * Campo a remplazar en la formula de customPrintListado.
	 * Cuando haya mas de un campo a incluir deberan separarse con coma. No hay que olvidar encerrar los campor con llaves para que el sistema los reconosca.
	 *
	 * @name incluirCampo
	 * @var string
	 * @access protected
	 */
	protected $incluirCampo = '';

	/**
	 * sprintf para imprimir en el listado.
	 * %s sera el valor del campo y {id} se remplaza por el Id del registro definido para la tabla.
	 *
	 * @example @link examples/customPrintListado.html
	 *
	 * @name customPrintListado
	 * @access protected
	 * @var string
	 */
	protected $customPrintListado = '';

	/**
	 * Campo que usa para hacer el order by al cliquear el titulo de la columna, esto es ideal para cuando se usa un query en la funcion generarAbm()
	 *
	 * @name campoOrder
	 * @access protected
	 * @var string
	 */
	protected $campoOrder = '';

	/**
	 * el campo es requerido
	 *
	 * @name requerido
	 * @var boolean
	 * @access protected
	 */
	protected $requerido = false;

	/**
	 * una funcion de usuario que reciba el parametro $fila.
	 * Es para poner un campo especial en el formulario de alta y modificacion para ese campo en particular. Esto es util por ejemplo para poner un editor WUSIWUG.
	 *
	 * @name formItem
	 * @access protected
	 * @var string
	 */
	protected $formItem = '';

	/**
	 * Agrega el class "label" cuando colorea un valor.
	 * Por defecto es FALSE
	 *
	 * @access protected
	 * @name colorearConEtiqueta
	 * @var boolean
	 */
	protected $colorearConEtiqueta = '';

	/**
	 * JOIN a agregar a la consulta
	 *
	 * @access protected
	 * @name customJoin
	 * @var string
	 */
	protected $customJoin = '';

	/**
	 * Funcion de usuario que se encarga del archivo subido.
	 * Recibe los parametros id y tabla. Debe retornar TRUE si la subida se realizo con exito.
	 *
	 * @name uploadFunction
	 * @var string
	 * @access protected
	 */
	protected $uploadFunction = '';

	/**
	 * Para el tipo de campo upload.
	 * Si falla el upload borra el registro recien creado. Por defecto es FALSE. No tiene efecto en el update.
	 *
	 * @access protected
	 * @name borrarSiUploadFalla
	 * @var Boolean
	 */
	protected $borrarSiUploadFalla = '';

	/**
	 * para agregar html dentro de los tags del input.
	 * <input type='text' {ac&aacute;}>
	 *
	 * @access protected
	 * @name adicionalInput
	 * @var string
	 */
	protected $adicionalInput = '';

	/**
	 * permite especificar un ancho a esa columna en el listado (ej: 80px)
	 *
	 * @access protected
	 * @name anchoColumna
	 * @var string
	 */
	protected $anchoColumna = '';

	/**
	 * no muestra el campo en el formulario de edicion
	 *
	 * @access protected
	 * @name noMostrarEditar
	 * @var boolean
	 */
	protected $noMostrarEditar = false;

	/**
	 * para ejecutar una funcion del usuario en cada celda del listado sin imprimir ni siquiera los tags < td >< / td>.
	 * La funcion debe recibir el parametro $fila que contendra todos los datos de la fila
	 *
	 * @access protected
	 * @name customFuncionListado
	 * @var string
	 */
	protected $customFuncionListado = '';

	/**
	 * para ejecutar una funcion del usuario en el valor antes de usarlo para el query sql en las funciones de INSERT Y UPDATE.
	 * La funcion debe recibir el parametro $valor y retornar el nuevo valor
	 *
	 * @access protected
	 * @name customFuncionValor
	 * @var string
	 */
	protected $customFuncionValor = '';

	/**
	 * Listado de tipos admitidos como tipo de campo.
	 * No hay seter y geter de esta variable, se considera una constante.
	 *
	 *
	 * @access protected
	 * @var string[]
	 */
	// protected const TIPOSADMITIDOS = array (
	protected $tiposAdmitidos = array (
			'texto',
			'bit',
			'textarea',
			'combo',
			'dbcombo',
			'password',
			'upload',
			'moneda',
			'numero',
			'rownum',
			'fecha'
	);

	/**
	 * Objeto que aglutina las opciones y funciones genericas
	 *
	 * @access protected
	 * @var object
	 */
	protected $sitio;

	/*
	 * Parametros referidos a la busqueda
	 */

	/**
	 * Si esta en true permite buscar por ese campo.
	 * No funciona si se usa la funcion generarAbm() con un query.
	 *
	 * @todo NOTA: el buscador funciona verificando variables de $_REQUEST con los nombres de los campos con prefijo "c_". Si se quisiera hacer un formulario de busqueda independiente sin usar el de la class se puede hacer usando los mismos nombres de los campos, o sea con el prefijo "c_".)
	 *
	 * @access protected
	 * @name buscar
	 * @var boolean
	 */
	protected $buscar = true;

	/**
	 * lo mismo que tipo pero solo para el formulario de busqueda
	 *
	 * @access protected
	 * @name tipoBuscar
	 * @var string
	 */
	protected $tipoBuscar = '';

	/**
	 * Operador que usa en el where.
	 * Ej. = , LIKE
	 *
	 * @access protected
	 * @name buscarOperador
	 * @var string
	 */
	protected $buscarOperador = '';

	/**
	 * Si esta seteado usa ese campo en el where para buscar
	 *
	 * @access protected
	 * @name buscarUsarCampo
	 * @var string
	 */
	protected $buscarUsarCampo = '';

	/**
	 * Funcion del usuario para poner un HTML especial en el lugar donde iria el form item del formulario de busqueda.
	 * La funcion no recibe ningun parametro.
	 *
	 * @access protected
	 * @name customFuncionBuscar
	 * @var string
	 */
	protected $customFuncionBuscar = '';

	/**
	 * si esta seteado usa este titulo en el formulario de busqueda
	 *
	 * @access protected
	 * @name tituloBuscar
	 * @var string
	 */
	protected $tituloBuscar = '';

	/**
	 * Select personal incluir en la consulta para el campo particular
	 *
	 * @access protected
	 * @var string
	 */
	protected $selectPersonal = '';

	/**
	 * En caso de ir en una solapa diferente indica en cual.
	 *
	 * @access protected
	 * @var integer
	 */
	protected $enSolapa = 0;

	/**
	 * Valor del atributo autofocus
	 *
	 * @access protected
	 * @var string
	 */
	protected $autofocusAttr = "";

	/**
	 * Valor del atributo autofocus
	 *
	 * @access protected
	 * @var boolean
	 */
	protected $autofocus = false;

	/**
	 * Va a retornar el valor (la informacion) del campo.
	 *
	 * @access public
	 * @return String
	 */
	public function __toString(): string
	{
		// return valorCampo ();
		return $this->getCampo ();
	}

	/**
	 * devuelve un objeto campo basado en el array pasado.
	 *
	 * @access public
	 * @param array $array
	 * @return class_campo
	 */
	public static function toObject(array $array): class_campo
	{
		$array = new class_campo ($array);

		return $array;
	}

	/**
	 * Asigna los valores del array a cada uno de los parametros de la clase
	 *
	 * @access public
	 * @param array $array
	 */
	public function __construct(array $array = array())
	{
		if (isset ($array) and !empty ($array))
		{
			if (array_key_exists ('campo', $array))
			{
				$this->setCampo ($array['campo']);
			}
			if (array_key_exists ('tipo', $array))
			{
				$this->setTipo ($array['tipo']);
			}
			if (array_key_exists ('exportar', $array))
			{
				$this->isExportar ($array['exportar']);
			}
			if (array_key_exists ('titulo', $array))
			{
				$this->setTitulo ($array['titulo']);
			}
			if (array_key_exists ('centrarColumna', $array))
			{
				$this->isCentrarColumna ($array['centrarColumna']);
			}
			if (array_key_exists ('customEvalListado', $array))
			{
				$this->setCustomEvalListado ($array['customEvalListado']);
			}
			if (array_key_exists ('cantidadDecimales', $array))
			{
				$this->setCantidadDecimales ($array['cantidadDecimales']);
			}
			if (array_key_exists ('buscar', $array))
			{
				$this->setBuscar ($array['buscar']);
			}
			if (array_key_exists ('noMostrar', $array))
			{
				$this->setNoMostrar ($array['noMostrar']);
			}
			if (array_key_exists ('noEditar', $array))
			{
				$this->setNoEditar ($array['noEditar']);
			}
			if (array_key_exists ('noListar', $array))
			{
				$this->setNoListar ($array['noListar']);
			}
			if (array_key_exists ('noLimpiar', $array))
			{
				$this->setNoLimpiar ($array['noLimpiar']);
			}
			if (array_key_exists ('noNuevo', $array))
			{
				$this->setNoNuevo ($array['noNuevo']);
			}
			if (array_key_exists ('tituloListado', $array))
			{
				$this->setTituloListado ($array['tituloListado']);
			}
			if (array_key_exists ('colorearValores', $array))
			{
				$this->setColorearValores ($array['colorearValores']);
			}
			if (array_key_exists ('separador', $array))
			{
				$this->setSeparador ($array['separador']);
			}
			if (array_key_exists ('maxLen', $array))
			{
				$this->setMaxLen ($array['maxLen']);
			}
			if (array_key_exists ('noOrdenar', $array))
			{
				$this->isNoOrdenar ($array['noOrdenar']);
			}
			if (array_key_exists ('sqlQuery', $array))
			{
				$this->setSqlQuery ($array['sqlQuery']);
			}
			if (array_key_exists ('campoValor', $array))
			{
				$this->setCampoValor ($array['campoValor']);
			}
			if (array_key_exists ('campoTexto', $array))
			{
				$this->setCampoTexto ($array['campoTexto']);
			}
			if (array_key_exists ('joinTable', $array))
			{
				$this->setJoinTable ($array['joinTable']);
			}
			if (array_key_exists ('joinCondition', $array))
			{
				$this->setJoinCondition ($array['joinCondition']);
			}
			if (array_key_exists ('omitirJoin', $array))
			{
				$this->setOmitirJoin ($array['omitirJoin']);
			}
			if (array_key_exists ('incluirOpcionVacia', $array))
			{
				$this->isIncluirOpcionVacia ($array['incluirOpcionVacia']);
			}
			if (array_key_exists ('mostrarValor', $array))
			{
				$this->isMostrarValor ($array['mostrarValor']);
			}
			if (array_key_exists ('textoMayuscula', $array))
			{
				$this->isTextoMayuscula ($array['textoMayuscula']);
			}
			if (array_key_exists ('valorPredefinido', $array))
			{
				$this->setValorPredefinido ($array['valorPredefinido']);
			}
			if (array_key_exists ('incluirCampo', $array))
			{
				$this->setIncluirCampo ($array['incluirCampo']);
			}
			if (array_key_exists ('customPrintListado', $array))
			{
				$this->setCustomPrintListado ($array['customPrintListado']);
			}
			if (array_key_exists ('campoOrder', $array))
			{
				$this->setCampoOrder ($array['campoOrder']);
			}
			if (array_key_exists ('tituloBuscar', $array))
			{
				$this->setTituloBuscar ($array['tituloBuscar']);
			}
			if (array_key_exists ('requerido', $array))
			{
				$this->isRequerido ($array['requerido']);
			}
			if (array_key_exists ('formItem', $array))
			{
				$this->setFormItem ($array['formItem']);
			}
			if (array_key_exists ('colorearConEtiqueta', $array))
			{
				$this->isColorearConEtiqueta ($array['colorearConEtiqueta']);
			}
			if (array_key_exists ('customJoin', $array))
			{
				$this->setCustomJoin ($array['customJoin']);
			}
			if (array_key_exists ('selectPersonal', $array))
			{
				$this->setSelectPersonal ($array['selectPersonal']);
			}
			if (array_key_exists ('uploadFunction', $array))
			{
				$this->setUploadFunction ($array['uploadFunction']);
			}
			if (array_key_exists ('borrarSiUploadFalla', $array))
			{
				$this->isBorrarSiUploadFalla ($array['borrarSiUploadFalla']);
			}
			if (array_key_exists ('', $array))
			{
				$this->setBuscarOperador ($array['buscarOperador']);
			}
			if (array_key_exists ('buscarUsarCampo', $array))
			{
				$this->setBuscarUsarCampo ($array['buscarUsarCampo']);
			}
			if (array_key_exists ('customFuncionBuscar', $array))
			{
				$this->setCustomFuncionBuscar ($array['customFuncionBuscar']);
			}
			if (array_key_exists ('adicionalInput', $array))
			{
				$this->setAdicionalInput ($array['adicionalInput']);
			}
			if (array_key_exists ('anchoColumna', $array))
			{
				$this->setAnchoColumna ($array['anchoColumna']);
			}
			if (array_key_exists ('noMostrarEditar', $array))
			{
				$this->setNoMostrarEditar ($array['noMostrarEditar']);
			}
			if (array_key_exists ('customFuncionListado', $array))
			{
				$this->setCustomFuncionListado ($array['customFuncionListado']);
			}
			if (array_key_exists ('customFuncionValor', $array))
			{
				$this->setCustomFuncionValor ($array['customFuncionValor']);
			}
			if (array_key_exists ('tipoBuscar', $array))
			{
				$this->setTipoBuscar ($array['tipoBuscar']);
			}
			if (array_key_exists ('compareMasJoin', $array))
			{
				$this->setCompareMasJoin ($array['compareMasJoin']);
			}
		}
	}

	/*
	 * **********************************************************************
	 * ACA EN EL FONDO METO LOS GETERS AND SETERS
	 * *********************************************************************
	 */
	/**
	 * Retorna los datos del campo.
	 *
	 * @access public
	 * @return string
	 */
	public function getCampo(): string
	{
		return $this->campo;
	}

	/**
	 * Retorna el valor del Tipo
	 *
	 * @access public
	 * @return string
	 */
	public function getTipo(): string
	{
		return $this->tipo;
	}

	/**
	 * Retorna el valor de Exportar.
	 *
	 * @access public
	 * @return boolean
	 */
	public function isExportar(): bool
	{
		if ($this->exportar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de Titulo
	 *
	 * @access public
	 * @return string
	 */
	public function getTitulo(): string
	{
		return $this->titulo;
	}

	/**
	 * Retorna el valor de centrarColumna
	 *
	 * @access public
	 * @return boolean
	 */
	public function isCentrarColumna(): bool
	{
		if ($this->centrarColumna == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de customEvalListado
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomEvalListado(): string
	{
		return $this->customEvalListado;
	}

	/**
	 * Retorna el valor de getParametroUsr
	 *
	 * @access public
	 * @return string
	 */
	public function getParametroUsr(): string
	{
		return $this->parametroUsr;
	}

	/**
	 * Retorna el valor de cantidadDecimales
	 *
	 * @access public
	 * @return int
	 */
	public function getCantidadDecimales(): int
	{
		return $this->cantidadDecimales;
	}

	/**
	 * Retorna el valor de buscar
	 *
	 * @access public
	 * @return boolean
	 */
	public function isBuscar(): bool
	{
		if ($this->buscar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de noMostrar
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNoMostrar(): bool
	{
		if ($this->noMostrar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de noEditar
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNoEditar(): bool
	{
		if ($this->noEditar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de noListar
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNoListar(): bool
	{
		if ($this->noListar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de noNuevo
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNoNuevo(): bool
	{
		if ($this->noNuevo == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de tituloListado
	 *
	 * @access public
	 * @return string
	 */
	public function getTituloListado(): string
	{
		return $this->tituloListado;
	}

	/**
	 * Retorna el valor de colorearValores
	 *
	 * @access public
	 * @return array
	 */
	public function getColorearValores(): array
	{
		return $this->colorearValores;
	}

	/**
	 * Retorna el valor de separador
	 *
	 * @access public
	 * @return string
	 */
	public function getSeparador(): string
	{
		return $this->separador;
	}

	/**
	 * Retorna el valor de maxLen
	 *
	 * @access public
	 * @return number
	 */
	public function getMaxLen(): int
	{
		return $this->maxLen;
	}

	/**
	 * Retorna el valor de noOrdenar
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNoOrdenar(): bool
	{
		if ($this->noOrdenar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de campoValor
	 *
	 * @access public
	 * @return string
	 */
	public function getCampoValor(): string
	{
		return $this->campoValor;
	}

	/**
	 * Retorna el valor de campoTexto
	 *
	 * @access public
	 * @return string
	 */
	public function getCampoTexto(): string
	{
		return $this->campoTexto;
	}

	/**
	 * Retorna el valor de joinTable
	 *
	 * @access public
	 * @return string
	 */
	public function getJoinTable(): string
	{
		return $this->joinTable;
	}

	/**
	 * Retorna el valor de joinCondition
	 *
	 * @access public
	 * @return string
	 */
	public function getJoinCondition(): string
	{
		return $this->joinCondition;
	}

	/**
	 * Retorna el valor de incluirOpcionVacia
	 *
	 * @access public
	 * @return boolean
	 */
	public function isIncluirOpcionVacia(): bool
	{
		if ($this->incluirOpcionVacia == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de mostrarValor
	 *
	 * @access public
	 * @return boolean
	 */
	public function isMostrarValor(): bool
	{
		if ($this->mostrarValor == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de textoMayuscula
	 *
	 * @access public
	 * @return boolean
	 */
	public function isTextoMayuscula(): bool
	{
		if ($this->textoMayuscula == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de valorPredefinido
	 *
	 * @access public
	 * @return string
	 */
	public function getValorPredefinido(): string
	{
		return $this->valorPredefinido;
	}

	/**
	 * Retorna el valor de incluirCampo
	 *
	 * @access public
	 * @return string
	 */
	public function getIncluirCampo(): string
	{
		return $this->incluirCampo;
	}

	/**
	 * Retorna el valor de customPrintListado
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomPrintListado(): string
	{
		return $this->customPrintListado;
	}

	/**
	 * Retorna el valor de campoOrder
	 *
	 * @access public
	 * @return string
	 */
	public function getCampoOrder(): string
	{
		if ($this->campoOrder != "")
		{
			return $this->campoOrder;
		}
		else
		{
			if ($this->joinTable == "" or $this->selectPersonal != "")
			{
				return $this->tabla . "." . $this->getCampo ();
			}
			else
			{
				return $this->getJoinTable () . "." . $this->getCampoTexto ();
			}
		}
	}

	/**
	 * Retorna el valor de tituloBuscar
	 *
	 * @access public
	 * @return string
	 */
	public function getTituloBuscar(): string
	{
		return $this->tituloBuscar;
	}

	/**
	 * Retorna el valor de requerido
	 *
	 * @access public
	 * @return boolean
	 */
	public function isRequerido(): bool
	{
		if ($this->requerido == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de formItem
	 *
	 * @access public
	 * @return string
	 */
	public function getFormItem(): string
	{
		return $this->formItem;
	}

	/**
	 * Retorna el valor de colorearConEtiqueta
	 *
	 * @access public
	 * @return boolean
	 */
	public function isColorearConEtiqueta(): bool
	{
		if ($this->colorearConEtiqueta == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de customJoin
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomJoin(): string
	{
		return $this->customJoin;
	}

	/**
	 * Retorna el valor de omitirJoin
	 *
	 * @access public
	 * @return boolean
	 */
	public function isOmitirJoin(): bool
	{
		if ($this->omitirJoin == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de uploadFunction
	 *
	 * @access public
	 * @return string
	 */
	public function getUploadFunction(): string
	{
		return $this->uploadFunction;
	}

	/**
	 * Retorna el valor de borrarSiUploadFalla
	 *
	 * @access public
	 * @return boolean
	 */
	public function isBorrarSiUploadFalla(): bool
	{
		if ($this->borrarSiUploadFalla == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de buscarOperador
	 *
	 * @access public
	 * @return string
	 */
	public function getBuscarOperador(): string
	{
		return $this->buscarOperador;
	}

	/**
	 * Retorna el valor de buscarUsarCampo
	 *
	 * @access public @return string
	 */
	public function getBuscarUsarCampo(): string
	{
		return $this->buscarUsarCampo;
	}

	/**
	 * Retorna el valor de customFuncionBuscar
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomFuncionBuscar(): string
	{
		return $this->customFuncionBuscar;
	}

	/**
	 * Retorna el valor de adicionalInput
	 *
	 * @access public
	 * @return string
	 */
	public function getAdicionalInput(): string
	{
		return $this->adicionalInput;
	}

	/**
	 * Retorna el valor de anchoColumna
	 *
	 * @access public
	 * @return string
	 */
	public function getAnchoColumna(): string
	{
		return $this->anchoColumna;
	}

	/**
	 * Retorna el valor de noMostrarEditar
	 *
	 * @access public
	 * @return bool
	 */
	public function isNoMostrarEditar(): bool
	{
		if ($this->noMostrarEditar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el valor de
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomFuncionListado(): string
	{
		return $this->customFuncionListado;
	}

	/**
	 * Retorna el valor de customFuncionValor
	 *
	 * @access public
	 * @return string
	 */
	public function getCustomFuncionValor(): string
	{
		return $this->customFuncionValor;
	}

	/**
	 * Retorna el valor de tipoBuscar.
	 *
	 * @access public
	 * @return string
	 */
	public function getTipoBuscar(): string
	{
		return $this->tipoBuscar;
	}

	/**
	 * Retorna el valor de selectPersonal.
	 *
	 * @access public
	 * @return string
	 */
	public function getSelectPersonal(): string
	{
		return $this->selectPersonal;
	}

	/**
	 * Setter del parametro $noLimpiar de la clase.
	 *
	 * @access public
	 * @param boolean $noLimpiar
	 *        	dato a cargar en la variable.
	 */
	public function setNoLimpiar(bool $noLimpiar)
	{
		$this->noLimpiar = $noLimpiar;
	}

	/**
	 * Retorna el dato de no limpiar
	 *
	 * @access public
	 * @return boolean
	 */
	public function isNoLimpiar(): bool
	{
		if ($this->noLimpiar == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el dato de tituloMouseOver
	 *
	 * @access public
	 * @return string
	 */
	public function getTituloMouseOver(): string
	{
		return $this->tituloMouseOver;
	}

	/**
	 * Retorna el dato de tabla
	 *
	 * @access public
	 * @return string
	 */
	public function getTabla(): string
	{
		return $this->tabla;
	}

	/**
	 * Retorna el dato de compareMasJoin
	 *
	 * @access public
	 * @return string
	 */
	public function getCompareMasJoin(): string
	{
		return $this->compareMasJoin;
	}

	/*
	 * *************************************************************
	 * ARRANCA EL SETEO DE DATOS
	 * *************************************************************
	 */

	/**
	 * Comprueba y setea el valor de campo
	 *
	 * @example /u00/html/classes/examples/campo.html 12 10 Ejemplo de definicion de un campo para la utilizacion de la clase abm.
	 *
	 * @access public
	 * @param string $campo
	 */
	public function setCampo(string $campo)
	{
		$this->campo = $campo;
	}

	/**
	 * Comprueba y setea el valor de tipo
	 *
	 * @access public
	 * @param string $tipo
	 * @throws Exception En caso de no pertenecer a alguno de los tipos aceptados.
	 * @access public
	 * @return true Va a retornar true si todo salio Ok. En caso contrario retorna error.
	 */
	public function setTipo(string $tipo): bool
	{
		if (in_array (strtolower ($tipo), $this->tiposAdmitidos))
		{
			$this->tipo = strtolower ($tipo);

			return true;
		}
		else
		{
			throw new Exception ('Tipo de campo no admitido: ' . strtolower ($tipo) . '.');
		}
	}

	/**
	 * Comprueba y setea el valor de exportar
	 *
	 * @access public
	 * @param boolean $exportar
	 */
	public function setExportar(bool $exportar)
	{
		$this->exportar = $exportar;
	}

	/**
	 * Comprueba y setea el valor de titulo
	 *
	 * @access public
	 * @param string $titulo
	 */
	public function setTitulo(string $titulo)
	{
		$this->titulo = $titulo;
	}

	/**
	 * Comprueba y setea el valor de centrarColumna
	 *
	 * @access public
	 * @param boolean $centrarColumna
	 */
	public function setCentrarColumna(bool $centrarColumna)
	{
		$this->centrarColumna = $centrarColumna;
	}

	/**
	 * Comprueba y setea el valor de customEvalListado
	 *
	 * @access public
	 * @param string $customEvalListado
	 */
	public function setCustomEvalListado(string $customEvalListado)
	{
		$this->customEvalListado = $customEvalListado;
	}

	/**
	 * Comprueba y setea el valor de parametroUsr
	 *
	 * @access public
	 * @param string $parametroUsr
	 */
	public function setParametroUsr(string $parametroUsr)
	{
		$this->parametroUsr = $parametroUsr;
	}

	/**
	 * Comprueba y setea el valor de cantidadDecimales
	 *
	 * @access public
	 * @param number $cantidadDecimales
	 */
	public function setCantidadDecimales(int $cantidadDecimales)
	{
		$this->cantidadDecimales = $cantidadDecimales;
	}

	/**
	 * Comprueba y setea el valor de buscar
	 *
	 * @access public
	 * @param boolean $buscar
	 */
	public function setBuscar(bool $buscar)
	{
		$this->buscar = $buscar;
	}

	/**
	 * Comprueba y setea el valor de noMostrar
	 *
	 * @access public
	 * @param boolean $noMostrar
	 */
	public function setNoMostrar(bool $noMostrar)
	{
		$this->noMostrar = $noMostrar;
	}

	/**
	 * Comprueba y setea el valor de noEditar
	 *
	 * @access public
	 * @param boolean $noEditar
	 */
	public function setNoEditar(bool $noEditar)
	{
		$this->noEditar = $noEditar;
	}

	/**
	 * Comprueba y setea el valor de noListar
	 *
	 * @access public
	 * @param boolean $noListar
	 */
	public function setNoListar(bool $noListar)
	{
		$this->noListar = $noListar;
	}

	/**
	 * Comprueba y setea el valor de noNuevo
	 *
	 * @access public
	 * @param boolean $noNuevo
	 */
	public function setNoNuevo(bool $noNuevo)
	{
		$this->noNuevo = $noNuevo;
	}

	/**
	 * Comprueba y setea el valor de tituloListado
	 *
	 * @access public
	 * @param string $tituloListado
	 */
	public function setTituloListado(string $tituloListado)
	{
		$this->tituloListado = $tituloListado;
	}

	/**
	 * Comprueba y setea el valor de colorearValores
	 *
	 * @access public
	 * @param array $colorearValores
	 */
	public function setColorearValores(array $colorearValores)
	{
		$this->colorearValores = $colorearValores;
	}

	/**
	 * Comprueba y setea el valor de separador
	 *
	 * @access public
	 * @param string $separador
	 */
	public function setSeparador(string $separador)
	{
		$this->separador = $separador;
	}

	/**
	 * Comprueba y setea el valor de maxLen
	 *
	 * @access public
	 * @param int $maxLen
	 */
	public function setMaxLen(int $maxLen)
	{
		$this->maxLen = $maxLen;
	}

	/**
	 * Comprueba y setea el valor de noOrdenar
	 *
	 * @access public
	 * @param boolean $noOrdenar
	 */
	public function setNoOrdenar(bool $noOrdenar)
	{
		$this->noOrdenar = $noOrdenar;
	}

	/**
	 * Comprueba y setea el valor de campoValor
	 *
	 * @access public
	 * @param string $campoValor
	 */
	public function setCampoValor(string $campoValor)
	{
		$this->campoValor = $campoValor;
	}

	/**
	 * Comprueba y setea el valor de campoTexto
	 *
	 * @access public
	 * @param string $campoTexto
	 */
	public function setCampoTexto(string $campoTexto)
	{
		$this->campoTexto = $campoTexto;
	}

	/**
	 * Comprueba y setea el valor de joinTable
	 *
	 * @access public
	 * @param string $joinTable
	 */
	public function setJoinTable(string $joinTable)
	{
		$this->joinTable = $joinTable;
	}

	/**
	 * Comprueba y setea el valor de omitirJoin.
	 * En cualquier caso que el valor paseado no sea 1, true o v se seteara como FALSE
	 *
	 * @access public
	 * @param mixed $omitirJoin
	 */
	public function setOmitirJoin($omitirJoin)
	{
		if ($omitirJoin == 1 or $omitirJoin == true or strtolower ($omitirJoin) == 'v')
		{
			$this->omitirJoin = TRUE;
		}
		else
		{
			$this->omitirJoin = FALSE;
		}
	}

	/**
	 * Comprueba y setea el valor de joinCondition
	 *
	 * @access public
	 * @param string $joinCondition
	 */
	public function setJoinCondition(string $joinCondition)
	{
		$this->joinCondition = $joinCondition;
	}

	/**
	 * Comprueba y setea el valor de incluirOpcionVacia
	 *
	 * @access public
	 * @param boolean $incluirOpcionVacia
	 */
	public function setIncluirOpcionVacia(bool $incluirOpcionVacia)
	{
		$this->incluirOpcionVacia = $incluirOpcionVacia;
	}

	/**
	 * Comprueba y setea el valor de mostrarValor
	 *
	 * @access public
	 * @param boolean $mostrarValor
	 */
	public function setMostrarValor(bool $mostrarValor)
	{
		$this->mostrarValor = $mostrarValor;
	}

	/**
	 * Comprueba y setea el valor de textoMayuscula
	 *
	 * @access public
	 * @param boolean $textoMayuscula
	 */
	public function setTextoMayuscula(bool $textoMayuscula)
	{
		$this->textoMayuscula = $textoMayuscula;
	}

	/**
	 * Comprueba y setea el valor de valorPredefinido
	 *
	 * @access public
	 * @param string $valorPredefinido
	 */
	public function setValorPredefinido(string $valorPredefinido)
	{
		$this->valorPredefinido = $valorPredefinido;
	}

	/**
	 * Comprueba y setea el valor de incluirCampo.
	 * En caso de que incluirCampo este vasio lo inicializa, si no lo esta agrega el valor separado por coma.
	 *
	 * @access public
	 * @param string $incluirCampo
	 */
	public function setIncluirCampo(string $incluirCampo)
	{
		if ($this->incluirCampo == "")
		{
			$this->incluirCampo = $incluirCampo;
		}
		else
		{
			$this->incluirCampo .= ", " . $incluirCampo;
		}
	}

	/**
	 * Comprueba y setea el valor de customPrintListado
	 *
	 * @access public
	 * @param string $customPrintListado
	 */
	public function setCustomPrintListado(string $customPrintListado)
	{
		$this->customPrintListado = $customPrintListado;
	}

	/**
	 * Comprueba y setea el valor de campoOrder
	 *
	 * @access public
	 * @param string $campoOrder
	 */
	public function setCampoOrder(string $campoOrder)
	{
		$this->campoOrder = $campoOrder;
	}

	/**
	 * Comprueba y setea el valor de tituloBuscar
	 *
	 * @access public
	 * @param string $tituloBuscar
	 */
	public function setTituloBuscar(string $tituloBuscar)
	{
		$this->tituloBuscar = $tituloBuscar;
	}

	/**
	 * Comprueba y setea el valor de requerido
	 *
	 * @access public
	 * @param boolean $requerido
	 */
	public function setRequerido(bool $requerido)
	{
		$this->requerido = $requerido;
	}

	/**
	 * Comprueba y setea el valor de formItem
	 *
	 * @access public
	 * @param string $formItem
	 */
	public function setFormItem(string $formItem)
	{
		$this->formItem = $formItem;
	}

	/**
	 * Comprueba y setea el valor de colorearConEtiqueta
	 *
	 * @access public
	 * @param boolean $colorearConEtiqueta
	 */
	public function setColorearConEtiqueta(bool $colorearConEtiqueta)
	{
		$this->colorearConEtiqueta = $colorearConEtiqueta;
	}

	/**
	 * Comprueba y setea el valor de customJoin
	 *
	 * @access public
	 * @param string $customJoin
	 */
	public function setCustomJoin(string $customJoin)
	{
		$this->customJoin = $customJoin;
	}

	/**
	 * Comprueba y setea el valor de uploadFunction
	 *
	 * @access public
	 * @param string $uploadFunction
	 */
	public function setUploadFunction(string $uploadFunction)
	{
		$this->uploadFunction = $uploadFunction;
	}

	/**
	 * Comprueba y setea el valor de borrarSiUploadFalla
	 *
	 * @access public
	 * @param boolean $borrarSiUploadFalla
	 */
	public function setBorrarSiUploadFalla(bool $borrarSiUploadFalla)
	{
		$this->borrarSiUploadFalla = $borrarSiUploadFalla;
	}

	/**
	 * Comprueba y setea el valor de buscarOperador
	 *
	 * @access public
	 * @param string $buscarOperador
	 */
	public function setBuscarOperador(string $buscarOperador)
	{
		$this->buscarOperador = $buscarOperador;
	}

	/**
	 * Comprueba y setea el valor de buscarUsarCampo
	 *
	 * @access public
	 * @param string $buscarUsarCampo
	 */
	public function setBuscarUsarCampo(string $buscarUsarCampo)
	{
		$this->buscarUsarCampo = $buscarUsarCampo;
	}

	/**
	 * Comprueba y setea el valor de customFuncionBuscar
	 *
	 * @access public
	 * @param string $customFuncionBuscar
	 */
	public function setCustomFuncionBuscar(string $customFuncionBuscar)
	{
		$this->customFuncionBuscar = $customFuncionBuscar;
	}

	/**
	 * Comprueba y setea el valor de adicionalInput
	 *
	 * @access public
	 * @param string $adicionalInput
	 */
	public function setAdicionalInput(string $adicionalInput)
	{
		$this->adicionalInput = $adicionalInput;
	}

	/**
	 * Comprueba y setea el valor de anchoColumna
	 *
	 * @access public
	 * @param string $anchoColumna
	 */
	public function setAnchoColumna(string $anchoColumna)
	{
		$this->anchoColumna = $anchoColumna;
	}

	/**
	 * Comprueba y setea el valor de noMostrarEditar
	 *
	 * @access public
	 * @param bool $noMostrarEditar
	 */
	public function setNoMostrarEditar(bool $noMostrarEditar)
	{
		$this->noMostrarEditar = $noMostrarEditar;
	}

	/**
	 * Comprueba y setea el valor de customFuncionListado
	 *
	 * @access public
	 * @param string $customFuncionListado
	 */
	public function setCustomFuncionListado(string $customFuncionListado)
	{
		$this->customFuncionListado = $customFuncionListado;
	}

	/**
	 * Comprueba y setea el valor de customFuncionValor
	 *
	 * @access public
	 * @param string $customFuncionValor
	 */
	public function setCustomFuncionValor(string $customFuncionValor)
	{
		$this->customFuncionValor = $customFuncionValor;
	}

	/**
	 * Comprueba y setea el valor de tipoBuscar
	 *
	 * @access public
	 * @param string $tipoBuscar
	 */
	public function setTipoBuscar(string $tipoBuscar)
	{
		$this->tipoBuscar = $tipoBuscar;
	}

	/**
	 *
	 * Comprueba y setea el valor de selectPersonal
	 *
	 * @access public
	 * @param string $selectPersonal
	 */
	public function setSelectPersonal(string $selectPersonal)
	{
		$this->selectPersonal = $selectPersonal;
	}

	/**
	 * Setea el parametro tituloMouseOver
	 *
	 * @access public
	 * @param string $tituloMouseOver
	 */
	public function setTituloMouseOver(string $tituloMouseOver)
	{
		$this->tituloMouseOver = $tituloMouseOver;
	}

	/**
	 * Setea el parametro sqlQuery
	 *
	 * @access public
	 * @param string $sqlQuery
	 */
	public function setSqlQuery(string $sqlQuery)
	{
		$this->setSqlQuery = $sqlQuery;
	}

	/**
	 * Setea el parametro tabla
	 *
	 * @access public
	 * @param string $tabla
	 */
	public function setTabla(string $tabla)
	{
		$this->tabla = $tabla;
	}

	/**
	 * Setea el parametro compareMasJoin
	 *
	 * @access public
	 * @param string $compareMasJoin
	 */
	public function setCompareMasJoin(string $compareMasJoin)
	{
		$this->compareMasJoin = $compareMasJoin;
	}

	/**
	 * Retorna el valor de $enSolapa
	 *
	 * @access public
	 * @return number
	 */
	public function getEnSolapa(): int
	{
		return $this->enSolapa;
	}

	/**
	 * Setea $enSolapa con el parametro dado.
	 *
	 * @access public
	 * @param int $enSolapa
	 */
	public function setEnSolapa(int $enSolapa)
	{
		$this->enSolapa = $enSolapa;
	}

	/*
	 * OTRAS FUNCIONES
	 */

	/**
	 * Retorna un string con la funcion a insetar en el form
	 *
	 * @access public
	 */
	public function getTituloOver(): string
	{
		if ($this->tituloMouseOver = !"")
		{
			return ' title="' . $this->tituloMouseOver . '" ';
		}
	}

	/**
	 * Comprueba que esxista cun campo en particular y que sea distinto de nulo
	 *
	 * @param string $dato
	 *        	nombre del atributo a comprobar.
	 * @access public
	 * @return boolean
	 */
	public function existeDato(string $dato): bool
	{
		if (isset ($this->$dato) and $this->$dato != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna el titulo que se debe utilizar para un campo.
	 *
	 * @param boolean $paraBuscar
	 *        	especifica si se utilizara en un formulario de busqueda.
	 * @access public
	 * @return string
	 */
	public function obtenerTitulo(bool $paraBuscar = false): string
	{
		if ($this->existeDato ("tituloBuscar") and $paraBuscar == true)
		{
			return $this->getTituloBuscar ();
		}
		elseif ($this->existeDato ("tituloListado"))
		{
			return $this->getTituloListado ();
		}
		elseif ($this->existeDato ("titulo"))
		{
			return $this->getTitulo ();
		}
		else
		{
			return $this->getCampo ();
		}
	}

	/**
	 * Limpia el campo y genera el elemento del formulario de busqueda para incorporar en la pagina.
	 *
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 * @access public
	 * @return string
	 */
	public function campoFormBuscar(&$busqueda): string
	{
		$retorno = "";
		if (isset ($_REQUEST['c_' . $this->campo]))
		{
			$valor = Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);

			// FIXME - esto es un parche para poder paginar sin perder la busqueda pero hay que corregirlo para mejorarlo
			$busqueda .= '&c_' . $this->campo . '=' . Funciones::limpiarEntidadesHTML ($_REQUEST['c_' . $this->campo]);
		}
		else
		{
			$valor = "";
		}

		$retorno .= "<input type='text' class='input-text' name='c_" . $this->campo . "' value='" . $valor . "' /> \n";

		return $retorno;
	}

	/**
	 *
	 * @access public
	 * @return mixed
	 */
	public function prepara_joinTable()
	{
		$join = explode (".", $this->joinTable);

		return $join[count ($join) - 1];
	}

	/**
	 * Si esta seteado el campo retorna el parametro a poner en la etiqueta.
	 *
	 * @access public
	 * @return string
	 */
	public function establecerMaxLeng(): string
	{
		if ($this->getMaxLen () > 0)
		{
			return " maxlength='" . $this->getMaxLen () . "' ";
		}
		else
		{
			return " ";
		}
	}

	/**
	 * Si esta seteado el campo retorna el parametro a poner en la etiqueta.
	 *
	 * @access public
	 * @return string
	 */
	public function establecerHint(): string
	{
		if ($this->existeDato ('hint'))
		{
			return "title='" . $this->hint . "' ";
		}
		else
		{
			return " ";
		}
	}

	/**
	 * Retorna el valor de autofocus
	 *
	 * @access public
	 * @return boolean el dato de la variable $autofocus
	 */
	public function isAutofocus(): bool
	{
		if ($this->autofocus == true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Establece el atributo del elemento autofocusAttr en base al parametro pasado.
	 *
	 * @access public
	 * @param
	 *        	boolean a cargar en la variable $autofocusAttr
	 */
	public function setAutofocus(bool $autofocus)
	{
		if ($autofocus == true)
		{
			$this->autofocusAttr = "autofocus='autofocus'";
		}
		elseif ($autofocus == false)
		{
			$this->autofocusAttr = "";
		}
	}

	/**
	 * Retorna el valor del atributo $valor
	 *
	 * @access public
	 * @return mixed $valor el dato de la variable.
	 */
	public function getValor()
	{
		return $this->valor;
	}

	/**
	 * Setter del parametro $valor de la clase.
	 *
	 * @access public
	 * @param mixed $valor
	 *        	dato a cargar en la variable.
	 */
	public function setValor($valor)
	{
		$this->valor = $valor;
	}

	/**
	 * Arma el string del nombre del campo (tabla.campo AS nombrecampo) para agregar en el SELECT
	 *
	 * @access public
	 * @return string
	 */
	public function get_campo_select(): string
	{
		if ($this->isBuscar () == true or $this->isNoListar () == false)
		{
			if ($this->existeDato ("joinTable"))
			{
				$tablaJoin = $this->prepara_joinTable ($this->getJoinTable ());

				if ($this->isOmitirJoin () == false)
				{
					if ($this->getSelectPersonal () == true)
					{
						return $this->getSelectPersonal () . " AS " . substr ($tablaJoin . "_" . $this->getCampoTexto (), 0, 30);
					}
					else
					{
						if ($this->getCampoTexto () != "")
						{
							return $this->getJoinTable () . "." . $this->getCampoTexto () . " AS " . substr ($tablaJoin . "_" . $this->getCampoTexto (), 0, 30);
						}
						else
						{
							return $this->getJoinTable () . "." . $this->getCampo () . " AS " . substr ($tablaJoin . "_" . $this->getCampo (), 0, 30);
						}
					}
					// FIXME calculo que habria que armar otra funcion que haga esto
					// $camposOrder .= "|" . $this->getCampoTexto ();
				}
				else
				{
					if ($this->getSelectPersonal () == true)
					{
						return $this->getSelectPersonal () . " AS " . $this->getCampoTexto ();
					}
					else
					{
						// FIXME Hay que encontrar un metodo mejor ya que si hay mas de una tabla con el mismo campo y las primeras tres letras del nombre de la tabla iguales tirara que la columna esta definida de forma ambigua.

						$camposSelect = $this->getJoinTable () . "." . $this->getCampo () . " AS " . substr ($tablaJoin, 0, 3) . "_" . $this->getCampo ();

						$this->setCampo (substr ($tablaJoin, 0, 3) . "_" . $this->getCampo ());

						return $camposSelect;
					}
				}
			}
			else
			{
				if ($this->getSelectPersonal () == true)
				{
					return $this->getSelectPersonal () . " AS " . $this->getCampo ();
				}
				else
				{
					return $this->tabla . "." . $this->getCampo ();
				}
			}
		}
	}

	/**
	 * Arma el where para la busqueda dentro de ese campo.
	 *
	 * @param string $valorABuscar
	 * @access public
	 * @return string
	 */
	public function get_where_buscar(string $valorABuscar): string
	{
		$camposWhereBuscar = "";

		if ($this->buscarUsarCampo != "")
		{
			$camposWhereBuscar .= "UPPER(" . $this->getBuscarUsarCampo () . ")";
		}
		else
		{
			if ($this->joinTable == "" or $this->selectPersonal != "")
			{
				$camposWhereBuscar .= "UPPER(" . $this->tabla . "." . $this->getCampo () . ")";
			}
			else
			{
				$camposWhereBuscar .= "UPPER(" . $this->getJoinTable () . "." . $this->getCampoTexto () . ")";
			}
		}

		$camposWhereBuscar .= " ";

		if ($this->getBuscarOperador () != "" and strtolower ($this->getBuscarOperador ()) != 'like')
		{
			$camposWhereBuscar .= $this->buscarOperador . " UPPER('" . $valorABuscar . "')";
		}
		else
		{
			$valorABuscar = str_replace (" ", "%", $valorABuscar);
			$camposWhereBuscar .= "LIKE UPPER('%" . $valorABuscar . "%')";
		}

		return $camposWhereBuscar;
	}

	/**
	 * Comprueba que este habilitado el ocultar columna y en caso de estarlo retorna la etiqueta para realizarlo.
	 *
	 * @access public
	 * @return string
	 */
	public function get_no_mostrar(): string
	{
		if ($this->isNoMostrar () == true)
		{
			return " style='display: none;' ";
		}
		else
		{
			return " ";
		}
	}

	/**
	 * Comprueba que este habilitado el centrado de la columna y en caso de estarlo retorna la etiqueta para realizarlo.
	 *
	 * @access public
	 * @return string
	 */
	public function get_centrar_columna(): string
	{
		if ($this->isCentrarColumna () == true)
		{
			return ' align="center" ';
		}
		else
		{
			return " ";
		}
	}

	/**
	 * Comprueba si hay que colorear y retorna la etiqueta correspondiente
	 *
	 * @access protected
	 * @return string
	 */
	protected function get_spanColorear(): string
	{
		if (is_array ($this->getColorearValores ()))
		{
			if ($this->getCampo () != "" and array_key_exists ($this->getValor (), $this->getColorearValores ()))
			{
				return "<span class='" . ($this->isColorearConEtiqueta () ? "label" : "") . "' style='" . ($this->isColorearConEtiqueta () ? "background-" : "") . "color:" . $this->getColorearValores ()[$this->getValor ()] . "'>";
			}
		}

		return "";
	}

	/**
	 * Arma un Td con el dato de valor del campo
	 *
	 * @access public
	 * @return string
	 */
	public function get_celda_dato(): string
	{
		if ($this->isNoLimpiar () == true)
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . html_entity_decode ($this->getValor ()) . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . " </td> \n";
		}
		else
		{
			return "<td " . $this->get_centrar_columna () . " " . $this->get_no_mostrar () . ">" . $this->get_spanColorear () . " " . $this->getValor () . " " . ($this->get_spanColorear () != "" ? "</span>" : "") . "</td> \n";
		}
	}

	/**
	 * Arma el string para que el campo del formulario sea o no obligatorio.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAtrRequerido(): string
	{
		if ($this->isRequerido () == true)
		{
			return " validate[required] required ";
		}
		else
		{
			return "";
		}
	}

	/**
	 * Arma un string en caso de que el dato no pueda modificarse para bloquear el input.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getAtrDisabled(): string
	{
		if ($this->isRequerido () == true)
		{
			return " readonly='readonly' disabled='disabled' ";
		}
		else
		{
			return "";
		}
	}

	/**
	 * Arma un string con el input correspondiente al campo para armar un formulario de update.
	 *
	 * @access public
	 * @return string
	 */
	public function generar_elemento_form_update(): string
	{
		return "<input type='text' class='input-text " . $this->getAtrRequerido () . " name='" . $this->getCampo () . "' id='" . $this->getCampo () . "' " . $this->autofocusAttr . " " . $this->getAtrDisabled () . " value='" . $this->getValor () . "' " . $this->establecerMaxLeng () . " " . $this->establecerHint () . " " . $this->getAdicionalInput () . "/> \n";
	}
}
?>