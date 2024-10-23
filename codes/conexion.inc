<?php
    //parametros de conexion (opcionales)
    $servidor = "127.0.0.1";
    $basedatos = "ccsscosm";
    $usuario = "root";
    $clave = ""; // contraseña vacia si no hay contraseña

    // suprime advertencias de error
    // error_reporting(0);

    // proceso para conectar con el servidor
    if (!$conex = mysqli_connect($servidor, $usuario, $clave, $basedatos)) {
        echo "<h3>><font color='red'>No se puede conectar al servidor de basedatos</font></h3>";
        echo "<strong>Número... </strong>". mysqli_connect_errno(). "<br>";
        echo "<strong>Descripción... </strong>". mysqli_connect_error(). "<br>";
        exit;
    }

    mysqli_set_charset($conex, "utf8");
    
?>
