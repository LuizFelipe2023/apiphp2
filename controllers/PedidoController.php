<?php

require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../config/Database.php';

class PedidoController
{
    private $pedido;

    public function __construct()
    {
        $database = new Database();
        $conn = $database->getConnection();
        $this->pedido = new Pedido($conn);
    }

    public function getAll()
    {
        try {
            $result = $this->pedido->getAll();
            return [
                "status" => "success",
                "data" => $result
            ];
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Erro ao buscar pedidos: " . $e->getMessage()
            ];
        }
    }

    public function getById($id)
    {
        try {
            $result = $this->pedido->getById($id);
            if (is_array($result)) {
                return [
                    "status" => "success",
                    "data" => $result
                ];
            } else {
                return [
                    "status" => "error",
                    "message" => $result
                ];
            }
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Erro ao buscar o pedido: " . $e->getMessage()
            ];
        }
    }

    public function criarPedido($data)
    {
        try {
            if (!is_array($data)) {
                throw new InvalidArgumentException('Os dados do pedido devem ser um array.');
            }

            $dataPedido = $data['data_pedido'] ?? null;
            $nomeCliente = $data['nome_cliente'] ?? null;
            $cpfCliente = $data['cpf_cliente'] ?? null;
            $enderecoCliente = $data['endereco_cliente'] ?? null;
            $produtos = $data['produtos'] ?? [];

            if (!$dataPedido || !$nomeCliente || !$cpfCliente || !$enderecoCliente || empty($produtos)) {
                throw new InvalidArgumentException('Os campos nome_cliente, cpf_cliente, endereco_cliente, data_pedido e produtos são obrigatórios.');
            }

            $this->pedido->setDetails($nomeCliente, $cpfCliente, $enderecoCliente, $dataPedido);
            $this->pedido->setProdutos($produtos);

            return $this->pedido->criarPedido();
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Erro ao criar o pedido: " . $e->getMessage()
            ];
        }
    }

    public function atualizarPedido($pedidoId, $data)
    {
        try {
            if (!is_array($data)) {
                throw new InvalidArgumentException('Os dados do pedido devem ser um array.');
            }

            $dataPedido = $data['data_pedido'] ?? null;
            $nomeCliente = $data['nome_cliente'] ?? null;
            $cpfCliente = $data['cpf_cliente'] ?? null;
            $enderecoCliente = $data['endereco_cliente'] ?? null;
            $produtos = $data['produtos'] ?? [];

            if (!$dataPedido || !$nomeCliente || !$cpfCliente || !$enderecoCliente || empty($produtos)) {
                throw new InvalidArgumentException('Os campos nome_cliente, cpf_cliente, endereco_cliente, data_pedido e produtos são obrigatórios.');
            }

            $this->pedido->setDetails($nomeCliente, $cpfCliente, $enderecoCliente, $dataPedido);
            $this->pedido->setProdutos($produtos);

            return $this->pedido->atualizarPedido($pedidoId);
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Erro ao atualizar o pedido: " . $e->getMessage()
            ];
        }
    }

    public function deletePedido($id)
    {
        try {
            $result = $this->pedido->deletePedido($id);
            return $result;
        } catch (Exception $e) {
            return [
                "status" => "error",
                "message" => "Erro ao apagar o pedido: " . $e->getMessage()
            ];
        }
    }
}
