@echo off
title Editorial Mesoamericana - Plataforma Web MVC
color 0A
echo ===================================================================
echo     UNIVERSIDAD MESOAMERICANA - SEDE QUETZALTENANGO
echo     HACKATHON DE ARQUITECTURA DE SISTEMAS (PROYECTO FINAL)
echo     Plataforma Editorial Multicanal (Patron MVC)
echo ===================================================================
echo.
echo Iniciando servidor web local desde tu carpeta del proyecto:
echo C:\Users\Eduardo\Desktop\Universidad\6to semestre\Arquitectura de sistemas\ArquiWebProyecto
echo.
echo Abriendo http://localhost:8000 en el navegador...
echo Presione CTRL + C para detener el servidor.
echo.

cd /d "C:\Users\Eduardo\Desktop\Universidad\6to semestre\Arquitectura de sistemas\ArquiWebProyecto"
start "" http://localhost:8000
"C:\xampp\php\php.exe" -S localhost:8000
pause
