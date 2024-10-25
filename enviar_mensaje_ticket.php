<?php
session_start();
include_once('codes/conexion.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $mensaje = $_POST['mensaje'];
    $nombre_usuario = isset($_SESSION['tecnico_id']) ? 'Técnico' : $_SESSION['nombre_usuario']; // Asignar nombre basado en si es un técnico o usuario normal

    // Guardar el mensaje en la base de datos
    $query = "INSERT INTO mensajes (nombre_usuario, mensaje, chat_grupo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $nombre_usuario, $mensaje, $ticket_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje']);
    }

    $stmt->close();
    $conn->close();
}
