<?php 
// Inicia la sesión
session_start();

// Verificar si el usuario es administrador
if ($_SESSION['role'] != 'admin') {
    header('Location: acceso_denegado.php'); // Si no es admin, lo redirigimos
    exit();
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

// Obtener el id del producto a editar
if (isset($_GET['id'])) {
    $id_producto = $_GET['id'];
    $sql = "SELECT * FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
    $stmt->close();
}

// Actualizar el producto
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $imagen_nombre = $_POST['imagen'];

    // Actualizar producto en la base de datos
    $sql = "UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ?, imagen = ? WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisi", $nombre_producto, $descripcion, $precio, $stock, $imagen_nombre, $id_producto);

    if ($stmt->execute()) {
        $mensaje = "Producto actualizado correctamente.";
    } else {
        $mensaje = "Error al actualizar el producto.";
    }

    $stmt->close();
}

// Obtener la lista de imágenes disponibles en la carpeta 'imagenes'
$imagenes = array_diff(scandir('imagenes'), array('.', '..')); // Obtenemos los archivos de la carpeta 'imagenes'

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="admin-container">
    <h2>Editar Producto</h2>

    <!-- Mostrar mensaje -->
    <?php if (isset($mensaje)) { echo "<p class='message'>$mensaje</p>"; } ?>

    <form action="editar_producto.php?id=<?php echo $id_producto; ?>" method="POST" class="dashboard-form">
        <label for="nombre_producto">Nombre del Producto:</label>
        <input type="text" name="nombre_producto" id="nombre_producto" value="<?php echo $producto['nombre']; ?>" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required><?php echo $producto['descripcion']; ?></textarea>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" value="<?php echo $producto['precio']; ?>" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" value="<?php echo $producto['stock']; ?>" required>

        <label for="imagen">Seleccionar Imagen del Producto:</label>
        <select name="imagen" id="imagen" required>
            <option value="">Selecciona una imagen</option>
            <?php foreach ($imagenes as $imagen) { ?>
                <option value="<?php echo $imagen; ?>" <?php echo ($producto['imagen'] == $imagen) ? 'selected' : ''; ?>>
                    <?php echo $imagen; ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="update_product" class="dashboard-btn">Actualizar Producto</button>
    </form>

    <a href="admin_dashboard.php" class="dashboard-btn-secondary">Volver al Panel de Administración</a>
</div>

</body>
</html>
