<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php');
    exit();
}

include_once('codes/conexion.inc');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = $_POST['ticket_id'];
    $tecnico = $_SESSION['tecnico']; // El nombre del técnico logueado

    // Actualizar el estado del ticket a "en proceso" y asignar al técnico
    $stmt = $conex->prepare("UPDATE tickets SET estado = 'en proceso', tecnico_asignado = ? WHERE id = ?");
    $stmt->bind_param("si", $tecnico, $ticket_id);
    $stmt->execute();
    $stmt->close();

    // Redirigir a la ventana de chat
    header("Location: chat_ticket.php?ticket_id=$ticket_id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <title>Chat Ticket #<?= $ticket_id ?></title>
</head>

<body>

    <div class="container text-center">
        <h1>No cerrar esta pestaña hasta que el Técnico de TI se lo indique</h1>
        <h2>Chat con <?= $nombre_usuario ?></h2>
        <div id="chat"></div>
    </div>

    <script>
    // Función para cargar el historial de mensajes
    function cargarHistorialChat() {
        fetch('cargar_historial_chat.php?ticket_id=<?= $ticket_id ?>')
            .then(response => response.json()) // Aquí estás esperando un JSON
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                let chatHtml = '';
                data.forEach(mensaje => {
                    chatHtml +=
                        `<p><strong>${mensaje.nombre_usuario}</strong>: ${mensaje.mensaje} <br><small>${mensaje.fecha}</small></p>`;
                });

                document.getElementById('chat').innerHTML = chatHtml;
            })
            .catch(error => console.error('Error al cargar el chat:', error));
    }

    // Actualizar el historial del chat cada 5 segundos
    setInterval(cargarHistorialChat, 5000);

    // Enviar mensaje con AJAX
    document.getElementById('formEnviarMensaje').addEventListener('submit', function(e) {
        e.preventDefault();

        const mensaje = document.getElementById('mensaje').value;

        fetch('enviar_mensaje_chat.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `ticket_id=<?= $ticket_id ?>&mensaje=${encodeURIComponent(mensaje)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('mensaje').value = ''; // Limpiar el campo de texto
                    cargarHistorialChat(); // Actualizar el historial después de enviar el mensaje
                } else {
                    alert('Error al enviar el mensaje.');
                }
            })
            .catch(error => console.error('Error al enviar mensaje:', error));
    });
    </script>

</body>

</html>