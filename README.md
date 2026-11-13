# proyecto editorial mvc

este es el proyecto final de arquitectura de sistemas. es una tienda y cotizador de libros hecho con php y patron mvc.

## requisitos
- tener instalado xampp (o php 8 en adelante)
- navegador web (chrome, edge, firefox, etc)

## como ejecutarlo

### opcion 1 (la mas facil):
1. dar doble clic al archivo `Iniciar_Proyecto_Editorial_MVC.bat`
2. solito abre la consola y el navegador en `http://localhost:8000`

### opcion 2 (manual por consola):
1. abres una terminal en la carpeta del proyecto.
2. escribes este comando:
```bash
php -S localhost:8000
```
3. abres tu navegador y entras a `http://localhost:8000`.

### si usas mysql con xampp:
- abre el xampp control panel e inicia apache y mysql.
- entra a `http://localhost/phpmyadmin`.
- le das a importar y subes el archivo `database.sql` que viene aqui.
- si no tienes mysql abierto igual funciona porque tiene una base de datos local incluida para que no se caiga.

## usuarios de prueba para entrar
- admin: admin@editorial.com / admin123
- asesor: asesor@editorial.com / asesor123
- mayorista: mayorista@colegio.edu.gt / mayoreo123
- minorista: lector@gmail.com / lector123
