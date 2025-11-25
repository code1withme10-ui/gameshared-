<?php

// Simple login function for demonstration 😊
function login($username, $password) {

    echo "\n👀 login() function called...";
    echo "\nChecking credentials...\n";

    $validUser = "admin";     // hard-coded username
    $validPass = "1234";      // hard-coded password

    return ($username === $validUser && $password === $validPass);
}
