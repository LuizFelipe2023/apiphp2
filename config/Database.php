<?php

class Database
{
    private $host = 'localhost';  
    private $dbname = 'cardapio';  
    private $username = 'root';    
    private $password = '';      
    private $conn;               

    public function getConnection()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
        }

        return $this->conn;
    }
}

?>
