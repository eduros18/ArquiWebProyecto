<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';

class HomeController {
    private Book $bookModel;
    private Category $categoryModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
    }

    public function index(): void {
        $featured = $this->bookModel->getFeatured();
        $newReleases = $this->bookModel->getNewReleases();
        $categories = $this->categoryModel->getAll();

        require_once __DIR__ . '/../views/home/index.php';
    }

    public function catalog(): void {
        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'categoria_id' => !empty($_GET['categoria_id']) ? (int)$_GET['categoria_id'] : null,
            'formato' => trim($_GET['formato'] ?? ''),
            'precio_max' => !empty($_GET['precio_max']) ? (float)$_GET['precio_max'] : null
        ];

        $books = $this->bookModel->getAll($filters);
        $categories = $this->categoryModel->getAll();

        require_once __DIR__ . '/../views/home/catalog.php';
    }

    public function searchAjax(): void {
        header('Content-Type: application/json');
        $q = trim($_GET['term'] ?? '');
        if (strlen($q) < 2) {
            echo json_encode([]);
            exit;
        }

        $results = $this->bookModel->searchAjax($q);
        echo json_encode($results);
        exit;
    }
}
