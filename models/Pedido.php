<?php

class Pedido
{
    private $conn;
    private $produtos;
    private $nome;
    private $cpf;
    private $endereco;
    private $dataPedido;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function setDetails($nome, $cpf, $endereco, $dataPedido)
    {
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->endereco = $endereco;
        $this->dataPedido = $dataPedido;
    }

    public function setProdutos(array $produtos)
    {
        $this->produtos = $produtos;
    }

    public function adicionarProduto($produto)
    {
        $this->produtos[] = $produto;
    }

    public function removerProduto($produtoId)
    {
        foreach ($this->produtos as $index => $produto) {
            if ($produto['id'] === $produtoId) {
                unset($this->produtos[$index]);
                $this->produtos = array_values($this->produtos); // Reindex array
                return true;
            }
        }
        return false;
    }

    public function calcularValorTotal()
    {
        $valorTotal = 0;

        foreach ($this->produtos as $produto) {
            $stmt = $this->conn->prepare('SELECT valor_produto FROM produtos WHERE id = ?');
            $stmt->execute([$produto['id']]);
            $produtoDados = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produtoDados) {
                $valorTotal += $produtoDados['valor_produto'] * $produto['quantidade'];
            }
        }

        return $valorTotal;
    }

    public function criarPedido()
    {
        try {
            $this->conn->beginTransaction();
    
            $valorTotal = $this->calcularValorTotal();
    
            $produtosJson = json_encode($this->produtos);
    
            $stmt = $this->conn->prepare('INSERT INTO pedidos (nome_cliente, cpf_cliente, endereco_cliente, valor_total, data_pedido, produtos) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$this->nome, $this->cpf, $this->endereco, $valorTotal, $this->dataPedido, $produtosJson]);
    
            $pedidoId = $this->conn->lastInsertId();
    
            $this->conn->commit();
    
            return [
                "status" => "success",
                "message" => "Pedido criado com sucesso!",
                "data" => [
                    "pedido_id" => $pedidoId,
                    "valor_total" => number_format($valorTotal, 2, ',', '.')
                ]
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                "status" => "error",
                "message" => "Erro ao criar o pedido: " . $e->getMessage()
            ];
        }
    }

    public function atualizarPedido($pedidoId)
    {
        try {
            $this->conn->beginTransaction();
    
            $valorTotal = $this->calcularValorTotal();
    
            $produtosJson = json_encode($this->produtos);
    
            $stmt = $this->conn->prepare('UPDATE pedidos SET nome_cliente = ?, cpf_cliente = ?, endereco_cliente = ?, valor_total = ?, data_pedido = ?, produtos = ? WHERE id = ?');
            $stmt->execute([$this->nome, $this->cpf, $this->endereco, $valorTotal, $this->dataPedido, $produtosJson, $pedidoId]);
    
            $this->conn->commit();
    
            return [
                "status" => "success",
                "message" => "Pedido atualizado com sucesso!",
                "data" => [
                    "valor_total" => number_format($valorTotal, 2, ',', '.')
                ]
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                "status" => "error",
                "message" => "Erro ao atualizar o pedido: " . $e->getMessage()
            ];
        }
    }
    
    public function getAll()
    {
        try {
            $stmt = $this->conn->query('SELECT id, nome_cliente, cpf_cliente, endereco_cliente, valor_total, data_pedido FROM pedidos');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return "Erro ao buscar pedidos: " . $e->getMessage();
        }
    }
    
    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT id, nome_cliente, cpf_cliente, endereco_cliente, valor_total, data_pedido, produtos FROM pedidos WHERE id = ?');
            $stmt->execute([$id]);
    
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($pedido) {
                $pedido['produtos'] = json_decode($pedido['produtos'], true); 
                return $pedido;
            } else {
                return "Pedido não encontrado.";
            }
        } catch (Exception $e) {
            return "Erro ao buscar pedido: " . $e->getMessage();
        }
    }
    
    public function deletePedido($id)
    {
        try {
            $stmtDelete = $this->conn->prepare('DELETE FROM pedidos WHERE id = ?');
            if ($stmtDelete->execute([$id])) {
                return [
                    "status" => "success",
                    "message" => "Pedido apagado com sucesso."
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => "Não foi encontrado nenhum registro de um pedido."
                ];
            }
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Erro ao apagar o pedido: " . $e->getMessage()
            ];
        }
    }
}
