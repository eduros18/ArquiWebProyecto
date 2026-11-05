<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h2 class="fw-bold text-dark mb-4"><i class="bi bi-cart3"></i> Carrito de Compras</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <div class="text-center py-5 bg-white rounded shadow-sm">
        <i class="bi bi-cart-x text-muted display-4 mb-3 d-block"></i>
        <h4 class="fw-bold">Tu carrito de compras está vacío</h4>
        <p class="text-muted">Añade obras desde nuestro catálogo para calcular tus descuentos por volumen.</p>
        <a href="index.php?c=home&a=catalog" class="btn btn-umes mt-2 px-4">
            <i class="bi bi-book"></i> Explorar Libros
        </a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <!-- Tabla de Productos en el Carrito (RF-03.1) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Obra / Libro</th>
                                <th class="text-center">Precio Unit.</th>
                                <th class="text-center" style="width: 140px;">Cantidad</th>
                                <th class="text-end">Subtotal</th>
                                <th class="text-center" style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($summary['items'] as $item): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= sanitize($item['titulo']) ?></div>
                                        <small class="text-muted"><?= sanitize($item['autor']) ?> • <span class="badge bg-secondary-subtle text-dark"><?= sanitize($item['formato']) ?></span></small>
                                    </td>
                                    <td class="text-center fw-semibold">
                                        <?= formatMoney($item['precio_base']) ?>
                                    </td>
                                    <td class="text-center">
                                        <form action="index.php?c=cart&a=update" method="POST" class="d-flex justify-content-center">
                                            <input type="hidden" name="book_id" value="<?= $item['id'] ?>">
                                            <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>" 
                                                   min="1" max="<?= $item['stock'] ?>" class="form-control form-control-sm text-center fw-bold me-1" style="width: 70px;">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Actualizar">
                                                <i class="bi bi-arrow-clockwise"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        <?= formatMoney($item['line_subtotal']) ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="index.php?c=cart&a=remove&id=<?= $item['id'] ?>" class="text-danger" title="Eliminar">
                                            <i class="bi bi-trash fs-5"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between py-3">
                    <a href="index.php?c=cart&a=clear" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas vaciar el carrito?');">
                        <i class="bi bi-trash3"></i> Vaciar Carrito
                    </a>
                    <a href="index.php?c=home&a=catalog" class="btn btn-sm btn-outline-umes">
                        <i class="bi bi-plus-lg"></i> Agregar Más Libros
                    </a>
                </div>
            </div>
        </div>

        <!-- Resumen Financiero con Lógica de Negocio (RF-03.2 y RF-03.3) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 85px;">
                <div class="card-header bg-umes text-white py-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-receipt"></i> Resumen del Pedido</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Total de Ejemplares:</span>
                        <span class="fw-bold"><?= $summary['total_units'] ?> unidades</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal Base:</span>
                        <span class="fw-bold"><?= formatMoney($summary['subtotal_base']) ?></span>
                    </div>

                    <hr>

                    <!-- Detalle de Descuento por Volumen -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between text-success">
                            <span><i class="bi bi-tag-fill"></i> Desc. por Volumen:</span>
                            <span class="fw-bold">- <?= formatMoney($summary['volumen_monto']) ?></span>
                        </div>
                        <small class="text-muted d-block ps-3"><?= $summary['volumen_regla'] ?></small>
                    </div>

                    <!-- Detalle de Descuento Institucional -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between text-success">
                            <span><i class="bi bi-building"></i> Desc. Institucional:</span>
                            <span class="fw-bold">- <?= formatMoney($summary['institucional_monto']) ?></span>
                        </div>
                        <small class="text-muted d-block ps-3"><?= $summary['institucional_regla'] ?></small>
                    </div>

                    <div class="d-flex justify-content-between mb-2 pt-2 border-top">
                        <span class="text-muted">Subtotal con Descuento:</span>
                        <span class="fw-bold"><?= formatMoney($summary['subtotal_neto']) ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Impuestos (<?= $summary['tasa_impuesto'] ?>):</span>
                        <span class="fw-bold"><?= formatMoney($summary['impuestos']) ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-4 p-3 bg-light rounded border">
                        <span class="fs-5 fw-bold text-dark">TOTAL FINAL:</span>
                        <span class="fs-5 fw-bold text-umes"><?= formatMoney($summary['total_final']) ?></span>
                    </div>

                    <div class="d-grid">
                        <?php if (currentUser()): ?>
                            <a href="index.php?c=order&a=checkout" class="btn btn-warning btn-lg fw-bold text-dark shadow-sm">
                                Proceder al Cierre <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php else: ?>
                            <a href="index.php?c=auth&a=login" class="btn btn-umes btn-lg fw-bold shadow-sm">
                                Iniciar Sesión para Comprar
                            </a>
                            <small class="text-muted text-center mt-2">Los visitantes no pueden cerrar órdenes sin autenticarse.</small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
