<?php
session_start();
include_once('codes/conexion.inc');

// Consulta para obtener los tickets
$query = "SELECT tickets.id, tickets.nombre_usuario, tickets.mensaje, tickets.estado, tickets.fecha, 
                 tickets.ip_usuario, tickets.nombre_equipo, tecnicos.username 
          FROM tickets 
          LEFT JOIN tecnicos ON tickets.tecnico_asignado = tecnicos.id";

$result = $conex->query($query);

if ($result) {
    $tickets = [];
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    echo json_encode($tickets);
} else {
    echo json_encode([]); // Retornar un array vacío si no hay resultados
}