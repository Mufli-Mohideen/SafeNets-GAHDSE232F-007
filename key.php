<?php
require_once 'vendor/autoload.php'; // Ensure you have the autoload file included

use Defuse\Crypto\Key;

// Generate a new random key
$key = Key::createNewRandomKey();
$keyString = $key->saveToAsciiSafeString();

// Output the key string to use in your .env file
echo $keyString;
