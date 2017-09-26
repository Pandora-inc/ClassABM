<?php
 /**
  * Class para validar datos enviados por un formulario
  * 
    Ejemplo de uso:
   
  	$validador = new validar();
		$validador->validarCampoForm("usuario", "strClave", "Verifique el campo Usuario. Debe ser solo letras y números (se permite usar ._)", "Usuario", 2, 20);
		$validador->validarCampoForm("pass", "", "", "Contraseña", 4, 20);
		$validador->comparar("pass", "pass2", "Las contraseñas no coinciden");
		$validador->validarCampoForm("email", "email");
		$validador->validarCampoForm("nombre", "", "", "", 1, 50);
		$validador->validarCampoForm("apellido", "", "", "", 1, 50);
		$validador->rango("edad", 18, 100);
		if($_REQUEST[aceptaTerminos]!="1") $validador->agregarError("Debe aceptar los terminos y condiciones");
 *
 * @author Andres Carizza
 * @version 1.9
 */
class validar{
	/**
	 * Descripcion de los errores de validación para imprimir en pantalla
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
	
	public $txtElCampoNoPuedeEstarVacio = "El campo %s no puede estar vacío.";
	public $txtVerifiqueElCampo = "Revise el campo %s.";
	public $txtElCampoDebeContenerSoloLetras = "El campo %s debe contener solo letras.";
	public $txtElCampoDebeSerAlfanumerico = "El campo %s debe ser alfanumérico.";
	public $txtElCampoDebeSerNumerico = "El campo %s debe ser numérico.";
	public $txtElCampoDebeTenerAlMenos = "El campo %s debe tener al menos %u caracteres.";
	public $txtElCampoDebeTenerEntreYEntre = "El campo %s debe tener entre %u y %u caracteres.";
	public $txtElCampoDebeSerEntreYEntre = "El campo %s debe ser entre %u y %u.";
	
	/**
	 * Retorna true si ocurrieron errores (si algun campo no paso la validacion)
	 *
	 * @return boolean
	 */
	public function ocurrieronErrores(){
		if($this->errores != ""){
			return true;
		}else{
			return false;
		}
	}	
	
	public function agregarError($msgError, $campo="", $textoCampo=""){
		if ($this->unErrorPorVez and $this->errores!="") return;
		
		if ($msgError == "") {
			$msgError = sprintf($this->txtVerifiqueElCampo, $this->formatearNombreCampo($campo, $textoCampo));
		}
		
		$this->errores .= $this->separador . $msgError . ($this->textoErroresEnHTML ? "<br/>" : "\n");
	}
		
	/**
	 * Valida un campo de un formulario y si hay error lo agrega al $errores
	 *
	 * @param String $campo Nombre del campo a verificar (debe encontrarse en $_REQUEST)
	 * @param String $tipoDeValidacion Tipo de verificacion a realizar: email, alfa, alfanumerico, strClave, email, url, numero o mandar vacio para verificar que no sea un string vacio
	 * @param String $msgError Mensaje de error que debe mostrar para ese campo
	 * @param String $textoCampo Nombre textual del campo (ej: Tu email)
	 * @param Integer $minimoLargo Minimo de largo para strings
	 * @param Integer $maximoLargo Maximo de largo para strings
	 */
	public function validarCampoForm($campo, $tipoDeValidacion="", $msgError="", $textoCampo="", $minimoLargo=0, $maximoLargo=0){
		switch ($tipoDeValidacion) {
			case "email":
				if(!$this->esEmail($_REQUEST[$campo])) $this->agregarError($msgError, $campo, $textoCampo);
				break;
								
			case "strClave":
				if(!$this->esStrClave($_REQUEST[$campo])) $this->agregarError($msgError, $campo, $textoCampo);
				$this->verificarLargo($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				break;
				
			case "alfa":
				if(!$this->esAlfa($_REQUEST[$campo])) $this->agregarError($msgError=="" ? sprintf($this->txtElCampoDebeContenerSoloLetras, $this->formatearNombreCampo($campo, $textoCampo)) : $msgError, $campo, $textoCampo); break;
				$this->verificarLargo($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				break;
				
			case "alfanumerico":
				if(!$this->esAlfaNumerico($_REQUEST[$campo])) $this->agregarError($msgError=="" ? sprintf($this->txtElCampoDebeSerAlfanumerico, $this->formatearNombreCampo($campo, $textoCampo)) : $msgError, $campo, $textoCampo); break;
				$this->verificarLargo($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				break;
				
			case "url":
				if(!$this->esURL($_REQUEST[$campo])) $this->agregarError($msgError, $campo, $textoCampo);
				break;
				
			case "numero":
				if(!is_numeric($_REQUEST[$campo])) $this->agregarError($msgError=="" ? sprintf($this->txtElCampoDebeSerNumerico, $this->formatearNombreCampo($campo, $textoCampo)) : $msgError, $campo, $textoCampo); break;
				$this->verificarLargo($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				break;
		
			default:
				if ($minimoLargo > 0 or $maximoLargo > 0) {
					$this->verificarLargo($_REQUEST[$campo], $minimoLargo, $maximoLargo, $campo, $textoCampo);
				}else{
					if($msgError=="") $msgError = sprintf($this->txtElCampoNoPuedeEstarVacio, $this->formatearNombreCampo($campo, $textoCampo));
					if(trim($_REQUEST[$campo])=="") $this->agregarError($msgError, $campo, $textoCampo);
				}
				break;
		}
	}
	
	private function verificarLargo($str, $minimoLargo, $maximoLargo, $campo, $textoCampo){
		$str = trim($str);
		if ($minimoLargo > 0 or $maximoLargo > 0) {
			if (strlen($str)==0) {
					$this->agregarError( sprintf($this->txtElCampoNoPuedeEstarVacio, $this->formatearNombreCampo($campo, $textoCampo)) , $campo, $textoCampo);
			}else{
				if($minimoLargo > 0 and $maximoLargo > 0){
					if(strlen($str) < $minimoLargo or strlen($str) > $maximoLargo) $this->agregarError( sprintf($this->txtElCampoDebeTenerEntreYEntre, $this->formatearNombreCampo($campo, $textoCampo), $minimoLargo, $maximoLargo) , $campo, $textoCampo);
				}else{
					if ($minimoLargo > 0) {
						if(strlen($str) < $minimoLargo){
							if (strlen($str)==0) {
								$this->agregarError( sprintf($this->txtElCampoNoPuedeEstarVacio, $this->formatearNombreCampo($campo, $textoCampo)) , $campo, $textoCampo);
							}else{
								$this->agregarError( sprintf($this->txtElCampoDebeTenerAlMenos, $this->formatearNombreCampo($campo, $textoCampo)) , $campo, $textoCampo);
							}
							
						}
					}else{
						if(strlen($str) > $maximoLargo) $this->agregarError( "El campo ".$this->formatearNombreCampo($campo, $textoCampo)." debe tener como máximo $maximoLargo ".(strlen($str)>1?"caracteres":"caracter") , $campo, $textoCampo);
					}
				}
			}
		}
	}
	
	private function formatearNombreCampo($str, $textoCampo){
		if ($textoCampo=="") {
			return ucfirst(str_replace("_", " ", $str));
		}else{
			return $textoCampo;
		}
		
	}
	
	/**
     * Comparar si dos campos son iguales
     *
     * @param string  $value1 First value to compare
     * @param string  $value2 Second value to compare
     * @param string  $msgError Mensaje de error que debe mostrar para ese campo
     * @param boolean $caseSensitive [Optional] TRUE if compare is case sensitive
     */
    public function comparar($value1, $value2, $msgError, $caseSensitive = false)
    {
        if ($caseSensitive) {
            if($_REQUEST[$value1] !=  $_REQUEST[$value2]) $this->agregarError($msgError, $value1) ;
        } else {
            if (strtoupper($_REQUEST[$value1]) !=  strtoupper($_REQUEST[$value2])) {
                $this->agregarError($msgError, $value1);
            }
        }
    }
    
    /**
     * Verifica que un campo numerico este entre un determinado rango
     *
     * @param String $campo Nombre del campo a verificar (debe encontrarse en $_REQUEST)
     * @param Integer $minimo
	 * @param Integer $maximo
	 * @param String $textoCampo Nombre textual del campo (ej: Tu email)
	 * @param String $msgError Mensaje de error que debe mostrar para ese campo
     */
    public function rango($campo, $minimo, $maximo, $textoCampo="", $msgError="")
    {
        if($this->esNumero($_REQUEST[$campo])){
        	if ($_REQUEST[$campo] < $minimo or $_REQUEST[$campo] > $maximo) {
        		//$this->agregarError($msgError, $campo, $textoCampo);
        		$this->agregarError( sprintf($this->txtElCampoDebeSerEntreYEntre, $this->formatearNombreCampo($campo, $textoCampo), $minimo, $maximo) , $campo, $textoCampo);
        	}
        }else{
        	$this->agregarError($msgError, $campo, $textoCampo);
        }
    }

    /**
     * Determines if a string is alpha only
     *
     * @param string $value The value to check for alpha (letters) only
     * @param string $allow Any additional allowable characters
     * @return boolean
     */
    public static function esAlfa($value, $allow = '')
    {
        if (preg_match('/^[a-zA-Z' . $allow . ']+$/', $value))
        {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determines if a string is alpha-numeric
     *
     * @param string $value The value to check
     * @return boolean TRUE if there are letters and numbers, FALSE if other
     */
    public static function esAlfaNumerico($value)
    {
        if (preg_match("/^[A-Za-z0-9 ]+$/", $value))
        {
            return true;
        } else {
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
	public static function esStrClave($str){
		$caracteres = Array("0","1","2","3","4","5","6","7","8","9",".","-","_","q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M");
	
		for($i=0; $i<strlen($str); $i++){
			if(!in_array(substr($str, $i, 1), $caracteres)){
				return false;
			}
		}
		return true;
	}
	
	
	/**
	 * Verifica si un email tiene formato válido y opcionalmente verifica los registros MX del dominio tambien
	 * 
	 * @param String $email
	 * @param Boolean $test_mx Verificar los registros MX del dominio
	 */
	public static function esEmail($email, $test_mx = false){
		if(eregi("^([_a-z0-9+-]+)(\.[_a-z0-9+-]+)*@([a-z0-9-]+)(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
		{
			if($test_mx)
			{
				list( , $domain) = split("@", $email);
				return getmxrr($domain, $mxrecords);
			}
			else
				return true;
		}
		else
			return false;
	}
	
	/**
     * Checks for a valid internet URL
     *
     * @param string $value The value to check
     * @return boolean TRUE if the value is a valid URL, FALSE if not
     */
    public static function esURL($value)
    {
        if (preg_match("/^http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?$/i", $value))
        {
            return true;
        } else {
            return false;
        }
    }


}
?>