<?php
session_start();
include_once('codes/conexion.inc');

// Obtener el ID del ticket de la URL
$ticket_id = $_GET['ticket_id'] ?? 0;

// Consulta para obtener los mensajes del ticket
$query = "SELECT * FROM mensajes_ticket WHERE ticket_id = ? ORDER BY fecha ASC"; // Asegúrate de que este sea el nombre correcto de la tabla
$stmt = $conex->prepare($query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();

$mensajes = [];
while ($row = $result->fetch_assoc()) {
    $mensajes[] = $row;
}

// Devolver los mensajes en formato JSON
header('Content-Type: application/json');
echo json_encode(['mensajes' => $mensajes]);
