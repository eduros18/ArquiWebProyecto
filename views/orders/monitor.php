<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Seguimiento de pedidos</h3>
        <p class="text-muted small mb-0">Control y cambio de estado de pedidos para el equipo comercial y administrativo</p>
    </div>
    <div class="btn-group">
        <a href="index.php?c=order&a=monitor" class="btn btn-sm btn-outline-secondary <?= empty($_GET['estado']) ? 'active' : '' ?>">Todas</a>
        <a href="index.php?c=order&a=monitor&estado=Pendiente" class="btn btn-sm btn-outline-warning <?= (isset($_GET['estado']) && $_GET['estado'] === 'Pendiente') ? 'active' : '' ?>">Pendientes</a>
        <a href="index.php?c=order&a=monitor&estado=Aprobado" class="btn btn-sm btn-outline-primary <?= (isset($_GET['estado']) && $_GET['estado'] === 'Aprobado') ? 'active' : '' ?>">Aprobadas</a>
        <a href="index.php?c=order&a=monitor&estado=Enviado" class="btn btn-sm btn-outline-success <?= (isset($_GET['estado']) && $_GET['estado'] === 'Enviado') ? 'active' : '' ?>">Enviadas</a>
        <a href="index.php?c=order&a=monitor&estado=Cancelado" class="btn btn-sm btn-outline-danger <?= (isset($_GET['estado']) && $_GET['estado'] === 'Cancelado') ? 'active' : '' ?>">Canceladas</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Folio</th>
                    <th>Cliente</th>
                    <th>Rol</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado Actual</th>
                    <th>Cambiar Estado</th>
                    <th class="text-center">Comprobante</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                    <tr>
                        <td class="fw-bold">#ORD-<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td>
                            <div class="fw-semibold"><?= sanitize($o['usuario_nombre']) ?></div>
                            <small class="text-muted"><?= sanitize($o['usuario_email']) ?></small>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-dark"><?= sanitize($o['rol_nombre']) ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($o['fecha_creacion'])) ?></td>
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
                        <td>
                            <form action="index.php?c=order&a=updateStatus" method="POST" class="d-flex gap-1">
                                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                <select name="estado" class="form-select form-select-sm" style="width: 125px;">
                                    <option value="Pendiente" <?= $o['estado'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="Aprobado" <?= $o['estado'] === 'Aprobado' ? 'selected' : '' ?>>Aprobado</option>
                                    <option value="Enviado" <?= $o['estado'] === 'Enviado' ? 'selected' : '' ?>>Enviado</option>
                                    <option value="Cancelado" <?= $o['estado'] === 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-dark" title="Guardar">OK</button>
                            </form>
                        </td>
                        <td class="text-center">
                            <a href="index.php?c=order&a=show&id=<?= $o['id'] ?>" class="btn btn-sm btn-outline-umes">
                                Ver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
