<?php

class Produto
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllProducts()
    {
        $query = "SELECT * FROM produtos";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductById($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return [
                "status" => "error",
                "message" => 'ID inválido.'
            ];
        }

        try {
            $stmt = $this->conn->prepare('SELECT * FROM produtos WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                return [
                    "status" => "error",
                    "message" => 'Não foi encontrado o produto selecionado.'
                ];
            }

            return [
                "status" => "success",
                "data" => $product
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => 'Erro ao acessar o banco de dados: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => 'Houve um erro ao recuperar o produto: ' . $e->getMessage()
            ];
        }
    }
    public function insertProduct($nome, $valor, $categoria)
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO produtos (nome_produto, valor_produto, categoria_produto) VALUES (?, ?, ?)');

            if ($stmt->execute([$nome, $valor, $categoria])) {
                return [
                    "status" => "success",
                    "message" => "Produto cadastrado com sucesso!"
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "Houve um erro! Verifique as informações digitadas."
                ];
            }
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Houve um erro ao cadastrar um novo produto: " . $e->getMessage()
            ];
        }
    }

    public function insertProducts(array $produtos)
    {
        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare('INSERT INTO produtos (nome_produto, valor_produto, categoria_produto) VALUES (?, ?, ?)');

            foreach ($produtos as $produto) {
                $stmt->execute([$produto['nome_produto'], $produto['valor_produto'], $produto['categoria_produto']]);
            }

            $this->conn->commit();
            return [
                "status" => "success",
                "message" => "Produtos cadastrados com sucesso!"
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                "status" => "error",
                "message" => "Houve um erro ao cadastrar os produtos: " . $e->getMessage()
            ];
        }
    }

    public function updateProduct($id, $nome, $valor, $categoria)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE produtos SET nome_produto = ?, valor_produto = ?, categoria_produto = ? WHERE id = ?');

            if ($stmt->execute([$nome, $valor, $categoria, $id])) {
                return [
                    "status" => "success",
                    "message" => "Produto atualizado com sucesso"
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "Houve um erro ao atualizar o produto"
                ];
            }
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => 'Houve um erro ao atualizar o produto: ' . $e->getMessage()
            ];
        }
    }

    public function deleteProduct($id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM produtos WHERE id = ?');
            if ($stmt->execute([$id])) {
                return [
                    "status" => "success",
                    "message" => "Produto apagado com sucesso"
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "Não foi encontrado produto"
                ];
            }
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Houve um erro ao apagar o produto: " . $e->getMessage()
            ];
        }
    }
}
