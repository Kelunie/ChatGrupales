<?php
include_once('codes/conexion.inc');

$chat_grupo = $_GET['chat_grupo'];

$query = "SELECT nombre_usuario, mensaje, DATE_FORMAT(fecha, '%d-%m-%Y %H:%i:%s') as fecha FROM mensajes WHERE chat_grupo = ? ORDER BY fecha DESC";
$stmt = $conex->prepare($query);
$stmt->bind_param("s", $chat_grupo);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p style='border-bottom: 1px solid black;
  margin-bottom: 10px;'><strong>" . htmlspecialchars($row['nombre_usuario']) . "</strong> (" . $row['fecha'] . "): <br> " . htmlspecialchars($row['mensaje']) . "</p>";
}

$stmt->close();
$conex->close();
