<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3 border-bottom">
                <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-success"></i> Confirmación y Cierre de Orden (RF-03.3)</h4>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info py-2" role="alert">
                    <i class="bi bi-person-badge"></i> Comprador: <strong><?= sanitize($user['nombre']) ?></strong> (Rol: <span class="badge bg-umes"><?= sanitize($user['rol_nombre']) ?></span>)
                </div>

                <h6 class="fw-bold text-dark mb-3">Artículos a Procesar:</h6>
                <ul class="list-group list-group-flush mb-4">
                    <?php foreach ($summary['items'] as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="fw-bold text-dark"><?= sanitize($item['titulo']) ?></span>
                                <small class="text-muted d-block"><?= $item['cantidad'] ?> uds × <?= formatMoney($item['precio_base']) ?></small>
                            </div>
                            <span class="fw-bold"><?= formatMoney($item['line_subtotal']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <!-- Desglose Explícito Requerido -->
                <div class="p-4 bg-light rounded border mb-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Desglose Financiero Oficial:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Base:</span>
                        <span class="fw-bold"><?= formatMoney($summary['subtotal_base']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Descuento por Volumen (<?= $summary['volumen_porcentaje'] ?>%):</span>
                        <span class="fw-bold">- <?= formatMoney($summary['volumen_monto']) ?></span>
                    </div>
                    <?php if ($summary['institucional_monto'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Descuento Institucional Adicional (<?= $summary['institucional_porcentaje'] ?>%):</span>
                            <span class="fw-bold">- <?= formatMoney($summary['institucional_monto']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuestos (<?= $summary['tasa_impuesto'] ?>):</span>
                        <span class="fw-bold"><?= formatMoney($summary['impuestos']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pt-3 border-top mt-2">
                        <span class="fs-5 fw-bold">TOTAL FINAL A PAGAR:</span>
                        <span class="fs-4 fw-bold text-umes"><?= formatMoney($summary['total_final']) ?></span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="index.php?c=cart&a=index" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Modificar Carrito
                    </a>
                    <form action="index.php?c=order&a=process" method="POST">
                        <button type="submit" class="btn btn-success btn-lg fw-bold px-4 shadow">
                            <i class="bi bi-bag-check-fill"></i> Confirmar y Generar Orden
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
