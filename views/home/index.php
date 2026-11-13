<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- Hero Promocional -->
<section class="hero-banner mb-4 text-white">
    <div class="hero-copy">
        <span class="hero-kicker">
            Editorial universitaria
        </span>
        <h1 class="display-5 fw-bold">Fondos bibliográficos <em>y ediciones académicas</em></h1>
        <p class="hero-intro">
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
                    Crear cuenta
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-art" aria-hidden="true">
        <div class="print-plate"><div class="print-orbit"></div><div class="print-pages"></div><span>EM</span></div>
    </div>
</section>

<!-- Descuentos por volumen -->
<div class="volume-strip card mb-5">
    <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-3 text-center border-end-md">
                <h5 class="fw-bold text-dark mt-2">Descuentos por volumen</h5>
                <span class="badge bg-success-subtle text-success">Por cantidad de ejemplares</span>
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

<!-- Publicaciones destacadas (Sin icono de estrella) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-dark mb-0">Publicaciones destacadas</h3>
    <a href="index.php?c=home&a=catalog" class="text-umes fw-semibold text-decoration-none">Ver todas &rarr;</a>
</div>

<div class="row g-4 mb-5">
    <?php foreach ($featured as $book): ?>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm book-card">
                <div class="position-relative p-3 text-center bg-transparent">
                    <span class="position-absolute top-0 end-0 m-2 badge bg-secondary"><?= sanitize($book['formato']) ?></span>
                    <div class="book-cover-placeholder d-flex align-items-center justify-content-center mx-auto shadow-sm">
                        <span aria-hidden="true"><?= sanitize($book['titulo']) ?></span>
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

<!-- Novedades del mes (Sin icono de rayo) -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-dark mb-0">Novedades del mes</h3>
    <a href="index.php?c=home&a=catalog" class="text-umes fw-semibold text-decoration-none">Ver catálogo &rarr;</a>
</div>

<div class="row g-4 mb-5">
    <?php foreach ($newReleases as $book): ?>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm book-card">
                <div class="position-relative p-3 text-center bg-transparent">
                    <span class="position-absolute top-0 end-0 m-2 badge bg-secondary"><?= sanitize($book['formato']) ?></span>
                    <div class="book-cover-placeholder d-flex align-items-center justify-content-center mx-auto shadow-sm">
                        <span aria-hidden="true"><?= sanitize($book['titulo']) ?></span>
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
