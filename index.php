<?php
session_start();  // Inicia la sesión
include('header.php');  // Incluye el encabezado con la navegación
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Tienda Web</title>
    <!-- Enlazar el archivo de estilos -->
    <link rel="stylesheet" href="css/estilos.css">  <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>

<!-- Contenido principal de la página -->
<main>
<div class="bienvenida">
    <div class="bienvenida-texto">
        <h1>Bienvenidos a BILLAR SC</h1>
        <p>Encuentra los mejores accesorios de billar al mejor precio</p>
        <a href="productos.php" class="btn-bienvenida">Ver Productos</a> <!-- Botón para ver productos -->
        <a href="contacto.php" class="btn-bienvenida">Contáctanos</a> <!-- Botón para contactar -->
    </div>
    <div class="bienvenida-imagen">
        <img src="imagenes/banner.png" alt="Accesorios de Billar">
    </div>
</div>


    <!-- Productos destacados -->
    <div class="productos-destacados">
        <h2>Productos Destacados</h2>
        <div class="productos-container">
            <?php
            // Agregar algunos productos destacados
            echo "<div class='producto'>";
            echo "<img src='imagenes/taco.jpg' alt='Producto 1' class='producto-imagen'>";
            echo "<h3>Producto 1</h3>";
            echo "<p>Taco de billar profesional.</p>";
            echo "<p><strong>Precio: $500.00</strong></p>";
            echo "<a href='productos.php' class='btn-secundario'>Ver más</a>";
            echo "</div>";
            
            echo "<div class='producto'>";
            echo "<img src='imagenes/tiza-roja.jpg' alt='Producto 2' class='producto-imagen'>";
            echo "<h3>Producto 2</h3>";
            echo "<p>Tiza roja clasica en China.</p>";
            echo "<p><strong>Precio: $50.00</strong></p>";
            echo "<a href='productos.php.php' class='btn-secundario'>Ver más</a>";
            echo "</div>";
            ?>
        </div>
    </div>
</main>

<?php
include('footer.php');  // Incluye el pie de página
?>

</body>
</html>
