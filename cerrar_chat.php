<?php
session_start();

if (isset($_SESSION['tecnico'])) {
    header('Location: gestionar_tickets.php');
    exit();
} else {
    // Limpia todas las variables de sesión
    session_unset();

    // Destruye la sesión
    session_destroy();

    header("Location: index.php"); // Redirige al inicio

    // Opcional: Puedes redirigir o enviar una respuesta
    echo json_encode(['success' => true]);
}
