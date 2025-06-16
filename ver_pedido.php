<?php
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

// Obtener el ID de la venta desde el parámetro de la URL
$id_venta = $_GET['id_venta'];

// Consulta para obtener los detalles del pedido junto con el teléfono del cliente
$sql = "SELECT p.nombre, dp.cantidad, p.precio, (dp.cantidad * p.precio) AS total_producto, c.telefono
        FROM detalles_venta dp
        JOIN productos p ON dp.id_producto = p.id_producto
        JOIN ventas v ON dp.id_venta = v.id_venta
        JOIN clientes c ON v.id_cliente = c.id_cliente
        WHERE dp.id_venta = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Pedido</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="admin-container">
    <h2>Detalles del Pedido #<?php echo $id_venta; ?></h2>

    <?php 
    // Obtener el teléfono del cliente desde el primer resultado de la consulta
    $row_cliente = $result->fetch_assoc();
    $telefono_cliente = $row_cliente['telefono']; 
    ?>
    
    <h3>Teléfono del Cliente: <?php echo $telefono_cliente ? $telefono_cliente : 'No disponible'; ?></h3>

    <table class="dashboard-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Reiniciar el puntero de los resultados para poder mostrar las filas
            $result->data_seek(0);
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <tr>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['cantidad']; ?></td>
                        <td><?php echo number_format($row['precio'], 2, ',', '.'); ?></td>
                        <td><?php echo number_format($row['total_producto'], 2, ',', '.'); ?></td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='4'>No hay productos en este pedido.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

</body>
</html>

<?php
// Cerrar la conexión
$conn->close();
?>
