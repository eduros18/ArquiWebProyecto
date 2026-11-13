<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">CRUD de Catálogo Bibliográfico (RF-05.1)</h3>
        <p class="text-muted small mb-0">Mantenimiento de títulos, categorías, precios y eliminación lógica en la base de datos</p>
    </div>
    <a href="index.php?c=book&a=create" class="btn btn-success fw-bold">
        Nuevo Libro
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Título / Autor</th>
                    <th>Categoría</th>
                    <th>ISBN</th>
                    <th>Formato</th>
                    <th>Precio Base</th>
                    <th>Stock</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $b): ?>
                    <tr>
                        <td class="text-muted"><?= $b['id'] ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= sanitize($b['titulo']) ?></div>
                            <small class="text-muted">Por: <?= sanitize($b['autor']) ?> (<?= $b['anio_edicion'] ?>)</small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><?= sanitize($b['categoria_nombre']) ?></span></td>
                        <td><code><?= sanitize($b['isbn']) ?></code></td>
                        <td><?= sanitize($b['formato']) ?></td>
                        <td class="fw-bold text-umes"><?= formatMoney($b['precio_base']) ?></td>
                        <td>
                            <span class="badge <?= $b['stock'] > 10 ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= $b['stock'] ?> uds
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="index.php?c=book&a=edit&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-primary" title="Editar">
                                Editar
                            </a>
                            <a href="index.php?c=book&a=delete&id=<?= $b['id'] ?>" class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('¿Confirmas la baja lógica de este libro? Dejará de mostrarse en catálogo sin borrar su historial.');" title="Eliminar (Lógica)">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
