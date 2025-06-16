<?php
session_start();

$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "tienda";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $nombre = $_POST['nombre'];
    $clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM clientes WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $mensaje = "<div class='error-msg'>El correo ya está registrado.</div>";
    } else {
        $sql = "INSERT INTO clientes (email, nombre, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $email, $nombre, $clave);
        
        if ($stmt->execute()) {
            $mensaje = "<div class='success-msg'>Registro exitoso. <a href='login.php'>Inicia sesión</a>.</div>";
        } else {
            $mensaje = "<div class='error-msg'>Error al registrar: " . $stmt->error . "</div>";
        }
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
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="register-container">
    <h2>Regístrate</h2>

    <?php echo $mensaje; ?>

    <form action="register.php" method="POST">
        <label for="email">Correo electrónico:</label>
        <input type="email" name="email" required>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required>

        <label for="clave">Contraseña:</label>
        <input type="password" name="clave" required>

        <button type="submit" class="btn-primary">Registrar</button>
    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
</div>

</body>
</html>
    