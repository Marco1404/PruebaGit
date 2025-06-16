<?php
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

// Si se enviaron los datos del formulario, procesar la compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger los datos del formulario
    $nombre = $_POST['nombre'] ?? null;
    $email = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $direccion = $_POST['direccion'] ?? null;
    $metodo_pago = $_POST['metodo_pago'] ?? null;

    // Validar que los campos no estén vacíos
    if (empty($nombre) || empty($email) || empty($telefono) || empty($direccion)) {
        echo "<p style='color:red;'>Por favor completa todos los campos.</p>";
        exit;
    }
    if (empty($metodo_pago)) {
        echo "<p style='color:red;'>Por favor selecciona un método de pago.</p>";
        exit;
    }

    // Actualizar los datos del cliente en la base de datos
    $sql_actualizar_cliente = "UPDATE clientes SET nombre = ?, email = ?, telefono = ?, direccion = ? WHERE id_cliente = ?";
    $stmt_actualizar_cliente = $conn->prepare($sql_actualizar_cliente);
    $stmt_actualizar_cliente->bind_param("ssssi", $nombre, $email, $telefono, $direccion, $id_cliente);
    if (!$stmt_actualizar_cliente->execute()) {
        echo "<p style='color:red;'>Hubo un error al actualizar los datos del cliente. Intenta de nuevo.</p>";
        exit;
    }

    // Procesar la compra
    $total = 0;
    $productos = [];

    // Actualizar el stock de los productos
    $sql_update = "UPDATE productos SET stock = stock - ? WHERE id_producto = ?";
    $stmt_update = $conn->prepare($sql_update);

    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
        $productos[] = $producto;
        $stmt_update->bind_param("ii", $producto['cantidad'], $producto['id_producto']);
        $stmt_update->execute();
    }

    // Insertar la venta en la base de datos
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

    // Vaciar el carrito
    $sql_vaciar_carrito = "DELETE FROM carrito WHERE id_cliente = ?";
    $stmt_vaciar_carrito = $conn->prepare($sql_vaciar_carrito);
    $stmt_vaciar_carrito->bind_param("i", $id_cliente);
    $stmt_vaciar_carrito->execute();

    // Limpiar el carrito de la sesión
    unset($_SESSION['carrito']);

    // Mensaje de éxito
    echo "<p>Compra realizada con éxito. En breve nos pondremos en contacto contigo!
    ¡Gracias por tu compra!</p>";
    echo "<a href='index.php' class='btn-primary'>Volver a la tienda</a>";
    exit;
}

// Obtener los datos del cliente para rellenar el formulario
$sql_cliente = "SELECT nombre, email, telefono, direccion FROM clientes WHERE id_cliente = ?";
$stmt_cliente = $conn->prepare($sql_cliente);
$stmt_cliente->bind_param("i", $id_cliente);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();
$cliente = $result_cliente->fetch_assoc();

if (!$cliente) {
    echo "<p>Error: No se encontraron datos del cliente.</p>";
    exit;
}

// Calcular el total de la compra
$total = 0;
foreach ($_SESSION['carrito'] as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
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

        <!-- Mostrar el total de la compra -->
        <div class="total-compra">
            <h3>Total de la compra: $<?php echo number_format($total, 2); ?></h3>
        </div>

        <form action="facturacion.php" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required class="form-input" placeholder="Ingresa tu nombre">
            </div>

            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($cliente['email']); ?>" required class="form-input" placeholder="Ingresa tu correo electrónico">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>" required class="form-input" placeholder="Ingresa tu teléfono">
            </div>

            <div class="form-group">
                <label for="direccion">Dirección de Envío</label>
                <input type="text" name="direccion" id="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>" required class="form-input" placeholder="Ingresa tu dirección">
            </div>

            <div class="form-group">
                <label for="metodo_pago">Método de Pago</label>
                <select name="metodo_pago" id="metodo_pago" class="form-input" required>
                    <option value="">Selecciona un método de pago</option>
                    <option value="tarjeta" <?php echo (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] == 'tarjeta') ? 'selected' : ''; ?>>Tarjeta de Crédito/Débito</option>
                    <option value="paypal" <?php echo (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] == 'paypal') ? 'selected' : ''; ?>>PayPal</option>
                    <option value="transferencia" <?php echo (isset($_POST['metodo_pago']) && $_POST['metodo_pago'] == 'transferencia') ? 'selected' : ''; ?>>Transferencia Bancaria</option>
                </select>
            </div>

            <button type="submit" class="btn-primary">Finalizar Compra</button>
        </form>
    </div>
</body>
</html>
