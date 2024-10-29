<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php'); // Redirigir al index si no está autenticado
    exit();
}

include_once('codes/conexion.inc');

// Consulta para obtener los tickets abiertos
$query = "SELECT tickets.id, tickets.nombre_usuario, tickets.mensaje, tickets.estado, tickets.fecha, 
                 tickets.ip_usuario, tickets.nombre_equipo, tecnicos.username 
          FROM tickets 
          LEFT JOIN tecnicos ON tickets.tecnico_asignado = tecnicos.username"; // Cambiado para usar username

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

            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <?php if ($result->num_rows > 0): ?>
                    <table class="table table-bordered" id="tablaTickets">
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
                                    <td><?= !empty($row['username']) ? $row['username'] : 'Sin asignar' ?></td>
                                    <td>
                                        <form action="aceptar_ticket.php" method="POST" target="_blank"
                                            style="display:inline-block;">
                                            <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
                                            <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                                        </form>

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
            const table = $('#tablaTickets').DataTable(); // Inicializa DataTables

            // No es necesario hacer nada con el evento search.dt ya que DataTables maneja el filtrado automáticamente

            // Reemplazar la palabra 'Sin asignar' si el campo está vacío
            table.on('draw.dt', function() {
                const rows = table.rows().data();
                for (let i = 0; i < rows.length; i++) {
                    if (!rows[i][5]) { // Si 'username' está vacío
                        rows[i][5] = 'Sin asignar';
                    }
                }
            });
        });
    </script>
    <script>
        let ultimoNumeroTickets = 0;

        function cargarTickets() {
            fetch('obtener_tickets.php')
                .then(response => response.json())
                .then(data => {
                    console.log(data);
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
                        <form action="aceptar_ticket.php" method="POST" target="_blank" style="display:inline-block;">
                            <input type="hidden" name="ticket_id" value="${ticket.id}">
                            <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                        </form>
                        <a href="chat_ticket.php?ticket_id=${ticket.id}" target="_blank" class="btn btn-info">Ver Chat</a>
                    </td>
                `;
                        tabla.appendChild(fila);
                    });

                    // Reproducir sonido si hay un nuevo ticket
                    /* if (data.length > ultimoNumeroTickets) {
                         document.getElementById('sonidoNuevoTicket').play();
                    } */

                    // Actualizar el número de tickets
                    ultimoNumeroTickets = data.length;
                })
                .catch(error => {
                    console.error('Error al cargar los tickets:', error);
                    alert('Error al cargar los tickets. Por favor, intenta de nuevo más tarde.');
                });
        }

        // Cargar los tickets cada 10 segundos
        cargarTickets();
        setInterval(cargarTickets, 30000);
    </script>
</body>

</html>