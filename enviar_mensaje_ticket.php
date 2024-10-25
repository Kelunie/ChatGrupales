<?php
header('Content-Type: application/json');
session_start();
include_once('codes/conexion.inc');

// Verifica que se recibió la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos enviados desde el formulario
    $ticket_id = $_POST['ticket_id'] ?? '';
    $nombre_usuario = $_POST['nombre_usuario'] ?? ''; // Debes enviar este dato desde el formulario
    $mensaje = $_POST['mensaje'] ?? '';

    // Prepara y ejecuta la consulta para guardar el mensaje en la tabla mensajes_ticket
    $query = "INSERT INTO mensajes_ticket (ticket_id, nombre_usuario, mensaje) VALUES (?, ?, ?)";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("iss", $ticket_id, $nombre_usuario, $mensaje);

    if ($stmt->execute()) {
        // Envía respuesta JSON de éxito
        echo json_encode(['success' => true]);
    } else {
        // Envía respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
    }

    // Cierra la declaración y la conexión
    $stmt->close();
    $conex->close();
} else {
    // Envía respuesta JSON si no es una solicitud POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
