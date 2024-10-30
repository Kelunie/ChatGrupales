<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <link rel="stylesheet" href="codes/css/bootstrap.min.css" /> <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="codes/css/stilos.css" /> <!-- Tu CSS personalizado -->

    <style>
        .btn-warning a {
            text-decoration: none;
            /* Removes the underline */
            color: inherit;
            /* Inherits the button's text color */
        }

        /* Agregaremos que brille el boton con hover*/
        .btn-warning :hover {
            background-color: #f8b400;
            border-color: #f8b400;
        }

        .btn-warning a :hover {
            background-color: #f8b400;
            border-color: #f8b400;
        }

        .btn-info a {
            text-decoration: none;
            /* Removes the underline */
            color: inherit;
            /* Inherits the button's text color */
        }

        .modal-perso {
            max-width: 90%;
            /* Ajusta el ancho al 90% de la pantalla */
            height: 90%;
            /* Ajusta la altura al 90% de la altura de la ventana */
        }
    </style>
</head>

<body>
    <header>
        <?php
        include_once('codes/menu.inc');
        ?>
        <?php if (isset($_SESSION['tecnico'])) : ?>
            <!-- Botón para crear un nuevo usuario técnico -->
            <div class="container text-center mt-4">
                <button type="button" class="btn btn-warning"><a href="crear_tecnico.php">Crear Usuario</a></button>
                <button type="button" class="btn btn-info"><a href="gestionar_tickets.php">Getionar Tickets
                        Usuario</a></button>
            </div>

        <?php endif; ?>


    </header>
    <!-- si inicio sesion como tecnico no se muestra el boton -->
    <!-- Botón para iniciar sesión como técnico -->
    <?php if (!isset($_SESSION['tecnico'])) : ?>
        <div class="container text-center mt-4">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">
                Iniciar sesión como Técnico
            </button>
        </div>
    <?php endif; ?>

    <main>
        <div class="container mt-4 text-center">
            <h1>Bienvenid@</h1>
        </div>

        <div class="container mt-4 text-center">
            <form id="formUsuario" action="index.php" method="POST">
                <input type="text" id="nombreUsuarioPrincipal" name="nombreUsuario" placeholder="Tu nombre" required>

            </form>
        </div>

        <div class="container mt-4 text-center">
            <select id="chatSelect" class="form-select form-select-lg m-auto" aria-label="Large select">
                <option selected>Seleccione el chat que quiere ingresar</option>
                <option value="Odontología">Odontología</option>
                <option value="Administración">Administración</option>
                <option value="Redes">Redes</option>
                <option value="Soporte TI">CGI</option>
            </select>
            <button type="button" id="openModalBtn" class="btn btn-primary mt-4">Ingresar al chat</button>
        </div>
        <?php if (!isset($_SESSION['tecnico'])) : ?>
            <!-- Botón para enviar un ticket de soporte -->
            <div class="container mt-4 text-center">
                <button type="button" id="openTicketBtn" class="btn btn-info">Enviar Ticket de Soporte</button>
            </div>

            <!-- Modal para enviar ticket de soporte -->
            <div id="ticketModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Enviar Ticket de Soporte</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formEnviarTicket">
                                <div class="mb-3">
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario"
                                        placeholder="Tu nombre" required>
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" id="mensajeTicket" name="mensajeTicket" rows="3"
                                        placeholder="Describe tu problema..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar Ticket</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <br>

        <!-- Modal para iniciar sesión como Técnico -->
        <div id="loginModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Iniciar sesión</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formLogin" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Usuario" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para el chat seleccionado -->
        <div id="chatModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-perso" role="document">
                <!-- modal-lg o modal-xl para ampliar el tamaño -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chatGrupoTitulo">Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="historialMensajes"
                            style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
                        </div>

                        <form id="formEnviarMensaje" method="POST">
                            <input type="hidden" id="chatGrupo" name="chat_grupo" value="">
                            <input type="hidden" id="nombreUsuarioModal" name="nombre_usuario" value="">

                            <div class="mb-3">
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="3"
                                    placeholder="Escribe tu mensaje..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <?php
            include_once('codes/pie.inc');
            ?>
        </footer>

        <!-- Enlazar Bootstrap JS -->
        <script src="codes/js/bootstrap.bundle.min.js"></script>

        <script>
            // Llamada AJAX para iniciar sesión como técnico
            document.getElementById('formLogin').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevenir el envío normal del formulario

                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;

                fetch('login_tecnico.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Redirigir a la página de gestión de tickets
                            window.location.href = data.redirect;
                        } else {
                            alert(data.message); // Mostrar mensaje de error
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });

            // Validar si el nombre de usuario está ingresado
            function validarNombreUsuario() {
                var nombreUsuario = document.getElementById("nombreUsuarioPrincipal").value;
                return nombreUsuario.trim() !== ""; // Devuelve true si hay nombre ingresado
            }

            // Abrir el chat y cargar el historial de mensajes
            document.getElementById("openModalBtn").onclick = function() {
                var selectedChat = document.getElementById("chatSelect").value;
                var nombreUsuario = document.getElementById("nombreUsuarioPrincipal").value;

                if (validarNombreUsuario() && selectedChat !== "Seleccione el chat que quiere ingresar") {
                    document.getElementById("chatGrupo").value = selectedChat;
                    document.getElementById("nombreUsuarioModal").value = nombreUsuario;
                    document.getElementById("chatGrupoTitulo").innerText = "Chat: " + selectedChat;

                    // Cargar el historial de mensajes con AJAX
                    cargarHistorialMensajes(selectedChat);

                    var chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
                    chatModal.show();
                } else {
                    alert("Por favor ingresa tu nombre y selecciona un chat.");
                }
            };

            // Función para cargar el historial de mensajes del grupo
            function cargarHistorialMensajes(chatGrupo) {
                fetch("cargar_historial.php?chat_grupo=" + encodeURIComponent(chatGrupo))
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById("historialMensajes").innerHTML = data;
                        // Desplazarse al final del historial de mensajes
                        const historialMensajes = document.getElementById("historialMensajes");
                        historialMensajes.scrollTop = historialMensajes.scrollHeight;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            // Abrir el modal de enviar ticket de soporte
            document.getElementById('openTicketBtn').addEventListener('click', function() {
                var ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));
                ticketModal.show();
            });

            // Manejar el envío del formulario para el ticket
            document.getElementById('formEnviarTicket').addEventListener('submit', function(e) {
                e.preventDefault();

                const nombreUsuario = document.getElementById('nombreUsuario').value;
                const mensajeTicket = document.getElementById('mensajeTicket').value;

                // Obtener IP del usuario
                const ipUsuario = '<?php echo $_SERVER["REMOTE_ADDR"]; ?>';
                const nombreEquipo = window.navigator.userAgent;

                fetch('crear_ticket.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `nombre_usuario=${encodeURIComponent(nombreUsuario)}&mensaje=${encodeURIComponent(mensajeTicket)}&ip_usuario=${encodeURIComponent(ipUsuario)}&nombre_equipo=${encodeURIComponent(nombreEquipo)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Abrir una nueva pestaña con el chat del ticket
                            const nuevaVentana = window.open('chat_ticket.php?ticket_id=' + data.ticket_id,
                                '_blank');
                            // Limpiar los campos del formulario
                            document.getElementById('nombreUsuario').value = '';
                            document.getElementById('mensajeTicket').value = '';
                        } else {
                            alert('Hubo un problema al enviar el ticket: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
            // Manejar el envío del formulario para enviar mensajes
            document.getElementById('formEnviarMensaje').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevenir el envío normal del formulario

                const nombreUsuario = document.getElementById('nombreUsuarioPrincipal').value;
                const mensaje = document.getElementById('mensaje').value;
                const chatGrupo = document.getElementById('chatGrupo').value;
                const nombreEquipo = window.navigator.userAgent; // Usamos el user agent como nombre del equipo

                fetch('enviar_mensaje.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `nombre_usuario=${encodeURIComponent(nombreUsuario)}&mensaje=${encodeURIComponent(mensaje)}&chat_grupo=${encodeURIComponent(chatGrupo)}&nombre_equipo=${encodeURIComponent(nombreEquipo)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Muestra el mensaje en el historial de mensajes
                            const historialMensajes = document.getElementById("historialMensajes");
                            historialMensajes.innerHTML +=
                                `<p><strong>${data.nombre_usuario}:</strong> ${data.mensaje}</p>`;
                            // Limpia el campo del mensaje
                            document.getElementById('mensaje').value = '';
                            // Desplazarse al final del historial de mensajes
                            historialMensajes.scrollTop = historialMensajes.scrollHeight;
                        } else {
                            alert('Error al enviar el mensaje: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        </script>
    </main>
</body>

</html>