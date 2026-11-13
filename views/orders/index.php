<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h3 class="fw-bold text-dark mb-4"><i class="bi bi-clock-history"></i> Historial de Mis Pedidos</h3>

<?php if (empty($orders)): ?>
    <div class="text-center py-5 bg-white rounded shadow-sm">
        <i class="bi bi-bag-x text-muted display-4 mb-3 d-block"></i>
        <h5>Aún no has realizado ninguna orden de compra</h5>
        <a href="index.php?c=home&a=catalog" class="btn btn-umes mt-2">Ver Catálogo</a>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N.º Orden</th>
                        <th>Fecha</th>
                        <th>Subtotal Base</th>
                        <th>Descuento Total</th>
                        <th>Impuestos</th>
                        <th>Total Final</th>
                        <th>Estado</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                        <tr>
                            <td class="fw-bold">#ORD-<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($o['fecha_creacion'])) ?></td>
                            <td><?= formatMoney($o['subtotal_base']) ?></td>
                            <td class="text-success fw-bold">- <?= formatMoney($o['descuento_total']) ?></td>
                            <td><?= formatMoney($o['impuestos']) ?></td>
                            <td class="fw-bold text-umes"><?= formatMoney($o['total_final']) ?></td>
                            <td>
                                <?php
                                    $badge = 'secondary';
                                    if ($o['estado'] === 'Pendiente') $badge = 'warning text-dark';
                                    if ($o['estado'] === 'Aprobado') $badge = 'primary';
                                    if ($o['estado'] === 'Enviado') $badge = 'success';
                                    if ($o['estado'] === 'Cancelado') $badge = 'danger';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $o['estado'] ?></span>
                            </td>
                            <td class="text-center">
                                <a href="index.php?c=order&a=show&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-umes">
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
