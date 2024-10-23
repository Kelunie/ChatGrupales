<?php
include_once('codes/conexion.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $mensaje = $_POST['mensaje'];
    $chat_grupo = $_POST['chat_grupo'];

    $query = "INSERT INTO mensajes (nombre_usuario, mensaje, chat_grupo) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("sss", $nombre_usuario, $mensaje, $chat_grupo);
    $stmt->execute();

    $stmt->close();
    $conex->close();
    
    // Redirigir de vuelta al chat después de enviar el mensaje
    header("Location: index.php");
    exit();
}
?>