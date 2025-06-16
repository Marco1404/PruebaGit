<?php

class Pedido
{
    private $productos;
    private $descuento;

    public function __construct()
    {
        $this->productos = [];
        $this->descuento = 0;
    }

    public function agregarProducto($producto, $precio)
    {
        $this->productos[] = ['producto' => $producto, 'precio' => $precio];
    }

    public function aplicarDescuento($porcentaje)
    {
        $this->descuento = $porcentaje;
    }

    public function obtenerPrecioTotal()
    {
        $total = 0;
        foreach ($this->productos as $producto) {
            $total += $producto['precio'];
        }
        $total -= $total * ($this->descuento / 100);
        return $total;
    }
}
