<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Usuario
{
    public $nome;
    public $email;
    private $password;
    private $cpf;
    private $endereco;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function setDetails($nome, $email, $cpf, $endereco)
    {
        $this->nome = $nome;
        $this->email = $email;
        $this->cpf = $cpf;
        $this->endereco = $endereco;
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->conn->prepare('SELECT id, nome, email, cpf, endereco FROM usuarios');
            $stmt->execute();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$usuarios) {
                return $this->errorResponse('Nenhum usuário foi encontrado no momento. Por favor, tente novamente mais tarde.');
            }

            return $usuarios;
        } catch (Exception $e) {
            return $this->errorResponse('Desculpe, ocorreu um erro ao buscar a lista de usuários. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }

    public function getUserById($id)
    {
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if ($id === false) {
            return [
                "status" => "error",
                "message" => 'ID inválido.'
            ];
        }
        try {
            $stmt = $this->conn->prepare('SELECT nome, email, cpf, endereco FROM usuarios WHERE id = ?');
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->execute([$id]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$usuario) {
                return $this->errorResponse('Nenhum usuario foi encontrado com esta id, por favor digite uma id válida');
            }
            return $usuario;
        } catch (Exception $e) {
            return $this->errorResponse('Desculpe, ocorreu um erro ao retornar o usuario solicitado. Por favor, tente novamente. Detalhes: ' . $e->getMessage());
        }
    }

    public function createUser($nome, $email, $password, $cpf, $endereco)
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->errorResponse("O endereço de e-mail fornecido não é válido.");
            }

            if (strlen($password) < 8) {
                return $this->errorResponse("A senha no mínimo deve conter 8 caracteres.");
            }

            $password_hashed = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare('INSERT INTO usuarios (nome, email, password, cpf, endereco) VALUES (?, ?, ?, ?, ?)');

            $stmt->bindParam(1, $nome, PDO::PARAM_STR);
            $stmt->bindParam(2, $email, PDO::PARAM_STR);
            $stmt->bindParam(3, $password_hashed, PDO::PARAM_STR);
            $stmt->bindParam(4, $cpf, PDO::PARAM_STR); 
            $stmt->bindParam(5, $endereco, PDO::PARAM_STR);

            $stmt->execute();

            return $this->successResponse("Usuário cadastrado com sucesso");
        } catch (PDOException $e) {
            return $this->errorResponse("Erro ao conectar com o banco de dados: " . $e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse("Ocorreu um erro inesperado: " . $e->getMessage());
        }
    }

    public function login($email, $password)
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->errorResponse("O endereço de e-mail fornecido não é válido.");
            }
            $stmt = $this->conn->prepare('SELECT id, nome, email, password, isAdmin FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $token = $this->generateToken($user['id'], $user['nome'], $user['email']);
                $_SESSION['token'] = $token;

                $isAdmin = (bool)$user['isAdmin'];
                $_SESSION['isAdmin'] = $isAdmin;

                return $this->successResponse("Login bem-sucedido", [
                    'token' => $token,
                    'isAdmin' => $isAdmin
                ]);
            } elseif (!$user) {
                return $this->errorResponse("Usuário não encontrado com o email fornecido.");
            } else {
                return $this->errorResponse("Senha incorreta. Verifique sua senha e tente novamente.");
            }
        } catch (PDOException $e) {
            return $this->errorResponse("Erro ao conectar com o banco de dados: " . $e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse("Ocorreu um erro inesperado: " . $e->getMessage());
        }
    }

    private function generateToken($userId, $userName, $userEmail)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $userId,
            'name' => $userName,
            'email' => $userEmail
        ];

        return JWT::encode($payload, $_ENV['MY_SECRET'], 'HS256');
    }

    public function requestPasswordReset($email)
    {
        try {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $this->errorResponse("O endereço de e-mail fornecido não é válido.");
            }
            $stmt = $this->conn->prepare('SELECT id, nome FROM usuarios WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $resetToken = bin2hex(random_bytes(32));
                $expiresAt = time() + 3600;
                $stmt = $this->conn->prepare('INSERT INTO password_resets (email, reset_token, expires_at) VALUES (?, ?, ?)');
                $stmt->execute([$email, $resetToken, date('Y-m-d H:i:s', $expiresAt)]);
                $resetLink = "https://example.com/reset_password.php?token=$resetToken";
                $this->sendResetEmail($email, $resetLink);
                return $this->successResponse("Instruções para redefinir sua senha foram enviadas para seu e-mail.");
            } else {
                return $this->errorResponse("E-mail não encontrado.");
            }
        } catch (PDOException $e) {
            return $this->errorResponse("Erro ao conectar com o banco de dados: " . $e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse("Ocorreu um erro inesperado: " . $e->getMessage());
        }
    }

    private function sendResetEmail($to, $resetLink)
    {
        $subject = "Redefinir Senha";
        $message = "Clique no link para redefinir sua senha: $resetLink";
        $headers = "From: no-reply@example.com\r\n";

        if (!mail($to, $subject, $message, $headers)) {
            throw new Exception("Erro ao enviar o e-mail de redefinição de senha.");
        }
    }

    public function resetPassword($token, $newPassword, $confirmPassword)
    {
        try {
            $stmt = $this->conn->prepare('SELECT email, expires_at FROM password_resets WHERE reset_token = ?');
            $stmt->execute([$token]);
            $reset = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($reset && strtotime($reset['expires_at']) > time()) {
                if ($newPassword === $confirmPassword) {
                    if (strlen($newPassword) < 8) {
                        return $this->errorResponse("A senha no mínimo deve conter 8 caracteres.");
                    }
                    $password_hashed = password_hash($newPassword, PASSWORD_BCRYPT);
                    $email = $reset['email'];

                    $stmt = $this->conn->prepare('UPDATE usuarios SET password = ? WHERE email = ?');
                    if ($stmt->execute([$password_hashed, $email])) {
                        $stmt = $this->conn->prepare('DELETE FROM password_resets WHERE reset_token = ?');
                        $stmt->execute([$token]);
                        return $this->successResponse("Senha atualizada com sucesso.");
                    } else {
                        return $this->errorResponse("Erro ao atualizar a senha. Por favor, tente novamente.");
                    }
                } else {
                    return $this->errorResponse("As senhas não coincidem.");
                }
            } else {
                return $this->errorResponse("Token inválido ou expirado.");
            }
        } catch (PDOException $e) {
            return $this->errorResponse("Erro ao conectar com o banco de dados: " . $e->getMessage());
        } catch (Exception $e) {
            return $this->errorResponse("Ocorreu um erro inesperado: " . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            if (isset($_SESSION['token'])) {
                unset($_SESSION['token']);
            }

            return $this->successResponse("Logout realizado com sucesso.");
        } catch (Exception $e) {
            return $this->errorResponse("Ocorreu um erro inesperado: " . $e->getMessage());
        }
    }

    private function errorResponse($message)
    {
        return ['status' => 'error', 'message' => $message];
    }

    private function successResponse($message, $data = [])
    {
        return ['status' => 'success', 'message' => $message, 'data' => $data];
    }
}
