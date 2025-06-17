@echo off
title Inventaris Lab - Development Environment
color 0A

echo ========================================
echo    INVENTARIS LAB - DEV ENVIRONMENT
echo ========================================
echo.

REM Change to the project directory
cd /d "D:\magang\inventaris-lab"

REM Check if we're in the right directory
if not exist "artisan" (
    echo [ERROR] Laravel project not found!
    echo Make sure this file is in the project root directory.
    echo Current directory: %CD%
    echo.
    pause
    exit /b 1
)

REM Check if PHP is available
php --version >nul 2>&1
if errorlevel 1 (
    echo [ERROR] PHP is not installed or not in PATH!
    echo Please install PHP or add it to your system PATH.
    echo.
    pause
    exit /b 1
)

REM Check if Composer is available
composer --version >nul 2>&1
if errorlevel 1 (
    echo [WARNING] Composer not found in PATH.
    echo Some features may not work properly.
    echo.
)

echo [INFO] PHP Version:
php --version | findstr "PHP"
echo.

echo [INFO] Project directory: %CD%
echo [INFO] Checking Laravel installation...

REM Check if vendor directory exists
if not exist "vendor" (
    echo [WARNING] Vendor directory not found!
    echo You may need to run: composer install
    echo.
)

REM Check if .env file exists
if not exist ".env" (
    echo [WARNING] .env file not found!
    echo You may need to copy .env.example to .env
    echo.
)

echo [INFO] Starting Laravel development server...
echo [INFO] Server URL: http://127.0.0.1:8000
echo [INFO] Dashboard: http://127.0.0.1:8000/dashboard
echo [INFO] Lab FKI: http://127.0.0.1:8000/labs/lab-fki
echo.
echo ========================================
echo    SERVER IS RUNNING
echo ========================================
echo.
echo [TIPS]
echo - Press Ctrl+C to stop the server
echo - Open http://127.0.0.1:8000 in your browser
echo - Check the console for any errors
echo.

REM Start the Laravel development server
php artisan serve --host=127.0.0.1 --port=8000

REM If the server stops, show this message
echo.
echo ========================================
echo    SERVER STOPPED
echo ========================================
echo.
echo Server has been stopped.
echo You can close this window or press any key to exit.
pause
