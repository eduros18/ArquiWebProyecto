# PLAN DE PRUEBAS FUNCIONALES Y CERTIFICACIÓN DE CALIDAD (QA)
**Proyecto:** Plataforma Web Editorial Multicanal (Arquitectura MVC)  
**Institución:** Universidad Mesoamericana (UMES) – Sede Quetzaltenango  
**Responsable QA:** Desarrollador Frontend & UX / QA  

---

## 1. Alcance y Estrategia de Pruebas
El presente plan de pruebas valida el cumplimiento estricto de los requerimientos funcionales (RF-01 al RF-05) y no funcionales (RNF-01 al RNF-04) antes de la demostración final ante el jurado evaluador.

---

## 2. Matriz de Casos de Prueba Funcionales

| ID | Módulo | Caso de Prueba | Procedimiento | Resultado Esperado | Estado |
| :---: | :--- | :--- | :--- | :--- | :---: |
| **CP-01** | Catálogo | Búsqueda en tiempo real | Escribir "clean" en el buscador de la cabecera. | Se despliega lista flotante con "Clean Code" sin recargar la página. | **Aprobado** |
| **CP-02** | Catálogo | Filtrado combinado | Seleccionar categoría "Arquitectura", formato "Tapa dura" y precio máx. Q300. | Se muestran únicamente los libros que cumplen con todos los criterios simultáneamente. | **Aprobado** |
| **CP-03** | Catálogo | Ficha técnica detallada | Hacer clic en "Ficha Técnica" de cualquier libro. | Se visualiza portada, sinopsis completa, ISBN, stock disponible y precio base. | **Aprobado** |
| **CP-04** | Seguridad | Control de acceso anónimo | Un visitante intenta ingresar a `index.php?c=quote&a=create`. | El sistema redirige al login mostrando mensaje: "Debes iniciar sesión". | **Aprobado** |
| **CP-05** | Negocio | Descuento por volumen (6 a 20 uds) | Agregar 10 unidades de un libro al carrito con usuario minorista. | Se aplica automáticamente el 5% de descuento sobre el subtotal base. | **Aprobado** |
| **CP-06** | Negocio | Descuento mayorista acumulado | Iniciar sesión con cuenta institucional y agregar 25 unidades. | Se aplica 10% por volumen + 8% adicional preferencial calculado acumulativamente. | **Aprobado** |
| **CP-07** | Cotizaciones | Formulario de pedido especial | Cliente institucional envía solicitud con tiraje de 100 uds y acabado especial. | La solicitud se registra con folio #COT-xxxx y estado inicial "Pendiente". | **Aprobado** |
| **CP-08** | Comercial | Bandeja del Asesor | Asesor entra a bandeja comercial, asigna precio Q12,500 y comentarios. | Estado cambia a "Cotizado", visible inmediatamente para el cliente mayorista. | **Aprobado** |
| **CP-09** | Admin | Baja lógica de libro (CRUD) | Administrador da clic en eliminar libro en el panel de control. | El libro cambia a inactivo (`activo = 0`), no se borra físicamente y ya no aparece en tienda. | **Aprobado** |
| **CP-10** | Frontend | Adaptabilidad responsiva | Visualizar la web en pantalla de teléfono móvil (375px ancho). | El menú colapsa en hamburguesa, las tablas tienen scroll horizontal y las tarjetas se apilan a 1 columna. | **Aprobado** |

---

## 3. Certificación de Calidad
Todas las pruebas de regresión y validación de seguridad (prevención XSS, SQLi y verificación de sesiones) arrojaron resultados satisfactorios, certificando el sistema como **apto para demostración en vivo**.
