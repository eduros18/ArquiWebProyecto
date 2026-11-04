<?php
require_once __DIR__ . '/../config/Database.php';

class Quote {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create(int $userId, array $data): bool {
        $sql = "INSERT INTO cotizaciones (usuario_id, titulo_obra, cantidad_estimada, acabado_portada, tipo_papel, fecha_requerida, comentarios_cliente, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'Pendiente')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $userId, $data['titulo_obra'], $data['cantidad_estimada'],
            $data['acabado_portada'], $data['tipo_papel'], $data['fecha_requerida'],
            $data['comentarios_cliente'] ?? ''
        ]);
    }

    public function getAll(?string $status = null): array {
        $sql = "SELECT c.*, u.nombre AS cliente_nombre, u.email AS cliente_email, u.institucion, a.nombre AS asesor_nombre 
                FROM cotizaciones c 
                JOIN usuarios u ON c.usuario_id = u.id 
                LEFT JOIN usuarios a ON c.asesor_id = a.id";
        $params = [];

        if (!empty($status)) {
            $sql .= " WHERE c.estado = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY c.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("SELECT c.*, a.nombre AS asesor_nombre FROM cotizaciones c LEFT JOIN usuarios a ON c.asesor_id = a.id WHERE c.usuario_id = ? ORDER BY c.id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT c.*, u.nombre AS cliente_nombre, u.email AS cliente_email, u.institucion, u.telefono, a.nombre AS asesor_nombre 
                                    FROM cotizaciones c 
                                    JOIN usuarios u ON c.usuario_id = u.id 
                                    LEFT JOIN usuarios a ON c.asesor_id = a.id 
                                    WHERE c.id = ?");
        $stmt->execute([$id]);
        $quote = $stmt->fetch();
        return $quote ?: null;
    }

    public function review(int $id, int $advisorId, float $quotedPrice, string $comments, string $status): bool {
        $sql = "UPDATE cotizaciones SET asesor_id = ?, precio_cotizado = ?, comentarios_asesor = ?, estado = ?, fecha_respuesta = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$advisorId, $quotedPrice, $comments, $status, $id]);
    }
}
