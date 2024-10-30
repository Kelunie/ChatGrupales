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

// Guardar el nombre del usuario que creó el ticket y el ID del técnico asignado
$usuarioTicket = $ticket['nombre_usuario'];
$tecnicoAsignado = $ticket['tecnico_asignado']; // ID del técnico asignado al ticket
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <?php
    $titulo = "Chat de Tcikets - Ticket ID: " . htmlspecialchars($ticket_id);
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?></title> <!-- Usa el título dinámico -->
    <link rel="stylesheet" href="path/to/bootstrap.css">
    <style>
        #chat {
            height: 400px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            padding: 10px;

        }

        .boton {
            border: none;
            color: black;
            padding: 14px 28px;
            cursor: pointer;
            border-radius: 200px;
            display: inline-flex;
            width: 100%;
            height: 50%;
            font-size: 20px;
            font-optical-sizing: none;
        }

        .boton i {
            margin-right: 5px;
            margin-left: 5px;
            margin-top: 4.5px;
        }

        .resuelto {
            background-color: #04AA6D;
        }

        .resuelto:hover {
            background-color: #46a049;
        }

        .separador {
            /* vamos a agregar una linea negra para cada vez que llega un mensaje para diferenciarlos*/
            border-bottom: 1px solid black;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <header>
        <?php include_once('codes/menu.inc'); ?>
        <?php include_once('codes/enca.inc'); ?>
    </header>
    <main>
        <div class="container ">
            <header>
                <h1 class="text-center">Chat del Ticket #<?php echo $ticket_id; ?> ||
                    <!-- la siguiente codigo de php lo que hace es ver si el tecnico que esta viendo el chat
                     es el mismo de que esta asignado el ticket, de no ser asi, el boton no funcionarara -->
                    <?php if (isset($_SESSION['tecnico_id'])) : ?>
                        <form action="cerrado_ticket.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
                            <button class="boton resuelto" type="submit"
                                <?php if ($_SESSION['tecnico_id'] != $tecnicoAsignado) echo 'disabled'; ?>>
                                Caso resuelto <i class="fas fa-check-circle"></i>
                            </button>
                        </form>
                    <?php endif; ?>
                </h1>
                <div id="chat">
                    <!-- Aquí se mostrarán los mensajes -->
                </div>

                <form id="formEnviarMensaje">
                    <div class="form-group">
                        <textarea class="form-control mb-2" id="mensaje"
                            placeholder="Dinos en que podemos ayudarte hoy..." required></textarea>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-outline-primary btn-lg btn-block" type="submit">Enviar</button>
                    </div>
                </form>

                <br>

                <footer>
                    <?php include_once('codes/pie.inc'); ?>
                </footer>
                <script>
                    // funcion para cargar mensaje
                    function cargarMensajes() {
                        const chatDiv = document.getElementById('chat');
                        fetch('obtener_mensaje_ticket.php?ticket_id=<?php echo $ticket_id; ?>')
                            .then(response => response.json())
                            .then(data => {
                                chatDiv.innerHTML = '';
                                data.mensajes.forEach(mensaje => {
                                    chatDiv.innerHTML +=
                                        `<p class="separador"><strong>${mensaje.nombre_usuario}:</strong><br>  ${mensaje.mensaje}</p>`;
                                });
                                chatDiv.scrollTop = chatDiv.scrollHeight;
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }

                    cargarMensajes();
                    setInterval(cargarMensajes, 5000);
                    //manejar envio del formulario
                    document.getElementById('formEnviarMensaje').addEventListener('submit', function(e) {
                        e.preventDefault();
                        const mensaje = document.getElementById('mensaje').value;

                        <?php if (isset($_SESSION['tecnico'])): ?>
                            const nombreUsuario = '<?php echo $_SESSION['username']; ?>';
                        <?php else: ?>
                            const nombreUsuario = '<?php echo $usuarioTicket; ?>';
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
                                    document.getElementById('mensaje').value = '';
                                    cargarMensajes();
                                } else {
                                    alert('Hubo un error al enviar el mensaje.');
                                }
                            }).catch(error => {
                                console.error('Error:', error);
                            });
                    });
                </script>
            </header>
        </div>
    </main>
</body>

</html>