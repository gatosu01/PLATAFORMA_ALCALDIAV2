<?php
namespace App\Models;

class Conexion {
    private $pdo;
    public function __construct() {
        $config = require __DIR__ . '/../../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};port=" . ($config['port'] ?? 3306);
        try {
            $this->pdo = new \PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("❌ Conexión fallida: " . $e->getMessage());
        }
    }
    public function getConexion() {
        return $this->pdo;
    }
}
