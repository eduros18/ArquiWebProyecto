<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Hero Promocional -->
<div class="p-5 mb-4 rounded-3 hero-banner text-white shadow">
    <div class="container-fluid py-4">
        <span class="badge bg-warning text-dark px-3 py-2 mb-3 fw-bold">
            Catálogo Editorial Multicanal
        </span>
        <h1 class="display-5 fw-bold">Fondos Bibliográficos & Ediciones Académicas</h1>
        <p class="col-md-9 fs-5 text-light opacity-90">
            Publicaciones universitarias, técnicas y literarias. 
            Contamos con precios especiales por volumen para colegios y universidades, 
            e impresiones bajo demanda gestionadas por asesores comerciales.
        </p>
        <div class="d-flex flex-wrap gap-2 mt-4">
            <a href="index.php?c=home&a=catalog" class="btn btn-warning btn-lg fw-bold text-dark px-4 shadow-sm">
                Explorar Catálogo
            </a>
            <?php if (!currentUser()): ?>
                <a href="index.php?c=auth&a=register" class="btn btn-outline-light btn-lg px-4">
                    Crear Cuenta de Cliente
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Banner Informativo de Descuentos -->
<div class="card border-0 shadow-sm mb-5 bg-white">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center border-end-md">
                <h5 class="fw-bold text-dark mt-2">Escalas de Descuento</h5>
                <span class="badge bg-success-subtle text-success">Automatizadas en el Modelo</span>
            </div>
            <div class="col-md-9">
                <div class="row g-3 text-center">
                    <div class="col-sm-3 col-6">
                        <div class="p-2 border rounded bg-light">
                            <span class="d-block fw-bold text-umes">6 a 20 uds</span>
                            <span class="badge bg-umes">5% Descuento</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="p-2 border rounded bg-light">
                            <span class="d-block fw-bold text-umes">21 a 50 uds</span>
                            <span class="badge bg-umes">10% Descuento</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="p-2 border rounded bg-light">
                            <span class="d-block fw-bold text-umes">+50 uds</span>
                            <span class="badge bg-danger">15% Descuento</span>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="p-2 border rounded bg-warning-subtle border-warning">
                            <span class="d-block fw-bold text-dark">Mayoristas</span>
                            <span class="badge bg-warning text-dark">+8% Acumulado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Publicaciones Destacadas (Sin icono de estrella) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-dark mb-0">Publicaciones Destacadas</h3>
    <a href="index.php?c=home&a=catalog" class="text-umes fw-semibold text-decoration-none">Ver todas &rarr;</a>
</div>

<div class="row g-4 mb-5">
    <?php foreach ($featured as $book): ?>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm book-card">
                <div class="position-relative p-3 text-center bg-transparent">
                    <span class="position-absolute top-0 end-0 m-2 badge bg-secondary"><?= sanitize($book['formato']) ?></span>
                    <div class="book-cover-placeholder d-flex align-items-center justify-content-center mx-auto shadow-sm">
                        <span class="text-muted small">Libro</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted text-uppercase fw-semibold mb-1"><?= sanitize($book['categoria_nombre']) ?></small>
                    <h6 class="card-title fw-bold text-dark mb-1 line-clamp-2"><?= sanitize($book['titulo']) ?></h6>
                    <p class="text-muted small mb-3">Por: <?= sanitize($book['autor']) ?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fs-5 fw-bold text-umes"><?= formatMoney($book['precio_base']) ?></span>
                        <a href="index.php?c=book&a=show&id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-umes">
                            Ver Ficha
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Novedades del Mes (Sin icono de rayo) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-dark mb-0">Novedades del Mes</h3>
    <a href="index.php?c=home&a=catalog" class="text-umes fw-semibold text-decoration-none">Ver catálogo &rarr;</a>
</div>

<div class="row g-4 mb-5">
    <?php foreach ($newReleases as $book): ?>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm book-card">
                <div class="position-relative p-3 text-center bg-transparent">
                    <span class="position-absolute top-0 end-0 m-2 badge bg-secondary"><?= sanitize($book['formato']) ?></span>
                    <div class="book-cover-placeholder d-flex align-items-center justify-content-center mx-auto shadow-sm">
                        <span class="text-muted small">Libro</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <small class="text-muted text-uppercase fw-semibold mb-1"><?= sanitize($book['categoria_nombre']) ?></small>
                    <h6 class="card-title fw-bold text-dark mb-1 line-clamp-2"><?= sanitize($book['titulo']) ?></h6>
                    <p class="text-muted small mb-3">Por: <?= sanitize($book['autor']) ?></p>
                    <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fs-5 fw-bold text-umes"><?= formatMoney($book['precio_base']) ?></span>
                        <a href="index.php?c=book&a=show&id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-umes">
                            Ver Ficha
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
