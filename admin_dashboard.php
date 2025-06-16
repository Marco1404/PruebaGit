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

// Agregar un nuevo producto con imagen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nombre_producto = $_POST['nombre_producto'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Verificar si se ha seleccionado una imagen de la carpeta 'imagenes'
    if (isset($_POST['imagen']) && !empty($_POST['imagen'])) {
        $imagen_nombre = $_POST['imagen'];  // Obtener el nombre de la imagen seleccionada

        // Insertar los datos del producto y el nombre de la imagen en la base de datos
        $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $nombre_producto, $descripcion, $precio, $stock, $imagen_nombre); // Guardar solo el nombre de la imagen

        if ($stmt->execute()) {
            $mensaje = "Producto agregado exitosamente con imagen.";
        } else {
            $mensaje = "Error al agregar el producto.";
        }

        $stmt->close();
    } else {
        $mensaje = "Por favor, selecciona una imagen para el producto.";
    }
}

// Eliminar producto
if (isset($_GET['delete'])) {
    $id_producto = $_GET['delete'];
    $sql = "DELETE FROM productos WHERE id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_producto);

    if ($stmt->execute()) {
        $mensaje = "Producto eliminado exitosamente.";
    } else {
        $mensaje = "Error al eliminar el producto.";
    }

    $stmt->close();
}

// Obtener todos los productos
$sql = "SELECT id_producto, nombre, descripcion, precio, stock, imagen FROM productos";
$result = $conn->query($sql);

// Cerrar la conexión
$conn->close();

// Obtener la lista de imágenes disponibles en la carpeta 'imagenes'
$imagenes = array_diff(scandir('imagenes'), array('.', '..')); // Obtenemos los archivos de la carpeta 'imagenes'

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="admin-container">
    <h2>Bienvenido, <?php echo $_SESSION['nombre_cliente']; ?> - Panel de Administración</h2>

    <!-- Mostrar mensaje -->
    <?php if (isset($mensaje)) { echo "<p class='message'>$mensaje</p>"; } ?>

    <h3>Agregar un nuevo producto</h3>
    <form action="admin_dashboard.php" method="POST" class="dashboard-form">
        <label for="nombre_producto">Nombre del Producto:</label>
        <input type="text" name="nombre_producto" id="nombre_producto" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" required></textarea>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" required>

        <label for="stock">Stock:</label>
        <input type="number" name="stock" id="stock" required>

        <label for="imagen">Seleccionar Imagen del Producto:</label>
        <select name="imagen" id="imagen" required>
            <option value="">Selecciona una imagen</option>
            <?php foreach ($imagenes as $imagen) { ?>
                <option value="<?php echo $imagen; ?>"><?php echo $imagen; ?></option>
            <?php } ?>
        </select>

        <button type="submit" name="add_product" class="dashboard-btn">Agregar Producto</button>
    </form>

    <h3>Lista de Productos</h3>
    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['nombre']; ?></td>
                    <td><?php echo $row['descripcion']; ?></td>
                    <td><?php echo $row['precio']; ?></td>
                    <td><?php echo $row['stock']; ?></td>
                    <td><img src="imagenes/<?php echo $row['imagen']; ?>" alt="Imagen del producto" width="100"></td>
                    <td>
                        <a href="editar_producto.php?id=<?php echo $row['id_producto']; ?>" class="dashboard-btn-secondary">Editar</a>
                        <a href="admin_dashboard.php?delete=<?php echo $row['id_producto']; ?>" class="dashboard-btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?');">Eliminar</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h3>Gestión de Pedidos</h3>
    <a href="pedidos.php" class="dashboard-btn">Ver Pedidos</a>

    <a href="logout.php" class="dashboard-btn-logout">Cerrar sesión</a>
</div>

</body>
</html>
