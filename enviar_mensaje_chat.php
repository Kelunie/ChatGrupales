<?php
include_once('codes/conexion.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $mensaje = $_POST['mensaje'];
    $nombre_usuario = $_SESSION['nombre_usuario'] ?? $_SESSION['tecnico']; // Puede ser usuario o técnico

    // Insertar el mensaje en la tabla mensajes_ticket
    $stmt = $conex->prepare("INSERT INTO mensajes_ticket (ticket_id, nombre_usuario, mensaje, fecha) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $ticket_id, $nombre_usuario, $mensaje);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
