<?php
include_once('codes/conexion.inc');

$ticket_id = $_GET['ticket_id'];

// Consulta para obtener los mensajes del ticket
$query = "SELECT * FROM mensajes WHERE chat_grupo = ? ORDER BY fecha ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($mensaje = $result->fetch_assoc()) {
    $mensajes[] = $mensaje;
}

// Devolver los mensajes en formato JSON
echo json_encode(['mensajes' => $mensajes]);

$stmt->close();
$conn->close();
