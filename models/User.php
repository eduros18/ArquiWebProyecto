<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function authenticate(string $email, string $password): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol_nombre 
                                    FROM usuarios u 
                                    JOIN roles r ON u.rol_id = r.id 
                                    WHERE u.email = ? AND u.activo = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // No almacenar el hash en sesión
            return $user;
        }
        return null;
    }

    public function register(array $data): bool {
        $sql = "INSERT INTO usuarios (nombre, email, password, rol_id, telefono, institucion) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $hashed = password_hash($data['password'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['nombre'],
            $data['email'],
            $hashed,
            $data['rol_id'],
            $data['telefono'] ?? null,
            $data['institucion'] ?? null
        ]);
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT u.*, r.nombre AS rol_nombre 
                                    FROM usuarios u 
                                    JOIN roles r ON u.rol_id = r.id 
                                    WHERE u.id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        if ($user) unset($user['password']);
        return $user ?: null;
    }
}
