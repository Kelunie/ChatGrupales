<?php
include_once('codes/conexion.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'])) {
    $ticket_id = $_POST['ticket_id'];

    $query = "SELECT tickets.id, tickets.nombre_usuario, tickets.mensaje, tickets.estado, tickets.fecha,
                     tickets.ip_usuario, tickets.nombre_equipo, tecnicos.username AS tecnico_asignado
              FROM tickets 
              LEFT JOIN tecnicos ON tickets.tecnico_asignado = tecnicos.id
              WHERE tickets.id = ?";

    if ($stmt = $conex->prepare($query)) {
        $stmt->bind_param("i", $ticket_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $ticket = $result->fetch_assoc();
            echo json_encode($ticket);
        } else {
            echo json_encode(['error' => 'Ticket no encontrado']);
        }
    } else {
        echo json_encode(['error' => 'Error en la consulta']);
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida']);
}
