<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-umes text-white py-3">
                <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-spreadsheet"></i> Solicitud de Pedido Especial / Cotización (RF-04.1)</h4>
                <p class="small text-white-50 mb-0">Módulo exclusivo para Clientes Institucionales (colegios, universidades y empresas)</p>
            </div>
            <div class="card-body p-4 p-md-5">
                <div class="alert alert-warning py-2 mb-4" role="alert">
                    <i class="bi bi-info-circle"></i> Complete los requerimientos técnicos de la tirada. La solicitud ingresará directamente a la bandeja de un asesor comercial para la emisión de su cotización formal.
                </div>

                <!-- Formulario de Requerimientos (RF-04.2) -->
                <form action="index.php?c=quote&a=store" method="POST">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Título de la Obra o Proyecto Editorial *</label>
                            <input type="text" name="titulo_obra" class="form-control" required placeholder="Ej. Manual Didáctico de Algoritmos 2026">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Cantidad Estimada (Tiraje) *</label>
                            <input type="number" name="cantidad_estimada" class="form-control" required min="50" step="10" placeholder="Mínimo 50 ejemplares">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Acabado de Portada *</label>
                            <select name="acabado_portada" class="form-select" required>
                                <option value="Tapa blanda laminado brillante">Tapa blanda con laminado brillante</option>
                                <option value="Tapa blanda laminado mate con barniz UV">Tapa blanda mate con reserva UV</option>
                                <option value="Tapa dura acolchada (Hardcover)">Tapa dura acolchada (Hardcover)</option>
                                <option value="Rústica cosida con solapas">Rústica cosida con solapas</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo de Papel Interior *</label>
                            <select name="tipo_papel" class="form-select" required>
                                <option value="Papel Bond blanco 80g">Papel Bond blanco 80g (Económico / Textos)</option>
                                <option value="Papel Ahuesado editorial 75g">Papel Ahuesado editorial 75g (Lectura cómoda)</option>
                                <option value="Papel Couché brillante 115g">Papel Couché brillante 115g (Ilustraciones / Full color)</option>
                                <option value="Papel Ecológico reciclado 80g">Papel Ecológico reciclado 80g</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Fecha Requerida de Entrega *</label>
                            <input type="date" name="fecha_requerida" class="form-control" required min="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Institución Solicitante</label>
                            <input type="text" class="form-control bg-light" readonly value="<?= sanitize($user['institucion'] ?: $user['nombre']) ?>">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Especificaciones Adicionales / Comentarios</label>
                            <textarea name="comentarios_cliente" class="form-control" rows="3" placeholder="Indique número aproximado de páginas, tintas interiores (blanco y negro o color), o empaque especial..."></textarea>
                        </div>

                        <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <a href="index.php?c=home&a=catalog" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning btn-lg fw-bold text-dark px-4 shadow">
                                <i class="bi bi-send-fill"></i> Enviar Solicitud al Asesor
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
