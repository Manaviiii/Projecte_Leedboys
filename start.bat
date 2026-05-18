@echo off
title LEDBOYS - Iniciando...
echo.
echo  ================================
echo   LEDBOYS - Iniciando proyecto
echo  ================================
echo.

:: Apache y MySQL (requiere admin)
echo [1/3] Iniciando Apache y MySQL...
net start Apache2.4 >nul 2>&1 && echo       Apache OK || echo       Apache ya estaba activo
net start mysql     >nul 2>&1 && echo       MySQL  OK || echo       MySQL  ya estaba activo

:: Laravel
echo [2/3] Iniciando Laravel...
start "LEDBOYS - Laravel" cmd /k "cd /d %~dp0Ledboys && php artisan serve"

:: Espera un momento para que Laravel arranque
timeout /t 2 /nobreak >nul

:: Frontend
echo [3/3] Iniciando Frontend...
start "LEDBOYS - Frontend" cmd /k "cd /d %~dp0ledboys-frontend && npm run dev"

echo.
echo  ================================
echo   Todo listo!
echo   Laravel:  http://127.0.0.1:8000
echo   Frontend: http://localhost:5173
echo  ================================
echo.
echo  Para cerrar todo ejecuta stop.bat
echo.
pause
