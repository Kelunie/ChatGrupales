<?php
header('Content-Type: application/json');
session_start();
include_once('codes/conexion.inc'); // Incluye la conexión a la base de datos

// Verifica que se recibió la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene los datos enviados desde el formulario
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $mensaje = $_POST['mensaje'] ?? '';
    $ip_usuario = $_SERVER['REMOTE_ADDR']; // Obtiene la IP del usuario
    $nombre_equipo = $_POST['nombre_equipo'] ?? ''; // Asegúrate de que este valor se envíe

    // Prepara y ejecuta la consulta para guardar el ticket en la base de datos
    $query = "INSERT INTO tickets (nombre_usuario, mensaje, estado, ip_usuario, nombre_equipo) VALUES (?, ?, 'abierto', ?, ?)";
    $stmt = $conex->prepare($query);
    $stmt->bind_param("ssss", $nombre_usuario, $mensaje, $ip_usuario, $nombre_equipo);

    if ($stmt->execute()) {
        // Obtiene el ID del ticket creado
        $ticket_id = $stmt->insert_id;

        // Guardar información en la sesión
        $_SESSION['nombre_usuario'] = $nombre_usuario;
        $_SESSION['ip_usuario'] = $ip_usuario;
        $_SESSION['nombre_equipo'] = $nombre_equipo;

        // Envía respuesta JSON de éxito
        echo json_encode(['success' => true, 'ticket_id' => $ticket_id]);
    } else {
        // Envía respuesta JSON de error
        echo json_encode(['success' => false, 'message' => 'Error al crear el ticket']);
    }

    // Cierra la declaración y la conexión
    $stmt->close();
    $conex->close();
} else {
    // Envía respuesta JSON si no es una solicitud POST
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
exit;
