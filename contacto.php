<?php
include('header.php');
?>

<h1>Contáctanos</h1>
<p>Si tienes alguna pregunta, no dudes en ponerte en contacto con nosotros:</p>
<p>Correo electrónico: contacto@billarsc.com</p>

<?php
// Variables para el mensaje de confirmación
$mensaje_enviado = false;

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $mensaje = $_POST['mensaje'];

    // Aquí puedes procesar el mensaje (enviar el correo o almacenarlo en la base de datos)
    // Para este ejemplo, solo vamos a mostrar un mensaje de confirmación
    $mensaje_enviado = true;
}
?>

<!-- Formulario de contacto -->
<form action="contacto.php" method="POST">
    <label for="nombre">Tu nombre:</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="correo">Tu correo:</label>
    <input type="email" id="correo" name="correo" required>

    <label for="mensaje">Tu mensaje:</label>
    <textarea id="mensaje" name="mensaje" required></textarea>

    <button type="submit">Enviar mensaje</button>
</form>

<!-- Mostrar mensaje de confirmación si se envió el formulario -->
<?php if ($mensaje_enviado): ?>
    <div class="confirmacion">
        <p>¡Gracias por tu mensaje! Nos pondremos en contacto contigo pronto.</p>
    </div>
<?php endif; ?>

<?php
// Incluir footer.php
include('footer.php');
?>
