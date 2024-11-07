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
$mensaje = $ticket['mensaje'];
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
        border: 1px solid black;
        padding: 10px;
        background-color: white;
        border-radius: 20px;
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

    body {
        background-image: url("../ChatGrupales/codes/img/fondo.jpg");
        /* Ruta actualizada desde la raíz del servidor */
        background-size: cover;
        /* Asegura que la imagen cubra toda la pantalla */
        background-repeat: no-repeat;
        /* Evita que la imagen se repita */
        background-attachment: fixed;
        /* Hace que la imagen de fondo se mantenga fija al hacer scroll */
        background-position: center;
        /* Centra la imagen en la página */
    }

    footer {
        background-color: black;
        /* Color de fondo suave */
        color: white;
        /* Color de texto */
        padding: 20px 0;
        /* Espaciado vertical */
        text-align: center;
        /* Centra el texto */
        border-top: 2px solid gold;
        /* Línea superior azul */
    }

    .btn-gold {
        background-color: gold;
        /* Fondo dorado */
        color: black;
        /* Letra negra */
        border: 1px solid gold;
        /* Borde del mismo color */
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 0.375rem;
        /* Bordes redondeados */
        transition: background-color 0.3s ease, color 0.3s ease;
        /* Transiciones suaves */

    }

    .btn-gold:hover {
        background-color: white;
        /* Fondo blanco en hover */
        color: black;
        /* Letra negra */
        border: 1px solid black;
        /* Borde negro en hover */
    }


    .mi-btn2 {
        border: 1px;
        border-radius: 50%;
        background-color: gold;
    }

    .mi-btn2:hover {
        background-color: white;
        color: black;
    }

    .menuti {
        color: gold;
    }

    .menuti:hover {
        color: white;
    }

    .tablitadinamica {
        background-color: white;
        color: black;
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
                <h1 style="color: gold;" class="text-center">Chat del Ticket #<?php echo $ticket_id; ?> ||
                    <!-- la siguiente codigo de php lo que hace es ver si el tecnico que esta viendo el chat
                     es el mismo de que esta asignado el ticket, de no ser asi, el boton no funcionarara -->
                    <?php if (isset($_SESSION['tecnico_id'])) : ?>
                    <form action="cerrado_ticket.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="ticket_id" value="<?= $ticket_id ?>">
                        <button class="btn btn-gold" type="submit"
                            <?php if ($_SESSION['tecnico_id'] != $tecnicoAsignado) echo 'disabled'; ?>>
                            Caso resuelto <i class="fas fa-check-circle"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </h1>
                <div
                    style="background-color: gold; border-radius: 2%; border-bottom-left-radius: 15%; border-bottom-right-radius: 15%">
                    <h4>
                        <p><strong> Detalle de la Solicitud:</strong></p>
                    </h4>
                    <h5 class=" separador"><?php echo $mensaje ?></h3>
                        <br>
                        <div id="chat">
                            <!-- Aquí se mostrarán los mensajes -->
                        </div>
                </div>

                <form id="formEnviarMensaje">
                    <div class="form-group">
                        <textarea class="form-control mb-2" id="mensaje"
                            placeholder="Dinos en que podemos ayudarte hoy..." required></textarea>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-gold btn-lg btn-block" type="submit">Enviar</button>
                    </div>
                </form>

                <br>

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
                // Mostrar un mensaje de confirmación al intentar cerrar la ventana o navegar a otra página
                window.onbeforeunload = function(event) {
                    // Configurar el mensaje de advertencia
                    const message =
                        "¿Estás seguro de querer salir? \n si lo hace no podras volver al chat, y tendra que abrir otro.";
                    event.returnValue = message; // Para algunos navegadores
                    return message; // Para otros navegadores
                };

                // Limpiar la sesión al cerrar la ventana
                window.addEventListener('beforeunload', function() {
                    fetch('cerrar_chat.php') // Llama al archivo PHP para limpiar la sesión
                        .then(response => {
                            if (!response.ok) {
                                console.error('Error al limpiar la sesión.');
                            }
                        });
                });
                </script>
            </header>
        </div>
        <footer>
            <?php include_once('codes/pie.inc'); ?>
        </footer>
    </main>
</body>

</html>