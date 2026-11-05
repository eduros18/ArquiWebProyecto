<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Mis Solicitudes de Cotización Especial</h3>
        <p class="text-muted small mb-0">Seguimiento de pedidos por volumen y tiradas bajo demanda</p>
    </div>
    <a href="index.php?c=quote&a=create" class="btn btn-warning fw-bold text-dark">
        Nueva Solicitud
    </a>
</div>

<?php if (empty($quotes)): ?>
    <div class="text-center py-5 bg-white rounded shadow-sm">
        <h5>No tienes solicitudes de cotización activas</h5>
        <a href="index.php?c=quote&a=create" class="btn btn-umes mt-2">Crear Solicitud Especial</a>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Folio</th>
                        <th>Obra / Proyecto</th>
                        <th>Tiraje</th>
                        <th>Acabado y Papel</th>
                        <th>Fecha Requerida</th>
                        <th>Estado</th>
                        <th>Cotización Asignada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quotes as $q): ?>
                        <tr>
                            <td class="fw-bold">#COT-<?= str_pad($q['id'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?= sanitize($q['titulo_obra']) ?></div>
                                <small class="text-muted">Enviado: <?= date('d/m/Y', strtotime($q['fecha_creacion'])) ?></small>
                            </td>
                            <td class="fw-bold"><?= $q['cantidad_estimada'] ?> uds</td>
                            <td>
                                <small class="d-block text-dark"><?= sanitize($q['acabado_portada']) ?></small>
                                <small class="text-muted"><?= sanitize($q['tipo_papel']) ?></small>
                            </td>
                            <td><?= date('d/m/Y', strtotime($q['fecha_requerida'])) ?></td>
                            <td>
                                <?php
                                    $badge = 'secondary';
                                    if ($q['estado'] === 'Pendiente') $badge = 'warning text-dark';
                                    if ($q['estado'] === 'Cotizado') $badge = 'info text-dark';
                                    if ($q['estado'] === 'Aprobado') $badge = 'success';
                                    if ($q['estado'] === 'Rechazado') $badge = 'danger';
                                ?>
                                <span class="badge bg-<?= $badge ?>"><?= $q['estado'] ?></span>
                            </td>
                            <td>
                                <?php if ($q['precio_cotizado']): ?>
                                    <div class="fw-bold text-umes fs-6"><?= formatMoney($q['precio_cotizado']) ?></div>
                                    <small class="text-muted">Asesor: <?= sanitize($q['asesor_nombre'] ?? 'Comercial') ?></small>
                                    <?php if (!empty($q['comentarios_asesor'])): ?>
                                        <div class="small text-secondary fst-italic">"<?= sanitize($q['comentarios_asesor']) ?>"</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted small">En revisión por asesor</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
