#!/bin/bash

# Build and Deploy Script for Lab Inventory
echo "=========================================="
echo "   Lab Inventory - Docker Build & Deploy"
echo "=========================================="

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

echo "✅ Docker is running"

# Check if .env file exists
if [ ! -f ".env" ]; then
    if [ -f ".env.production" ]; then
        echo "📋 Copying .env.production to .env"
        cp .env.production .env
    else
        echo "❌ No .env file found. Please create one from .env.production"
        exit 1
    fi
fi

# Build the Docker image
echo "🔨 Building Docker image..."
docker build -t lab-inventory .

if [ $? -eq 0 ]; then
    echo "✅ Docker image built successfully"
else
    echo "❌ Failed to build Docker image"
    exit 1
fi

# Stop existing container if running
if docker ps -q -f name=lab-inventory > /dev/null; then
    echo "🛑 Stopping existing container..."
    docker stop lab-inventory
    docker rm lab-inventory
fi

# Run the container
echo "🚀 Starting Lab Inventory container..."
docker run -d \
    --name lab-inventory \
    -p 8080:80 \
    --env-file .env \
    --restart unless-stopped \
    lab-inventory

if [ $? -eq 0 ]; then
    echo "✅ Container started successfully"
    echo "🌐 Application is running at: http://localhost:8080"
    echo "📊 Dashboard: http://localhost:8080/dashboard"
    echo ""
    echo "To view logs: docker logs -f lab-inventory"
    echo "To stop: docker stop lab-inventory"
else
    echo "❌ Failed to start container"
    exit 1
fi
