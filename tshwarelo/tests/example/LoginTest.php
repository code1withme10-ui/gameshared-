<?php
use PHPUnit\Framework\TestCase;

// Load login.php
require_once __DIR__ . '/../../include/auth/login.php';

class LoginTest extends TestCase {

    public function testValidLogin() {

        echo "\n🧪 Starting LoginTest::testValidLogin...";
        echo "\nNow asserting if username and password are correct... 🔍\n";

        $this->assertTrue(login("admin", "1234"));
    }

    public function testInvalidLogin() {

        echo "\n🧪 Starting LoginTest::testInvalidLogin...";
        echo "\nNow asserting invalid login attempt... ❌\n";

        $this->assertFalse(login("wrong", "nope"));
    }
}
  