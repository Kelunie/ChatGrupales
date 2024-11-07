<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php'); // Redirigir al index si no está autenticado
    exit();
}

include_once('codes/conexion.inc');


// Consulta para obtener los tickets abiertos con el nombre de usuario del técnico asignado
$query = "SELECT tickets.id, tickets.nombre_usuario, tickets.mensaje, tickets.estado, tickets.fecha,
tickets.ip_usuario, tickets.nombre_equipo, tecnicos.username AS tecnico_asignado
FROM tickets
LEFT JOIN tecnicos ON tickets.tecnico_asignado = tecnicos.id";

$result = $conex->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <title>Gestión de Tickets</title>
    <link rel="stylesheet" href="codes/css/bootstrap.min.css" />
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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

        body {
            background-image: url("../ChatGrupales/codes/img/file.png");
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

        /* 
        .tablita {
            color: gold;
            background-color: black;
            border-color: white;
            border: white, 1px;
        }

        .filitas {
            color: gold;
            background-color: black;
        }

        .tablitadinamica {
            background-color: white;
            color: gold;
        }

        /* Cambiar el color del texto que indica la cantidad de entradas mostradas */
        /* 
        .dataTables_info {
            color: gold;
            /* Cambiar a azul (puedes elegir el color que desees) */
        /* } 

        .dataTables_filter label {
            color: black;
        }
        */
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
</head>

<body>
    <header>
        <?php include_once('codes/menu.inc'); ?>
        <div class="text-center mt-4">
            <button type="button" class="btn btn-warning"><a href="crear_tecnico.php">Crear Usuario</a></button>
        </div>
    </header>

    <main>
        <div class="container-fluid">
            <h1 style="color: gold;">Tickets de Soporte</h1>

            <div class="table-responsive tablitadinamica"
                style="max-height: 500px; overflow-y: 5000px; max-width: 10000px">
                <?php if ($result->num_rows > 0): ?>
                    <table class="display" id="tablaTickets">
                        <thead>
                            <tr>
                                <th class="filitas">ID</th>
                                <th class="filitas">Activo (IP)</th>
                                <th class="filitas">Usuario</th>
                                <th class="filitas">Estado</th>
                                <th class="filitas">Fecha</th>
                                <th class="filitas">Técnico Asignado</th>
                                <th class="filitas">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="tablita">
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="filitas">
                                    <td style="color: black;"><?= $row['id'] ?></td>
                                    <td class="filitas">
                                        <?= (!empty($row['nombre_equipo']) ? $row['nombre_equipo'] : 'Desconocido') . " (" . (!empty($row['ip_usuario']) ? $row['ip_usuario'] : 'IP desconocida') . ")" ?>
                                    </td>
                                    <td class="filitas"><?= $row['nombre_usuario'] ?></td>
                                    <td class="filitas"><?= $row['estado'] ?></td>
                                    <td class="filitas"><?= $row['fecha'] ?></td>
                                    <td class="filitas"><?= $row['tecnico_asignado'] ?: 'Sin asignar' ?></td>
                                    <td class="filitas">
                                        <?php if (empty($row['tecnico_asignado'])): // Verificar si no hay técnico asignado 
                                        ?>
                                            <form action="aceptar_ticket.php" method="POST" id="aceptarTicketForm_<?= $row['id'] ?>"
                                                style="display:inline-block;">
                                                <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-gold" disabled>Aceptar Ticket</button>
                                        <?php endif; ?>
                                        <a href="chat_ticket.php?ticket_id=<?= $row['id'] ?>" target="_blank"
                                            class="btn btn-info">Ver Chat</a>
                                        <button class="btn btn-gold info-btn" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa-regular fa-address-card" alt="Detalles"></i>
                                        </button>

                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay tickets abiertos.</p>
                <?php endif; ?>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel">Ticket Information</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Aquí se cargará la información del ticket -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>

    <footer>
        <?php include_once('codes/pie.inc'); ?>
    </footer>

    <script>
        $(document).ready(function() {
            let dataTable = $('#tablaTickets').DataTable({
                "pageLength": 5
            });

            // Cargar y actualizar los tickets cada 30 segundos
            function cargarTickets() {
                fetch('obtener_tickets.php')
                    .then(response => response.json())
                    .then(data => {
                        const tabla = document.getElementById('tablaTickets').getElementsByTagName('tbody')[0];
                        tabla.innerHTML = ''; // Limpiar la tabla

                        data.forEach(ticket => {
                            const fila = document.createElement('tr');
                            fila.innerHTML = `
                                <td>${ticket.id}</td>
                                <td>${ticket.nombre_equipo || 'Desconocido'} (${ticket.ip_usuario || 'IP desconocida'})</td>
                                <td>${ticket.mensaje}</td>
                                <td>${ticket.estado}</td>
                                <td>${ticket.fecha}</td>
                                <td>${ticket.username || 'Sin asignar'}</td>
                                <td>
                                    ${!ticket.username ? `
                                    <form action="aceptar_ticket.php" method="POST" id="aceptarTicketForm_${ticket.id}" style="display:inline-block;">
                                        <input type="hidden" name="ticket_id" value="${ticket.id}">
                                        <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                                    </form>` : `<button class="btn btn-secondary" disabled>Aceptar Ticket</button>`}
                                    <a href="chat_ticket.php?ticket_id=${ticket.id}" target="_blank" class="btn btn-info">Ver Chat</a>
                                    <button class="btn btn-gold info-btn" data-id="${ticket.id}"; ?>">
    <i class="fa-regular fa-address-card" alt="Detalles"></i>
</button>

                                </td>
                            `;
                            tabla.appendChild(fila);
                        });

                        // Destruir y volver a inicializar DataTable
                        dataTable.destroy();
                        dataTable = $('#tablaTickets').DataTable({
                            "pageLength": 5
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar los tickets:', error);
                        alert('Error al cargar los tickets. Por favor, intenta de nuevo más tarde.');
                    });
            }

            // Intervalo para recargar los tickets cada 30 segundos
            cargarTickets();
            setInterval(cargarTickets, 30000);

            // Manejo del envío del formulario para aceptar tickets mediante AJAX
            $(document).on('submit', 'form[id^="aceptarTicketForm_"]', function(event) {
                event.preventDefault();

                const form = $(this);
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        alert("Ticket aceptado");
                        cargarTickets(); // Recargar los tickets para reflejar el cambio
                    },
                    error: function(xhr, status, error) {
                        alert("Hubo un error al aceptar el ticket: " + error);
                    }
                });
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.info-btn', function() {
                const ticketId = $(this).data('id');

                $.ajax({
                    url: 'detalles_ticket.php',
                    method: 'POST',
                    data: {
                        ticket_id: ticketId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            $('#infoModal .modal-body').html('<p>Error: ' + data.error +
                                '</p>');
                        } else {
                            $('#infoModal .modal-body').html(`
                        <p><strong>ID:</strong> ${data.id}</p>
                        <p><strong>Equipo:</strong> ${data.nombre_equipo || 'Desconocido'}</p>
                        <p><strong>IP:</strong> ${data.ip_usuario || 'IP desconocida'}</p>
                        <p><strong>Usuario:</strong> ${data.nombre_usuario}</p>
                        <p><strong>Estado:</strong> ${data.estado}</p>
                        <p><strong>Fecha:</strong> ${data.fecha}</p>
                        <p><strong>Técnico Asignado:</strong> ${data.tecnico_asignado || 'Sin asignar'}</p>
                        <p><strong>Mensaje:</strong> ${data.mensaje}</p>
                    `);
                        }
                        $('#infoModal').modal('show');
                    },
                    error: function() {
                        alert('Error al obtener los detalles del ticket.');
                    }
                });
            });
        });
    </script>
    <!-- Bootstrap JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>