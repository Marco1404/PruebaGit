<?php

// Asegúrate de que no haya un espacio de nombres en esta clase si no usas autoloading.
class Auth {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($usuario, $clave) {
        $sql = "SELECT id_cliente, nombre, password, role FROM clientes WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($clave, $row['password'])) {
                // Guardar información de sesión y redirigir
                $_SESSION['id_cliente'] = $row['id_cliente'];
                $_SESSION['nombre_cliente'] = $row['nombre'];
                $_SESSION['role'] = $row['role'];

                // Cambiar la redirección a solo devolver la URL de destino
                return $_SESSION['role'] == 'admin' ? 'admin_dashboard.php' : 'index.php';
            }
        }
        return false;
    }
}
