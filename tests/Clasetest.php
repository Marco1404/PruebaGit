<?php
use PHPUnit\Framework\TestCase;

class Clasetest extends TestCase
{
    public function testSuma()
    {
        $a = 2;
        $b = 3;
        $this->assertEquals(5, $a + $b);
    }
}
