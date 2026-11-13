<?php
require_once __DIR__ . '/../config/Database.php';

class Order {
    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    // Regla de Negocio: Cálculo de Descuentos e Impuestos en el Modelo
    public function calculateCartTotals(array $cartItems, string $userRole): array {
        $totalUnits = 0;
        $subtotalBase = 0.0;
        $items = [];

        foreach ($cartItems as $item) {
            $qty = (int)$item['cantidad'];
            $price = (float)$item['precio_base'];
            $lineTotal = $price * $qty;

            $totalUnits += $qty;
            $subtotalBase += $lineTotal;

            $item['line_subtotal'] = $lineTotal;
            $items[] = $item;
        }

        // 1. Descuento por Volumen (Unidades)
        $volPercent = 0.0;
        $volDesc = 'Sin descuento (1 a 5 unidades)';

        if ($totalUnits >= 6 && $totalUnits <= 20) {
            $volPercent = 0.05;
            $volDesc = '5% de descuento por volumen (6 a 20 unidades)';
        } elseif ($totalUnits >= 21 && $totalUnits <= 50) {
            $volPercent = 0.10;
            $volDesc = '10% de descuento por volumen (21 a 50 unidades)';
        } elseif ($totalUnits > 50) {
            $volPercent = 0.15;
            $volDesc = '15% de descuento por mayoreo (más de 50 unidades)';
        }

        $volMonto = round($subtotalBase * $volPercent, 2);
        $subtotalConVol = $subtotalBase - $volMonto;

        // 2. Descuento Institucional / Mayorista (8% acumulable)
        $instPercent = 0.0;
        $instMonto = 0.0;
        $instDesc = '0% adicional (Minorista / Estándar)';

        if ($userRole === 'institucional') {
            $instPercent = 0.08;
            $instMonto = round($subtotalConVol * $instPercent, 2);
            $instDesc = '8% adicional acumulativo (Cliente Institucional)';
        }

        $descuentoTotal = $volMonto + $instMonto;
        $subtotalNeto = $subtotalBase - $descuentoTotal;
        $impuestos = round($subtotalNeto * 0.12, 2); // IVA 12%
        $totalFinal = $subtotalNeto + $impuestos;

        return [
            'total_units' => $totalUnits,
            'subtotal_base' => $subtotalBase,
            'volumen_porcentaje' => $volPercent * 100,
            'volumen_monto' => $volMonto,
            'volumen_regla' => $volDesc,
            'institucional_porcentaje' => $instPercent * 100,
            'institucional_monto' => $instMonto,
            'institucional_regla' => $instDesc,
            'descuento_total' => $descuentoTotal,
            'subtotal_neto' => $subtotalNeto,
            'impuestos' => $impuestos,
            'tasa_impuesto' => '12% IVA',
            'total_final' => $totalFinal,
            'items' => $items
        ];
    }

    public function createOrder(int $userId, array $cartItems, string $userRole): int {
        $calc = $this->calculateCartTotals($cartItems, $userRole);

        $sql = "INSERT INTO ordenes 
                (usuario_id, subtotal_base, descuento_volumen_porcentaje, descuento_volumen_monto, 
                 descuento_institucional_porcentaje, descuento_institucional_monto, descuento_total, 
                 impuestos, total_final, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pendiente')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $userId,
            $calc['subtotal_base'],
            $calc['volumen_porcentaje'],
            $calc['volumen_monto'],
            $calc['institucional_porcentaje'],
            $calc['institucional_monto'],
            $calc['descuento_total'],
            $calc['impuestos'],
            $calc['total_final']
        ]);

        $orderId = (int)$this->db->lastInsertId();

        $stmtDetail = $this->db->prepare("INSERT INTO detalle_ordenes (orden_id, libro_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
        $stmtStock = $this->db->prepare("UPDATE libros SET stock = stock - ? WHERE id = ?");

        foreach ($calc['items'] as $item) {
            $stmtDetail->execute([$orderId, $item['id'], $item['cantidad'], $item['precio_base'], $item['line_subtotal']]);
            $stmtStock->execute([$item['cantidad'], $item['id']]);
        }

        return $orderId;
    }

    public function getByUser(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM ordenes WHERE usuario_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getAll(?string $status = null): array {
        $sql = "SELECT o.*, u.nombre AS usuario_nombre, u.email AS usuario_email, r.nombre AS rol_nombre 
                FROM ordenes o 
                JOIN usuarios u ON o.usuario_id = u.id 
                JOIN roles r ON u.rol_id = r.id";
        $params = [];

        if (!empty($status)) {
            $sql .= " WHERE o.estado = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY o.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getByIdWithDetails(int $orderId): ?array {
        $stmt = $this->db->prepare("SELECT o.*, u.nombre AS usuario_nombre, u.email AS usuario_email, u.institucion, r.nombre AS rol_nombre 
                                    FROM ordenes o 
                                    JOIN usuarios u ON o.usuario_id = u.id 
                                    JOIN roles r ON u.rol_id = r.id 
                                    WHERE o.id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        if (!$order) return null;

        $stmtDetails = $this->db->prepare("SELECT d.*, l.titulo, l.autor, l.formato FROM detalle_ordenes d JOIN libros l ON d.libro_id = l.id WHERE d.orden_id = ?");
        $stmtDetails->execute([$orderId]);
        $order['detalles'] = $stmtDetails->fetchAll();

        return $order;
    }

    public function updateStatus(int $orderId, string $status): bool {
        $stmt = $this->db->prepare("UPDATE ordenes SET estado = ? WHERE id = ?");
        return $stmt->execute([$status, $orderId]);
    }
}
