<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="mb-3">
    <a href="index.php?c=quote&a=index" class="text-muted text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Volver a la Bandeja
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-umes text-white py-3">
                <h4 class="fw-bold mb-0"><i class="bi bi-calculator"></i> Evaluación de Solicitud #COT-<?= str_pad($quote['id'], 4, '0', STR_PAD_LEFT) ?></h4>
            </div>
            <div class="card-body p-4">
                <!-- Resumen de Requerimientos del Cliente -->
                <div class="card bg-light border-0 p-3 mb-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2">Datos del Requerimiento Institucional:</h6>
                    <div class="row g-2 small">
                        <div class="col-sm-6"><strong>Cliente:</strong> <?= sanitize($quote['cliente_nombre']) ?></div>
                        <div class="col-sm-6"><strong>Institución:</strong> <?= sanitize($quote['institucion'] ?: 'No especificada') ?></div>
                        <div class="col-sm-6"><strong>Correo:</strong> <?= sanitize($quote['cliente_email']) ?></div>
                        <div class="col-sm-6"><strong>Teléfono:</strong> <?= sanitize($quote['telefono'] ?: 'No registrado') ?></div>
                        <div class="col-sm-6"><strong>Obra:</strong> <?= sanitize($quote['titulo_obra']) ?></div>
                        <div class="col-sm-6"><strong>Tiraje Requerido:</strong> <?= $quote['cantidad_estimada'] ?> ejemplares</div>
                        <div class="col-sm-6"><strong>Portada:</strong> <?= sanitize($quote['acabado_portada']) ?></div>
                        <div class="col-sm-6"><strong>Papel Interior:</strong> <?= sanitize($quote['tipo_papel']) ?></div>
                        <div class="col-sm-6"><strong>Fecha Requerida:</strong> <?= date('d/m/Y', strtotime($quote['fecha_requerida'])) ?></div>
                        <div class="col-12 mt-2"><strong>Comentarios del Cliente:</strong><br><?= nl2br(sanitize($quote['comentarios_cliente'] ?: 'Sin comentarios adicionales.')) ?></div>
                    </div>
                </div>

                <!-- Formulario de Respuesta del Asesor (RF-04.3) -->
                <form action="index.php?c=quote&a=processReview" method="POST">
                    <input type="hidden" name="quote_id" value="<?= $quote['id'] ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Precio Total Cotizado (Q) *</label>
                            <input type="number" step="0.01" name="precio_cotizado" class="form-control form-control-lg fw-bold text-umes" 
                                   required value="<?= $quote['precio_cotizado'] ?: '' ?>" placeholder="Ej. 12500.00">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Estado de la Cotización *</label>
                            <select name="estado" class="form-select form-select-lg" required>
                                <option value="Cotizado" <?= $quote['estado'] === 'Cotizado' ? 'selected' : '' ?>>Cotizado (Enviar propuesta formal)</option>
                                <option value="Aprobado" <?= $quote['estado'] === 'Aprobado' ? 'selected' : '' ?>>Aprobado para Impresión</option>
                                <option value="Rechazado" <?= $quote['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado / Inviable</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Observaciones Comerciales y Plazos de Producción *</label>
                            <textarea name="comentarios_asesor" class="form-control" rows="4" required placeholder="Especifique términos de pago, tiempo estimado de prensas y condiciones de entrega..."><?= sanitize($quote['comentarios_asesor']) ?></textarea>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <a href="index.php?c=quote&a=index" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark px-4 shadow">
                                <i class="bi bi-check-circle-fill"></i> Registrar y Emitir Cotización
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
