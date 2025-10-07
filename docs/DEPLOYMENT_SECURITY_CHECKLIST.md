# Production Deployment Security Checklist

Use this checklist before deploying Sauce Base to production to ensure your application is secure.

## Pre-Deployment

### Environment Configuration
- [ ] Copy `.env.production.example` to `.env` on production server
- [ ] Generate new application key: `php artisan key:generate`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL` to your production domain (HTTPS)
- [ ] Configure `APP_HOST` with your domain

### Database Security
- [ ] Create production database with strong credentials
- [ ] Use unique database username (not root)
- [ ] Use strong database password (32+ characters, random)
- [ ] Restrict database user to only required permissions
- [ ] Enable SSL/TLS for database connections if remote
- [ ] Set `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

### Session Security
- [ ] Set `SESSION_DRIVER=database` (or redis for better performance)
- [ ] Set `SESSION_ENCRYPT=true`
- [ ] Set `SESSION_SECURE_COOKIE=true` (requires HTTPS)
- [ ] Set `SESSION_HTTP_ONLY=true`
- [ ] Set `SESSION_SAME_SITE=lax` (or strict for better security)
- [ ] Set appropriate `SESSION_LIFETIME` (default: 120 minutes)

### CORS Configuration
- [ ] Set `CORS_ALLOWED_ORIGINS` to your actual domain(s)
- [ ] Remove localhost from allowed origins
- [ ] Example: `CORS_ALLOWED_ORIGINS=https://yourdomain.com,https://app.yourdomain.com`

### Security Headers
- [ ] Set `ENABLE_SECURITY_HEADERS=true`
- [ ] Review and customize `CONTENT_SECURITY_POLICY` if needed
- [ ] Ensure CSP allows only trusted sources

### Authentication & API
- [ ] Set strong `BCRYPT_ROUNDS` (12 or higher)
- [ ] Configure `LOGIN_RATE_LIMIT` (default: 5 attempts)
- [ ] Set `SANCTUM_STATEFUL_DOMAINS` to your domain
- [ ] Generate and store secure API keys for third-party services

### Cache & Queue
- [ ] Set `CACHE_STORE=redis` (recommended for production)
- [ ] Set `QUEUE_CONNECTION=redis` (or database)
- [ ] Configure Redis with password if exposed
- [ ] Set `REDIS_PASSWORD` if using Redis

### Mail Configuration
- [ ] Configure production mail service (not mailpit)
- [ ] Set `MAIL_FROM_ADDRESS` to a real email
- [ ] Set `MAIL_FROM_NAME` to your application name
- [ ] Configure SMTP credentials securely

### Logging
- [ ] Set `LOG_LEVEL=error` or `warning` (not debug)
- [ ] Set `LOG_CHANNEL=stack` or `daily`
- [ ] Configure log rotation
- [ ] Ensure logs directory is writable: `chmod -R 775 storage/logs`

## Server Configuration

### HTTPS/SSL
- [ ] Install valid SSL certificate (Let's Encrypt recommended)
- [ ] Configure server to redirect HTTP to HTTPS
- [ ] Enable HSTS (already handled by middleware in production)
- [ ] Verify SSL configuration with SSL Labs test

### Web Server (Nginx/Apache)
- [ ] Point document root to `/public` directory
- [ ] Disable directory listing
- [ ] Configure proper file permissions
- [ ] Block access to sensitive files (.env, .git, etc.)
- [ ] Enable gzip compression
- [ ] Configure rate limiting

### File Permissions
```bash
# Recommended permissions
sudo chown -R www-data:www-data /path/to/app
sudo chmod -R 755 /path/to/app
sudo chmod -R 775 /path/to/app/storage
sudo chmod -R 775 /path/to/app/bootstrap/cache
sudo chmod 644 /path/to/app/.env
```

### Firewall
- [ ] Configure firewall (UFW, firewalld, etc.)
- [ ] Allow only necessary ports (80, 443, 22)
- [ ] Restrict SSH access by IP if possible
- [ ] Install and configure fail2ban

### PHP Configuration
- [ ] Set `expose_php=Off` in php.ini
- [ ] Set `display_errors=Off` in php.ini
- [ ] Set appropriate `memory_limit`
- [ ] Set appropriate `upload_max_filesize`
- [ ] Set appropriate `post_max_size`
- [ ] Disable dangerous functions: `disable_functions=exec,passthru,shell_exec,system`

## Application Setup

### Dependencies
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `npm ci --production` (or build assets locally and deploy)
- [ ] Run `npm run build` to build production assets

### Database Migrations
- [ ] Run `php artisan migrate --force` (production flag)
- [ ] Create database backups before migrations
- [ ] Test migrations on staging first

### Cache Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Queue Workers
- [ ] Set up queue workers with supervisor/systemd
- [ ] Configure queue worker restart on deployment
- [ ] Set appropriate `--tries` and `--timeout` values

### Scheduler
- [ ] Add Laravel scheduler to cron: `* * * * * php /path/to/artisan schedule:run`

## Security Verification

### Dependency Audit
```bash
composer audit
npm audit --production
```

### Security Headers Check
- [ ] Test with securityheaders.com
- [ ] Verify CSP is working
- [ ] Check HSTS is enabled

### Vulnerability Scanning
- [ ] Run security scanner on production domain
- [ ] Check for common misconfigurations
- [ ] Verify no sensitive files are accessible

### Access Control
- [ ] Verify `.env` is not accessible via web
- [ ] Verify `.git` directory is not accessible
- [ ] Verify `storage` directory is protected
- [ ] Test error pages don't leak information

## Monitoring & Backups

### Monitoring
- [ ] Set up uptime monitoring
- [ ] Configure error monitoring (Sentry, etc.)
- [ ] Set up log monitoring
- [ ] Configure alerts for critical events

### Backups
- [ ] Set up automated database backups
- [ ] Set up automated file backups
- [ ] Test backup restoration process
- [ ] Store backups off-site/in different region
- [ ] Encrypt sensitive backups

### Performance
- [ ] Configure Redis/Memcached for caching
- [ ] Set up CDN for static assets
- [ ] Enable opcache for PHP
- [ ] Configure proper database indexes

## Post-Deployment

### Testing
- [ ] Test authentication flow
- [ ] Test critical user paths
- [ ] Verify email sending works
- [ ] Test file uploads
- [ ] Verify CSRF protection is working
- [ ] Test rate limiting

### Documentation
- [ ] Document deployment process
- [ ] Document rollback procedure
- [ ] Update runbooks
- [ ] Share credentials securely with team

### Maintenance
- [ ] Schedule regular security updates
- [ ] Plan for certificate renewal
- [ ] Set up monitoring alerts
- [ ] Document incident response plan

## Optional but Recommended

### Advanced Security
- [ ] Set up Web Application Firewall (WAF)
- [ ] Configure DDoS protection (Cloudflare, etc.)
- [ ] Implement rate limiting at load balancer level
- [ ] Set up intrusion detection system (IDS)
- [ ] Enable two-factor authentication for admins

### Compliance
- [ ] Review GDPR requirements if applicable
- [ ] Implement privacy policy
- [ ] Set up cookie consent if needed
- [ ] Configure data retention policies

## Regular Maintenance

### Weekly
- [ ] Review application logs for errors
- [ ] Check system resource usage
- [ ] Verify backups are running

### Monthly
- [ ] Run `composer audit` and `npm audit`
- [ ] Review and apply security updates
- [ ] Test backup restoration
- [ ] Review user access and permissions

### Quarterly
- [ ] Conduct security review
- [ ] Update dependencies
- [ ] Review and update documentation
- [ ] Test disaster recovery plan

## Emergency Contacts

Document these before deployment:
- [ ] Hosting provider support
- [ ] Database administrator
- [ ] Security incident contact
- [ ] On-call developer rotation

---

**Remember:** Security is an ongoing process, not a one-time setup. Regularly review and update your security measures.

For detailed security information, see [SECURITY.md](../SECURITY.md).
