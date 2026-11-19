@echo off
title Editorial Mesoamericana - Plataforma Web MVC
color 0A
echo ===================================================================
echo     UNIVERSIDAD MESOAMERICANA - SEDE QUETZALTENANGO
echo     HACKATHON DE ARQUITECTURA DE SISTEMAS (PROYECTO FINAL)
echo     Plataforma Editorial Multicanal (Patron MVC)
echo ===================================================================
echo.
echo Iniciando servidor web local en http://localhost:8000 ...
echo Presione CTRL + C para detener el servidor.
echo.

cd /d "C:\Users\Eduardo\Downloads\ArquiWebProyecto-UI\ArquiWebProyecto"
start "" http://localhost:8000
"C:\xampp\php\php.exe" -S localhost:8000
pause
