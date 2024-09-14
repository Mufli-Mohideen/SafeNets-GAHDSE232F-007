<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;

class EncryptionHelper {
    private static $encryptionKey;

    public static function initialize() {
        // Load the encryption key from the environment variable
        self::$encryptionKey = Key::loadFromAsciiSafeString($_ENV['ENCRYPTION_KEY']);
    }

    // Encrypt data
    public static function encrypt($data) {
        return Crypto::encrypt($data, self::$encryptionKey);
    }

    // Decrypt data
    public static function decrypt($data) {
        return Crypto::decrypt($data, self::$encryptionKey);
    }
}

// Initialize the encryption helper
EncryptionHelper::initialize();
?>
