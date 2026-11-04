<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/models/Order.php';
require_once __DIR__ . '/models/User.php';

$orderModel = new Order();

// 1. Probar Cliente Minorista con 10 libros de Q100 c/u (Total 10 uds -> 5% descuento por volumen)
$cart1 = [
    ['id' => 1, 'titulo' => 'Libro A', 'precio_base' => 100.0, 'cantidad' => 10]
];
$res1 = $orderModel->calculateCartTotals($cart1, 'minorista');
echo "TEST 1 (Minorista, 10 uds):\n";
echo "Subtotal Base: Q" . $res1['subtotal_base'] . " (Esperado: 1000)\n";
echo "Desc. Volumen: Q" . $res1['volumen_monto'] . " (" . $res1['volumen_porcentaje'] . "%) (Esperado: 50)\n";
echo "Desc. Institucional: Q" . $res1['institucional_monto'] . " (Esperado: 0)\n";
echo "Subtotal Neto: Q" . $res1['subtotal_neto'] . " (Esperado: 950)\n";
echo "IVA 12%: Q" . $res1['impuestos'] . " (Esperado: 114)\n";
echo "Total Final: Q" . $res1['total_final'] . " (Esperado: 1064)\n\n";

// 2. Probar Cliente Institucional con 25 libros de Q100 c/u (Total 25 uds -> 10% volumen + 8% institucional acumulado)
$cart2 = [
    ['id' => 1, 'titulo' => 'Libro B', 'precio_base' => 100.0, 'cantidad' => 25]
];
$res2 = $orderModel->calculateCartTotals($cart2, 'institucional');
// Subtotal base = 2500
// Desc volumen (10%) = 250 -> Subtotal tras vol = 2250
// Desc institucional (8% de 2250) = 180
// Subtotal neto = 2500 - 430 = 2070
// IVA 12% = 2070 * 0.12 = 248.40
// Total final = 2070 + 248.40 = 2318.40
echo "TEST 2 (Institucional, 25 uds):\n";
echo "Subtotal Base: Q" . $res2['subtotal_base'] . "\n";
echo "Desc. Volumen: Q" . $res2['volumen_monto'] . " (" . $res2['volumen_porcentaje'] . "%)\n";
echo "Desc. Institucional: Q" . $res2['institucional_monto'] . " (" . $res2['institucional_porcentaje'] . "%)\n";
echo "Subtotal Neto: Q" . $res2['subtotal_neto'] . "\n";
echo "IVA 12%: Q" . $res2['impuestos'] . "\n";
echo "Total Final: Q" . $res2['total_final'] . "\n\n";

// 3. Probar Login de Administrador
$userModel = new User();
$admin = $userModel->authenticate('admin@editorial.com', 'admin123');
echo "TEST 3 (Login Admin):\n";
echo "Autenticado con éxito: " . ($admin ? 'SÍ (' . $admin['nombre'] . ' - ' . $admin['rol_nombre'] . ')' : 'NO') . "\n";
