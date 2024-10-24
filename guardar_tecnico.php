<?php
// Incluir el archivo de conexión
include_once('codes/conexion.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validar que el usuario y la contraseña no estén vacíos
    if (empty($username) || empty($password)) {
        echo "Usuario y contraseña son obligatorios.";
        exit();
    }

    // Hashear la contraseña antes de almacenarla
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Consulta para insertar el nuevo técnico
    $sql = "INSERT INTO tecnicos (username, password) VALUES (?, ?)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ss", $username, $passwordHash);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Técnico creado exitosamente.";
        // Redirigir o mostrar un mensaje de éxito
        header("Location: gestionar_tickets.php");
        exit();
    } else {
        echo "Error al crear el técnico: " . $stmt->error;
    }

    $stmt->close();
}

$conex->close();
?>