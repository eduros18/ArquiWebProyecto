<?php
require_once __DIR__ . '/../config/Database.php';

class Book {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAll(array $filters = []): array {
        $sql = "SELECT l.*, c.nombre AS categoria_nombre 
                FROM libros l 
                JOIN categorias c ON l.categoria_id = c.id 
                WHERE l.activo = 1";
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND (l.titulo LIKE ? OR l.autor LIKE ? OR l.isbn LIKE ?)";
            $term = '%' . $filters['q'] . '%';
            $params = array_merge($params, [$term, $term, $term]);
        }

        if (!empty($filters['categoria_id'])) {
            $sql .= " AND l.categoria_id = ?";
            $params[] = $filters['categoria_id'];
        }

        if (!empty($filters['formato'])) {
            $sql .= " AND l.formato = ?";
            $params[] = $filters['formato'];
        }

        if (!empty($filters['precio_max'])) {
            $sql .= " AND l.precio_base <= ?";
            $params[] = $filters['precio_max'];
        }

        $sql .= " ORDER BY l.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getFeatured(): array {
        $stmt = $this->db->query("SELECT l.*, c.nombre AS categoria_nombre FROM libros l JOIN categorias c ON l.categoria_id = c.id WHERE l.destacado = 1 AND l.activo = 1 LIMIT 4");
        return $stmt->fetchAll();
    }

    public function getNewReleases(): array {
        $stmt = $this->db->query("SELECT l.*, c.nombre AS categoria_nombre FROM libros l JOIN categorias c ON l.categoria_id = c.id WHERE l.es_novedad = 1 AND l.activo = 1 LIMIT 4");
        return $stmt->fetchAll();
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT l.*, c.nombre AS categoria_nombre FROM libros l JOIN categorias c ON l.categoria_id = c.id WHERE l.id = ?");
        $stmt->execute([$id]);
        $book = $stmt->fetch();
        return $book ?: null;
    }

    public function create(array $data): bool {
        $sql = "INSERT INTO libros (categoria_id, titulo, autor, editorial, isbn, sinopsis, precio_base, stock, formato, anio_edicion, destacado, es_novedad, activo) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['categoria_id'], $data['titulo'], $data['autor'], $data['editorial'],
            $data['isbn'], $data['sinopsis'], $data['precio_base'], $data['stock'],
            $data['formato'] ?? 'Tapa blanda', $data['anio_edicion'],
            isset($data['destacado']) ? 1 : 0, isset($data['es_novedad']) ? 1 : 0
        ]);
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE libros SET categoria_id = ?, titulo = ?, autor = ?, editorial = ?, isbn = ?, sinopsis = ?, precio_base = ?, stock = ?, formato = ?, anio_edicion = ?, destacado = ?, es_novedad = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['categoria_id'], $data['titulo'], $data['autor'], $data['editorial'],
            $data['isbn'], $data['sinopsis'], $data['precio_base'], $data['stock'],
            $data['formato'], $data['anio_edicion'],
            isset($data['destacado']) ? 1 : 0, isset($data['es_novedad']) ? 1 : 0,
            $id
        ]);
    }

    public function softDelete(int $id): bool {
        // Eliminación lógica (baja lógica)
        $stmt = $this->db->prepare("UPDATE libros SET activo = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function searchAjax(string $term): array {
        $stmt = $this->db->prepare("SELECT id, titulo, autor, precio_base, formato FROM libros WHERE activo = 1 AND (titulo LIKE ? OR autor LIKE ? OR isbn LIKE ?) LIMIT 5");
        $like = '%' . $term . '%';
        $stmt->execute([$like, $like, $like]);
        return $stmt->fetchAll();
    }
}
