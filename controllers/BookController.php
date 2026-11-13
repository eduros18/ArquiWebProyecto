<?php
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../config/config.php';

class BookController {
    private Book $bookModel;
    private Category $categoryModel;

    public function __construct() {
        $this->bookModel = new Book();
        $this->categoryModel = new Category();
    }

    public function show(): void {
        $id = (int)($_GET['id'] ?? 0);
        $book = $this->bookModel->getById($id);

        if (!$book || $book['activo'] == 0) {
            header('Location: index.php?c=home&a=catalog&error=libro_no_encontrado');
            exit;
        }

        require_once __DIR__ . '/../views/books/show.php';
    }

    public function index(): void {
        requireAuth(['admin']);
        $books = $this->bookModel->getAll();
        require_once __DIR__ . '/../views/books/index.php';
    }

    public function create(): void {
        requireAuth(['admin']);
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/books/create.php';
    }

    public function store(): void {
        requireAuth(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'categoria_id' => (int)$_POST['categoria_id'],
                'titulo'       => trim($_POST['titulo']),
                'autor'        => trim($_POST['autor']),
                'editorial'    => trim($_POST['editorial']),
                'isbn'         => trim($_POST['isbn']),
                'sinopsis'     => trim($_POST['sinopsis']),
                'precio_base'  => (float)$_POST['precio_base'],
                'stock'        => (int)$_POST['stock'],
                'formato'      => trim($_POST['formato']),
                'anio_edicion' => (int)$_POST['anio_edicion'],
                'destacado'    => isset($_POST['destacado']),
                'es_novedad'   => isset($_POST['es_novedad']),
                'portada'      => 'book' . rand(1, 12) . '.svg'
            ];

            if ($this->bookModel->create($data)) {
                header('Location: index.php?c=book&a=index&msg=libro_creado');
            } else {
                header('Location: index.php?c=book&a=create&error=error_al_crear');
            }
            exit;
        }
    }

    public function edit(): void {
        requireAuth(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        $book = $this->bookModel->getById($id);
        if (!$book) {
            header('Location: index.php?c=book&a=index');
            exit;
        }
        $categories = $this->categoryModel->getAll();
        require_once __DIR__ . '/../views/books/edit.php';
    }

    public function update(): void {
        requireAuth(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)$_POST['id'];
            $data = [
                'categoria_id' => (int)$_POST['categoria_id'],
                'titulo'       => trim($_POST['titulo']),
                'autor'        => trim($_POST['autor']),
                'editorial'    => trim($_POST['editorial']),
                'isbn'         => trim($_POST['isbn']),
                'sinopsis'     => trim($_POST['sinopsis']),
                'precio_base'  => (float)$_POST['precio_base'],
                'stock'        => (int)$_POST['stock'],
                'formato'      => trim($_POST['formato']),
                'anio_edicion' => (int)$_POST['anio_edicion'],
                'destacado'    => isset($_POST['destacado']),
                'es_novedad'   => isset($_POST['es_novedad'])
            ];

            if ($this->bookModel->update($id, $data)) {
                header('Location: index.php?c=book&a=index&msg=libro_actualizado');
            } else {
                header('Location: index.php?c=book&a=edit&id=' . $id . '&error=error_al_actualizar');
            }
            exit;
        }
    }

    public function delete(): void {
        requireAuth(['admin']);
        $id = (int)($_GET['id'] ?? 0);
        // Eliminación lógica
        $this->bookModel->softDelete($id);
        header('Location: index.php?c=book&a=index&msg=libro_eliminado');
        exit;
    }
}
