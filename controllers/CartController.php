<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../config/config.php';

class CartController {
    private Book $bookModel;
    private Order $orderModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->orderModel = new Order();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index(): void {
        $cartItems = $_SESSION['cart'];
        $user = currentUser();
        $userRole = $user ? $user['rol_nombre'] : 'visitante';

        // Lógica de cálculo en el Modelo
        $summary = $this->orderModel->calculateCartTotals($cartItems, $userRole);

        require_once __DIR__ . '/../views/cart/index.php';
    }

    public function add(): void {
        $bookId = (int)($_POST['book_id'] ?? 0);
        $qty = max(1, (int)($_POST['cantidad'] ?? 1));

        $book = $this->bookModel->getById($bookId);
        if (!$book || $book['stock'] < 1) {
            header('Location: index.php?c=home&a=catalog&error=stock_no_disponible');
            exit;
        }

        if (isset($_SESSION['cart'][$bookId])) {
            $_SESSION['cart'][$bookId]['cantidad'] += $qty;
        } else {
            $_SESSION['cart'][$bookId] = [
                'id'          => $book['id'],
                'titulo'      => $book['titulo'],
                'autor'       => $book['autor'],
                'precio_base' => (float)$book['precio_base'],
                'portada'     => $book['portada'],
                'formato'     => $book['formato'],
                'stock'       => $book['stock'],
                'cantidad'    => $qty
            ];
        }

        header('Location: index.php?c=cart&a=index&msg=item_agregado');
        exit;
    }

    public function update(): void {
        $bookId = (int)($_POST['book_id'] ?? 0);
        $qty = (int)($_POST['cantidad'] ?? 1);

        if (isset($_SESSION['cart'][$bookId])) {
            if ($qty <= 0) {
                unset($_SESSION['cart'][$bookId]);
            } else {
                $_SESSION['cart'][$bookId]['cantidad'] = $qty;
            }
        }

        header('Location: index.php?c=cart&a=index');
        exit;
    }

    public function remove(): void {
        $bookId = (int)($_GET['id'] ?? 0);
        if (isset($_SESSION['cart'][$bookId])) {
            unset($_SESSION['cart'][$bookId]);
        }
        header('Location: index.php?c=cart&a=index&msg=item_removido');
        exit;
    }

    public function clear(): void {
        $_SESSION['cart'] = [];
        header('Location: index.php?c=cart&a=index');
        exit;
    }
}
