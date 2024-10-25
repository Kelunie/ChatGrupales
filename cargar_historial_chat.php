<?php
session_start();
include_once('codes/conexion.inc');

// Obtener la IP del usuario de la sesión
$ipUsuario = $_SESSION['ip_usuario'] ?? null;

if ($ipUsuario === null) {
    // Devolver un error en formato JSON si la IP no está en la sesión
    echo json_encode(['error' => 'No tienes permisos para acceder a este chat.']);
    exit();
}

// Cargar los mensajes asociados con la IP del usuario
$stmt = $conex->prepare("SELECT nombre_usuario, mensaje, fecha FROM mensajes_ticket WHERE ip_usuario = ? ORDER BY fecha ASC");
$stmt->bind_param("s", $ipUsuario);
$stmt->execute();
$result = $stmt->get_result();

$historial = [];
while ($row = $result->fetch_assoc()) {
    $historial[] = $row;
}

$stmt->close();

// Devolver el historial en formato JSON
echo json_encode($historial);
