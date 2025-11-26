<?php
use PHPUnit\Framework\TestCase;

// Load the file we are testing
require_once __DIR__ . '/../../src/Math.php';

class MathTest extends TestCase {

    public function testAddition() {

        echo "\n🧪 Running MathTest::testAddition...\n";

        $math = new Math();
        $result = $math->add(2, 3);

        echo "➡️  Asserting that 2 + 3 = 5...\n";

        $this->assertEquals(5, $result);
    }
}
   