<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center py-4">
    <div class="col-md-7 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="bi bi-person-plus-fill text-umes display-5"></i>
                    <h3 class="fw-bold mt-2">Crear Cuenta de Cliente</h3>
                    <p class="text-muted small">Regístrate como comprador individual o cliente institucional</p>
                </div>

                <form action="index.php?c=auth&a=processRegister" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nombre Completo o Razón Social *</label>
                        <input type="text" name="nombre" class="form-control" required placeholder="Ej. Juan Pérez o Colegio Los Altos">
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Correo Electrónico *</label>
                            <input type="email" name="email" class="form-control" required placeholder="correo@ejemplo.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Contraseña *</label>
                            <input type="password" name="password" class="form-control" required placeholder="Mínimo 6 caracteres">
                        </div>
                    </div>

                    <!-- Selección de Perfil / Rol (RF-02) -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Tipo de Cuenta de Cliente *</label>
                        <select name="rol_id" class="form-select" id="roleSelector" onchange="toggleInstitucionField()">
                            <option value="2">Cliente Minorista (Comprador Individual)</option>
                            <option value="3">Cliente Institucional / Mayorista (Colegios, Universidades, Empresas)</option>
                        </select>
                        <div class="form-text small text-success">
                            <i class="bi bi-info-circle"></i> Los clientes institucionales gozan de 8% de descuento adicional por compras a volumen y acceso al módulo de cotizaciones especiales.
                        </div>
                    </div>

                    <div class="mb-3 d-none" id="institucionGroup">
                        <label class="form-label small fw-semibold">Nombre de la Institución / Empresa *</label>
                        <input type="text" name="institucion" class="form-control" placeholder="Ej. Universidad Mesoamericana">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Teléfono de Contacto</label>
                        <input type="text" name="telefono" class="form-control" placeholder="Ej. 7765-4321">
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-warning fw-bold text-dark py-2 shadow-sm">
                            <i class="bi bi-check2-circle"></i> Completar Registro
                        </button>
                    </div>

                    <div class="text-center">
                        <span class="small text-muted">¿Ya tienes cuenta? </span>
                        <a href="index.php?c=auth&a=login" class="small text-umes fw-bold text-decoration-none">Inicia sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleInstitucionField() {
    const val = document.getElementById('roleSelector').value;
    const group = document.getElementById('institucionGroup');
    if (val === '3') {
        group.classList.remove('d-none');
    } else {
        group.classList.add('d-none');
    }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
