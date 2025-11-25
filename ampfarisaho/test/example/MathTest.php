<?php
use PHPUnit\Framework\TestCase;

 
require_once __DIR__ . '/../../src/Math.php';

class MathTest extends TestCase
{
    public function testAddition()
    {
        $math = new Math();
        $result = $math->add(2, 3);

        $this->assertEquals(5, $result);
    }
}

