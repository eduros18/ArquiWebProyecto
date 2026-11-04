<?php
require_once __DIR__ . '/../models/Quote.php';
require_once __DIR__ . '/../config/config.php';

class QuoteController {
    private Quote $quoteModel;

    public function __construct() {
        $this->quoteModel = new Quote();
    }

    public function create(): void {
        // Solo Clientes Institucionales o Mayoristas pueden solicitar pedidos especiales (RF-04.1)
        requireAuth(['institucional', 'admin']);
        require_once __DIR__ . '/../views/quotes/create.php';
    }

    public function store(): void {
        requireAuth(['institucional', 'admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = currentUser();
            
            // Sanitización estricta de entradas contra XSS
            $data = [
                'titulo_obra'          => sanitize(trim($_POST['titulo_obra'] ?? '')),
                'cantidad_estimada'    => (int)($_POST['cantidad_estimada'] ?? 0),
                'acabado_portada'      => sanitize(trim($_POST['acabado_portada'] ?? '')),
                'tipo_papel'           => sanitize(trim($_POST['tipo_papel'] ?? '')),
                'fecha_requerida'      => trim($_POST['fecha_requerida'] ?? ''),
                'comentarios_cliente'  => sanitize(trim($_POST['comentarios_cliente'] ?? ''))
            ];

            if ($this->quoteModel->create($user['id'], $data)) {
                header('Location: index.php?c=quote&a=myQuotes&msg=cotizacion_enviada');
            } else {
                header('Location: index.php?c=quote&a=create&error=error_al_enviar');
            }
            exit;
        }
    }

    public function myQuotes(): void {
        requireAuth(['institucional']);
        $user = currentUser();
        $quotes = $this->quoteModel->getByUser($user['id']);
        require_once __DIR__ . '/../views/quotes/my_quotes.php';
    }

    public function index(): void {
        // Bandeja de trabajo del Asesor de Ventas (RF-04.3 y RF-05.3)
        requireAuth(['asesor', 'admin']);
        $status = trim($_GET['estado'] ?? '');
        $quotes = $this->quoteModel->getAll(!empty($status) ? $status : null);
        require_once __DIR__ . '/../views/quotes/index.php';
    }

    public function review(): void {
        requireAuth(['asesor', 'admin']);
        $id = (int)($_GET['id'] ?? 0);
        $quote = $this->quoteModel->getById($id);
        if (!$quote) {
            header('Location: index.php?c=quote&a=index');
            exit;
        }
        require_once __DIR__ . '/../views/quotes/review.php';
    }

    public function processReview(): void {
        requireAuth(['asesor', 'admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['quote_id'];
            $price = (float)$_POST['precio_cotizado'];
            $comments = sanitize(trim($_POST['comentarios_asesor'] ?? ''));
            $status = trim($_POST['estado']);

            $user = currentUser();
            $this->quoteModel->review($id, $user['id'], $price, $comments, $status);
            header('Location: index.php?c=quote&a=index&msg=cotizacion_respondida');
            exit;
        }
    }
}
