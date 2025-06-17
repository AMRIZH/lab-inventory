@echo off
title Lab Inventory - Docker Build and Deploy
color 0A

echo ==========================================
echo    Lab Inventory - Docker Build ^& Deploy
echo ==========================================
echo.

REM Check if Docker is running
docker info >nul 2>&1
if errorlevel 1 (
    echo [ERROR] Docker is not running. Please start Docker first.
    pause
    exit /b 1
)

echo [INFO] Docker is running
echo.

REM Check if .env file exists
if not exist ".env" (
    if exist ".env.production" (
        echo [INFO] Copying .env.production to .env
        copy ".env.production" ".env"
    ) else (
        echo [ERROR] No .env file found. Please create one from .env.production
        pause
        exit /b 1
    )
)

REM Build the Docker image
echo [INFO] Building Docker image...
echo [INFO] This may take a few minutes...
docker build -t lab-inventory . --no-cache

if errorlevel 1 (
    echo [ERROR] Failed to build Docker image
    pause
    exit /b 1
)

echo [SUCCESS] Docker image built successfully
echo.

REM Stop existing container if running
docker ps -q -f name=lab-inventory >nul 2>&1
if not errorlevel 1 (
    echo [INFO] Stopping existing container...
    docker stop lab-inventory
    docker rm lab-inventory
)

REM Run the container
echo [INFO] Starting Lab Inventory container...
docker run -d --name lab-inventory -p 8080:80 --env-file .env --restart unless-stopped lab-inventory

if errorlevel 1 (
    echo [ERROR] Failed to start container
    pause
    exit /b 1
)

echo [SUCCESS] Container started successfully
echo.
echo ==========================================
echo    APPLICATION IS RUNNING
echo ==========================================
echo.
echo [URLs]
echo Application: http://localhost:8080
echo Dashboard:   http://localhost:8080/dashboard
echo Lab FKI:     http://localhost:8080/labs/lab-fki
echo.
echo [COMMANDS]
echo View logs:   docker logs -f lab-inventory
echo Stop:        docker stop lab-inventory
echo Restart:     docker restart lab-inventory
echo.
echo Press any key to exit...
pause >nul
