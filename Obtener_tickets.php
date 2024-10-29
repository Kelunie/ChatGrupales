<?php
session_start();
include_once('codes/conexion.inc');

// Consulta para obtener los tickets
$query = "SELECT id, nombre_usuario, mensaje, estado, fecha, ip_usuario, nombre_equipo FROM tickets";
$result = $conex->query($query);

$tickets = [];
while ($row = $result->fetch_assoc()) {
    $tickets[] = $row;
}

echo json_encode($tickets);
