<?php
// Inicia la sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Conexión a la base de datos
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "tienda";  

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Procesar el formulario
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];

    // Modificación en la consulta SQL para incluir el role
    $sql = "SELECT id_cliente, nombre, password, role FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($clave, $row['password'])) {
            // Guarda el ID, nombre y el rol en la sesión
            $_SESSION['id_cliente'] = $row['id_cliente'];
            $_SESSION['nombre_cliente'] = $row['nombre'];
            $_SESSION['role'] = $row['role']; // Guardamos el rol del usuario

            // Redirige según el rol
            if ($_SESSION['role'] == 'admin') {
                header("Location: admin_dashboard.php"); // Página de administrador
            } else {
                header("Location: index.php"); // Página de cliente
            }
            exit();
        } else {
            $mensaje = "<p class='error-msg'>Contraseña incorrecta.</p>";
        }
    } else {
        $mensaje = "<p class='error-msg'>Usuario no encontrado.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    
    <!-- Mostrar mensajes de error -->
    <?php echo $mensaje; ?>

    <form action="login.php" method="POST">
        <label for="usuario">Correo Electrónico:</label>
        <input type="email" name="usuario" id="usuario" required>

        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" id="clave" required>

        <button type="submit" class="btn-primary">Iniciar sesión</button>
    </form>

    <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
</div>

</body>
</html>
