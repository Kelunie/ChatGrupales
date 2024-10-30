<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php');
    exit();
}

include_once('codes/conexion.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $tecnico = $_SESSION['tecnico_id']; // El nombre del técnico logueado

    // Actualizar el estado del ticket a "en proceso" y asignar al técnico
    $stmt = $conex->prepare("UPDATE tickets SET estado = 'en proceso', tecnico_asignado = ? WHERE id = ?");
    $stmt->bind_param("si", $tecnico, $ticket_id);
    $stmt->execute();
    $stmt->close();
    header('Location: gestionar_tickets.php');
    exit();
}
