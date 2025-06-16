<?php
session_start();

// Verificar si el índice del producto es válido
if (isset($_POST['index']) && isset($_SESSION['carrito'][$_POST['index']])) {
    // Eliminar el producto del carrito usando el índice
    unset($_SESSION['carrito'][$_POST['index']]);
    // Reindexar el carrito para que los índices se ajusten
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

// Redirigir de vuelta al carrito para mostrar los cambios
header('Location: carrito.php');
exit();
?>
