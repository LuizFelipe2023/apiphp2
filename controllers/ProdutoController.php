<?php

require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../config/Database.php';

class ProdutoController
{
    private $produto;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->produto = new Produto($this->conn);
    }

    public function getAllProducts()
    {
        $result = $this->produto->getAllProducts();
        return $result;
    }

    public function getProductById($id)
    {
        $result = $this->produto->getProductById($id);
        return $result;
    }

    public function insertProduct($nome, $valor, $categoria)
    {
        $result = $this->produto->insertProduct($nome, $valor, $categoria);
        return $result;
    }

    public function insertProducts(array $produtos)
    {
        $result = $this->produto->insertProducts($produtos);
        return $result;
    }

    public function updateProduct($id, $nome, $valor, $categoria)
    {
        $result = $this->produto->updateProduct($id, $nome, $valor, $categoria);
        return $result;
    }

    public function deleteProduct($id)
    {
        $result = $this->produto->deleteProduct($id);
        return $result;
    }
}
