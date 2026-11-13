<?php
require_once __DIR__ . '/../../config/config.php';
$user = currentUser();
$cartCount = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'cantidad')) : 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> · Librería universitaria</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="public/css/custom.css">
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

<!-- Navbar Principal -->
<nav class="navbar navbar-expand-xl navbar-dark bg-umes sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php?c=home&a=index">
            <i class="brand-mark bi bi-book-half" aria-hidden="true"></i>
            <div>
                <span class="d-block lh-1">EDITORIAL</span>
                <span class="fs-6 fw-light text-warning">MESOAMERICANA</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Abrir navegación">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?c=home&a=index"><i class="bi bi-house-door"></i> Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?c=home&a=catalog"><i class="bi bi-grid"></i> Catálogo</a>
                </li>

                <?php if ($user && in_array($user['rol_nombre'], ['institucional', 'admin'])): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="index.php?c=quote&a=create">
                            <i class="bi bi-pencil-square"></i> Solicitar Pedido Especial
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($user && $user['rol_nombre'] === 'institucional'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?c=quote&a=myQuotes">
                            <i class="bi bi-file-earmark-text"></i> Mis Cotizaciones
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ($user && in_array($user['rol_nombre'], ['asesor', 'admin'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-info fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-briefcase"></i> Panel Comercial
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php?c=quote&a=index"><i class="bi bi-inbox"></i> Bandeja Cotizaciones</a></li>
                            <li><a class="dropdown-item" href="index.php?c=order&a=monitor"><i class="bi bi-receipt"></i> Monitoreo de Órdenes</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($user && $user['rol_nombre'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-semibold" href="index.php?c=book&a=index">
                            <i class="bi bi-gear-fill"></i> Gestionar catálogo
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Sección Derecha: Carrito y Autenticación -->
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                <li class="nav-item">
                    <a class="btn btn-outline-light position-relative" href="index.php?c=cart&a=index">
                        <i class="bi bi-cart3"></i> Carrito
                        <?php if ($cartCount > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if ($user): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 text-white" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-5"></i>
                            <div>
                                <small class="d-block text-warning fw-bold text-uppercase" style="font-size: 0.7rem;">
                                    <?= sanitize($user['rol_nombre']) ?>
                                </small>
                                <span><?= sanitize($user['nombre']) ?></span>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <?php if (in_array($user['rol_nombre'], ['minorista', 'institucional'])): ?>
                                <li><a class="dropdown-item" href="index.php?c=order&a=myOrders"><i class="bi bi-bag-check"></i> Mis Pedidos</a></li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-danger" href="index.php?c=auth&a=logout"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-outline-warning" href="index.php?c=auth&a=login">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-warning fw-semibold text-dark" href="index.php?c=auth&a=register">Registrarse</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Mensajes Flash de Éxito o Error -->
<div class="container mt-3">
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php 
                $msgs = [
                    'item_agregado' => '¡Libro agregado al carrito exitosamente!',
                    'item_removido' => 'Libro eliminado del carrito.',
                    'orden_creada_exito' => '¡Orden procesada y registrada con éxito!',
                    'cotizacion_enviada' => '¡Solicitud de pedido especial enviada al asesor comercial!',
                    'cotizacion_respondida' => 'Cotización actualizada y enviada al cliente.',
                    'libro_creado' => 'Nuevo libro registrado en el catálogo.',
                    'libro_actualizado' => 'Libro actualizado correctamente.',
                    'libro_eliminado' => 'Libro desactivado del catálogo (baja lógica).',
                    'cuenta_creada' => 'Cuenta registrada con éxito. Ahora puedes iniciar sesión.',
                    'sesion_cerrada' => 'Has cerrado sesión correctamente.',
                    'estado_actualizado' => 'Estado de la orden actualizado.'
                ];
                echo $msgs[$_GET['msg']] ?? 'Operación realizada con éxito.';
            ?>
            <button type="button" aria-label="Cerrar aviso" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php 
                $errors = [
                    'debes_iniciar_sesion' => 'Debes iniciar sesión para acceder a esta función.',
                    'acceso_denegado' => 'No tienes permisos de rol suficientes para ver esta sección.',
                    'credenciales_invalidas' => 'Correo o contraseña incorrectos.',
                    'correo_ya_existe' => 'El correo electrónico ya se encuentra registrado.',
                    'carrito_vacio' => 'Tu carrito de compras está vacío.',
                    'stock_no_disponible' => 'El libro seleccionado no cuenta con existencias suficientes.',
                    'libro_no_encontrado' => 'El libro solicitado no existe o está inactivo.',
                    'orden_no_encontrada' => 'La orden solicitada no fue encontrada.'
                ];
                echo $errors[$_GET['error']] ?? 'Ocurrió un error al procesar la solicitud.';
            ?>
            <button type="button" aria-label="Cerrar aviso" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<main class="container py-4 flex-grow-1">
