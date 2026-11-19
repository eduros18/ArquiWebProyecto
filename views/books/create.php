<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-3">
    <a href="index.php?c=book&a=index" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Volver a la gestión del catálogo
    </a>
</div>

<div class="card border-0 shadow-sm col-lg-8 mx-auto">
    <div class="card-header bg-white py-3 border-bottom">
        <h4 class="fw-bold mb-0 text-dark"><i class="bi bi-journal-plus text-success"></i> Registrar Nuevo Libro en Catálogo</h4>
    </div>
    <div class="card-body p-4">
        <form action="index.php?c=book&a=store" method="POST">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Título de la Obra *</label>
                    <input type="text" name="titulo" class="form-control" required placeholder="Ej. Patrones de Diseño MVC">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Categoría *</label>
                    <select name="categoria_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= sanitize($cat['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Autor(es) *</label>
                    <input type="text" name="autor" class="form-control" required placeholder="Ej. Martin Fowler">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Editorial *</label>
                    <input type="text" name="editorial" class="form-control" required placeholder="Ej. Editorial Mesoamericana">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">ISBN *</label>
                    <input type="text" name="isbn" class="form-control" required placeholder="978-0-00-000000-0">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Precio Base (Q) *</label>
                    <input type="number" step="0.01" name="precio_base" class="form-control" required placeholder="150.00">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Stock Inicial *</label>
                    <input type="number" name="stock" class="form-control" required value="20" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Formato *</label>
                    <select name="formato" class="form-select" required>
                        <option value="Tapa blanda">Tapa blanda</option>
                        <option value="Tapa dura">Tapa dura</option>
                        <option value="E-book">E-book (Digital)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Año de Edición *</label>
                    <input type="number" name="anio_edicion" class="form-control" required value="2024">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Sinopsis / Resumen de la Obra *</label>
                    <textarea name="sinopsis" class="form-control" rows="4" required placeholder="Descripción detallada de la publicación..."></textarea>
                </div>

                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="destacado" id="chkDestacado">
                        <label class="form-check-label small" for="chkDestacado">Mostrar en Publicaciones Destacadas</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="es_novedad" id="chkNovedad" checked>
                        <label class="form-check-label small" for="chkNovedad">Marcar como Novedad del Mes</label>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="index.php?c=book&a=index" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success fw-bold px-4">Guardar Libro</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
