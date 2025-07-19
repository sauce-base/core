# Multi-stage build for Nginx with Node.js assets
FROM node:20-alpine AS assets

WORKDIR /var/www

# Copy package files
COPY package*.json ./

# Install npm dependencies
RUN npm ci

# Copy source files
COPY . .

# Build assets
RUN npm run build

# Production Nginx stage
FROM nginx:alpine

# Copy Nginx configuration
COPY docker/production/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy built assets from previous stage
COPY --from=assets /var/www/public /var/www/public

# Set proper permissions
RUN chown -R nginx:nginx /var/www/public

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]