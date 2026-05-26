@echo off
chcp 65001 >nul
title LEDBOYS — Instalación de dependencias

echo.
echo ╔══════════════════════════════════════════════════════════╗
echo ║         LEDBOYSS ^& LEDGIRLSS — Setup inicial            ║
echo ╚══════════════════════════════════════════════════════════╝
echo.

:: ─────────────────────────────────────────
:: RUTAS — ajusta si tu estructura es distinta
:: ─────────────────────────────────────────
set BACKEND=%~dp0Ledboys
set FRONTEND=%~dp0Ledboys-frontend

:: ─────────────────────────────────────────
:: COMPROBAR REQUISITOS
:: ─────────────────────────────────────────
echo [1/6] Comprobando requisitos...

where php >nul 2>&1
if %errorlevel% neq 0 (
    echo  ✗ PHP no encontrado. Instala XAMPP o Laragon y asegurate de que php esta en el PATH.
    pause & exit /b 1
)
echo  ✓ PHP encontrado

where composer >nul 2>&1
if %errorlevel% neq 0 (
    echo  ✗ Composer no encontrado. Descargalo en https://getcomposer.org
    pause & exit /b 1
)
echo  ✓ Composer encontrado

where node >nul 2>&1
if %errorlevel% neq 0 (
    echo  ✗ Node.js no encontrado. Descargalo en https://nodejs.org
    pause & exit /b 1
)
echo  ✓ Node.js encontrado

where npm >nul 2>&1
if %errorlevel% neq 0 (
    echo  ✗ npm no encontrado.
    pause & exit /b 1
)
echo  ✓ npm encontrado

echo.

:: ─────────────────────────────────────────
:: BACKEND — Laravel
:: ─────────────────────────────────────────
echo [2/6] Instalando dependencias del backend (Laravel)...
cd /d "%BACKEND%"
if not exist "composer.json" (
    echo  ✗ No se encontro composer.json en %BACKEND%
    echo    Ajusta la variable BACKEND al inicio del script.
    pause & exit /b 1
)
call composer install --ignore-platform-reqs
echo  ✓ Dependencias PHP instaladas
echo.

:: ─────────────────────────────────────────
:: .ENV BACKEND
:: ─────────────────────────────────────────
echo [3/6] Configurando .env del backend...
if not exist ".env" (
    copy ".env.example" ".env" >nul
    echo  ✓ .env creado desde .env.example
    echo  ! Recuerda configurar DB, STRIPE_SECRET y MAIL en el .env
) else (
    echo  ✓ .env ya existe, no se sobreescribe
)
php artisan key:generate --ansi
echo.

:: ─────────────────────────────────────────
:: BASE DE DATOS
:: ─────────────────────────────────────────
echo [4/6] Ejecutando migraciones y seeders...
php artisan migrate --force
php artisan db:seed --force
php artisan config:clear
php artisan cache:clear
echo  ✓ Base de datos lista
echo.

:: ─────────────────────────────────────────
:: FRONTEND — React + Vite
:: ─────────────────────────────────────────
echo [5/6] Instalando dependencias del frontend (React/Vite)...
cd /d "%FRONTEND%"
if not exist "package.json" (
    echo  ✗ No se encontro package.json en %FRONTEND%
    echo    Ajusta la variable FRONTEND al inicio del script.
    pause & exit /b 1
)
call npm install
echo  ✓ Dependencias npm instaladas
echo.

:: ─────────────────────────────────────────
:: RESUMEN
:: ─────────────────────────────────────────
echo [6/6] Instalacion completada.
echo.
echo ╔══════════════════════════════════════════════════════════╗
echo ║  Para arrancar el proyecto:                              ║
echo ║                                                          ║
echo ║  Backend:   cd Ledboys                                   ║
echo ║             php artisan serve                            ║
echo ║                                                          ║
echo ║  Frontend:  cd Ledboys-frontend                          ║
echo ║             npm run dev                                  ║
echo ║                                                          ║
echo ║  ! Asegurate de tener MySQL arrancado (XAMPP/Laragon)    ║
echo ╚══════════════════════════════════════════════════════════╝
echo.
pause
