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

// Consulta para obtener los pedidos (ventas) junto con los datos del cliente
$sql = "SELECT v.id_venta, v.total, v.fecha_venta, v.direccion, v.metodo_pago, c.nombre, c.email, c.telefono
        FROM ventas v
        JOIN clientes c ON v.id_cliente = c.id_cliente
        ORDER BY v.fecha_venta DESC";  // Ordenar por fecha de venta
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Pedidos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<div class="admin-container">
    <h2>Pedidos</h2>

    <table class="dashboard-table">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Nombre Completo</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Método de Pago</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Verificar si la consulta devuelve resultados
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
                    <tr>
                        <td><?php echo $row['id_venta']; ?></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['telefono']; ?></td>
                        <td><?php echo $row['direccion']; ?></td>
                        <td><?php echo $row['metodo_pago']; ?></td>
                        <td><?php echo number_format($row['total'], 2, ',', '.'); ?></td>
                        <td><?php echo date('d/m/Y H:i:s', strtotime($row['fecha_venta'])); ?></td>
                        <td>
                            <a href="ver_pedido.php?id_venta=<?php echo $row['id_venta']; ?>" class="dashboard-btn">Ver detalles</a>
                        </td>
                    </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='9'>No hay pedidos registrados.</td></tr>";
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
