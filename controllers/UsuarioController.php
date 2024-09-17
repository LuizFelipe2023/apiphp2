<<?php

    require_once __DIR__ . '/../config/Database.php';
    require_once __DIR__ . '/../models/Usuario.php';

    class UsuarioController
    {
        private $usuario;

        public function __construct($conn)
        {
            $this->usuario = new Usuario($conn);
        }

        public function criarUsuario($nome, $email, $password, $cpf, $endereco)
        {
            $result = $this->usuario->createUser($nome, $email, $password, $cpf, $endereco);
            echo json_encode($result);
        }

        public function login($email, $password)
        {
            $result = $this->usuario->login($email, $password);
            echo json_encode($result);
        }

        public function solicitarRedefinicaoSenha($email)
        {
            $result = $this->usuario->requestPasswordReset($email);
            echo json_encode($result);
        }

        public function redefinirSenha($token, $novaSenha, $confirmacaoSenha)
        {
            $result = $this->usuario->resetPassword($token, $novaSenha, $confirmacaoSenha);
            echo json_encode($result);
        }

        public function logout()
        {
            $result = $this->usuario->logout();
            echo json_encode($result);
        }
    }
