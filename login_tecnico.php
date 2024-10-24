<?php
session_start();

// Conectar a la base de datos
include_once('codes/conexion.inc');

// Inicializar variables
$autenticacion_exitosa = false;
$isAdmin = false;

// Verificar si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar la base de datos para verificar las credenciales
    $stmt = $conex->prepare("SELECT password FROM tecnicos WHERE username = ?"); // Cambiado de $conn a $conex
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Verificar si se encontró un usuario
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($password, $hashed_password)) {
            $autenticacion_exitosa = true;
        }
    }

    $stmt->close();
}

// Manejar la respuesta de autenticación
if ($autenticacion_exitosa) {
    $_SESSION['tecnico'] = true;
    $_SESSION['username'] = $username; // Guardar el nombre de usuario en la sesión
    $_SESSION['is_admin'] = 1; // Indicar que el usuario es administrador
    echo json_encode(['success' => true, 'redirect' => 'gestionar_tickets.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Credenciales incorrectas']);
}

$conex->close(); // Cambiado de $conn a $conex
exit;
?>