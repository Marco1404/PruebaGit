<?php
session_start();

// Obtener datos del producto
$id_producto = $_POST['id_producto'];
$cantidad = $_POST['cantidad'];

// Aquí iría la consulta a la base de datos para obtener los detalles del producto, por ejemplo:
include('db.php');
$sql = "SELECT * FROM productos WHERE id_producto = $id_producto";
$result = $conn->query($sql);
$producto = $result->fetch_assoc();

// Verificar si el producto ya está en el carrito
if (isset($_SESSION['carrito'])) {
    $existe = false;
    foreach ($_SESSION['carrito'] as &$producto_carrito) {
        if ($producto_carrito['id_producto'] == $id_producto) {
            $producto_carrito['cantidad'] += $cantidad;  // Sumar cantidad si ya existe
            $existe = true;
            break;
        }
    }

    // Si no existe, agregar el producto al carrito
    if (!$existe) {
        $_SESSION['carrito'][] = array(
            'id_producto' => $producto['id_producto'],
            'nombre' => $producto['nombre'],
            'descripcion' => $producto['descripcion'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'imagen' => $producto['imagen'],
            'stock' => $producto['stock']  // Incluimos el stock del producto
        );
    }
} else {
    // Si no hay carrito, crearlo e incluir el producto
    $_SESSION['carrito'] = array(
        array(
            'id_producto' => $producto['id_producto'],
            'nombre' => $producto['nombre'],
            'descripcion' => $producto['descripcion'],
            'precio' => $producto['precio'],
            'cantidad' => $cantidad,
            'imagen' => $producto['imagen'],
            'stock' => $producto['stock']  // Incluimos el stock del producto
        )
    );
}

// Redirigir al carrito
header('Location: carrito.php');
exit();
?>
