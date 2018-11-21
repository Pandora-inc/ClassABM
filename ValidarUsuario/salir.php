<?php
/**
 * Destruye la sesion actual
 */
session_start ();
session_destroy ();

header ("location:login.php");
?>
