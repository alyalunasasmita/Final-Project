<?php
//connection between another file with .env file
namespace App;

class Env {

    private static ?array $data = null;

    public static function get(string $key, $default = null) {

        if (self::$data === null) {
            $envPath = __DIR__ . '/.env';

            if (!file_exists($envPath)) {
                throw new \Exception(".env file tidak ditemukan di: " . $envPath);
            }

            self::$data = parse_ini_file(
                $envPath,
                false,
                INI_SCANNER_RAW
            );
        }

        return self::$data[$key] ?? $default;
    }
}
