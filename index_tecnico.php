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
            max-width: 50%;
            /* Ajusta el ancho al 90% de la pantalla */
            height: 100%;
            /* Ajusta la altura al 90% de la altura de la ventana */
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
            position: relative;
            top: -13px;
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

        .separador {
            /* vamos a agregar una linea negra para cada vez que llega un mensaje para diferenciarlos*/
            border-bottom: 1px solid black;
            margin-bottom: 10px;
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
    <!-- redirige a index.php si no esta login -->
    <?php if (!isset($_SESSION['tecnico'])) : ?>
        <script>
            alert('Debe iniciar sesión como técnico para acceder a esta página.');
            window.location.href = 'index.php';
        </script>
    <?php endif; ?>

    <main>
        <div class="container mt-4 text-center">
            <h1 style=color:white>Bienvenid@</h1>
        </div>

        <div class="container mt-4 text-center">
            <form id="formUsuario" action="index.php" method="POST">
                <h2 style="color: gold;"> <?php echo $_SESSION['username']; ?></h2>
                <input type="text" id="nombreUsuarioPrincipal" name="nombreUsuario"
                    value="<?php echo $_SESSION['username']; ?>" readonly required hidden>
            </form>
        </div>
        <br>

        <div class="container mt-4 text-center">
            <select id="chatSelect" class="form-select form-select-lg m-auto order-2" aria-label="Large select">
                <option selected>Seleccione el chat que quiere ingresar</option>
                <option value="Administración">Administración</option>
                <option value="AtencionPrimaria">Atención Primaria</option>
                <option value="CGI">CGI</option>
                <option value="ConsultaExterna">Consulta Externa</option>
                <option value="Emergencia">Emergencias</option>
                <option value="Farmacia">Farmacia</option>
                <option value="Laboratorio">Laboratorio</option>
                <option value="Odontología">Odontología</option>
                <option value="Preconsulta">Preconsulta</option>
                <option value="Redes">Redes</option>
            </select>
            <button type="button" id="openModalBtn" class="mi-boton btn btn-gold mt-4">Ingresar al chat</button>
        </div>
        <br>

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
                            // Muestra el mensaje en el historial de mensajes con hora
                            const historialMensajes = document.getElementById("historialMensajes");
                            const fechaActual = new Date();
                            const fechaFormateada =
                                `${fechaActual.getFullYear()}-${String(fechaActual.getMonth() + 1).padStart(2, '0')}-${String(fechaActual.getDate()).padStart(2, '0')} ${String(fechaActual.getHours()).padStart(2, '0')}:${String(fechaActual.getMinutes()).padStart(2, '0')}`;

                            // Agregar el mensaje con la fecha y hora en el formato deseado
                            historialMensajes.innerHTML +=
                                `<p class="separador"><strong>${data.nombre_usuario}</strong> (${fechaFormateada}): <br> ${data.mensaje}</p>`;

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