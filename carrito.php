<?php
session_start();
include('header.php');

// Verificar si hay productos en el carrito
if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    echo "<h2>Tu Carrito</h2>";
    echo "<ul class='carrito-lista'>";

    foreach ($_SESSION['carrito'] as $index => $producto) {
        echo "<li class='carrito-item'>";
        echo "<img src='imagenes/{$producto['imagen']}' alt='{$producto['nombre']}' class='producto-imagen'>";
        echo "<div class='producto-info'>";
        echo "<h3>{$producto['nombre']}</h3>";
        echo "<p>Precio: $" . number_format($producto['precio'], 2) . "</p>";
        echo "<p>Cantidad: {$producto['cantidad']}</p>";
        echo "</div>";
        echo "<form action='eliminar_producto.php' method='POST' class='eliminar-form'>";
        echo "<input type='hidden' name='index' value='{$index}'>"; // Enviar el índice del producto para eliminarlo
        echo "<button type='submit' class='btn-eliminar'>Eliminar</button>";
        echo "</form>";
        echo "</li>";
    }

    echo "</ul>";

    // Mostrar botón para ir a la página de facturación
    echo "<form action='facturacion.php' method='GET'>
            <button type='submit' class='btn-primary'>Ir a Facturación</button>
          </form>";

} else {
    echo "<p>No tienes productos en el carrito.</p>";
}

include('footer.php');
?>
