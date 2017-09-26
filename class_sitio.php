<?php 
/**
 * Version: 1.0.5
 * Autor: Andres Carizza
 */
class class_sitio{
	/**titulo o nombre del sitio*/
	public $nombre; 	

	/**url del sitio*/
	public $url;
	
	/**url del sitio corta para otros propositos. Ej: MiSitio.com */
	public $urlCorta;
	
	/** El path completo del sitio */
	public $pathBase;
	
	/**email de donde salen los envios para los usuarios*/
	public $emailEnvios;
	
	/**from del email de donde salen los envios para los usuarios*/
	public $emailEnviosFrom;
	
	/**email del webmaster*/
	public $emailWebmaster;
	
	/**codigo de idioma por defecto del sitio*/
	public $idiomaPorDefecto = "es";
	
	/**idioma actualmente seleccionado*/
	public $idioma = "es";
	
	/**extension que agrega a las url SEO amigables*/
	public $extension;
	
	public $charset;
	
	public $dbHost;
	public $dbPort;
	public $dbDSN;
	public $dbUser;
	public $dbPass;
	public $dbDB;
	public $dbMostrarErrores = true;
	
	/** Acá se puede asignar un email para enviar aviso cuando hay errores sql **/
	public $emailAvisoErrorSql;
	
	/** Graba log con todas las consultas realizadas (solo usar en casos puntuales para debugear) **/
	public $grabarArchivoLogQuery = false;
	
	/** Graba log con los errores de BD **/
	public $grabarArchivoLogError = false;
	
	
	/**
	 * Esto es para setear un mensaje que será mostrado en las paginas con la funcion showMsg(). Ejemplo, para notificaciones de acciones realizadas
	 * 
	 * @param string $msg
	 * @param string $class Class para el mensaje (info, tip, error, atencion )
	 * @param string $mostrarUnaSolaVez
	 */
	public function setMsg($msg, $class='info', $mostrarUnaSolaVez=true){
		$_SESSION['_sitio_msg'] = $msg;
		$_SESSION['_sitio_msgClass'] = $class;
		$_SESSION['_sitio_msgMostrarUnaSolaVez'] = $mostrarUnaSolaVez;
	}
	
	/**
	 * Imprime, si es que hay, un mensaje asignado por setMsg()
	 */
	public function showMsg(){
		if ($_SESSION['_sitio_msg'] != '') {
			echo "<div class='".$_SESSION['_sitio_msgClass']."'>".$_SESSION['_sitio_msg']."</div>";
			
			if ($_SESSION['_sitio_msgMostrarUnaSolaVez']) {
				unset($_SESSION['_sitio_msg']);
				unset($_SESSION['_sitio_msgClass']);
				unset($_SESSION['_sitio_msgMostrarUnaSolaVez']);
			}
			
		}		
	}
	
	/**
	 * Esto es para setear una notificación emergente que será mostrado en las paginas con la funcion showNotif(). Ejemplo, para notificaciones de acciones realizadas
	 */
	public function setNotif($titulo, $msg, $segundos=5){
		$_SESSION['_sitio_notTit'] = $titulo;
		$_SESSION['_sitio_notMsg'] = $msg;
		$_SESSION['_sitio_notSeg'] = $segundos;
	}
	
	/**
	 * Imprime, si es que hay, un mensaje asignado por setMsg()
	 */
	public function showNotif(){
		if ($_SESSION['_sitio_notTit'] != '') {
			?>
			<script type="text/javascript">
				$(function(){
					$.gritter.add({
						title: '<?=$_SESSION['_sitio_notTit']?>',
						text: '<?=$_SESSION['_sitio_notMsg']?>',
						time: <?=($_SESSION['_sitio_notSeg']*1000)?>
					});
				});
			</script>
			<?php
			unset($_SESSION['_sitio_notTit']);
			unset($_SESSION['_sitio_notMsg']);
			unset($_SESSION['_sitio_notSeg']);
		}		
	}
	
	/**
	 * Lee de la BD una configuracion del sitio
	 */
	public function getConfig($parametro, $valorPorDefecto=""){
		global $db;
		
		$valor = $db->getValue("config", "valor", $parametro, "parametro");	
		
		if ($valor === false) {
			return $valorPorDefecto;
		}else{
			return $valor;
		}
	}
	
	/**
	 * Formatea y retorna la url agregando el path del sitio y la extension, para usar en los formularios, links del html del sitio
	 *
	 * @param string $url 
	 * @param boolean $agregarExtension agrega la extension de archivo
	 * @param array $arrQS array de variables del query string ej: array("nombre"=>"juan")
	 * @return string
	 */
	public function link($url, $agregarExtension=true, $arrQS=""){
		if (is_array($arrQS)) {
			$qs = "?".http_build_query($arrQS);
		}
		if ($agregarExtension) {
			return $this->pathBase.$url.$this->extension.$qs;
		}else{
			return $this->pathBase.$url.$qs;
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
}
?>