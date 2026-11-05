<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold text-dark mb-0">Catálogo Bibliográfico</h2>
    </div>
    <div class="col-md-6">
        <!-- Buscador en tiempo real -->
        <div class="position-relative">
            <div class="input-group shadow-sm">
                <input type="text" id="liveSearchInput" class="form-control" 
                       placeholder="Buscar por título, autor, ISBN..." autocomplete="off">
            </div>
            <div id="liveSearchResults" class="list-group position-absolute w-100 shadow mt-1 d-none z-3"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Filtros -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm sticky-top" style="top: 85px;">
            <div class="card-header bg-white py-3 border-bottom">
                <h6 class="fw-bold mb-0 text-dark">Filtros</h6>
            </div>
            <div class="card-body">
                <form action="index.php" method="GET">
                    <input type="hidden" name="c" value="home">
                    <input type="hidden" name="a" value="catalog">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Palabra clave</label>
                        <input type="text" name="q" class="form-control form-control-sm" 
                               value="<?= sanitize($_GET['q'] ?? '') ?>" placeholder="Título, autor o ISBN">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Categoría / Género</label>
                        <select name="categoria_id" class="form-select form-select-sm">
                            <option value="">Todas las categorías</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $cat['id']) ? 'selected' : '' ?>>
                                    <?= sanitize($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Formato</label>
                        <select name="formato" class="form-select form-select-sm">
                            <option value="">Todos los formatos</option>
                            <option value="Tapa blanda" <?= (isset($_GET['formato']) && $_GET['formato'] == 'Tapa blanda') ? 'selected' : '' ?>>Tapa blanda</option>
                            <option value="Tapa dura" <?= (isset($_GET['formato']) && $_GET['formato'] == 'Tapa dura') ? 'selected' : '' ?>>Tapa dura</option>
                            <option value="E-book" <?= (isset($_GET['formato']) && $_GET['formato'] == 'E-book') ? 'selected' : '' ?>>E-book (Digital)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Precio Máximo (Q)</label>
                        <input type="number" name="precio_max" class="form-control form-control-sm" 
                               min="10" max="1000" step="10" 
                               value="<?= sanitize($_GET['precio_max'] ?? '') ?>" placeholder="Ej. 200">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-umes btn-sm fw-bold">
                            Aplicar Filtros
                        </button>
                        <a href="index.php?c=home&a=catalog" class="btn btn-outline-secondary btn-sm">
                            Limpiar Filtros
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de Libros -->
    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted small">Total: <strong><?= count($books) ?></strong> libros</span>
        </div>

        <?php if (empty($books)): ?>
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <h5 class="fw-bold">No se encontraron libros</h5>
                <p class="text-muted">Intenta cambiar los filtros o escribe un término diferente.</p>
                <a href="index.php?c=home&a=catalog" class="btn btn-outline-umes">Ver todo el catálogo</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($books as $b): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 shadow-sm book-card">
                            <div class="position-relative p-3 text-center bg-transparent">
                                <span class="position-absolute top-0 end-0 m-2 badge bg-secondary"><?= sanitize($b['formato']) ?></span>
                                <div class="book-cover-placeholder d-flex align-items-center justify-content-center mx-auto shadow-sm">
                                    <span class="text-muted small">Libro</span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <small class="text-muted text-uppercase fw-semibold mb-1"><?= sanitize($b['categoria_nombre']) ?></small>
                                <h6 class="card-title fw-bold text-dark mb-1 line-clamp-2"><?= sanitize($b['titulo']) ?></h6>
                                <p class="text-muted small mb-2">Por: <?= sanitize($b['autor']) ?></p>
                                <p class="text-muted small mb-3">
                                    <span class="badge <?= $b['stock'] > 10 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-dark' ?>">
                                        Stock: <?= $b['stock'] ?> uds
                                    </span>
                                </p>
                                <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                    <span class="fs-5 fw-bold text-umes"><?= formatMoney($b['precio_base']) ?></span>
                                    <a href="index.php?c=book&a=show&id=<?= $b['id'] ?>" class="btn btn-sm btn-umes">
                                        Ficha Técnica
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
