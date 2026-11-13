<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center py-4">
    <div class="col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mt-2">Iniciar Sesión</h3>
                    <p class="text-muted small">Accede a tu cuenta de cliente o panel de control</p>
                </div>

                <form action="index.php?c=auth&a=processLogin" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Correo Electrónico</label>
                        <input type="email" id="loginEmail" name="email" class="form-control" required placeholder="correo@ejemplo.com">
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Contraseña</label>
                        <input type="password" id="loginPass" name="password" class="form-control" required placeholder="••••••••">
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-umes fw-bold py-2 shadow-sm">
                            Entrar a la Plataforma
                        </button>
                    </div>

                    <div class="text-center">
                        <span class="small text-muted">¿No tienes una cuenta? </span>
                        <a href="index.php?c=auth&a=register" class="small text-umes fw-bold text-decoration-none">Regístrate aquí</a>
                    </div>
                </form>

                <!-- Atajos rápidos para evaluación del Hackathon -->
                <div class="mt-4 pt-3 border-top">
                    <label class="form-label small fw-bold text-muted d-block text-center">Credenciales de Prueba (clic para autocompletar):</label>
                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                        <button type="button" class="btn btn-xs btn-outline-danger" onclick="fillLogin('admin@editorial.com', 'admin123')">Admin</button>
                        <button type="button" class="btn btn-xs btn-outline-info" onclick="fillLogin('asesor@editorial.com', 'asesor123')">Asesor</button>
                        <button type="button" class="btn btn-xs btn-outline-warning" onclick="fillLogin('mayorista@colegio.edu.gt', 'mayoreo123')">Mayorista</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="fillLogin('lector@gmail.com', 'lector123')">Minorista</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(email, pass) {
    document.getElementById('loginEmail').value = email;
    document.getElementById('loginPass').value = pass;
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
