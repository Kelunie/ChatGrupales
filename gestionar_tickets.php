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
    <style>
        .btn-warning a {
            text-decoration: none;
            color: inherit;
        }

        .btn-warning:hover {
            background-color: #f8b400;
            border-color: #f8b400;
        }

        .btn-info a {
            text-decoration: none;
            color: inherit;
        }
    </style>
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
        <div class="container-md">
            <h1>Tickets de Soporte</h1>

            <div class="table-responsive" style="max-height: auto; overflow-y: auto;">
                <?php if ($result->num_rows > 0): ?>
                    <table class="display" id="tablaTickets">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Activo (IP)</th>
                                <th>Mensaje</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Técnico Asignado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= (!empty($row['nombre_equipo']) ? $row['nombre_equipo'] : 'Desconocido') . " (" . (!empty($row['ip_usuario']) ? $row['ip_usuario'] : 'IP desconocida') . ")" ?>
                                    </td>
                                    <td><?= $row['mensaje'] ?></td>
                                    <td><?= $row['estado'] ?></td>
                                    <td><?= $row['fecha'] ?></td>
                                    <td><?= $row['tecnico_asignado'] ?: 'Sin asignar' ?></td>
                                    <td>
                                        <?php if (empty($row['tecnico_asignado'])): // Verificar si no hay técnico asignado 
                                        ?>
                                            <form action="aceptar_ticket.php" method="POST" id="aceptarTicketForm_<?= $row['id'] ?>"
                                                style="display:inline-block;">
                                                <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
                                                <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-secondary" disabled>Aceptar Ticket</button>
                                        <?php endif; ?>
                                        <a href="chat_ticket.php?ticket_id=<?= $row['id'] ?>" target="_blank"
                                            class="btn btn-info">Ver Chat</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No hay tickets abiertos.</p>
                <?php endif; ?>
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
    </script>
</body>

</html>