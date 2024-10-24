<?php
session_start();
if (!isset($_SESSION['tecnico'])) {
    header('Location: index.php'); // Redirigir al index si no está autenticado
    exit();
}

include_once('codes/conexion.inc');

// Consulta para obtener los tickets abiertos
$query = "SELECT id, nombre_usuario, mensaje, estado, fecha FROM tickets WHERE estado = 'abierto'";
$result = $conex->query($query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('codes/enca.inc');?>
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
<header>
    <?php include_once('codes/enca.inc');?>
    <link rel="stylesheet" href="codes/css/bootstrap.min.css" /> <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="codes/css/stilos.css" /> <!-- Tu CSS personalizado -->

</header>

<body>
    <header>
        <?php include_once('codes/menu.inc');?>
        <!-- Botón para crear un nuevo usuario técnico -->
        <div class="container text-center mt-4">
            <button type="button" class="btn btn-warning"><a href="crear_tecnico.php">Crear Usuario</a></button>
        </div>

    </header>
    <main>
        <div class="container-fluid">
            <h1>Tickets de Soporte</h1>

            <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
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
                        <td><?= $row['nombre_usuario'] ?></td>
                        <td><?= $row['mensaje'] ?></td>
                        <td><?= $row['estado'] ?></td>
                        <td><?= $row['fecha'] ?></td>
                        <td>
                            <form action="aceptar_ticket.php" method="POST">
                                <input type="hidden" name="ticket_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-primary">Aceptar Ticket</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No hay tickets abiertos.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <?php include_once('codes/pie.inc');?>
    </footer>
</body>

</html>