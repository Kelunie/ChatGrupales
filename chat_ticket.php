<?php
session_start();
include_once('codes/conexion.inc');

// Obtener la IP del usuario
$ip_usuario = $_SERVER['REMOTE_ADDR'];
$ticket_id = $_GET['ticket_id'];

// Consulta para obtener los detalles del ticket
$query = "SELECT * FROM tickets WHERE id = ?";
$stmt = $conex->prepare($query);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if (!$ticket) {
    echo "Ticket no encontrado.";
    exit;
}

// Verificar que el usuario que creó el ticket o un técnico asignado pueda ver el ticket
if ($ticket['ip_usuario'] !== $ip_usuario && !isset($_SESSION['tecnico_id'])) {
    echo "No tienes permiso para ver este ticket.";
    exit;
}

// Mostrar el chat del ticket
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Ticket #<?php echo $ticket_id; ?></title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <style>
        #chat {
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>

<body>
    <h1>Chat del Ticket #<?php echo $ticket_id; ?></h1>
    <div id="chat"></div>

    <form id="formEnviarMensaje">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
        <input type="text" id="mensaje" name="mensaje" placeholder="Escribe tu mensaje" required>
        <button type="submit">Enviar</button>
    </form>

    <script>
        // Función para cargar los mensajes del ticket
        function cargarMensajes() {
            const chatDiv = document.getElementById('chat');
            fetch('obtener_mensajes_ticket.php?ticket_id=<?php echo $ticket_id; ?>')
                .then(response => response.json())
                .then(data => {
                    chatDiv.innerHTML = '';
                    data.mensajes.forEach(mensaje => {
                        chatDiv.innerHTML +=
                            `<p><strong>${mensaje.nombre_usuario}:</strong> ${mensaje.mensaje}</p>`;
                    });
                    chatDiv.scrollTop = chatDiv.scrollHeight; // Desplazar hacia el final del chat
                });
        }

        // Cargar los mensajes periódicamente
        setInterval(cargarMensajes, 5000);

        // Enviar nuevo mensaje
        document.getElementById('formEnviarMensaje').addEventListener('submit', function(e) {
            e.preventDefault();
            const mensaje = document.getElementById('mensaje').value;

            fetch('enviar_mensaje_ticket.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `ticket_id=<?php echo $ticket_id; ?>&mensaje=${encodeURIComponent(mensaje)}`
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('mensaje').value = ''; // Limpiar el campo de mensaje
                        cargarMensajes(); // Recargar los mensajes
                    } else {
                        alert('Hubo un error al enviar el mensaje.');
                    }
                });
        });
    </script>
</body>

</html>