<?php

/**
 * Archivo principal de la clase validar.
 *
 * @name class_validar.php
 * @author iberlot <@> iberlot@usal.edu.ar
 *
 */

/*
 * Querido programador:
 * Cuando escribi este codigo, solo Dios y yo sabiamos como funcionaba.
 * Ahora, Solo Dios lo sabe!!!
 * Asi que, si esta tratando de 'optimizar' esta rutina y fracasa (seguramente),
 * por favor, incremente el siguiente contador como una advertencia para el
 * siguiente colega:
 * totalHorasPerdidasAqui = 10
 */
/**
 * Class para validar datos enviados por un formulario.
 *
 * @name validar
 * @author iberlot <@> iberlot@usal.edu.ar
 * @author Andres Carizza
 * @version 2.0 - Correcciones de codigo optimizacion y comnetado.
 * @version 1.9
 *
 * @todo $validador = new validar();
 *       $validador->validarCampoForm("usuario", "strClave", "Verifique el campo Usuario. Debe ser solo letras y numeros (se permite usar ._)", "Usuario", 2, 20);
 *       $validador->validarCampoForm("pass", "", "", "Contrasena", 4, 20);
 *       $validador->comparar("pass", "pass2", "Las contrasenas no coinciden");
 *       $validador->validarCampoForm("email", "email");
 *       $validador->validarCampoForm("nombre", "", "", "", 1, 50);
 *       $validador->validarCampoForm("apellido", "", "", "", 1, 50);
 *       $validador->rango("edad", 18, 100);
 *       if($_REQUEST[aceptaTerminos]!="1") $validador->agregarError("Debe aceptar los terminos y condiciones");
 *
 */
class validar
{
	/**
	 * Descripcion de los errores de validaciÔøΩn para imprimir en pantalla
	 */
	public $errores;

	/**
	 * Separador al principio de cada error de la lista que imprime si hay errores (por defecto *)
	 */
	public $separador = "* ";

	/**
	 * Usar HTML en el texto de salida de los errores, de lo contrario es texto plano
	 */
	public $textoErroresEnHTML = true;

	/**
	 * Retorna solo el primer error en vez de mostrar una lista con todos los errores
	 */
	public $unErrorPorVez = false;

	/**
	 * Texto para errores de campo obligatorio vasio
	 *
	 * @var string
	 */
	private $txtElCampoNoPuedeEstarVacio = "El campo %s no puede estar vacio.";

	/**
	 * Texto para errores varios
	 *
	 * @var string
	 */
	private $txtVerifiqueElCampo = "Revise el campo %s.";

	/**
	 * Texto para errores de campos de texto que solo pueden contener letras
	 *
	 * @var string
	 */
	private $txtElCampoDebeContenerSoloLetras = "El campo %s debe contener solo letras.";

	/**
	 * Texto para errores de campos de texto que solo pueden ser alfanumericos
	 *
	 * @var string
	 */
	private $txtElCampoDebeSerAlfanumerico = "El campo %s debe ser alfanumerico.";

	/**
	 * Texto para errores de campos de numericos.
	 *
	 * @var string
	 */
	private $txtElCampoDebeSerNumerico = "El campo %s debe ser numerico.";

	/**
	 * Texto para errores de cantidad minima de caracteres.
	 *
	 * @var string
	 */
	private $txtElCampoDebeTenerAlMenos = "El campo %s debe tener al menos %u caracteres.";

	/**
	 * Texto para errores de valores entre.
	 *
	 * @var string
	 */
	private $txtElCampoDebeTenerEntreYEntre = "El campo %s debe tener entre %u y %u caracteres.";

	/**
	 * Texto para errores de valores entre.
	 *
	 * @var string
	 */
	private $txtElCampoDebeSerEntreYEntre = "El campo %s debe ser entre %u y %u.";

	/**
	 * Retorna true si ocurrieron errores (si algun campo no paso la validacion)
	 *
	 * @return boolean
	 */
	public function ocurrieronErrores()
	{
		if ($this->errores != "")
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Agrega un error en $errores para mostrarlo.
	 *
	 * @param string $msgError
	 * @param string $campo
	 * @param string $textoCampo
	 */
	public function agregarError($msgError, $campo = "", $textoCampo = "")
	{
		// FIXME - Hay que revisar el funcionamiento de eesta funcion para asegurarse que es correcta y mejorar los comentarios para que sean mas explicativos.
		if ($this->unErrorPorVez and $this->errores != "")
		{
			return;
		}

		if ($msgError == "")
		{
			$msgError = sprintf ($this->txtVerifiqueElCampo, $this->formatearNombreCampo ($campo, $textoCampo));
		}

		$this->errores .= $this->separador . $msgError . ($this->textoErroresEnHTML ? "<br/>" : "\n");
	}

	/**
	 * Valida un campo de un formulario y si hay error lo agrega al $errores
	 *
	 * @param String $campo
	 *        	Nombre del campo a verificar (debe encontrarse en $_REQUEST)
	 * @param String $tipoDeValidacion
	 *        	Tipo de verificacion a realizar: email, alfa, alfanumerico, strClave, email, url, numero o mandar vacio para verificar que no sea un string vacio
	 * @param String $msgError
	 *        	Mensaje de error que debe mostrar para ese campo
	 * @param String $textoCampo
	 *        	Nombre textual del campo (ej: Tu email)
	 * @param Integer $minimoLargo
	 *        	Minimo de largo para strings
	 * @param Integer $maximoLargo
	 *        	Maximo de largo para strings
	 */
	public function validarCampoForm($campo, $tipoDeValidacion = "", $msgError = "", $textoCampo = "", $minimoLargo = 0, $maximoLargo = 0)
	{
		switch ($tipoDeValidacion)
		{
			case "email" :
				if (!$this->esEmail ($_REQUEST[$campo]))
				{
					$this->agregarError ($msgError, $campo, $textoCampo);
				}
				break;

			case "strClave" :
				if (!$this->esStrClave ($_REQUEST[$campo]))
				{
					$this->agregarError ($msgError, $campo, $textoCampo);
				}
				$this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				break;
			/*
			 * case "alfa" :
			 * if (!$this->esAlfa ($_REQUEST[$campo]))
			 * {
			 * $this->agregarError ($msgError == "" ? sprintf ($this->txtElCampoDebeContenerSoloLetras, $this->formatearNombreCampo ($campo, $textoCampo)) : $msgError, $campo, $textoCampo);
			 * }
			 * break;
			 * $this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
			 * break;
			 *
			 * case "alfanumerico" :
			 * if (!$this->esAlfaNumerico ($_REQUEST[$campo]))
			 * $this->agregarError ($msgError == "" ? sprintf ($this->txtElCampoDebeSerAlfanumerico, $this->formatearNombreCampo ($campo, $textoCampo)) : $msgError, $campo, $textoCampo);
			 * break;
			 * $this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
			 * break;
			 */
			case "alfa" :
				if (!$this->esAlfa ($_REQUEST[$campo]))
				{
					$this->agregarError ($msgError == "" ? sprintf ($this->txtElCampoDebeContenerSoloLetras, $this->formatearNombreCampo ($campo, $textoCampo)) : $msgError, $campo, $textoCampo);
				}
				else
				{
					$this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				}
				break;

			case "alfanumerico" :

				if (!$this->esAlfaNumerico ($_REQUEST[$campo]))
				{

					$this->agregarError ($msgError == "" ? sprintf ($this->txtElCampoDebeSerAlfanumerico, $this->formatearNombreCampo ($campo, $textoCampo)) : $msgError, $campo, $textoCampo);
				}
				else
				{

					$this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				}
				break;

			case "alfanumericoAcentos" :

				if (!$this->esAlfaNumericoAcentos ($_REQUEST[$campo]))
				{

					$this->agregarError ($msgError == "" ? sprintf ($this->txtElCampoDebeSerAlfanumerico, $this->formatearNombreCampo ($campo, $textoCampo)) : $msgError, $campo, $textoCampo);
				}
				else
				{

					$this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				}
				break;

			case "url" :
				if (!$this->esURL ($_REQUEST[$campo]))
				{
					$this->agregarError ($msgError, $campo, $textoCampo);
				}
				break;

			case "numero" :
				if (!is_numeric ($_REQUEST[$campo]))
				{
					$this->agregarError ($msgError == "" ? sprintf ($this->txtElCampoDebeSerNumerico, $this->formatearNombreCampo ($campo, $textoCampo)) : $msgError, $campo, $textoCampo);
				}
				break;
				$this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				break;

			default :
				if ($minimoLargo > 0 or $maximoLargo > 0)
				{
					$this->verificarLargo ($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				}
				else
				{
					if ($msgError == "")
					{
						$msgError = sprintf ($this->txtElCampoNoPuedeEstarVacio, $this->formatearNombreCampo ($campo, $textoCampo));
					}
					if (trim ($_REQUEST[$campo]) == "")
					{
						$this->agregarError ($msgError, $campo, $textoCampo);
					}
				}
				break;
		}
	}

	/**
	 * Comprueba el largo de un string.
	 * Carga un mensaje en $agregarError en caso de que el strin a comprobar sea 0, mayor al maximo o menor al minimo.
	 *
	 * @param string $str
	 *        	- Valor a comparar
	 * @param int $minimoLargo
	 *        	Largo minimo del string, en caso de ser cero no se tiene en cuenta.
	 * @param int $maximoLargo
	 *        	Largo maximo del string, en caso de ser cero no se tiene en cuenta.
	 * @param string $campo
	 *        	Campo.
	 * @param string $textoCampo
	 *        	Nombre del campo
	 */
	private function verificarLargo($str, $minimoLargo, $maximoLargo, $campo, $textoCampo)
	{
		$str = trim ($str);

		if ($minimoLargo > 0 or $maximoLargo > 0)
		{
			if (strlen ($str) == 0)
			{
				$this->agregarError (sprintf ($this->txtElCampoNoPuedeEstarVacio, $this->formatearNombreCampo ($campo, $textoCampo)), $campo, $textoCampo);
			}
			else
			{
				if ($minimoLargo > 0 and $maximoLargo > 0)
				{
					if (strlen ($str) < $minimoLargo or strlen ($str) > $maximoLargo)
					{
						$this->agregarError (sprintf ($this->txtElCampoDebeTenerEntreYEntre, $this->formatearNombreCampo ($campo, $textoCampo), $minimoLargo, $maximoLargo), $campo, $textoCampo);
					}
				}
				else
				{
					if ($minimoLargo > 0)
					{
						if (strlen ($str) < $minimoLargo)
						{
							if (strlen ($str) == 0)
							{
								$this->agregarError (sprintf ($this->txtElCampoNoPuedeEstarVacio, $this->formatearNombreCampo ($campo, $textoCampo)), $campo, $textoCampo);
							}
							else
							{
								$this->agregarError (sprintf ($this->txtElCampoDebeTenerAlMenos, $this->formatearNombreCampo ($campo, $textoCampo)), $campo, $textoCampo);
							}
						}
					}
					else
					{
						if (strlen ($str) > $maximoLargo)
						{
							$this->agregarError ("El campo " . $this->formatearNombreCampo ($campo, $textoCampo) . " debe tener como maximo $maximoLargo " . (strlen ($str) > 1 ? "caracteres" : "caracter"), $campo, $textoCampo);
						}
					}
				}
			}
		}
	}

	/**
	 * Devuelve un string formateado en vase al nombre del campo
	 *
	 * @param string $str
	 * @param string $textoCampo
	 * @return string
	 */
	private function formatearNombreCampo($str, $textoCampo)
	{
		// FIXME - No creo que esta funion este funcionando correctamente. Hay que revisarla.
		// FIXME - Hay que devolver una excepcion en caso de error.
		if ($textoCampo == "")
		{
			return ucfirst (str_replace ("_", " ", $str));
		}
		else
		{
			return $textoCampo;
		}
	}

	/**
	 * Comparar si dos campos son iguales
	 *
	 * @param string $value1
	 *        	First value to compare
	 * @param string $value2
	 *        	Second value to compare
	 * @param string $msgError
	 *        	Mensaje de error que debe mostrar para ese campo
	 * @param boolean $caseSensitive
	 *        	[Optional] TRUE if compare is case sensitive
	 */
	public function comparar($value1, $value2, $msgError, $caseSensitive = false)
	{
		if ($caseSensitive)
		{
			if ($_REQUEST[$value1] != $_REQUEST[$value2])
			{
				$this->agregarError ($msgError, $value1);
			}
		}
		else
		{
			if (strtoupper ($_REQUEST[$value1]) != strtoupper ($_REQUEST[$value2]))
			{
				$this->agregarError ($msgError, $value1);
			}
		}
	}

	/**
	 * Verifica que un campo numerico este entre un determinado rango
	 *
	 * @param String $campo
	 *        	Nombre del campo a verificar (debe encontrarse en $_REQUEST)
	 * @param Integer $minimo
	 * @param Integer $maximo
	 * @param String $textoCampo
	 *        	Nombre textual del campo (ej: Tu email)
	 * @param String $msgError
	 *        	Mensaje de error que debe mostrar para ese campo
	 */
	public function rango($campo, $minimo, $maximo, $textoCampo = "", $msgError = "")
	{
		if ($this->esNumero ($_REQUEST[$campo]))
		{
			if ($_REQUEST[$campo] < $minimo or $_REQUEST[$campo] > $maximo)
			{
				// $this->agregarError($msgError, $campo, $textoCampo);
				$this->agregarError (sprintf ($this->txtElCampoDebeSerEntreYEntre, $this->formatearNombreCampo ($campo, $textoCampo), $minimo, $maximo), $campo, $textoCampo);
			}
		}
		else
		{
			$this->agregarError ($msgError, $campo, $textoCampo);
		}
	}

	/**
	 * Determines if a string is alpha only
	 *
	 * @param string $value
	 *        	The value to check for alpha (letters) only
	 * @param string $allow
	 *        	Any additional allowable characters
	 * @return boolean
	 */
	public static function esAlfa($value, $allow = '')
	{
		if (preg_match ('/^[a-zA-Z' . $allow . ']+$/', $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Determines if a string is alpha-numeric
	 *
	 * @param string $value
	 *        	The value to check
	 * @return boolean TRUE if there are letters and numbers, FALSE if other
	 */
	public static function esAlfaNumerico($value)
	{
		if (preg_match ("/^[A-Za-z0-9 ]+$/", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Determines if a string is alpha-numeric with acentos
	 *
	 * @param string $value
	 *        	The value to check
	 * @return boolean TRUE if there are letters and numbers, FALSE if other
	 */
	public static function esAlfaNumericoAcentos($value)
	{
		if (preg_match ("/^[A-Za-z0-9 ·ÈÌÛ˙Ò¡…Õ”⁄—‹¸]+$/", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retorna true si $str es una cadena de caracteres de letras numeros o caracteres - _ .
	 * Esta pensada para usar para comprobar nombres de usuario que tengan caracteres validos
	 *
	 * @param String $str
	 * @return Boolean
	 */
	public static function esStrClave($str)
	{
		$caracteres = Array (
				"0",
				"1",
				"2",
				"3",
				"4",
				"5",
				"6",
				"7",
				"8",
				"9",
				".",
				"-",
				"_",
				"q",
				"w",
				"e",
				"r",
				"t",
				"y",
				"u",
				"i",
				"o",
				"p",
				"a",
				"s",
				"d",
				"f",
				"g",
				"h",
				"j",
				"k",
				"l",
				"z",
				"x",
				"c",
				"v",
				"b",
				"n",
				"m",
				"Q",
				"W",
				"E",
				"R",
				"T",
				"Y",
				"U",
				"I",
				"O",
				"P",
				"A",
				"S",
				"D",
				"F",
				"G",
				"H",
				"J",
				"K",
				"L",
				"Z",
				"X",
				"C",
				"V",
				"B",
				"N",
				"M"
		);

		for($i = 0; $i < strlen ($str); $i ++)
		{
			if (!in_array (substr ($str, $i, 1), $caracteres))
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Verifica si un email tiene formato valido y opcionalmente verifica los registros MX del dominio tambien
	 *
	 * @version 0.2 - Se corrigio para que utilizara preg_match en vez de eregi.
	 *
	 * @param String $email
	 * @param Boolean $test_mx
	 *        	Verificar los registros MX del dominio
	 */
	public static function esEmail($email, $test_mx = false)
	{
		$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,10})$/i";
		if (preg_match ($regex, $email))
		{
			if ($test_mx)
			{
				list (, $domain) = explode ("@", $email);
				return getmxrr ($domain, $mxrecords);
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Checks for a valid internet URL
	 *
	 * @param string $value
	 *        	The value to check
	 * @return boolean TRUE if the value is a valid URL, FALSE if not
	 */
	public static function esURL($value)
	{
		if (preg_match ("/^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/i", $value))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>