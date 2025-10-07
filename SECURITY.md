# Security Policy

## Supported Versions

We release security updates for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability within Sauce Base, please send an email to security@saucebase.dev. All security vulnerabilities will be promptly addressed.

**Please do not report security vulnerabilities through public GitHub issues.**

## Security Best Practices

### Environment Configuration

1. **Never commit sensitive data**
   - Keep `.env` files out of version control
   - Use strong, unique passwords for all services
   - Rotate API keys and secrets regularly

2. **Production Environment Variables**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_KEY=<generate-with-php-artisan-key:generate>
   ```

3. **Session Security**
   ```bash
   SESSION_DRIVER=database
   SESSION_ENCRYPT=true
   SESSION_LIFETIME=120
   SESSION_SECURE_COOKIE=true  # HTTPS only
   SESSION_HTTP_ONLY=true
   SESSION_SAME_SITE=lax
   ```

4. **CORS Configuration**
   ```bash
   # Restrict to your actual domains in production
   CORS_ALLOWED_ORIGINS=https://yourdomain.com,https://app.yourdomain.com
   ```

### Security Headers

The application includes security headers middleware that automatically sets:

- **Content-Security-Policy (CSP)**: Prevents XSS attacks
- **X-Content-Type-Options**: Prevents MIME type sniffing
- **X-Frame-Options**: Prevents clickjacking
- **X-XSS-Protection**: Additional XSS protection
- **Referrer-Policy**: Controls referrer information
- **Strict-Transport-Security (HSTS)**: Forces HTTPS in production
- **Permissions-Policy**: Restricts access to browser features

To customize CSP in production:
```bash
CONTENT_SECURITY_POLICY="default-src 'self'; script-src 'self' https://trusted-cdn.com"
```

### Authentication & Authorization

1. **Password Requirements**
   - Minimum 8 characters (configurable)
   - Use Laravel's built-in password validation
   - Implement rate limiting on login attempts

2. **Two-Factor Authentication**
   - Enable 2FA for admin accounts
   - Use time-based one-time passwords (TOTP)

3. **API Security**
   - Use Laravel Sanctum for API authentication
   - Implement proper CSRF protection
   - Rate limit API endpoints

### Database Security

1. **Prepared Statements**
   - Always use Eloquent ORM or Query Builder
   - Avoid raw SQL queries when possible
   - If raw queries are needed, use parameter binding

2. **Database Credentials**
   - Use strong, unique database passwords
   - Restrict database user permissions
   - Enable SSL/TLS for database connections in production

### File Upload Security

1. **Validation**
   - Validate file types and sizes
   - Use Laravel's file validation rules
   - Scan uploaded files for malware in production

2. **Storage**
   - Store uploads outside web root when possible
   - Use signed URLs for private file access
   - Implement proper access controls

### HTTPS Configuration

1. **Force HTTPS in Production**
   ```php
   // Add to App\Providers\AppServiceProvider::boot()
   if (app()->environment('production')) {
       URL::forceScheme('https');
   }
   ```

2. **SSL/TLS Certificates**
   - Use Let's Encrypt or commercial certificates
   - Keep certificates up to date
   - Enable HSTS with appropriate max-age

### Dependency Management

1. **Regular Updates**
   ```bash
   # Check for PHP vulnerabilities
   composer audit
   
   # Check for npm vulnerabilities
   npm audit
   ```

2. **Automated Scanning**
   - Enable Dependabot or similar tools
   - Review and apply security patches promptly

### Logging & Monitoring

1. **Security Logging**
   - Log authentication attempts
   - Monitor for suspicious activity
   - Set up alerts for security events

2. **Error Handling**
   - Never expose stack traces in production
   - Log errors securely
   - Use proper error pages

### Deployment Security

1. **Server Hardening**
   - Keep server software updated
   - Disable unnecessary services
   - Configure firewall rules
   - Use fail2ban or similar

2. **File Permissions**
   ```bash
   # Recommended permissions
   chmod -R 755 storage bootstrap/cache
   chmod -R 644 .env
   ```

3. **Composer Autoloader**
   ```bash
   # Optimize for production
   composer install --no-dev --optimize-autoloader
   ```

### Code Security

1. **Input Validation**
   - Validate all user input
   - Use Form Requests for validation
   - Sanitize output where necessary

2. **XSS Prevention**
   - Vue.js templates escape by default
   - Avoid `v-html` with user content
   - Use Content Security Policy

3. **CSRF Protection**
   - Laravel's CSRF protection is enabled by default
   - Include CSRF token in forms
   - Sanctum handles CSRF for SPA

4. **SQL Injection Prevention**
   - Use Eloquent ORM
   - Use Query Builder with parameter binding
   - Avoid DB::raw() with user input

### Third-Party Services

1. **OAuth Providers**
   - Store client secrets securely
   - Use state parameter for OAuth flows
   - Validate redirect URIs

2. **API Keys**
   - Store in environment variables
   - Rotate regularly
   - Restrict by IP when possible

## Security Checklist for Production

- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Enable session encryption (`SESSION_ENCRYPT=true`)
- [ ] Configure CORS to allow only your domains
- [ ] Enable HTTPS with valid SSL certificate
- [ ] Set secure cookie flags (`SESSION_SECURE_COOKIE=true`)
- [ ] Configure security headers (enabled by default)
- [ ] Review and set strong database credentials
- [ ] Enable rate limiting on authentication endpoints
- [ ] Set up proper file upload validation
- [ ] Configure proper backup strategy
- [ ] Enable monitoring and logging
- [ ] Run `composer audit` and `npm audit`
- [ ] Review all environment variables
- [ ] Set up firewall rules
- [ ] Configure proper file permissions
- [ ] Disable directory listing
- [ ] Remove test/development files

## Additional Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Vue.js Security Best Practices](https://vuejs.org/guide/best-practices/security.html)

## Contact

For security concerns, contact: security@saucebase.dev
