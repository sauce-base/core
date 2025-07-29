# E2E Testing with Playwright

## Quick Start

1. **Start development servers** (in one terminal):
   ```bash
   composer dev
   ```
   This starts both Laravel (port 8000) and Vite (port 5173) servers.

2. **Run tests** (in another terminal):
   ```bash
   npm run test:e2e
   ```

## Development Workflow

### Interactive Testing
```bash
npm run test:e2e:ui
```
Opens Playwright UI for interactive test development and debugging.

### Debug Mode
```bash
npm run test:e2e:debug
```
Runs tests in debug mode with inspector.

### Headed Mode
```bash
npm run test:e2e:headed
```
Runs tests with visible browser for debugging.

## Troubleshooting

### Tests are slow or hanging
- Make sure `composer dev` is running in a separate terminal
- Check that both servers are accessible:
  - Laravel: http://localhost:8000
  - Vite: http://localhost:5173

### Port conflicts
- If port 8000 is in use, Laravel will use the next available port
- Update `baseURL` in `playwright.config.ts` if needed

### Database issues
- Make sure test database is set up
- Run migrations: `php artisan migrate`
- Seed test users: `php artisan db:seed`

### Authentication tests failing
- Update test user credentials in `tests/e2e/fixtures/users.ts`
- Or create the test user in your database:
  ```sql
  INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at) 
  VALUES ('Test User', 'test@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW(), NOW(), NOW());
  ```
  (Password: `password`)

## Test Structure

```
tests/e2e/
├── auth/           # Authentication tests
├── pages/          # Page Object Models
├── utils/          # Test utilities
├── fixtures/       # Test data
└── example.spec.ts # Basic example tests
```

## Writing New Tests

1. Create Page Object Models in `pages/` for reusable page interactions
2. Use `data-testid` attributes for reliable element selection
3. Store test data in `fixtures/` 
4. Use helper functions from `utils/` for common operations like login