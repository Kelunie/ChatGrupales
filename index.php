<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <link rel="stylesheet" href="codes/css/bootstrap.min.css" /> <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="codes/css/estilos.css" /> <!-- Tu CSS personalizado -->
</head>

<body>
    <main>
        <!-- mensaje de bienvenida y formulario para escribir nombre -->
        <div class="container mt-4 text-center">
            <h1>Bienvenid@</h1>
        </div>

        <!-- espacio para escribir nombre -->
        <div class="container mt-4 text-center">
            <form id="formUsuario" action="index.php" method="POST">
                <input type="text" id="nombreUsuario" name="nombreUsuario" placeholder="Tu nombre" required>
            </form>
        </div>

        <!-- select para elegir a que chat quiere entrar -->
        <div class="container mt-auto text-center">
            <select id="chatSelect" class="form-select form-select-lg m-auto" aria-label="Large select">
                <option selected class="diferente">Seleccione el chat que quiere ingresar</option>
                <option value="Odontología">Odontología</option>
                <option value="Administración">Administración</option>
                <option value="Redes">Redes</option>
                <option value="Soporte TI">Soporte TI</option>
            </select>
            <button type="button" id="openModalBtn" class="btn btn-primary mt-4">Ingresar</button>
        </div>

        <!-- Modal para el chat seleccionado -->
        <div id="chatModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="chatGrupoTitulo">Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Historial de mensajes -->
                        <div id="historialMensajes"
                            style="height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px;">
                            <!-- Aquí se cargarán los mensajes -->
                        </div>

                        <!-- Formulario para enviar un mensaje -->
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

        <!-- Enlazar Bootstrap JS -->
        <script src="codes/js/bootstrap.bundle.min.js"></script>

        <script>
        // Validar si el nombre de usuario está ingresado
        function validarNombreUsuario() {
            var nombreUsuario = document.getElementById("nombreUsuario").value;
            return nombreUsuario.trim() !== ""; // Devuelve true si hay nombre ingresado
        }

        // Abrir el chat y cargar el historial de mensajes
        document.getElementById("openModalBtn").onclick = function() {
            var selectedChat = document.getElementById("chatSelect").value;
            var nombreUsuario = document.getElementById("nombreUsuario").value;

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
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "cargar_historial.php?chat_grupo=" + chatGrupo, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById("historialMensajes").innerHTML = this.responseText;
                }
            };
            xhr.send();
        }

        // Manejar el envío del formulario de mensajes con AJAX
        document.getElementById('formEnviarMensaje').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el envío del formulario tradicional

            var mensaje = document.getElementById('mensaje').value;
            var chatGrupo = document.getElementById('chatGrupo').value;
            var nombreUsuario = document.getElementById('nombreUsuarioModal').value;

            // Crear el objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "enviar_mensaje.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Enviar el mensaje al servidor
            xhr.onload = function() {
                if (xhr.status == 200) {
                    // Actualizar el historial de mensajes después de enviar uno nuevo
                    cargarHistorialMensajes(chatGrupo);

                    // Limpiar el área de texto después de enviar
                    document.getElementById('mensaje').value = "";
                }
            };

            // Enviar los datos del formulario
            xhr.send("nombre_usuario=" + encodeURIComponent(nombreUsuario) +
                "&mensaje=" + encodeURIComponent(mensaje) +
                "&chat_grupo=" + encodeURIComponent(chatGrupo));
        });
        </script>
</body>

</html>