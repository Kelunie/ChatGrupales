<?php
// Iniciar sesión para guardar el estado del usuario logueado
session_start();

// Incluir el archivo de conexión
include_once('codes/conexion.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar si el usuario existe
    $sql = "SELECT * FROM tecnicos WHERE username = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Obtener los datos del técnico
        $row = $result->fetch_assoc();

        // Verificar la contraseña (suponiendo que esté cifrada con password_hash)
        if (password_verify($password, $row['password'])) {
            // Autenticación exitosa
            $_SESSION['tecnico_id'] = $row['id'];
            $_SESSION['tecnico_username'] = $row['username'];

            // Redirigir a la página de tickets
            header("Location: ver_tickets.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
}

$conex->close();
?>