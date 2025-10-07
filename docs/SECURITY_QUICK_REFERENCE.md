# Security Quick Reference

Quick reference for common security configurations in Sauce Base.

## Environment Variables

### Production Essentials
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
CORS_ALLOWED_ORIGINS=https://yourdomain.com
ENABLE_SECURITY_HEADERS=true
```

### Development
```bash
APP_ENV=local
APP_DEBUG=true
APP_URL=https://localhost
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=null
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:5173
ENABLE_SECURITY_HEADERS=false
```

## Security Headers

The `SecurityHeaders` middleware automatically applies these headers in production:

### Content Security Policy (CSP)
Controls which resources can be loaded:
```bash
CONTENT_SECURITY_POLICY="default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self'; frame-ancestors 'none'"
```

### Other Headers
- **X-Content-Type-Options**: `nosniff` - Prevents MIME sniffing
- **X-XSS-Protection**: `1; mode=block` - Enables XSS filter
- **X-Frame-Options**: `DENY` - Prevents clickjacking
- **Referrer-Policy**: `strict-origin-when-cross-origin` - Controls referrer info
- **HSTS**: `max-age=31536000; includeSubDomains` - Forces HTTPS (production only)

## CORS Configuration

### Default (Development)
```php
'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000,http://localhost:5173'))
```

### Production Example
```bash
CORS_ALLOWED_ORIGINS=https://yourdomain.com,https://app.yourdomain.com,https://admin.yourdomain.com
```

## Session Security

### Recommended Production Settings
```bash
SESSION_DRIVER=database          # or redis for better performance
SESSION_LIFETIME=120             # 2 hours
SESSION_ENCRYPT=true             # Encrypt session data
SESSION_SECURE_COOKIE=true       # HTTPS only
SESSION_HTTP_ONLY=true           # No JavaScript access
SESSION_SAME_SITE=lax            # CSRF protection
```

### Session Drivers
- `database` - Good for most cases
- `redis` - Best performance for high traffic
- `cookie` - Not recommended for production
- `file` - Not recommended for multi-server setups

## Authentication

### Rate Limiting
```bash
LOGIN_RATE_LIMIT=5  # Max login attempts per minute
```

### Password Hashing
```bash
BCRYPT_ROUNDS=12  # Higher = more secure but slower
```

### Sanctum (API Authentication)
```bash
SANCTUM_STATEFUL_DOMAINS=yourdomain.com
```

## Database Security

### Production Configuration
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1              # Use private IP
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_db_user       # NOT root
DB_PASSWORD=long_random_string # 32+ characters
```

### Best Practices
- Use dedicated database user (not root)
- Grant only necessary permissions
- Use strong passwords (32+ random characters)
- Enable SSL/TLS for remote connections
- Regularly backup databases

## API Security

### CSRF Protection
Laravel's CSRF protection is enabled by default for web routes.

For API routes using Sanctum:
```javascript
// Frontend: Include CSRF cookie
await axios.get('/sanctum/csrf-cookie');
// Then make authenticated requests
```

### API Rate Limiting
Configure in `routes/api.php`:
```php
Route::middleware('throttle:60,1')->group(function () {
    // 60 requests per minute
});
```

## File Upload Security

### Validation Example
```php
$request->validate([
    'file' => 'required|file|mimes:jpg,png,pdf|max:2048', // 2MB
]);
```

### Storage
```php
// Store in private storage (not public)
$path = $request->file('file')->store('uploads', 'private');

// Generate temporary signed URL for access
return Storage::temporaryUrl($path, now()->addMinutes(30));
```

## Common Vulnerabilities & Prevention

### XSS (Cross-Site Scripting)
✅ **Prevention**:
- Vue.js escapes by default
- Avoid `v-html` with user content
- Use Content Security Policy
- Sanitize user input

### SQL Injection
✅ **Prevention**:
- Use Eloquent ORM (parameterized queries)
- Use Query Builder with bindings
- Avoid `DB::raw()` with user input

### CSRF (Cross-Site Request Forgery)
✅ **Prevention**:
- Laravel's CSRF protection (enabled by default)
- `@csrf` directive in forms
- `X-CSRF-TOKEN` header in AJAX

### Clickjacking
✅ **Prevention**:
- X-Frame-Options header (automatic)
- CSP frame-ancestors directive

### Session Hijacking
✅ **Prevention**:
- SESSION_SECURE_COOKIE=true
- SESSION_HTTP_ONLY=true
- SESSION_ENCRYPT=true
- Regenerate session on login

## Security Commands

### Check Dependencies
```bash
# PHP dependencies
composer audit

# JavaScript dependencies
npm audit

# Fix npm vulnerabilities
npm audit fix
```

### Cache for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Monitoring & Logging

### Log Levels
```bash
LOG_LEVEL=debug    # Development
LOG_LEVEL=error    # Production
```

### Log Channels
- `stack` - Multiple channels
- `daily` - Daily log files with rotation
- `single` - Single log file
- `syslog` - System log

### Security Events to Log
- Failed login attempts
- Permission denied errors
- Suspicious activity
- API rate limit hits

## Quick Security Audit

Run these checks periodically:

```bash
# Check npm vulnerabilities
npm audit --production

# Check composer vulnerabilities
composer audit

# Verify .env not committed
git status --ignored

# Check file permissions
ls -la .env storage/ bootstrap/cache/

# Test security headers
curl -I https://yourdomain.com
```

## Emergency Response

### If Compromised
1. Take site offline immediately
2. Change all passwords and API keys
3. Rotate APP_KEY: `php artisan key:generate`
4. Review logs for suspicious activity
5. Update all dependencies
6. Scan for malware
7. Restore from clean backup if necessary
8. Notify users if data was compromised

### Key Rotation
```bash
# Rotate application key
php artisan key:generate

# Update API keys in .env
# Then clear config cache
php artisan config:clear
```

## Resources

- [Laravel Security Documentation](https://laravel.com/docs/security)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Vue Security Guide](https://vuejs.org/guide/best-practices/security.html)
- [Full Security Guide](../SECURITY.md)
- [Deployment Checklist](DEPLOYMENT_SECURITY_CHECKLIST.md)
