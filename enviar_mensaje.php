<?php
include_once('codes/conexion.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $mensaje = $_POST['mensaje'];
    $chat_grupo = $_POST['chat_grupo'];

    $query = "INSERT INTO mensajes (nombre_usuario, mensaje, chat_grupo) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($query);

    if ($stmt) {
        $stmt->bind_param("sss", $nombre_usuario, $mensaje, $chat_grupo);
        $stmt->execute();

        // Respuesta JSON de éxito
        echo json_encode([
            'success' => true,
            'nombre_usuario' => $nombre_usuario,
            'mensaje' => $mensaje
        ]);
    } else {
        // Respuesta JSON de error
        echo json_encode([
            'success' => false,
            'message' => 'Error en la consulta SQL.'
        ]);
    }

    $stmt->close();
    $conex->close();
} else {
    // Respuesta JSON de error para solicitudes no POST
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}
