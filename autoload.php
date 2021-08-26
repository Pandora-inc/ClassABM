<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

// Definimos un autoload.
spl_autoload_register(function ($className) {
    // Cambiamos las \ a /
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

    // Le agregamos la extensión de php, y la carpeta de base. En este caso no tendremos carpeta base.
    $filepath = __DIR__ . '/' . $className . ".php";

    // Verificamos si existe, y en caso positivo, incluimos la clase.
    if (file_exists($filepath)) {
        require_once $filepath;
    } else {
        throw new Exception("Imposible cargar $filepath.");
    }
});