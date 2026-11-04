<?php
require_once __DIR__ . '/config.php';

class Database {
    private static ?PDO $instance = null;
    private static string $driverUsed = 'mysql';

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                // Intento 1: Conexión a MySQL (XAMPP)
                $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]);
                self::$driverUsed = 'mysql';
            } catch (PDOException $e) {
                // Fallback automático a SQLite local para que el proyecto corra de inmediato
                $sqlitePath = __DIR__ . '/../database/editorial.sqlite';
                $needsSeed = !file_exists($sqlitePath) || filesize($sqlitePath) < 1000;
                
                self::$instance = new PDO("sqlite:" . $sqlitePath, null, null, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                self::$driverUsed = 'sqlite';
                
                if ($needsSeed) {
                    self::seedSqlite(self::$instance);
                }
            }
        }
        return self::$instance;
    }

    public static function getDriver(): string {
        return self::$driverUsed;
    }

    private static function seedSqlite(PDO $db): void {
        $schema = <<<'SQL'
CREATE TABLE IF NOT EXISTS roles (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE,
    descripcion TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    rol_id INTEGER NOT NULL,
    telefono TEXT,
    institucion TEXT,
    fecha_registro TEXT DEFAULT CURRENT_TIMESTAMP,
    activo INTEGER DEFAULT 1,
    FOREIGN KEY (rol_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS categorias (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nombre TEXT NOT NULL UNIQUE,
    descripcion TEXT
);

CREATE TABLE IF NOT EXISTS libros (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    categoria_id INTEGER NOT NULL,
    titulo TEXT NOT NULL,
    autor TEXT NOT NULL,
    editorial TEXT NOT NULL,
    isbn TEXT NOT NULL UNIQUE,
    sinopsis TEXT NOT NULL,
    precio_base REAL NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    formato TEXT NOT NULL DEFAULT 'Tapa blanda',
    anio_edicion INTEGER NOT NULL,
    destacado INTEGER DEFAULT 0,
    es_novedad INTEGER DEFAULT 0,
    portada TEXT DEFAULT 'default_book.svg',
    activo INTEGER DEFAULT 1,
    fecha_creacion TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

CREATE TABLE IF NOT EXISTS ordenes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    subtotal_base REAL NOT NULL,
    descuento_volumen_porcentaje REAL DEFAULT 0.00,
    descuento_volumen_monto REAL DEFAULT 0.00,
    descuento_institucional_porcentaje REAL DEFAULT 0.00,
    descuento_institucional_monto REAL DEFAULT 0.00,
    descuento_total REAL DEFAULT 0.00,
    impuestos REAL NOT NULL,
    total_final REAL NOT NULL,
    estado TEXT DEFAULT 'Pendiente',
    fecha_creacion TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE IF NOT EXISTS detalle_ordenes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    orden_id INTEGER NOT NULL,
    libro_id INTEGER NOT NULL,
    cantidad INTEGER NOT NULL,
    precio_unitario REAL NOT NULL,
    subtotal REAL NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes(id),
    FOREIGN KEY (libro_id) REFERENCES libros(id)
);

CREATE TABLE IF NOT EXISTS cotizaciones (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    usuario_id INTEGER NOT NULL,
    asesor_id INTEGER,
    titulo_obra TEXT NOT NULL,
    cantidad_estimada INTEGER NOT NULL,
    acabado_portada TEXT NOT NULL,
    tipo_papel TEXT NOT NULL,
    fecha_requerida TEXT NOT NULL,
    comentarios_cliente TEXT,
    precio_cotizado REAL,
    comentarios_asesor TEXT,
    estado TEXT DEFAULT 'Pendiente',
    fecha_creacion TEXT DEFAULT CURRENT_TIMESTAMP,
    fecha_respuesta TEXT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (asesor_id) REFERENCES usuarios(id)
);

-- Seed Roles
INSERT OR IGNORE INTO roles (id, nombre, descripcion) VALUES
(1, 'visitante', 'Usuario anónimo'),
(2, 'minorista', 'Cliente individual'),
(3, 'institucional', 'Cliente para compras masivas y cotizaciones'),
(4, 'asesor', 'Personal comercial que evalúa cotizaciones'),
(5, 'admin', 'Administrador con acceso total');

-- Seed Usuarios
INSERT OR IGNORE INTO usuarios (id, nombre, email, password, rol_id, telefono, institucion) VALUES
(1, 'Administrador General', 'admin@editorial.com', '$2y$10$HUdrgIjiwy7dn/ipqDseF.VxYJCLB3xg6wloFHZlP5PBqMo4FILMC', 5, '5555-0101', 'Editorial Mesoamericana'),
(2, 'Lic. Carlos Morales (Asesor)', 'asesor@editorial.com', '$2y$10$t0tnP/tfPBRxhDZCXjCDGuJJT2lroUfKQ0EZO0oTlEquE83zA4OR.', 4, '5555-0202', 'Depto. Comercial'),
(3, 'Colegio Los Altos Quetzaltenango', 'mayorista@colegio.edu.gt', '$2y$10$MOrdYGxLqhNdTJmu.LEDPO/Z9n/BM1Kp4a0qR9rk2YJZmYTg3NYgm', 3, '7765-1234', 'Colegio Los Altos'),
(4, 'Juan Pérez Lector', 'lector@gmail.com', '$2y$10$QE4z4W67AnxIoqyvx2ghUOct6I83GGOgoKKuSfEG01dZ/JsdlQaha', 2, '5432-8899', 'Particular'),
(5, 'Universidad Rafael Landívar Quetzaltenango', 'compras@url.edu.gt', '$2y$10$MOrdYGxLqhNdTJmu.LEDPO/Z9n/BM1Kp4a0qR9rk2YJZmYTg3NYgm', 3, '7722-4400', 'URL Campus Xela');

-- Seed Categorías
INSERT OR IGNORE INTO categorias (id, nombre, descripcion) VALUES
(1, 'Arquitectura y Software', 'Libros técnicos y patrones de diseño'),
(2, 'Literatura Latinoamericana', 'Obras clásicas y contemporáneas'),
(3, 'Historia y Ciencias Sociales', 'Ensayos históricos y sociopolíticos'),
(4, 'Ciencia y Matemáticas', 'Textos universitarios de cálculo y ciencias'),
(5, 'Educación y Pedagogía', 'Materiales didácticos y formativos');

-- Seed Libros
INSERT OR IGNORE INTO libros (id, categoria_id, titulo, autor, editorial, isbn, sinopsis, precio_base, stock, formato, anio_edicion, destacado, es_novedad, portada) VALUES
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
(12, 3, 'Los Mayas: Historia, Pensamiento y Filosofía', 'Silvia Trejo', 'Editorial Cultura', '978-9-99-397210-4', 'Estudio integral de la cosmovisión maya, sus avances astronómicos y su vigencia en el altiplano occidental guatemalteco.', 115.00, 50, 'Tapa blanda', 2024, 0, 1, 'book12.svg');
SQL;

        $db->exec($schema);
    }
}
