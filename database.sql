CREATE DATABASE IF NOT EXISTS editorial_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE editorial_mvc;

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(200) NOT NULL
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    telefono VARCHAR(20),
    institucion VARCHAR(100),
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    activo TINYINT(1) DEFAULT 1,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(200)
);

CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    editorial VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) NOT NULL UNIQUE,
    sinopsis TEXT NOT NULL,
    precio_base DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    formato ENUM('Tapa blanda', 'Tapa dura', 'E-book') NOT NULL DEFAULT 'Tapa blanda',
    anio_edicion INT NOT NULL,
    destacado TINYINT(1) DEFAULT 0,
    es_novedad TINYINT(1) DEFAULT 0,
    portada VARCHAR(255) DEFAULT 'default_book.svg',
    activo TINYINT(1) DEFAULT 1,
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    subtotal_base DECIMAL(10,2) NOT NULL,
    descuento_volumen_porcentaje DECIMAL(5,2) DEFAULT 0.00,
    descuento_volumen_monto DECIMAL(10,2) DEFAULT 0.00,
    descuento_institucional_porcentaje DECIMAL(5,2) DEFAULT 0.00,
    descuento_institucional_monto DECIMAL(10,2) DEFAULT 0.00,
    descuento_total DECIMAL(10,2) DEFAULT 0.00,
    impuestos DECIMAL(10,2) NOT NULL,
    total_final DECIMAL(10,2) NOT NULL,
    estado ENUM('Pendiente', 'Aprobado', 'Enviado', 'Cancelado') DEFAULT 'Pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS detalle_ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    orden_id INT NOT NULL,
    libro_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes(id) ON DELETE CASCADE,
    FOREIGN KEY (libro_id) REFERENCES libros(id)
);

CREATE TABLE IF NOT EXISTS cotizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    asesor_id INT NULL,
    titulo_obra VARCHAR(150) NOT NULL,
    cantidad_estimada INT NOT NULL,
    acabado_portada VARCHAR(100) NOT NULL,
    tipo_papel VARCHAR(100) NOT NULL,
    fecha_requerida DATE NOT NULL,
    comentarios_cliente TEXT,
    precio_cotizado DECIMAL(10,2) NULL,
    comentarios_asesor TEXT,
    estado ENUM('Pendiente', 'Cotizado', 'Aprobado', 'Rechazado') DEFAULT 'Pendiente',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta DATETIME NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (asesor_id) REFERENCES usuarios(id)
);

-- ============================================================
-- DATOS SEMILLA (DML)
-- ============================================================

-- 1. Roles (Los 5 roles estrictos de la guía)
INSERT INTO roles (id, nombre, descripcion) VALUES
(1, 'visitante', 'Usuario anónimo para exploración de catálogo'),
(2, 'minorista', 'Cliente individual con compras estándar'),
(3, 'institucional', 'Cliente para compras masivas y cotizaciones'),
(4, 'asesor', 'Personal comercial que evalúa cotizaciones'),
(5, 'admin', 'Administrador con acceso y gestión total')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- 2. Usuarios Iniciales con Contraseñas Cifradas Bcrypt
-- admin@editorial.com / admin123
-- asesor@editorial.com / asesor123
-- mayorista@colegio.edu.gt / mayoreo123
-- lector@gmail.com / lector123
INSERT INTO usuarios (id, nombre, email, password, rol_id, telefono, institucion) VALUES
(1, 'Administrador General', 'admin@editorial.com', '$2y$10$HUdrgIjiwy7dn/ipqDseF.VxYJCLB3xg6wloFHZlP5PBqMo4FILMC', 5, '5555-0101', 'Editorial Mesoamericana'),
(2, 'Lic. Carlos Morales (Asesor)', 'asesor@editorial.com', '$2y$10$t0tnP/tfPBRxhDZCXjCDGuJJT2lroUfKQ0EZO0oTlEquE83zA4OR.', 4, '5555-0202', 'Depto. Comercial'),
(3, 'Colegio Los Altos Quetzaltenango', 'mayorista@colegio.edu.gt', '$2y$10$MOrdYGxLqhNdTJmu.LEDPO/Z9n/BM1Kp4a0qR9rk2YJZmYTg3NYgm', 3, '7765-1234', 'Colegio Los Altos'),
(4, 'Juan Pérez Lector', 'lector@gmail.com', '$2y$10$QE4z4W67AnxIoqyvx2ghUOct6I83GGOgoKKuSfEG01dZ/JsdlQaha', 2, '5432-8899', 'Particular'),
(5, 'Universidad Rafael Landívar Quetzaltenango', 'compras@url.edu.gt', '$2y$10$MOrdYGxLqhNdTJmu.LEDPO/Z9n/BM1Kp4a0qR9rk2YJZmYTg3NYgm', 3, '7722-4400', 'URL Campus Xela')
ON DUPLICATE KEY UPDATE email=VALUES(email);

-- 3. Categorías
INSERT INTO categorias (id, nombre, descripcion) VALUES
(1, 'Arquitectura y Software', 'Libros técnicos, patrones de diseño y sistemas'),
(2, 'Literatura Latinoamericana', 'Obras clásicas y contemporáneas de la región'),
(3, 'Historia y Ciencias Sociales', 'Ensayos históricos y análisis sociopolíticos'),
(4, 'Ciencia y Matemáticas', 'Textos universitarios de cálculo, física y ciencias'),
(5, 'Educación y Pedagogía', 'Materiales didácticos para colegios y universidades')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- 4. Libros Semilla (12 libros detallados cumpliendo el mínimo de 10)
INSERT INTO libros (id, categoria_id, titulo, autor, editorial, isbn, sinopsis, precio_base, stock, formato, anio_edicion, destacado, es_novedad, portada) VALUES
(1, 1, 'Patrones de Diseño MVC en Aplicaciones Modernas', 'Martin Fowler', 'Editorial Mesoamericana', '978-0-13-475759-9', 'Guía fundamental para la estructuración desacoplada de aplicaciones web corporativas utilizando el patrón Modelo-Vista-Controlador.', 180.00, 45, 'Tapa dura', 2024, 1, 1, 'book1.svg'),
(2, 1, 'Clean Code: Manual de Estilo para Desarrollo Ágil', 'Robert C. Martin', 'Prentice Hall', '978-0-13-235088-4', 'Principios, patrones y prácticas para escribir código limpio, legible y con alta mantenibilidad en equipos de ingeniería.', 220.00, 30, 'Tapa blanda', 2023, 1, 0, 'book2.svg'),
(3, 2, 'Cien Años de Soledad', 'Gabriel García Márquez', 'Editorial Sudamericana', '978-0-30-747472-8', 'La obra cumbre del realismo mágico que narra la historia de siete generaciones de la familia Buendía en el pueblo mítico de Macondo.', 125.00, 80, 'Tapa blanda', 2022, 1, 0, 'book3.svg'),
(4, 2, 'El Señor Presidente', 'Miguel Ángel Asturias', 'Fondo de Cultura Económica', '978-8-42-065860-5', 'Obra insigne de la literatura guatemalteca que retrata con maestría poética las consecuencias humanas de las dictaduras.', 95.00, 60, 'Tapa dura', 2024, 0, 1, 'book4.svg'),
(5, 3, 'Breve Historia de Guatemala Contemporánea', 'Jorge Luján Muñoz', 'Editorial Universitaria', '978-9-99-225912-1', 'Recorrido analítico y documentado por los procesos sociales, económicos y políticos de Guatemala en los siglos XIX y XX.', 110.00, 25, 'Tapa blanda', 2023, 0, 0, 'book5.svg'),
(6, 4, 'Cálculo Multivariable y Ecuaciones Diferenciales', 'James Stewart', 'Cengage Learning', '978-6-07-526549-0', 'Texto de referencia para estudiantes de ingeniería, con ejercicios aplicados a la física y al modelado matemático de sistemas.', 310.00, 40, 'Tapa dura', 2024, 1, 1, 'book6.svg'),
(7, 1, 'Arquitectura de Software Orientada a Microservicios', 'Sam Newman', 'O Reilly Media', '978-1-49-204345-4', 'Enfoque práctico para el diseño de servicios distribuidos, integración continua y resiliencia en infraestructuras cloud.', 260.00, 18, 'E-book', 2024, 0, 1, 'book7.svg'),
(8, 2, 'Leyendas de Guatemala', 'Miguel Ángel Asturias', 'Editorial Piedra Santa', '978-8-49-816732-4', 'Recreación de las tradiciones mayas y coloniales guatemaltecas con un lenguaje sensorial y mítico inconfundible.', 85.00, 55, 'Tapa blanda', 2023, 0, 0, 'book8.svg'),
(9, 4, 'Física Universitaria con Física Moderna Vol. 1', 'Sears & Zemansky', 'Pearson Educación', '978-6-07-322190-0', 'Fundamentos de mecánica clásica, termodinámica y ondas, esencial para las ciencias exactas y la computación.', 290.00, 22, 'Tapa dura', 2022, 0, 0, 'book9.svg'),
(10, 5, 'Didáctica General y Aprendizaje Basado en Retos', 'Díaz Barriga', 'McGraw-Hill', '978-9-70-104928-0', 'Metodologías pedagógicas de evaluación formativa y diseño de retos para la educación superior técnica.', 140.00, 35, 'Tapa blanda', 2023, 0, 1, 'book10.svg'),
(11, 1, 'Seguridad Web y Protección de Datos en Servidor', 'Peter Yaworski', 'No Starch Press', '978-1-59-327863-2', 'Guía sobre mitigación de inyecciones SQL, vulnerabilidades XSS, CSRF y mejores prácticas de autenticación RBAC.', 195.00, 28, 'E-book', 2024, 1, 1, 'book11.svg'),
(12, 3, 'Los Mayas: Historia, Pensamiento y Filosofía', 'Silvia Trejo', 'Editorial Cultura', '978-9-99-397210-4', 'Estudio integral de la cosmovisión maya, sus avances astronómicos y su vigencia en el altiplano occidental guatemalteco.', 115.00, 50, 'Tapa blanda', 2024, 0, 1, 'book12.svg')
ON DUPLICATE KEY UPDATE isbn=VALUES(isbn);
