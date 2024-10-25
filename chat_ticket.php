<?php
session_start();
include_once('codes/conexion.inc');

// Obtener el ID del ticket de la URL
$ticket_id = $_GET['ticket_id'] ?? 0;

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

// Guardar el nombre del usuario que creó el ticket
$usuarioTicket = $ticket['nombre_usuario']; // Asumiendo que hay un campo 'nombre_usuario' en la tabla 'tickets'
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
    <div id="chat">
        <!-- Aquí se mostrarán los mensajes -->
    </div>

    <form id="formEnviarMensaje">
        <textarea id="mensaje" placeholder="Escribe tu mensaje..." required></textarea>
        <button type="submit">Enviar</button>
    </form>

    <script>
        // Función para cargar los mensajes del ticket
        function cargarMensajes() {
            const chatDiv = document.getElementById('chat');
            fetch('obtener_mensaje_ticket.php?ticket_id=<?php echo $ticket_id; ?>') // Ajusta la ruta si es necesario
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la carga de mensajes.');
                    }
                    return response.json();
                })
                .then(data => {
                    chatDiv.innerHTML = '';
                    data.mensajes.forEach(mensaje => {
                        chatDiv.innerHTML +=
                            `<p><strong>${mensaje.nombre_usuario}:</strong> ${mensaje.mensaje}</p>`;
                    });
                    chatDiv.scrollTop = chatDiv.scrollHeight; // Desplazar hacia el final del chat
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // Cargar los mensajes al cargar la página
        cargarMensajes();
        // Cargar los mensajes periódicamente
        setInterval(cargarMensajes, 5000);

        // Manejar el envío del formulario para enviar un mensaje
        document.getElementById('formEnviarMensaje').addEventListener('submit', function(e) {
            e.preventDefault();
            const mensaje = document.getElementById('mensaje').value;

            // Definir el nombre del usuario según si es técnico o no
            <?php if (isset($_SESSION['tecnico'])): ?>
                const nombreUsuario = '<?php echo $_SESSION['username']; ?>';
            <?php else: ?>
                const nombreUsuario = '<?php echo $usuarioTicket; ?>'; // Usar el nombre del usuario que envió el ticket
            <?php endif; ?>

            fetch('enviar_mensaje_ticket.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `ticket_id=<?php echo $ticket_id; ?>&nombre_usuario=${encodeURIComponent(nombreUsuario)}&mensaje=${encodeURIComponent(mensaje)}`
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('mensaje').value = ''; // Limpiar el campo de mensaje
                        cargarMensajes(); // Recargar los mensajes
                    } else {
                        alert('Hubo un error al enviar el mensaje.');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
</body>

</html>