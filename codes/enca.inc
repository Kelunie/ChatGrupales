<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="chache-control" content="no-cache" />
<meta value="no-cache, no-store, must-revalidate" name="Cache-Control" />

<!-- Aquí va los CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
/>
<link rel="stylesheet" href="codes/css/stilos.css" />
<link rel="stylesheet" href="codes/css/bootstrap.min.css" />
<!-- CSS de Bootstrap -->
<link rel="stylesheet" href="../ChatGrupales/codes/css/stilos.css" />
<!-- Tu CSS personalizado -->

<!-- boostrap https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css -->
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
  crossorigin="anonymous"
/>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>

<!-- logo de la pagina -->
<link rel="icon" href="codes/img/logo.png" />

<!-- Colocar nombre de la página dinámicamente -->
<?php 
$page = basename($_SERVER['SCRIPT_NAME']); // Obtiene el nombre del archivo actual

if ($page == "gestionar_tickets.php") {
    echo "<title>Tickets</title>";
} elseif ($page == "chat_ticket.php" && isset($_GET['ticket_id'])) {
    $ticketId = htmlspecialchars($_GET['ticket_id']); // Sanitiza el ID del ticket para seguridad
    echo "<title>Soporte</title>";
} elseif ($page == "crear_tecnico.php"){
  echo "<title> Creación de Usuarios</title>";
} else {
    echo "<title>Chat Grupales CCSS</title>";
}
?>



<! -- font -->
<link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
/>
