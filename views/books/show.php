<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-3">
    <a href="index.php?c=home&a=catalog" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Regresar al Catálogo
    </a>
</div>

<!-- Ficha Técnica Detallada (RF-01.3) -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 p-lg-5">
        <div class="row g-5">
            <!-- Portada y Formato -->
            <div class="col-md-4 text-center">
                <div class="book-cover-large shadow rounded p-4 bg-light mb-3 d-flex align-items-center justify-content-center mx-auto" style="height: 340px; max-width: 250px;">
                    <i class="bi bi-journal-text text-umes" style="font-size: 6rem;"></i>
                </div>
                <span class="badge bg-umes px-3 py-2 fs-6 mb-2"><?= sanitize($book['formato']) ?></span>
                <div>
                    <span class="badge <?= $book['stock'] > 0 ? 'bg-success' : 'bg-danger' ?> px-3 py-1">
                        <?= $book['stock'] > 0 ? 'Disponible (' . $book['stock'] . ' en existencia)' : 'Agotado' ?>
                    </span>
                </div>
            </div>

            <!-- Datos Técnicos y Sinopsis -->
            <div class="col-md-8">
                <span class="badge bg-secondary-subtle text-dark mb-2"><?= sanitize($book['categoria_nombre']) ?></span>
                <h2 class="fw-bold text-dark mb-2"><?= sanitize($book['titulo']) ?></h2>
                <h5 class="text-muted mb-4">Autor: <span class="text-dark fw-semibold"><?= sanitize($book['autor']) ?></span></h5>

                <div class="fs-3 fw-bold text-umes mb-3">
                    <?= formatMoney($book['precio_base']) ?>
                    <small class="fs-6 text-muted fw-normal">(Precio unitario base)</small>
                </div>

                <h6 class="fw-bold text-dark mb-2">Sinopsis de la Obra:</h6>
                <p class="text-secondary leading-relaxed mb-4"><?= nl2br(sanitize($book['sinopsis'])) ?></p>

                <!-- Tabla de Ficha Bibliográfica -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body p-3">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle"></i> Ficha Bibliográfica</h6>
                        <div class="row g-2 small">
                            <div class="col-sm-6"><strong>Editorial:</strong> <?= sanitize($book['editorial']) ?></div>
                            <div class="col-sm-6"><strong>ISBN:</strong> <?= sanitize($book['isbn']) ?></div>
                            <div class="col-sm-6"><strong>Año de Edición:</strong> <?= $book['anio_edicion'] ?></div>
                            <div class="col-sm-6"><strong>Formato:</strong> <?= sanitize($book['formato']) ?></div>
                        </div>
                    </div>
                </div>

                <!-- Formulario Agregar al Carrito (RF-03.1) -->
                <?php if ($book['stock'] > 0): ?>
                    <form action="index.php?c=cart&a=add" method="POST" class="row g-3 align-items-center">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <div class="col-auto">
                            <label class="form-label small fw-bold mb-0">Cantidad:</label>
                        </div>
                        <div class="col-auto" style="width: 110px;">
                            <input type="number" name="cantidad" class="form-control text-center fw-bold" 
                                   value="1" min="1" max="<?= $book['stock'] ?>">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-warning fw-bold text-dark px-4 shadow-sm">
                                <i class="bi bi-cart-plus"></i> Agregar al Carrito
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning mb-0" role="alert">
                        <i class="bi bi-exclamation-circle"></i> Este ejemplar no cuenta con stock inmediato. Si eres cliente institucional, puedes solicitar un pedido personalizado de tiraje en el menú superior.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
