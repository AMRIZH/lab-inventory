@echo off
title Inventaris Lab - Laravel Server
color 0A

echo ========================================
echo    INVENTARIS LAB - LARAVEL SERVER
echo ========================================
echo.

REM Change to the project directory
cd /d "D:\magang\inventaris-lab"

REM Check if we're in the right directory
if not exist "artisan" (
    echo ERROR: Laravel project not found!
    echo Make sure this file is in the correct directory.
    echo Current directory: %CD%
    pause
    exit /b 1
)

echo [INFO] Starting Laravel development server...
echo [INFO] Project directory: %CD%
echo [INFO] Server will be available at: http://127.0.0.1:8000
echo.
echo ========================================
echo    SERVER IS STARTING...
echo ========================================
echo.
echo Press Ctrl+C to stop the server
echo.

REM Start the Laravel development server
php artisan serve --host=127.0.0.1 --port=8000

REM If the server stops, show this message
echo.
echo ========================================
echo    SERVER STOPPED
echo ========================================
echo.
pause
