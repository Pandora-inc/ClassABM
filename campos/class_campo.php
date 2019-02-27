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
	 * @var string
	 */
	protected $tipo = '';

	/**
	 * Incluye ese campo en la exportacion.
	 * Si al menos uno de los campos lo incluye entonces aparecen los iconos de exportar.
	 *
	 * ATENCION: Leer referencia de la funcion exportar_verificar()
	 *
	 * @name exportar
	 * @var boolean
	 */
	protected $exportar = true;

	/**
	 * Texto para el campo en los formularios y listado.
	 *
	 * @name titulo
	 * @var string
	 */
	protected $titulo = '';

	/**
	 * Texto para el campo en los formularios y listado al pasar el mouse por encima.
	 *
	 * @name tituloMouseOver
	 * @var string
	 */
	protected $tituloMouseOver = '';

	/**
	 * Centrar los datos de la columna en el listado.
	 *
	 * @name centrarColumna
	 * @var boolean
	 */
	protected $centrarColumna = false;

	/**
	 * Codig PHP para ejecutar en cada celda del listado sin imprimir ni siquiera los tags td.
	 * Las variables utilizables por defecto son $id y $valor.
	 *
	 * @example @link examples/customEvalListado.html
	 * @name customEvalListado
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
	 * @var string
	 */
	protected $parametroUsr = '';

	/**
	 * No muestra el dato en el listado (lo unico que hace es esconderlo por mecio de css con la propiedad display none, su valor por defecto es false.
	 *
	 * @name noMostrar
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
	 * @var boolean
	 */
	protected $noListar = false;

	/**
	 * No incuye ni muestra ese campo en el formulario de alta.
	 *
	 * @name noNuevo
	 * @var boolean
	 */
	protected $noNuevo = false;

	/**
	 * Si esta seteado usa este titulo en el listado.
	 *
	 * @name tituloListado
	 * @var string
	 */
	protected $tituloListado = '';

	/**
	 * Colorea el texto de esta columna en el listado segun el valor.
	 *
	 * @example Array("Hombre" => "blue", "Mujer" => "#FF00AE")
	 *
	 * @name colorearValores
	 * @var array
	 */
	protected $colorearValores = array ();

	/**
	 * String con el texto para mostrar en el separador.
	 * El separador aparece en los formularios de edicion y alta. Es un TH colspan='2' para separar la informacion visualmente.
	 *
	 * @name separador
	 * @var string
	 */
	protected $separador = '';

	/**
	 * Maximo de caracteres que permite ingresar el input del formulario.
	 *
	 * @name maxLen
	 * @var integer
	 */
	protected $maxLen = 00;

	/**
	 * Maximo de caracteres que mostrara por pantalla.
	 *
	 * @name maxMostrar
	 * @var integer
	 */
	protected $maxMostrar = 0;

	/**
	 * No permite ordenar por ese campo haciendo click en el titulo de la columna.
	 *
	 * @name noOrdenar
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
	 * @var string
	 */
	protected $campoValor = '';

	/**
	 * Campo de la tabla izquierda que tiene el texto que se muestra en el listado y que va en <option value=''>{ac&aacute;}</option>
	 *
	 * @todo Obligatorio para el tipo de campo dbCombo.
	 *
	 * @name campoTexto
	 * @var string
	 */
	protected $campoTexto = '';

	/**
	 * Tabla para hacer join en el listado (es la misma tabla de sqlQuery).
	 *
	 * @todo Obligatorio para el tipo de campo dbCombo.
	 *
	 * @name joinTable
	 * @var string
	 */
	protected $joinTable = '';

	/**
	 * Indica si un campo en particular imprime o no su join
	 * por defecto es true pero se puede usar para imprimir joins personalizados
	 *
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
	 * @var string
	 */
	protected $joinCondition = 'INNER';

	/**
	 * Valor predefinido para un campo en el formulario de alta.
	 *
	 * @name valorPredefinido
	 * @var string
	 */
	protected $valorPredefinido = '';

	/**
	 * Campo a remplazar en la formula de customPrintListado.
	 * Cuando haya mas de un campo a incluir deberan separarse con coma. No hay que olvidar encerrar los campor con llaves para que el sistema los reconosca.
	 *
	 * @name incluirCampo
	 * @var string
	 */
	protected $incluirCampo = '';

	/**
	 * sprintf para imprimir en el listado.
	 * %s sera el valor del campo y {id} se remplaza por el Id del registro definido para la tabla.
	 *
	 * @example @link examples/customPrintListado.html
	 *
	 * @name customPrintListado
	 * @var string
	 */
	protected $customPrintListado = '';

	/**
	 * Campo que usa para hacer el order by al cliquear el titulo de la columna, esto es ideal para cuando se usa un query en la funcion generarAbm()
	 *
	 * @name campoOrder
	 * @var string
	 */
	protected $campoOrder = '';

	/**
	 * el campo es requerido
	 *
	 * @name requerido
	 * @var boolean
	 */
	protected $requerido = '';

	/**
	 * una funcion de usuario que reciba el parametro $fila.
	 * Es para poner un campo especial en el formulario de alta y modificacion para ese campo en particular. Esto es util por ejemplo para poner un editor WUSIWUG.
	 *
	 * @name formItem
	 * @var string
	 */
	protected $formItem = '';

	/**
	 * Agrega el class "label" cuando colorea un valor.
	 * Por defecto es FALSE
	 *
	 * @name colorearConEtiqueta
	 * @var boolean
	 */
	protected $colorearConEtiqueta = '';

	/**
	 * JOIN a agregar a la consulta
	 *
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
	 */
	protected $uploadFunction = '';

	/**
	 * Para el tipo de campo upload.
	 * Si falla el upload borra el registro recien creado. Por defecto es FALSE. No tiene efecto en el update.
	 *
	 * @name borrarSiUploadFalla
	 * @var Boolean
	 */
	protected $borrarSiUploadFalla = '';

	/**
	 * para agregar html dentro de los tags del input.
	 * <input type='text' {ac&aacute;}>
	 *
	 * @name adicionalInput
	 * @var string
	 */
	protected $adicionalInput = '';

	/**
	 * permite especificar un ancho a esa columna en el listado (ej: 80px)
	 *
	 * @name anchoColumna
	 * @var string
	 */
	protected $anchoColumna = '';

	/**
	 * no muestra el campo en el formulario de edicion
	 *
	 * @name noMostrarEditar
	 * @var string
	 */
	protected $noMostrarEditar = '';

	/**
	 * para ejecutar una funcion del usuario en cada celda del listado sin imprimir ni siquiera los tags < td >< / td>.
	 * La funcion debe recibir el parametro $fila que contendra todos los datos de la fila
	 *
	 * @name customFuncionListado
	 * @var string
	 */
	protected $customFuncionListado = '';

	/**
	 * para ejecutar una funcion del usuario en el valor antes de usarlo para el query sql en las funciones de INSERT Y UPDATE.
	 * La funcion debe recibir el parametro $valor y retornar el nuevo valor
	 *
	 * @name customFuncionValor
	 * @var string
	 */
	protected $customFuncionValor = '';

	/**
	 * Listado de tipos admitidos como tipo de campo.
	 * No hay seter y geter de esta variable, se considera una constante.
	 *
	 * @var string[]
	 */
	protected $tiposAdmitidos = array (
			'texto',
			'bit',
			'textarea',
			'combo',
			'dbCombo',
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
	 * @name buscar
	 * @var boolean
	 */
	protected $buscar = true;

	/**
	 * lo mismo que tipo pero solo para el formulario de busqueda
	 *
	 * @name tipoBuscar
	 * @var string
	 */
	protected $tipoBuscar = '';

	/**
	 * Operador que usa en el where.
	 * Ej. = , LIKE
	 *
	 * @name buscarOperador
	 * @var string
	 */
	protected $buscarOperador = '';

	/**
	 * Si esta seteado usa ese campo en el where para buscar
	 *
	 * @name buscarUsarCampo
	 * @var string
	 */
	protected $buscarUsarCampo = '';

	/**
	 * Funcion del usuario para poner un HTML especial en el lugar donde iria el form item del formulario de busqueda.
	 * La funcion no recibe ningun parametro.
	 *
	 * @name customFuncionBuscar
	 * @var string
	 */
	protected $customFuncionBuscar = '';

	/**
	 * si esta seteado usa este titulo en el formulario de busqueda
	 *
	 * @name tituloBuscar
	 * @var string
	 */
	protected $tituloBuscar = '';

	/**
	 * Select personal incluir en la consulta para el campo particular
	 *
	 * @var string
	 */
	protected $selectPersonal = '';

	/**
	 * Va a retornar el valor (la informacion) del campo.
	 *
	 * @return String
	 */
	public function __toString()
	{
		return valorCampo ();
	}

	/**
	 * devuelve un objeto campo basado en el array pasado.
	 *
	 * @param array $array
	 * @return class_campo
	 */
	public static function toObject($array)
	{
		$array = new class_campo ($array);

		return $array;
	}

	/**
	 * Asigna los valores del array a cada uno de los parametros de la clase
	 *
	 * @param array $array
	 */
	public function __construct($array = array())
	{
		// $sitio = new class_sitio ();
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
				$this->isBuscar ($array['buscar']);
			}
			if (array_key_exists ('noMostrar', $array))
			{
				$this->isNoMostrar ($array['noMostrar']);
			}
			if (array_key_exists ('noEditar', $array))
			{
				$this->isNoEditar ($array['noEditar']);
			}
			if (array_key_exists ('noListar', $array))
			{
				$this->isNoListar ($array['noListar']);
			}
			if (array_key_exists ('noNuevo', $array))
			{
				$this->isNoNuevo ($array['noNuevo']);
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
			if (array_key_exists ('maxMostrar', $array))
			{
				$this->setMaxMostrar ($array['maxMostrar']);
			}
			// XXX esto existe para ofrecer compatibilidad con verciones anteriores
			if (array_key_exists ('tmostrar', $array))
			{
				$this->setMaxMostrar ($array['tmostrar']);
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
	 * @return string
	 */
	public function getCampo()
	{
		return $this->campo;
	}

	/**
	 * Retorna el valor del Tipo
	 *
	 * @return string
	 */
	public function getTipo()
	{
		return $this->tipo;
	}

	/**
	 * Retorna el valor de Exportar.
	 *
	 * @return boolean
	 */
	public function isExportar()
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
	 * @return string
	 */
	public function getTitulo()
	{
		return $this->titulo;
	}

	/**
	 * Retorna el valor de centrarColumna
	 *
	 * @return boolean
	 */
	public function isCentrarColumna()
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
	 * @return string
	 */
	public function getCustomEvalListado()
	{
		return $this->customEvalListado;
	}

	/**
	 * Retorna el valor de getParametroUsr
	 *
	 * @return string
	 */
	public function getParametroUsr()
	{
		return $this->parametroUsr;
	}

	/**
	 * Retorna el valor de cantidadDecimales
	 *
	 * @return number
	 */
	public function getCantidadDecimales()
	{
		return $this->cantidadDecimales;
	}

	/**
	 * Retorna el valor de buscar
	 *
	 * @return boolean
	 */
	public function isBuscar()
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
	 * @return boolean
	 */
	public function isNoMostrar()
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
	 * @return boolean
	 */
	public function isNoEditar()
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
	 * @return boolean
	 */
	public function isNoListar()
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
	 * @return boolean
	 */
	public function isNoNuevo()
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
	 * @return string
	 */
	public function getTituloListado()
	{
		return $this->tituloListado;
	}

	/**
	 * Retorna el valor de colorearValores
	 *
	 * @return array
	 */
	public function getColorearValores()
	{
		return $this->colorearValores;
	}

	/**
	 * Retorna el valor de separador
	 *
	 * @return string
	 */
	public function getSeparador()
	{
		return $this->separador;
	}

	/**
	 * Retorna el valor de maxLen
	 *
	 * @return number
	 */
	public function getMaxLen()
	{
		return $this->maxLen;
	}

	/**
	 * Retorna el valor de noOrdenar
	 *
	 * @return boolean
	 */
	public function isNoOrdenar()
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
	 * @return string
	 */
	public function getCampoValor()
	{
		return $this->campoValor;
	}

	/**
	 * Retorna el valor de campoTexto
	 *
	 * @return string
	 */
	public function getCampoTexto()
	{
		return $this->campoTexto;
	}

	/**
	 * Retorna el valor de joinTable
	 *
	 * @return string
	 */
	public function getJoinTable()
	{
		return $this->joinTable;
	}

	/**
	 * Retorna el valor de joinCondition
	 *
	 * @return string
	 */
	public function getJoinCondition()
	{
		return $this->joinCondition;
	}

	/**
	 * Retorna el valor de incluirOpcionVacia
	 *
	 * @return boolean
	 */
	public function isIncluirOpcionVacia()
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
	 * @return boolean
	 */
	public function isMostrarValor()
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
	 * @return boolean
	 */
	public function isTextoMayuscula()
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
	 * @return string
	 */
	public function getValorPredefinido()
	{
		return $this->valorPredefinido;
	}

	/**
	 * Retorna el valor de incluirCampo
	 *
	 * @return string
	 */
	public function getIncluirCampo()
	{
		return $this->incluirCampo;
	}

	/**
	 * Retorna el valor de customPrintListado
	 *
	 * @return string
	 */
	public function getCustomPrintListado()
	{
		return $this->customPrintListado;
	}

	/**
	 * Retorna el valor de campoOrder
	 *
	 * @return string
	 */
	public function getCampoOrder()
	{
		return $this->campoOrder;
	}

	/**
	 * Retorna el valor de tituloBuscar
	 *
	 * @return string
	 */
	public function getTituloBuscar()
	{
		return $this->tituloBuscar;
	}

	/**
	 * Retorna el valor de requerido
	 *
	 * @return boolean
	 */
	public function isRequerido()
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
	 * @return string
	 */
	public function getFormItem()
	{
		return $this->formItem;
	}

	/**
	 * Retorna el valor de colorearConEtiqueta
	 *
	 * @return boolean
	 */
	public function isColorearConEtiqueta()
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
	 * @return string
	 */
	public function getCustomJoin()
	{
		return $this->customJoin;
	}

	/**
	 * Retorna el valor de omitirJoin
	 *
	 * @return boolean
	 */
	public function isOmitirJoin()
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
	 * @return string
	 */
	public function getUploadFunction()
	{
		return $this->uploadFunction;
	}

	/**
	 * Retorna el valor de borrarSiUploadFalla
	 *
	 * @return boolean
	 */
	public function isBorrarSiUploadFalla()
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
	 * @return string
	 */
	public function getBuscarOperador()
	{
		return $this->buscarOperador;
	}

	/**
	 * Retorna el valor de buscarUsarCampo
	 *
	 * @return string
	 */
	public function getBuscarUsarCampo()
	{
		return $this->buscarUsarCampo;
	}

	/**
	 * Retorna el valor de customFuncionBuscar
	 *
	 * @return string
	 */
	public function getCustomFuncionBuscar()
	{
		return $this->customFuncionBuscar;
	}

	/**
	 * Retorna el valor de adicionalInput
	 *
	 * @return string
	 */
	public function getAdicionalInput()
	{
		return $this->adicionalInput;
	}

	/**
	 * Retorna el valor de anchoColumna
	 *
	 * @return string
	 */
	public function getAnchoColumna()
	{
		return $this->anchoColumna;
	}

	/**
	 * Retorna el valor de noMostrarEditar
	 *
	 * @return string
	 */
	public function getNoMostrarEditar()
	{
		return $this->noMostrarEditar;
	}

	/**
	 * Retorna el valor de
	 *
	 * @return string
	 */
	public function getCustomFuncionListado()
	{
		return $this->customFuncionListado;
	}

	/**
	 * Retorna el valor de customFuncionValor
	 *
	 * @return string
	 */
	public function getCustomFuncionValor()
	{
		return $this->customFuncionValor;
	}

	/**
	 * Retorna el valor de tipoBuscar.
	 *
	 * @return string
	 */
	public function getTipoBuscar()
	{
		return $this->tipoBuscar;
	}

	/**
	 * Retorna el valor de selectPersonal.
	 *
	 * @return string
	 */
	public function getSelectPersonal()
	{
		return $this->selectPersonal;
	}

	/**
	 * Retorna el valor de maxMostrar.
	 *
	 * @return number
	 */
	public function getMaxMostrar()
	{
		return $this->maxMostrar;
	}

	public function isNoLimpiar()
	{
		return false;
	}

	/**
	 *
	 * @return string
	 */
	public function getTituloMouseOver()
	{
		return $this->tituloMouseOver;
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
	 * @param string $campo
	 */
	public function setCampo($campo)
	{
		$this->campo = $campo;
	}

	/**
	 * Comprueba y setea el valor de tipo
	 *
	 * @param string $tipo
	 * @throws Exception En caso de no pertenecer a alguno de los tipos aceptados.
	 * @return string|true Va a retornar true si todo salio Ok. En caso contrario retorna error.
	 */
	public function setTipo($tipo)
	{
		if (in_array ($tipo, $this->tiposAdmitidos))
		{
			$this->tipo = $tipo;

			return true;
		}
		else
		{
			throw new Exception ('Tipo de campo no admitido: ' . $tipo . '.');
		}
	}

	/**
	 * Comprueba y setea el valor de exportar
	 *
	 * @param boolean $exportar
	 */
	public function setExportar($exportar)
	{
		$this->exportar = $exportar;
	}

	/**
	 * Comprueba y setea el valor de titulo
	 *
	 * @param string $titulo
	 */
	public function setTitulo($titulo)
	{
		$this->titulo = $titulo;
	}

	/**
	 * Comprueba y setea el valor de centrarColumna
	 *
	 * @param boolean $centrarColumna
	 */
	public function setCentrarColumna($centrarColumna)
	{
		$this->centrarColumna = $centrarColumna;
	}

	/**
	 * Comprueba y setea el valor de customEvalListado
	 *
	 * @param string $customEvalListado
	 */
	public function setCustomEvalListado($customEvalListado)
	{
		$this->customEvalListado = $customEvalListado;
	}

	/**
	 * Comprueba y setea el valor de parametroUsr
	 *
	 * @param string $parametroUsr
	 */
	public function setParametroUsr($parametroUsr)
	{
		$this->parametroUsr = $parametroUsr;
	}

	/**
	 * Comprueba y setea el valor de cantidadDecimales
	 *
	 * @param number $cantidadDecimales
	 */
	public function setCantidadDecimales($cantidadDecimales)
	{
		$this->cantidadDecimales = $cantidadDecimales;
	}

	/**
	 * Comprueba y setea el valor de buscar
	 *
	 * @param boolean $buscar
	 */
	public function setBuscar($buscar)
	{
		$this->buscar = $buscar;
	}

	/**
	 * Comprueba y setea el valor de noMostrar
	 *
	 * @param boolean $noMostrar
	 */
	public function setNoMostrar($noMostrar)
	{
		$this->noMostrar = $noMostrar;
	}

	/**
	 * Comprueba y setea el valor de noEditar
	 *
	 * @param boolean $noEditar
	 */
	public function setNoEditar($noEditar)
	{
		$this->noEditar = $noEditar;
	}

	/**
	 * Comprueba y setea el valor de noListar
	 *
	 * @param boolean $noListar
	 */
	public function setNoListar($noListar)
	{
		$this->noListar = $noListar;
	}

	/**
	 * Comprueba y setea el valor de noNuevo
	 *
	 * @param boolean $noNuevo
	 */
	public function setNoNuevo($noNuevo)
	{
		$this->noNuevo = $noNuevo;
	}

	/**
	 * Comprueba y setea el valor de tituloListado
	 *
	 * @param string $tituloListado
	 */
	public function setTituloListado($tituloListado)
	{
		$this->tituloListado = $tituloListado;
	}

	/**
	 * Comprueba y setea el valor de colorearValores
	 *
	 * @param array $colorearValores
	 */
	public function setColorearValores($colorearValores)
	{
		$this->colorearValores = $colorearValores;
	}

	/**
	 * Comprueba y setea el valor de separador
	 *
	 * @param string $separador
	 */
	public function setSeparador($separador)
	{
		$this->separador = $separador;
	}

	/**
	 * Comprueba y setea el valor de maxLen
	 *
	 * @param number $maxLen
	 */
	public function setMaxLen($maxLen)
	{
		$this->maxLen = $maxLen;
	}

	/**
	 * Comprueba y setea el valor de noOrdenar
	 *
	 * @param boolean $noOrdenar
	 */
	public function setNoOrdenar($noOrdenar)
	{
		$this->noOrdenar = $noOrdenar;
	}

	/**
	 * Comprueba y setea el valor de campoValor
	 *
	 * @param string $campoValor
	 */
	public function setCampoValor($campoValor)
	{
		$this->campoValor = $campoValor;
	}

	/**
	 * Comprueba y setea el valor de campoTexto
	 *
	 * @param string $campoTexto
	 */
	public function setCampoTexto($campoTexto)
	{
		$this->campoTexto = $campoTexto;
	}

	/**
	 * Comprueba y setea el valor de joinTable
	 *
	 * @param string $joinTable
	 */
	public function setJoinTable($joinTable)
	{
		$this->joinTable = $joinTable;
	}

	/**
	 * Comprueba y setea el valor de omitirJoin.
	 * En cualquier caso que el valor paseado no sea 1, true o v se seteara como FALSE
	 *
	 * @param boolean $omitirJoin
	 */
	public function setOmitirJoin($omitirJoin)
	{
		if ($omitirJoin == 1 or $omitirJoin == true or strtolower ($omitirJoin) == 'v')
		{
			$this->omitirJoin = TRUE;
		}
		else
		{
			FALSE;
		}
	}

	/**
	 * Comprueba y setea el valor de joinCondition
	 *
	 * @param string $joinCondition
	 */
	public function setJoinCondition($joinCondition)
	{
		$this->joinCondition = $joinCondition;
	}

	/**
	 * Comprueba y setea el valor de incluirOpcionVacia
	 *
	 * @param boolean $incluirOpcionVacia
	 */
	public function setIncluirOpcionVacia($incluirOpcionVacia)
	{
		$this->incluirOpcionVacia = $incluirOpcionVacia;
	}

	/**
	 * Comprueba y setea el valor de mostrarValor
	 *
	 * @param boolean $mostrarValor
	 */
	public function setMostrarValor($mostrarValor)
	{
		$this->mostrarValor = $mostrarValor;
	}

	/**
	 * Comprueba y setea el valor de textoMayuscula
	 *
	 * @param boolean $textoMayuscula
	 */
	public function setTextoMayuscula($textoMayuscula)
	{
		$this->textoMayuscula = $textoMayuscula;
	}

	/**
	 * Comprueba y setea el valor de valorPredefinido
	 *
	 * @param string $valorPredefinido
	 */
	public function setValorPredefinido($valorPredefinido)
	{
		$this->valorPredefinido = $valorPredefinido;
	}

	/**
	 * Comprueba y setea el valor de incluirCampo
	 *
	 * @param string $incluirCampo
	 */
	public function setIncluirCampo($incluirCampo)
	{
		$this->incluirCampo = $incluirCampo;
	}

	/**
	 * Comprueba y setea el valor de customPrintListado
	 *
	 * @param string $customPrintListado
	 */
	public function setCustomPrintListado($customPrintListado)
	{
		$this->customPrintListado = $customPrintListado;
	}

	/**
	 * Comprueba y setea el valor de campoOrder
	 *
	 * @param string $campoOrder
	 */
	public function setCampoOrder($campoOrder)
	{
		$this->campoOrder = $campoOrder;
	}

	/**
	 * Comprueba y setea el valor de tituloBuscar
	 *
	 * @param string $tituloBuscar
	 */
	public function setTituloBuscar($tituloBuscar)
	{
		$this->tituloBuscar = $tituloBuscar;
	}

	/**
	 * Comprueba y setea el valor de requerido
	 *
	 * @param boolean $requerido
	 */
	public function setRequerido($requerido)
	{
		$this->requerido = $requerido;
	}

	/**
	 * Comprueba y setea el valor de formItem
	 *
	 * @param string $formItem
	 */
	public function setFormItem($formItem)
	{
		$this->formItem = $formItem;
	}

	/**
	 * Comprueba y setea el valor de colorearConEtiqueta
	 *
	 * @param boolean $colorearConEtiqueta
	 */
	public function setColorearConEtiqueta($colorearConEtiqueta)
	{
		$this->colorearConEtiqueta = $colorearConEtiqueta;
	}

	/**
	 * Comprueba y setea el valor de customJoin
	 *
	 * @param string $customJoin
	 */
	public function setCustomJoin($customJoin)
	{
		$this->customJoin = $customJoin;
	}

	/**
	 * Comprueba y setea el valor de uploadFunction
	 *
	 * @param string $uploadFunction
	 */
	public function setUploadFunction($uploadFunction)
	{
		$this->uploadFunction = $uploadFunction;
	}

	/**
	 * Comprueba y setea el valor de borrarSiUploadFalla
	 *
	 * @param boolean $borrarSiUploadFalla
	 */
	public function setBorrarSiUploadFalla($borrarSiUploadFalla)
	{
		$this->borrarSiUploadFalla = $borrarSiUploadFalla;
	}

	/**
	 * Comprueba y setea el valor de buscarOperador
	 *
	 * @param string $buscarOperador
	 */
	public function setBuscarOperador($buscarOperador)
	{
		$this->buscarOperador = $buscarOperador;
	}

	/**
	 * Comprueba y setea el valor de buscarUsarCampo
	 *
	 * @param string $buscarUsarCampo
	 */
	public function setBuscarUsarCampo($buscarUsarCampo)
	{
		$this->buscarUsarCampo = $buscarUsarCampo;
	}

	/**
	 * Comprueba y setea el valor de customFuncionBuscar
	 *
	 * @param string $customFuncionBuscar
	 */
	public function setCustomFuncionBuscar($customFuncionBuscar)
	{
		$this->customFuncionBuscar = $customFuncionBuscar;
	}

	/**
	 * Comprueba y setea el valor de adicionalInput
	 *
	 * @param string $adicionalInput
	 */
	public function setAdicionalInput($adicionalInput)
	{
		$this->adicionalInput = $adicionalInput;
	}

	/**
	 * Comprueba y setea el valor de anchoColumna
	 *
	 * @param string $anchoColumna
	 */
	public function setAnchoColumna($anchoColumna)
	{
		$this->anchoColumna = $anchoColumna;
	}

	/**
	 * Comprueba y setea el valor de noMostrarEditar
	 *
	 * @param string $noMostrarEditar
	 */
	public function setNoMostrarEditar($noMostrarEditar)
	{
		$this->noMostrarEditar = $noMostrarEditar;
	}

	/**
	 * Comprueba y setea el valor de customFuncionListado
	 *
	 * @param string $customFuncionListado
	 */
	public function setCustomFuncionListado($customFuncionListado)
	{
		$this->customFuncionListado = $customFuncionListado;
	}

	/**
	 * Comprueba y setea el valor de customFuncionValor
	 *
	 * @param string $customFuncionValor
	 */
	public function setCustomFuncionValor($customFuncionValor)
	{
		$this->customFuncionValor = $customFuncionValor;
	}

	/**
	 * Comprueba y setea el valor de tipoBuscar
	 *
	 * @param string $tipoBuscar
	 */
	public function setTipoBuscar($tipoBuscar)
	{
		$this->tipoBuscar = $tipoBuscar;
	}

	/**
	 *
	 * Comprueba y setea el valor de selectPersonal
	 *
	 * @param string $selectPersonal
	 */
	public function setSelectPersonal($selectPersonal)
	{
		$this->selectPersonal = $selectPersonal;
	}

	/**
	 *
	 * Comprueba y setea el valor de maxMostrar
	 *
	 * @param number $maxMostrar
	 */
	public function setMaxMostrar($maxMostrar)
	{
		$this->maxMostrar = $maxMostrar;
	}

	/**
	 *
	 * @param string $tituloMouseOver
	 */
	public function setTituloMouseOver($tituloMouseOver)
	{
		$this->tituloMouseOver = $tituloMouseOver;
	}

	/*
	 * OTRAS FUNCIONES
	 */

	/**
	 *
	 * @param string $tituloOver
	 */
	public function getTituloOver()
	{
		if ($this->tituloMouseOver = !"")
		{
			return ' title="' . $this->tituloMouseOver . '" ';
		}
	}

	/**
	 * Comprueba que esxista cun campo en particular y que sea distinto de nulo
	 *
	 * @param String $dato
	 *        	nombre del atributo a comprobar.
	 * @return boolean
	 */
	public function existeDato($dato)
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
	 * @return string
	 */
	public function obtenerTitulo($paraBuscar = false)
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
	 * @param object $db
	 *        	Objeto de coneccion a la base.
	 * @param String $busqueda
	 *        	variable donde se registran los parametros de busqueda. es pasada por referencia con lo que se puede utilizar incluso fuera de la funcion.
	 * @return string
	 */
	public function campoFormBuscar($db, &$busqueda)
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
}
?>