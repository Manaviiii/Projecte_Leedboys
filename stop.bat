@echo off
title LEDBOYS - Cerrando...
echo.
echo  ================================
echo   LEDBOYS - Cerrando proyecto
echo  ================================
echo.

:: Cerrar ventanas de Laravel y Frontend por titulo
echo [1/3] Cerrando Laravel...
taskkill /fi "WindowTitle eq LEDBOYS - Laravel*" /f >nul 2>&1 && echo       Laravel cerrado || echo       Laravel no estaba abierto

echo [2/3] Cerrando Frontend...
taskkill /fi "WindowTitle eq LEDBOYS - Frontend*" /f >nul 2>&1 && echo       Frontend cerrado || echo       Frontend no estaba abierto

:: Detener servicios XAMPP
echo [3/3] Deteniendo Apache y MySQL...
net stop Apache2.4 >nul 2>&1 && echo       Apache detenido || echo       Apache ya estaba inactivo
net stop mysql     >nul 2>&1 && echo       MySQL  detenido || echo       MySQL  ya estaba inactivo

echo.
echo  ================================
echo   Proyecto cerrado correctamente
echo  ================================
echo.
pause
