<?php
	session_start();
	
	/* 
	 * Error log
	 * mixed by Marcexl
	 * version 24112015
	 *
	 */

	$Titulo = "Error";
	include ("variables.php");
	include ("/web/html/inc/header.php");
	include ("menu.php");

	echo '<div id="content">
			<div id="separadorh"></div>
			<h3><?php echo $Titulo;?></h3>
			<div id="separadorh"></div>	
				<div id="cuerpo" align="center">
					<div id="alert">
					<div class="alertHeader">Ha ocurrido un error</div>
						<p>Usted no posee permisos para ingresar.</br>Por favor consulte con el administrador.</p>
						<p><a href="salir.php" class="mxlbutton">Volver</a></p>
				</div>
			</div>
		</div>';

	include("/web/html/inc/footer.php");#incluimos el footer
?>