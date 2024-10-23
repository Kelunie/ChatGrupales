<?php
include_once('codes/conexion.inc');

$chat_grupo = $_GET['chat_grupo'];

$query = "SELECT nombre_usuario, mensaje, fecha FROM mensajes WHERE chat_grupo = ? ORDER BY fecha ASC";
$stmt = $conex->prepare($query);
$stmt->bind_param("s", $chat_grupo);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p><strong>" . htmlspecialchars($row['nombre_usuario']) . "</strong> (" . $row['fecha'] . "): " . htmlspecialchars($row['mensaje']) . "</p>";
}

$stmt->close();
$conex->close();
?>