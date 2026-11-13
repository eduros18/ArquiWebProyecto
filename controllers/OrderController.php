<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../config/config.php';

class OrderController {
    private Order $orderModel;

    public function __construct() {
        $this->orderModel = new Order();
    }

    public function checkout(): void {
        requireAuth(['minorista', 'institucional', 'admin']);
        $cartItems = $_SESSION['cart'] ?? [];

        if (empty($cartItems)) {
            header('Location: index.php?c=cart&a=index&error=carrito_vacio');
            exit;
        }

        $user = currentUser();
        $summary = $this->orderModel->calculateCartTotals($cartItems, $user['rol_nombre']);

        require_once __DIR__ . '/../views/cart/checkout.php';
    }

    public function process(): void {
        requireAuth(['minorista', 'institucional', 'admin']);
        $cartItems = $_SESSION['cart'] ?? [];

        if (empty($cartItems)) {
            header('Location: index.php?c=cart&a=index');
            exit;
        }

        $user = currentUser();
        try {
            $orderId = $this->orderModel->createOrder($user['id'], $cartItems, $user['rol_nombre']);
            $_SESSION['cart'] = []; // Vaciar carrito
            header('Location: index.php?c=order&a=show&id=' . $orderId . '&msg=orden_creada_exito');
        } catch (Exception $e) {
            header('Location: index.php?c=cart&a=checkout&error=error_procesar_orden');
        }
        exit;
    }

    public function myOrders(): void {
        requireAuth(['minorista', 'institucional']);
        $user = currentUser();
        $orders = $this->orderModel->getByUser($user['id']);
        require_once __DIR__ . '/../views/orders/index.php';
    }

    public function show(): void {
        requireAuth();
        $orderId = (int)($_GET['id'] ?? 0);
        $order = $this->orderModel->getByIdWithDetails($orderId);

        if (!$order) {
            header('Location: index.php?c=home&a=index&error=orden_no_encontrada');
            exit;
        }

        // Validar que el cliente solo vea sus propias órdenes (a menos que sea admin o asesor)
        $user = currentUser();
        if ($user['rol_nombre'] === 'minorista' || $user['rol_nombre'] === 'institucional') {
            if ($order['usuario_id'] != $user['id']) {
                header('Location: index.php?c=order&a=myOrders&error=acceso_no_autorizado');
                exit;
            }
        }

        require_once __DIR__ . '/../views/orders/detail.php';
    }

    public function monitor(): void {
        requireAuth(['admin', 'asesor']);
        $status = trim($_GET['estado'] ?? '');
        $orders = $this->orderModel->getAll(!empty($status) ? $status : null);
        require_once __DIR__ . '/../views/orders/monitor.php';
    }

    public function updateStatus(): void {
        requireAuth(['admin', 'asesor']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = (int)$_POST['order_id'];
            $newStatus = trim($_POST['estado']);
            $this->orderModel->updateStatus($orderId, $newStatus);
            header('Location: index.php?c=order&a=monitor&msg=estado_actualizado');
            exit;
        }
    }
}
