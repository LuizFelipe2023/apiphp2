<?php
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../config/Database.php';

class ProdutoController
{
    private $produto;

    public function __construct($conn)
    {
        $this->produto = new Produto($conn);
    }

    public function getAllProducts()
    {
        try {
            $result = $this->produto->getAllProducts();
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível carregar a lista de produtos. Por favor, tente novamente mais tarde."
            ]);
        }
    }


    public function insertProduct($nome, $valor, $categoria)
    {
        try {
            $result = $this->produto->insertProduct($nome, $valor, $categoria);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível inserir o produto. Verifique os dados e tente novamente."
            ]);
        }
    }

    public function insertProducts(array $produtos)
    {
        try {
            $result = $this->produto->insertProducts($produtos);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível inserir os produtos. Verifique os dados e tente novamente."
            ]);
        }
    }

    public function updateProduct($id, $nome, $valor, $categoria)
    {
        try {
            $result = $this->produto->updateProduct($id, $nome, $valor, $categoria);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível atualizar o produto. Verifique os dados e tente novamente."
            ]);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $result = $this->produto->deleteProduct($id);
            echo json_encode($result);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Não foi possível deletar o produto. Por favor, tente novamente mais tarde."
            ]);
        }
    }
}
