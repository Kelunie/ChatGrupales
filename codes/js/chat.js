function abrirChat(grupo) {
  // Establecer el grupo de chat en un campo oculto
  document.getElementById("chatGrupo").value = grupo;
  cargarHistorial(grupo); // Cargar el historial de mensajes para el grupo seleccionado

  // Mostrar el modal de chat
  var modal = new bootstrap.Modal(document.getElementById("chatModal"));
  modal.show();
}

function cargarHistorial(grupo) {
  const historialChat = document.getElementById("historialChat");
  historialChat.innerHTML = ""; // Limpiar el historial antes de cargar nuevos mensajes

  // Llama al servidor para cargar los mensajes del grupo
  fetch(`cargar_historial.php?grupo=${grupo}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al cargar el historial de chat");
      }
      return response.text();
    })
    .then((data) => {
      historialChat.innerHTML = data; // Agregar los mensajes al historial
    })
    .catch((error) => {
      console.error("Error:", error);
      historialChat.innerHTML = "<p>Error al cargar el historial.</p>"; // Mensaje de error en el historial
    });
}

// Manejar el envío del formulario de mensaje
document.getElementById("formMensaje").addEventListener("submit", function (e) {
  e.preventDefault(); // Prevenir el envío por defecto del formulario

  const nombre = document.getElementById("nombreUsuario").value;
  const mensaje = document.getElementById("mensaje").value;
  const grupo = document.getElementById("chatGrupo").value;

  // Enviar el mensaje al servidor
  fetch("enviar_mensaje.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `nombre=${encodeURIComponent(nombre)}&mensaje=${encodeURIComponent(
      mensaje
    )}&grupo=${encodeURIComponent(grupo)}`,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Error al enviar el mensaje");
      }
      return response.text(); // Aquí puedes manejar la respuesta si es necesario
    })
    .then(() => {
      cargarHistorial(grupo); // Refresca el historial después de enviar un mensaje
      document.getElementById("mensaje").value = ""; // Limpia el campo de texto
    })
    .catch((error) => {
      console.error("Error:", error); // Log del error
    });
});
