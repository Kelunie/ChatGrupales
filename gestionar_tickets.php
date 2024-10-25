<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php'); // Redirigir al index si no está autenticado
    exit();
}

include_once('codes/conexion.inc');

// Consulta para obtener los tickets abiertos
$query = "SELECT id, nombre_usuario, mensaje, estado, fecha, ip_usuario, nombre_equipo FROM tickets WHERE estado = 'abierto'";
$result = $conex->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <title>Gestión de Tickets</title>
    <link rel="stylesheet" href="codes/css/bootstrap.min.css" />
    <?php if ($_SERVER['PHP_SELF'] == '/gestionar_tickets.php'): ?>
    <title>Gestión de tickets</title>
    <?php else: ?>
    <title>Chat Grupales CCSS</title>
    <?php endif; ?>

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
    </style>

</head>

<body>
    <header>
        <?php include_once('codes/menu.inc'); ?>
        <!-- Botón para crear un nuevo usuario técnico -->
        <div class="text-center mt-4">
            <button type="button" class="btn btn-warning"><a href="crear_tecnico.php">Crear Usuario</a></button>
        </div>

    </header>
    <main>
        <div class="container-md">
            <h1>Tickets de Soporte</h1>

            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Activo (IP)</th>
                            <th>Mensaje</th>
                            <th>Estado</th>
                            <th>Fecha</th>
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
                            <td>
                                <form action="aceptar_ticket.php" method="POST" target="_blank"
                                    style="display:inline-block;">
                                    <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
                                    <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                                </form>

                                <!-- Enlace para abrir el chat del ticket -->
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
</body>

</html>