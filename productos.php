<?php
session_start();  // Inicia la sesión
include('header.php');  // Incluye el encabezado con la navegación
include('db.php');  // Conecta con la base de datos

// Consulta de productos
$sql = "SELECT * FROM productos WHERE stock > 0";
$result = $conn->query($sql);

// Verifica si la consulta retornó resultados
if ($result->num_rows > 0) {
    echo "<div class='productos-container'>";
    while ($row = $result->fetch_assoc()) {
        // Mostramos cada producto
        echo "<div class='producto'>";
        echo "<img src='imagenes/" . $row['imagen'] . "' alt='" . $row['nombre'] . "' class='producto-imagen'>";
        echo "<h3>" . $row['nombre'] . "</h3>";
        echo "<p>" . $row['descripcion'] . "</p>";
        echo "<p><strong>Precio: $" . number_format($row['precio'], 2) . "</strong></p>";
        
        // Formulario para agregar al carrito
        echo "<form action='agregar_carrito.php' method='POST'>
                <input type='hidden' name='id_producto' value='" . $row['id_producto'] . "'>
                <input type='number' name='cantidad' value='1' min='1' max='" . $row['stock'] . "'>
                <button type='submit' class='btn-primary'>Agregar al Carrito</button> <!-- Botón con clase btn-primary --> 
              </form>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<p>No hay productos disponibles en este momento.</p>";
}

include('footer.php');  // Incluye el pie de página
?>
    