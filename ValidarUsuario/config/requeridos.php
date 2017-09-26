<!--mgallardo 21102015: no se levantan estas librerias y se mudaron las liberarias CSS a /web/html/css/others/-->
<!--<link rel='stylesheet' href='/css/jquery-ui.css'  class='ui-theme'>
<link rel='stylesheet' href='/css/jquery1-1-11.css'>-->

<script language='javascript' src='/js/jquery.validate.js'></script>
<script language='javascript' src='/js/jquery-1-11-4.js'></script>

<!-- estas librerias levantan el calendario para ser usado en mozilla ya que el "input=date" no funciona en firefox -->
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/extras/modernizr-custom.js"></script>
<script src="http://cdn.jsdelivr.net/webshim/1.12.4/polyfiller.js"></script>
<script>
  webshims.setOptions('waitReady', false);
  webshims.setOptions('forms-ext', {types: 'date'});
  webshims.polyfill('forms forms-ext');
</script>


<!--*********************************************-->

<!--mgallardo 11082015
ese script ya esta cargado desde el header.php
<script src="/js/scriptsReque.js" /></script>-->

<!--
	Por incopatibilidad en el vercionado de JQuery los siguientes archivos no tienen este include
	(en caso de haber modificaciones hay que realizar los cambios correspondientes en ellos):
	archModulo.php
	dinaAgrup.php
	dinaModulo.php
	dinaUsrSist.php
	procesa2.php
	-->
<!--mgallardo 11082015
	se bajaron las librerias para no llamarlas fuera del servidor
	http://code.jquery.com/ui/1.11.4/jquery-ui.js-->

<!--mgallardo 11082015
	se bajaron las librerias para no llamarlas fuera del servidor
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />-->



