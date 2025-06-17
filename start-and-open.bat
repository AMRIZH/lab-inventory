@echo off
title Inventaris Lab - Auto Start & Open
color 0A

echo ========================================
echo    INVENTARIS LAB - AUTO START
echo ========================================
echo.

REM Change to the project directory
cd /d "D:\magang\inventaris-lab"

REM Check if we're in the right directory
if not exist "artisan" (
    echo [ERROR] Laravel project not found!
    pause
    exit /b 1
)

echo [INFO] Starting Laravel server...
echo [INFO] Server will open automatically in your browser
echo.

REM Start the server in the background and wait a moment
start /min cmd /c "php artisan serve --host=127.0.0.1 --port=8000"

REM Wait for server to start
echo [INFO] Waiting for server to start...
timeout /t 3 /nobreak >nul

REM Check if server is running by trying to connect
echo [INFO] Checking server status...
powershell -Command "try { Invoke-WebRequest -Uri 'http://127.0.0.1:8000' -UseBasicParsing -TimeoutSec 5 | Out-Null; Write-Host '[SUCCESS] Server is running!' } catch { Write-Host '[WARNING] Server may still be starting...' }"

REM Open the browser
echo [INFO] Opening browser...
start http://127.0.0.1:8000/dashboard

echo.
echo ========================================
echo    SERVER STARTED & BROWSER OPENED
echo ========================================
echo.
echo [INFO] Server is running at: http://127.0.0.1:8000
echo [INFO] Browser should open automatically
echo.
echo [IMPORTANT] 
echo Do NOT close this window - it keeps the server running!
echo Press Ctrl+C here to stop the server when you're done.
echo.

REM Keep the window open and show server output
php artisan serve --host=127.0.0.1 --port=8000

echo.
echo [INFO] Server stopped.
pause
