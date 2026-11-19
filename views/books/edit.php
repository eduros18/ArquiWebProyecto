<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-3">
    <a href="index.php?c=book&a=index" class="text-muted text-decoration-none small">
        &larr; Volver a la gestión del catálogo
    </a>
</div>

<div class="card border-0 shadow-sm col-lg-8 mx-auto">
    <div class="card-header bg-white py-3 border-bottom">
        <h4 class="fw-bold mb-0 text-dark">Modificar Libro</h4>
    </div>
    <div class="card-body p-4">
        <form action="index.php?c=book&a=update" method="POST">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label small fw-semibold">Título de la Obra *</label>
                    <input type="text" name="titulo" class="form-control" required value="<?= sanitize($book['titulo']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Categoría *</label>
                    <select name="categoria_id" class="form-select" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $book['categoria_id'] ? 'selected' : '' ?>>
                                <?= sanitize($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Autor(es) *</label>
                    <input type="text" name="autor" class="form-control" required value="<?= sanitize($book['autor']) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Editorial *</label>
                    <input type="text" name="editorial" class="form-control" required value="<?= sanitize($book['editorial']) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-semibold">ISBN *</label>
                    <input type="text" name="isbn" class="form-control" required value="<?= sanitize($book['isbn']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Precio Base (Q) *</label>
                    <input type="number" step="0.01" name="precio_base" class="form-control" required value="<?= $book['precio_base'] ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Stock Disponible *</label>
                    <input type="number" name="stock" class="form-control" required value="<?= $book['stock'] ?>" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Formato *</label>
                    <select name="formato" class="form-select" required>
                        <option value="Tapa blanda" <?= $book['formato'] === 'Tapa blanda' ? 'selected' : '' ?>>Tapa blanda</option>
                        <option value="Tapa dura" <?= $book['formato'] === 'Tapa dura' ? 'selected' : '' ?>>Tapa dura</option>
                        <option value="E-book" <?= $book['formato'] === 'E-book' ? 'selected' : '' ?>>E-book (Digital)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold">Año de Edición *</label>
                    <input type="number" name="anio_edicion" class="form-control" required value="<?= $book['anio_edicion'] ?>">
                </div>

                <div class="col-12">
                    <label class="form-label small fw-semibold">Sinopsis *</label>
                    <textarea name="sinopsis" class="form-control" rows="4" required><?= sanitize($book['sinopsis']) ?></textarea>
                </div>

                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="destacado" id="chkDestacado" <?= $book['destacado'] ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="chkDestacado">Publicación Destacada</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="es_novedad" id="chkNovedad" <?= $book['es_novedad'] ? 'checked' : '' ?>>
                        <label class="form-check-label small" for="chkNovedad">Novedad del Mes</label>
                    </div>
                </div>

                <div class="col-12 d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="index.php?c=book&a=index" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Actualizar Libro</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
