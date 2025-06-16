<?php

// Incluye el archivo de la clase Auth
require_once __DIR__ . '/../src/Auth.php';
 // Ajusta la ruta según corresponda

 use PHPUnit\Framework\TestCase;

 class AuthTestCase extends TestCase {
 
     protected $auth;
 
     protected function setUp(): void {
         // Simulamos la conexión a la base de datos
         $conn = new mysqli('localhost', 'root', '', 'tienda');
         $this->auth = new Auth($conn);
     }
 
     public function testLoginSuccess() {
         // Suponiendo que el usuario con email 'test@example.com' y contraseña 'password' existe
         $result = $this->auth->login('test@example.com', 'password');
         
         // Verificamos que la redirección es la esperada
         $this->assertEquals('index.php', $result);
     }
 
     public function testLoginFailure() {
         // Intentamos con credenciales incorrectas
         $result = $this->auth->login('test@example.com', 'wrongpassword');
         
         // Verificamos que la respuesta sea false
         $this->assertFalse($result);
     }
 
     public function testInvalidEmail() {
         // Intentamos con un email que no existe
         $result = $this->auth->login('invalid@example.com', 'password');
         
         // Verificamos que la respuesta sea false
         $this->assertFalse($result);
     }
 }
 