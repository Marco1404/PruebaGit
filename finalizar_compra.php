<?php
ob_start();
session_start();
include('db.php');

// Verificar si el carrito tiene productos
if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) === 0) {
    echo "<p>No tienes productos en el carrito.</p>";
    exit;
}

// Verificar si el usuario está logueado
$id_cliente = $_SESSION['id_cliente'] ?? null;
if (!$id_cliente) {
    echo "<p>Error: Debes iniciar sesión para finalizar la compra.</p>";
    exit;
}

// Si ya se enviaron los datos de facturación, procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $direccion = $_POST['direccion'] ?? null;
    $metodo_pago = $_POST['metodo_pago'] ?? null;

    // Validar que los campos no estén vacíos
    if (empty($direccion)) {
        echo "<p style='color:red;'>Por favor ingresa tu dirección de envío.</p>";
        exit;
    }
    if (empty($metodo_pago)) {
        echo "<p style='color:red;'>Por favor selecciona un método de pago.</p>";
        exit;
    }

    // Procesar la compra
    $total = 0;
    $productos = [];

    // Preparar la actualización de stock
    $sql_update = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
    $stmt_update = $conn->prepare($sql_update);

    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
        $productos[] = $producto;
        $stmt_update->bind_param("ii", $producto['cantidad'], $producto['id_producto']);
        $stmt_update->execute();
    }

    // Insertar la venta
    $sql_venta = "INSERT INTO ventas (id_cliente, total, direccion, metodo_pago) VALUES (?, ?, ?, ?)";
    $stmt_venta = $conn->prepare($sql_venta);
    $stmt_venta->bind_param("idss", $id_cliente, $total, $direccion, $metodo_pago);
    if (!$stmt_venta->execute()) {
        echo "<p style='color:red;'>Hubo un error al procesar la venta. Intenta de nuevo.</p>";
        exit;
    }
    $id_venta = $stmt_venta->insert_id;

    // Insertar los detalles de la venta
    $sql_detalle = "INSERT INTO detalles_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
    $stmt_detalle = $conn->prepare($sql_detalle);

    foreach ($productos as $producto) {
        $stmt_detalle->bind_param("iiid", $id_venta, $producto['id_producto'], $producto['cantidad'], $producto['precio']);
        $stmt_detalle->execute();
    }

    // Vaciar el carrito en la base de datos
    $sql_vaciar_carrito = "DELETE FROM carrito WHERE id_cliente = ?";
    $stmt_vaciar_carrito = $conn->prepare($sql_vaciar_carrito);
    $stmt_vaciar_carrito->bind_param("i", $id_cliente);
    $stmt_vaciar_carrito->execute();

    // Limpiar el carrito de la sesión
    unset($_SESSION['carrito']);

    // Mensaje de éxito con diseño
    echo "<div style='padding: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin: 20px 0;'>";
    echo "<p style='font-size: 18px; font-weight: bold;'>Compra realizada con éxito.</p>";
    echo "<p style='font-size: 16px;'>En breve nos pondremos en contacto contigo.</p>";
    echo "<p style='font-size: 16px;'>¡Gracias por tu compra!</p>";
    echo "<a href='index.php' class='btn-primary' style='display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Volver a la tienda</a>";
    echo "</div>";
    
    ob_end_flush(); // Asegurar que el buffer de salida se envíe
    exit;
}
?>


<!-- Formulario de datos de facturación y pago -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos de Facturación</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="facturacion-container">
        <h2>Datos de Facturación</h2>
        <form action="finalizar_compra.php" method="POST">
            <div class="form-group">
                <label for="direccion">Dirección de Envío</label>
                <input type="text" name="direccion" id="direccion" required class="form-input" placeholder="Ingresa tu dirección">
            </div>

            <div class="form-group">
                <label for="metodo_pago">Método de Pago</label>
                <select name="metodo_pago" id="metodo_pago" class="form-input" required>
                    <option value="">Selecciona un método de pago</option>
                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                    <option value="paypal">PayPal</option>
                    <option value="transferencia">Transferencia Bancaria</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">Finalizar Compra</button>
        </form>
    </div>
</body>
</html>
