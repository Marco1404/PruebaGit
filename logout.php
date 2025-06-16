<?php
// Inicia la sesión solo si no está ya iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destruye todas las variables de sesión
session_unset(); 

// Destruye la sesión
session_destroy(); 

// Redirige al usuario a la página de inicio o login
header("Location: index.php");  // Cambia 'index.php' por la página que prefieras
exit();
?>
