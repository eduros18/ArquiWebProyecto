<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/config.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login(): void {
        if (currentUser()) {
            header('Location: index.php?c=home&a=index');
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function processLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->authenticate($email, $password);
            if ($user) {
                $_SESSION['usuario'] = $user;
                // Inicializar carrito si no existe
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                // Redirección inteligente según el rol
                if ($user['rol_nombre'] === 'admin') {
                    header('Location: index.php?c=book&a=index');
                } elseif ($user['rol_nombre'] === 'asesor') {
                    header('Location: index.php?c=quote&a=index');
                } else {
                    header('Location: index.php?c=home&a=catalog');
                }
                exit;
            } else {
                header('Location: index.php?c=auth&a=login&error=credenciales_invalidas');
                exit;
            }
        }
    }

    public function register(): void {
        if (currentUser()) {
            header('Location: index.php?c=home&a=index');
            exit;
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function processRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            
            if ($this->userModel->findByEmail($email)) {
                header('Location: index.php?c=auth&a=register&error=correo_ya_existe');
                exit;
            }

            $rolId = (int)($_POST['rol_id'] ?? 2);
            // Solo permitir minorista (2) o institucional (3) para registro público
            if (!in_array($rolId, [2, 3])) {
                $rolId = 2;
            }

            $data = [
                'nombre'      => trim($_POST['nombre'] ?? ''),
                'email'       => $email,
                'password'    => $_POST['password'] ?? '',
                'rol_id'      => $rolId,
                'telefono'    => trim($_POST['telefono'] ?? ''),
                'institucion' => trim($_POST['institucion'] ?? '')
            ];

            if ($this->userModel->register($data)) {
                header('Location: index.php?c=auth&a=login&msg=cuenta_creada');
            } else {
                header('Location: index.php?c=auth&a=register&error=error_al_registrar');
            }
            exit;
        }
    }

    public function logout(): void {
        unset($_SESSION['usuario']);
        session_destroy();
        header('Location: index.php?c=home&a=index&msg=sesion_cerrada');
        exit;
    }
}
