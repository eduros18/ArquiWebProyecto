# Proyecto Editorial MVC

Universidad Mesoamericana, Quetzaltenango
Facultad de Ingeniería
Ingeniería en Sistemas, Informática y Ciencias de la Computación
Proyecto Final de Arquitectura de Sistemas
2026

Integrantes:
Morales Diaz, Angel Gabriel - 202608301
Rosales Alva, Eduardo José Emmanuel - 202408051
Ardavin Lopez, Fernando Alessandro - 202208057

## Descripción

Este es el proyecto final de Arquitectura de Sistemas. Es una tienda y cotizador de libros hecho con PHP y el patrón MVC. Funciona sin dependencias externas pesadas.

## Arquitectura

El flujo de la aplicación es el siguiente:

1. El usuario interactúa con la interfaz (HTML / Bootstrap 5 / JS).
2. La petición HTTP llega a `index.php`, que actúa como Front Controller (despachador de rutas).
3. El router envía la petición al controlador correspondiente (`HomeController`, `BookController`, `AuthController`, `CartController`, `OrderController`, `QuoteController`).
4. El controlador invoca a los modelos (`Book`, `User`, `Order`, `Quote`, `Category`).
5. Los modelos hacen consultas seguras (PDO) por medio de `Database.php`, que usa MySQL y, si no está disponible, hace fallback automático a SQLite.
6. El controlador pasa los datos a la vista (`views/home`, `books`, `cart`, `orders`, `quotes`, `auth`), que devuelve la respuesta HTML.

## Estructura del proyecto

```
editorial_mvc/
│
├── config/
│   ├── config.php            # Constantes globales, tasa de IVA 12%, sesión y helpers
│   └── Database.php          # Singleton PDO con soporte MySQL y fallback automático a SQLite
│
├── controllers/
│   ├── HomeController.php    # Landing page, catálogo, filtros y búsqueda AJAX
│   ├── BookController.php    # Ficha técnica y mantenimiento CRUD para Administrador
│   ├── AuthController.php    # Login, registro de perfiles, logout y control de sesiones
│   ├── CartController.php    # Manejo del carrito de compras y persistencia en sesión
│   ├── OrderController.php   # Checkout, confirmación con desglose de descuentos y monitoreo
│   └── QuoteController.php   # Módulo de cotizaciones especiales y bandeja del asesor
│
├── models/
│   ├── Book.php              # Consultas, stock, CRUD y baja lógica (activo = 0)
│   ├── User.php              # Hashing Bcrypt, verificación y roles RBAC
│   ├── Order.php             # MOTOR DE DESCUENTOS (Volumen 5-15% + 8% institucional)
│   ├── Quote.php             # Persistencia de requerimientos técnicos de cotización
│   └── Category.php          # Categorización bibliográfica
│
├── views/
│   ├── layout/               # Plantillas maestras (header con RBAC y footer institucional)
│   ├── home/                 # Landing page (destacados, novedades) y catálogo interactivo
│   ├── books/                # Ficha técnica completa y formularios CRUD de libros
│   ├── auth/                 # Formularios de Login (con autocompletado rápido) y Registro
│   ├── cart/                 # Carrito interactivo y pantalla de cierre con desglose
│   ├── orders/               # Historial de compras, detalle de comprobante y monitoreo
│   └── quotes/               # Solicitud de cotización institucional y bandeja del asesor
│
├── public/
│   ├── css/custom.css        # Estilos responsivos con identidad institucional UMES
│   └── js/app.js             # Búsqueda asíncrona en tiempo real (Fetch API / AJAX)
│
├── database/                 # Base de datos embebida SQLite (generada automáticamente)
├── database.sql              # Script DDL + DML con esquema y datos semilla iniciales
├── index.php                 # Front Controller principal y despachador de rutas
└── README.md                 # Documentación oficial del proyecto
```

## Requisitos

- XAMPP (o PHP 8 en adelante)
- Un navegador web (Chrome, Edge, Firefox, etc.)

## Instalación y ejecución

### Opción A

1. Abrir una terminal (PowerShell o CMD) en la carpeta del proyecto: cd "ubicación de la carpeta donde está el archivo"
2. Iniciar el servidor local de PHP con este comando: php -S localhost:8000
3. Abrir el navegador en http://localhost:8000

Con esta opción no hace falta tener MySQL abierto, porque el proyecto trae una base de datos SQLite local que se crea sola.

### Opción B

1. Iniciar Apache y MySQL desde el XAMPP Control Panel.
2. Entrar a phpMyAdmin (http://localhost/phpmyadmin).
3. Crear una base de datos llamada editorial_mvc con este comando: CREATE DATABASE editorial_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
4. Seleccionar la base de datos editorial_mvc y hacer clic en la pestaña Importar.
5. Seleccionar el archivo database.sql que viene en la raíz del proyecto y presionar Importar.
6. Copiar la carpeta editorial_mvc dentro de C:\xampp\htdocs\editorial_mvc
7. Entrar desde el navegador a http://localhost/editorial_mvc

### Opción C

Darle doble clic al archivo Iniciar_Proyecto_Editorial_MVC.bat (está en el escritorio o en la raíz del proyecto). Abre la consola y el navegador en http://localhost:8000.

Si no abre, hay que editar el archivo .bat con un editor de texto y cambiar la ruta que tiene escrita por la ruta donde está el proyecto.

## Usuarios de prueba

Todas las contraseñas están guardadas con Bcrypt en la base de datos. En la pantalla de inicio de sesión hay botones de autocompletado rápido para facilitar la evaluación.

- Administrador: admin@editorial.com / admin123
- Asesor de ventas: asesor@editorial.com / asesor123
- Cliente mayorista: mayorista@colegio.edu.gt / mayoreo123
- Cliente minorista: lector@gmail.com / lector123

## Roles del sistema

1. Visitante (anónimo): no necesita credenciales. Puede navegar el catálogo, aplicar filtros combinados y ver las fichas técnicas completas. No puede comprar ni cotizar.
2. Cliente minorista: entra con sus credenciales, hace compras individuales, usa el carrito, obtiene descuentos por volumen y revisa su historial de pedidos.
3. Cliente institucional / mayorista: entra con sus credenciales, tiene un 8% de descuento adicional acumulable, puede hacer compras grandes y usar el formulario de pedidos especiales.
4. Asesor de ventas: entra con sus credenciales, revisa la bandeja de cotizaciones pendientes, calcula presupuestos formales, aprueba o rechaza cotizaciones y monitorea la actividad comercial.
5. Administrador: entra con sus credenciales y tiene control total del sistema. Gestiona los libros con CRUD y baja lógica, controla el inventario y cambia el estado de las órdenes y cotizaciones.
