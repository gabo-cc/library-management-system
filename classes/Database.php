<?php

class Database
{
    private $host = 'localhost';
    private $db_name = 'biblioteca';
    private $username = 'root';
    private $password = '';
    public $conn;

    // Método para obtener la conexión a la base de datos
    public function getConnection()
    {
        $this->conn = null;

        // TODO: Implementar la conexión a la base de datos utilizando PDO

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
        } catch (PDOException $e) {
            echo "Error de conexion: " . $e->getMessage();
        }

        return $this->conn;
    }
}
