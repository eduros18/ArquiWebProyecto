<?php
// Configuración Global del Proyecto
define('APP_NAME', 'Editorial Mesoamericana');
define('APP_URL', 'http://localhost:8000');
define('TAX_RATE', 0.12); // IVA 12% Guatemala

// Configurar directorio local de sesiones
$sessionDir = __DIR__ . '/../database/sessions';
if (!is_dir($sessionDir)) {
    @mkdir($sessionDir, 0777, true);
}
if (is_dir($sessionDir) && is_writable($sessionDir)) {
    @ini_set('session.save_path', $sessionDir);
}

// Iniciar sesión silenciosamente si no está activa
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Configuración de Base de Datos MySQL (XAMPP Default)
define('DB_HOST', 'localhost');
define('DB_NAME', 'editorial_mvc');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', '3306');

// Helper de seguridad contra XSS para las Vistas
function sanitize($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Helper para formato de moneda (Quetzales)
function formatMoney($amount) {
    return 'Q' . number_format((float)$amount, 2, '.', ',');
}

// Helper de Autenticación y RBAC
function currentUser() {
    return $_SESSION['usuario'] ?? null;
}

function userHasRole($roles) {
    $user = currentUser();
    if (!$user) return false;
    if (is_array($roles)) {
        return in_array($user['rol_nombre'], $roles);
    }
    return $user['rol_nombre'] === $roles;
}

function requireAuth($roles = []) {
    if (!currentUser()) {
        header('Location: index.php?c=auth&a=login&error=debes_iniciar_sesion');
        exit;
    }
    if (!empty($roles) && !userHasRole($roles)) {
        header('Location: index.php?c=home&a=index&error=acceso_denegado');
        exit;
    }
}
