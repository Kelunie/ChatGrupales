<?php
session_start();

// Asegúrate de que el técnico esté autenticado
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php');
    exit();
}

include_once('codes/conexion.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $tecnico_id = $_SESSION['tecnico_id']; // ID del técnico logueado

    // Actualizar el estado del ticket si está en "en proceso"
    $stmt = $conex->prepare("UPDATE tickets SET estado = 'cerrado', tecnico_asignado = ? WHERE id = ? AND estado = 'en proceso'");
    $stmt->bind_param("ii", $tecnico_id, $ticket_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        // Redirige si se realizó la actualización
        header("Location: gestionar_tickets.php");
    } else {
        // Mensaje de error si no se realizó la actualización
        echo "No se pudo cerrar el ticket o el estado no era 'en proceso'.";
    }

    $stmt->close();
    exit();
}
