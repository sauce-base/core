#!/bin/bash

# Tadone Development Environment Setup Script
# This script resets and initializes the development environment

set -e  # Exit on any error

echo "🚀 Tadone Development Environment Setup"
echo "======================================"

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker and try again."
    exit 1
fi

# Stop any running containers
echo "🛑 Stopping existing containers..."
docker compose down --remove-orphans

# Remove volumes to ensure clean state
echo "🗑️  Removing volumes for clean reset..."
docker compose down -v

# Pull latest images
echo "📥 Pulling latest Docker images..."
docker compose pull

# Build containers
echo "🏗️  Building containers..."
docker compose build --no-cache

# Start services
echo "🚀 Starting services..."
docker compose up -d

# Wait for services to be ready
echo "⏳ Waiting for services to be ready..."
sleep 10

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
docker compose exec -T workspace composer install

# Install Node dependencies
echo "📦 Installing Node.js dependencies..."
docker compose exec -T workspace npm ci

# Generate app key if not exists
echo "🔑 Generating application key..."
if ! grep -q "APP_KEY=" .env 2>/dev/null || [ -z "$(grep "APP_KEY=" .env | cut -d'=' -f2)" ]; then
    docker compose exec -T workspace php artisan key:generate
fi

# Run database migrations and seed
echo "🗄️  Setting up database..."
docker compose exec -T workspace php artisan migrate:fresh --seed

# Clear all caches
echo "🧹 Clearing caches..."
docker compose exec -T workspace php artisan optimize:clear

# Run quality checks
echo "🔍 Running code quality checks..."
docker compose exec -T workspace ./vendor/bin/pint
docker compose exec -T workspace npm run lint

# Build frontend assets
echo "🎨 Building frontend assets..."
docker compose exec -T workspace npm run build

# Check SSL certificates
echo "🔒 Checking SSL certificates..."
if [ ! -f "docker/development/ssl/app.pem" ]; then
    echo "⚠️  SSL certificates not found!"
    echo "Please run the following commands to set up SSL:"
    echo ""
    echo "1. Install mkcert:"
    echo "   macOS: brew install mkcert"
    echo "   Windows: choco install mkcert"
    echo "   Linux: see https://github.com/FiloSottile/mkcert#installation"
    echo ""
    echo "2. Install mkcert CA in system trust store:"
    echo "   mkcert -install"
    echo ""
    echo "3. Generate SSL certificates:"
    echo "   cd docker/development/ssl"
    echo "   mkcert app.tadone.test localhost 127.0.0.1 ::1"
    echo ""
    echo "4. Add domain to hosts file:"
    echo "   echo '127.0.0.1 app.tadone.test' | sudo tee -a /etc/hosts"
    echo ""
else
    echo "✅ SSL certificates found!"
fi

# Check hosts file
if ! grep -q "app.tadone.test" /etc/hosts 2>/dev/null; then
    echo "⚠️  Domain not found in hosts file!"
    echo "Please add the following line to your /etc/hosts file:"
    echo "127.0.0.1 app.tadone.test"
else
    echo "✅ Domain configured in hosts file!"
fi

# Final status check
echo ""
echo "🎉 Setup complete!"
echo ""
echo "📋 Next steps:"
echo "1. Access your app at: https://app.tadone.test"
echo "2. Start development with: docker compose exec workspace npm run dev"
echo "3. Monitor queues with: docker compose exec workspace php artisan horizon"
echo ""
echo "🔧 Useful commands:"
echo "• Run tests: docker compose exec workspace composer test"
echo "• Run E2E tests: docker compose exec workspace npm run test:e2e"
echo "• Format code: docker compose exec workspace ./vendor/bin/pint"
echo "• Lint frontend: docker compose exec workspace npm run lint"
echo ""
echo "✅ Development environment is ready!"