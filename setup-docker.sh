#!/bin/bash

# Madrasa Management System - Docker Setup Script
# This script helps you get started with Docker for the first time

set -e

echo "🐳 Setting up Madrasa Management System with Docker..."
echo ""

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first:"
    echo "   Windows: https://docs.docker.com/desktop/install/windows-install/"
    echo "   macOS: https://docs.docker.com/desktop/install/mac-install/"
    echo "   Linux: https://docs.docker.com/engine/install/"
    exit 1
fi

# Check if Docker Compose is available
if ! command -v docker-compose &> /dev/null && ! docker compose version &> /dev/null; then
    echo "❌ Docker Compose is not available. Please install Docker Compose."
    exit 1
fi

echo "✅ Docker is installed"

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    echo "📋 Creating .env file from template..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "ℹ️  .env file already exists"
fi

# Generate application key if not set
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "🔑 Generating application key..."
    # Try to generate with PHP if available
    if command -v php &> /dev/null; then
        php artisan key:generate --no-interaction 2>/dev/null || true
    fi

    # If PHP artisan didn't work or APP_KEY is still empty, generate manually
    if ! grep -q "^APP_KEY=base64:" .env; then
        APP_KEY=$(openssl rand -base64 32)
        if [[ "$OSTYPE" == "darwin"* ]]; then
            # macOS
            sed -i '' "s|^APP_KEY=.*|APP_KEY=base64:$APP_KEY|" .env
        else
            # Linux/Windows
            sed -i "s|^APP_KEY=.*|APP_KEY=base64:$APP_KEY|" .env
        fi
        echo "✅ Application key generated"
    fi
else
    echo "ℹ️  Application key already set"
fi

echo ""
echo "🚀 Starting Docker containers..."
echo "This may take a few minutes on first run..."
echo ""

# Use docker compose (newer syntax) if available, otherwise docker-compose
if docker compose version &> /dev/null; then
    docker compose up -d --build
else
    docker-compose up -d --build
fi

echo ""
echo "⏳ Waiting for services to be ready..."
sleep 10

# Check if containers are running
if docker compose version &> /dev/null; then
    if docker compose ps | grep -q "Up"; then
        echo "✅ Docker containers are running"
    else
        echo "❌ Some containers failed to start. Check logs with: docker compose logs"
        exit 1
    fi
else
    if docker-compose ps | grep -q "Up"; then
        echo "✅ Docker containers are running"
    else
        echo "❌ Some containers failed to start. Check logs with: docker-compose logs"
        exit 1
    fi
fi

echo ""
echo "🎉 Setup complete! Access your application at:"
echo "   📱 Main App:     http://localhost:8000"
echo "   👨‍💼 Admin Panel: http://localhost:8000/admin"
echo "   🗄️  phpMyAdmin:  http://localhost:8080"
echo ""
echo "📊 Default Admin Credentials:"
echo "   Email: admin@madrasa.com"
echo "   Password: (check database/seeders/DatabaseSeeder.php)"
echo ""
echo "🛠 Useful commands:"
echo "   View logs: docker compose logs -f app"
echo "   Stop app:  docker compose down"
echo "   Restart:   docker compose restart"
echo ""
echo "Happy coding! 🎉"