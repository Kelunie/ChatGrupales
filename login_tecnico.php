<?php
session_start();
header('Content-Type: application/json'); // Aseguramos salida en JSON

// Conectar a la base de datos
include_once('codes/conexion.inc');

// Inicializar variables
$autenticacion_exitosa = false;
$mensaje = 'Credenciales incorrectas';

// Verificar si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Preparar la consulta para verificar las credenciales
        $stmt = $conex->prepare("SELECT password FROM tecnicos WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Verificar si se encontró un usuario y comprobar la contraseña
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $autenticacion_exitosa = true;
                $_SESSION['tecnico'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['is_admin'] = 1; // Indicar que el usuario es administrador
                $mensaje = ''; // Sin mensaje de error si la autenticación es exitosa
            }
        }
        $stmt->close();
    }
}

// Respuesta JSON
echo json_encode([
    'success' => $autenticacion_exitosa,
    'redirect' => $autenticacion_exitosa ? 'gestionar_tickets.php' : null,
    'message' => $mensaje
]);

$conex->close();
exit;
