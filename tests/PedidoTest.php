<?php
use PHPUnit\Framework\TestCase;

require_once 'src/Pedido.php';  // Asegúrate de que la ruta sea correcta

class PedidoTest extends TestCase
{
    public function testPrecioTotalSinDescuento()
    {
        $pedido = new Pedido();
        $pedido->agregarProducto('Camiseta', 20);
        $pedido->agregarProducto('Pantalón', 40);

        $this->assertEquals(60, $pedido->obtenerPrecioTotal());
    }

    public function testPrecioTotalConDescuento()
    {
        $pedido = new Pedido();
        $pedido->agregarProducto('Camiseta', 20);
        $pedido->agregarProducto('Pantalón', 40);
        $pedido->aplicarDescuento(10); // 10% de descuento

        $this->assertEquals(54, $pedido->obtenerPrecioTotal()); // 60 - 10% = 54
    }
}
