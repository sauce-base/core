# Production Nginx stage - expects pre-built assets from CI/CD
FROM nginx:alpine

# Copy Nginx configuration
COPY docker/production/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/production/nginx/default.conf /etc/nginx/conf.d/default.conf

# Copy pre-built assets (built in CI/CD pipeline)
COPY ./public /var/www/public

# Set proper permissions
RUN chown -R nginx:nginx /var/www/public

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]