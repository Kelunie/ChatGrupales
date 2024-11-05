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

        .modal-perso {
            max-width: 50%;
            /* Ajusta el ancho al 90% de la pantalla */
            height: 100%;
            /* Ajusta la altura al 90% de la altura de la ventana */
        }

        body {
            background-image: url("../ChatGrupales/codes/img/fondo.jpg");
            /* Ruta actualizada desde la raíz del servidor */
            background-size: cover;
            /* Asegura que la imagen cubra toda la pantalla */
            background-repeat: no-repeat;
            /* Evita que la imagen se repita */
            background-attachment: fixed;
            /* Hace que la imagen de fondo se mantenga fija al hacer scroll */
            background-position: center;
            /* Centra la imagen en la página */
        }

        footer {
            background-color: black;
            /* Color de fondo suave */
            color: white;
            /* Color de texto */
            padding: 20px 0;
            /* Espaciado vertical */
            text-align: center;
            /* Centra el texto */
            border-top: 2px solid gold;
            /* Línea superior azul */
        }

        .btn-gold {
            background-color: gold;
            /* Fondo dorado */
            color: black;
            /* Letra negra */
            border: 1px solid gold;
            /* Borde del mismo color */
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            /* Bordes redondeados */
            transition: background-color 0.3s ease, color 0.3s ease;
            /* Transiciones suaves */
            position: relative;
            top: -13px;
        }

        .btn-gold:hover {
            background-color: white;
            /* Fondo blanco en hover */
            color: black;
            /* Letra negra */
            border: 1px solid black;
            /* Borde negro en hover */
        }


        .mi-btn2 {
            border: 1px;
            border-radius: 50%;
            background-color: gold;
        }

        .mi-btn2:hover {
            background-color: white;
            color: black;
        }

        .menuti {
            color: gold;
        }

        .menuti:hover {
            color: white;
        }

        .separador {
            /* vamos a agregar una linea negra para cada vez que llega un mensaje para diferenciarlos*/
            border-bottom: 1px solid black;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <header>
        <?php include_once('codes/menu.inc'); ?>
    </header>
    <main class="">
        <div class="container mt-5">
            <h2 style="color: gold;">Crear Usuario Técnico</h2>
            <form method="POST" action="crear_tecnico.php" onsubmit="return validarFormulario();">
                <div class="mb-2">
                    <label style="color: gold;" for="username" class="form-label">Nombre de usuario</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-4">
                    <label style="color: gold;" for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-gold">Crear Usuario</button>
            </form>
        </div>
    </main>
    <footer>
        <?php
        include_once('codes/pie.inc');
        ?>
    </footer>
    <script>
        // Función para validar el nombre de usuario
        function validarFormulario() {
            var username = document.getElementById("username").value.trim();
            if (username.length < 8) {
                alert("El nombre de usuario debe tener al menos 8 caracteres.");
                return false; // Evita el envío del formulario
            }
            return true; // Permite el envío del formulario
        }
    </script>
</body>

</html>