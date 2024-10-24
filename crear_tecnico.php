<?php
session_start();
include_once('codes/conexion.inc');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cifrar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO tecnicos (username, password) VALUES (?, ?)";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Usuario creado exitosamente.";
    } else {
        echo "Error al crear el usuario: " . $conex->error;
    }

    $stmt->close();
}

$conex->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('codes/enca.inc'); ?>
    <link rel="stylesheet" href="codes/css/bootstrap.min.css" />
</head>
<header>
    <?php include_once('codes/menu.inc'); ?>
</header>

<body>
    <div class="container mt-4">
        <h2>Crear Usuario Técnico</h2>
        <form method="POST" action="crear_tecnico.php">
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div>
</body>

</html>