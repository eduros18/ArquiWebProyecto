<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Bandeja de cotizaciones</h3>
        <p class="text-muted small mb-0">Revisa, presupuesta y responde a las solicitudes de tirajes de clientes institucionales</p>
    </div>
    <div class="btn-group">
        <a href="index.php?c=quote&a=index" class="btn btn-sm btn-outline-secondary <?= empty($_GET['estado']) ? 'active' : '' ?>">Todas</a>
        <a href="index.php?c=quote&a=index&estado=Pendiente" class="btn btn-sm btn-outline-warning <?= (isset($_GET['estado']) && $_GET['estado'] === 'Pendiente') ? 'active' : '' ?>">Pendientes</a>
        <a href="index.php?c=quote&a=index&estado=Cotizado" class="btn btn-sm btn-outline-info <?= (isset($_GET['estado']) && $_GET['estado'] === 'Cotizado') ? 'active' : '' ?>">Cotizadas</a>
        <a href="index.php?c=quote&a=index&estado=Aprobado" class="btn btn-sm btn-outline-success <?= (isset($_GET['estado']) && $_GET['estado'] === 'Aprobado') ? 'active' : '' ?>">Aprobadas</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Folio</th>
                    <th>Institución / Cliente</th>
                    <th>Obra Solicitada</th>
                    <th>Tiraje</th>
                    <th>Fecha Límite</th>
                    <th>Estado</th>
                    <th>Cotizado</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotes as $q): ?>
                    <tr>
                        <td class="fw-bold">#COT-<?= str_pad($q['id'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= sanitize($q['institucion'] ?: $q['cliente_nombre']) ?></div>
                            <small class="text-muted"><?= sanitize($q['cliente_email']) ?></small>
                        </td>
                        <td><?= sanitize($q['titulo_obra']) ?></td>
                        <td class="fw-bold"><?= $q['cantidad_estimada'] ?> uds</td>
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
                        <td class="fw-bold text-umes">
                            <?= $q['precio_cotizado'] ? formatMoney($q['precio_cotizado']) : '<span class="text-muted fw-normal">--</span>' ?>
                        </td>
                        <td class="text-center">
                            <a href="index.php?c=quote&a=review&id=<?= $q['id'] ?>" class="btn btn-sm btn-umes">
                                Cotizar / Responder
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
