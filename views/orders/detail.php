<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="javascript:history.back()" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Regresar
    </a>
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-printer"></i> Imprimir Comprobante
    </button>
</div>

<div class="card border-0 shadow-sm print-area">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-4">
            <div>
                <h3 class="fw-bold text-umes mb-1"><i class="bi bi-book-half"></i> EDITORIAL MESOAMERICANA</h3>
                <p class="text-muted small mb-0">Quetzaltenango, Guatemala • Sistema Web Multicanal</p>
            </div>
            <div class="text-end">
                <span class="badge bg-umes fs-6 mb-1">ORDEN #ORD-<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></span>
                <div class="text-muted small">Fecha: <?= date('d/m/Y H:i', strtotime($order['fecha_creacion'])) ?></div>
                <div>Estado: <strong><?= $order['estado'] ?></strong></div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="fw-bold text-dark mb-1">Cliente Solicitante:</h6>
                <div><?= sanitize($order['usuario_nombre']) ?></div>
                <div class="text-muted small"><?= sanitize($order['usuario_email']) ?></div>
                <?php if (!empty($order['institucion'])): ?>
                    <div class="text-muted small"><strong>Institución:</strong> <?= sanitize($order['institucion']) ?></div>
                <?php endif; ?>
                <span class="badge bg-secondary-subtle text-dark mt-1">Rol: <?= sanitize($order['rol_nombre']) ?></span>
            </div>
        </div>

        <!-- Tabla de Artículos -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Descripción de la Obra</th>
                        <th>Formato</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-end">Precio Unit.</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['detalles'] as $d): ?>
                        <tr>
                            <td class="fw-bold"><?= sanitize($d['titulo']) ?><br><small class="text-muted fw-normal"><?= sanitize($d['autor']) ?></small></td>
                            <td><?= sanitize($d['formato']) ?></td>
                            <td class="text-center"><?= $d['cantidad'] ?></td>
                            <td class="text-end"><?= formatMoney($d['precio_unitario']) ?></td>
                            <td class="text-end fw-bold"><?= formatMoney($d['subtotal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Desglose de Lógica de Negocio -->
        <div class="row justify-content-end">
            <div class="col-md-6 col-lg-5">
                <div class="p-3 bg-light rounded border">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal Base:</span>
                        <span class="fw-bold"><?= formatMoney($order['subtotal_base']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Desc. Volumen (<?= $order['descuento_volumen_porcentaje'] ?>%):</span>
                        <span class="fw-bold">- <?= formatMoney($order['descuento_volumen_monto']) ?></span>
                    </div>
                    <?php if ($order['descuento_institucional_monto'] > 0): ?>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Desc. Institucional (<?= $order['descuento_institucional_porcentaje'] ?>%):</span>
                            <span class="fw-bold">- <?= formatMoney($order['descuento_institucional_monto']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuestos (IVA 12%):</span>
                        <span class="fw-bold"><?= formatMoney($order['impuestos']) ?></span>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top mt-2">
                        <span class="fw-bold fs-5">TOTAL PAGADO:</span>
                        <span class="fw-bold fs-5 text-umes"><?= formatMoney($order['total_final']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
