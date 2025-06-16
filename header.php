<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda Web</title>
    <link rel="stylesheet" href="css/estilos.css"> <!-- Ruta al archivo de estilos -->
</head>
<body>
    <header>
        <nav class="navbar">
            <ul>
                <li><a href="index.php" class="<?= ($_SERVER['REQUEST_URI'] == '/index.php') ? 'active' : ''; ?>">Inicio</a></li>
                <li><a href="productos.php" class="<?= ($_SERVER['REQUEST_URI'] == '/productos.php') ? 'active' : ''; ?>">Productos</a></li>
                <li><a href="contacto.php" class="<?= ($_SERVER['REQUEST_URI'] == '/contacto.php') ? 'active' : ''; ?>">Contacto</a></li>
                <li><a href="carrito.php" class="<?= ($_SERVER['REQUEST_URI'] == '/carrito.php') ? 'active' : ''; ?>">Carrito</a></li>

                <?php if (isset($_SESSION['id_cliente'])): ?>
                    <li><a href="logout.php">Cerrar sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar sesión</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Aquí iría el contenido principal de la página -->
</body>
</html>
