# E2E Testing with Playwright (Multi-lingual Compatible)

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

## Multi-language Testing Features

- **Translation-agnostic**: Tests use `data-testid` attributes instead of text content
- **Language utilities**: Helper functions for testing different language versions  
- **Flexible validation**: Error checking via test IDs, not specific text messages

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

## Translation-Compatible Testing Approach

### 1. Use data-testid Attributes (Language-Agnostic)

Instead of:
```typescript
page.getByRole('button', { name: 'Log in' })  // ❌ Breaks in Portuguese
```

Use:
```typescript
page.getByTestId('login-button')  // ✅ Works in any language
```

### 2. Error Checking via Test IDs

Instead of checking specific error text:
```typescript
await expect(page.getByText('Please enter your email')).toBeVisible();  // ❌ English only
```

Check for error presence:
```typescript
await expect(page.getByTestId('email-error')).toBeVisible();  // ✅ Language-agnostic
```

### 3. Required Frontend Test IDs

Your components need these `data-testid` attributes:

```vue
<!-- Login form -->
<form data-testid="login-form">
  <input data-testid="email" type="email" />
  <input data-testid="password" type="password" />
  <button data-testid="login-button">{{ $t('Log in') }}</button>
  <a data-testid="forgot-password-link">{{ $t('Forgot your password?') }}</a>
  <a data-testid="sign-up-link">{{ $t('Sign up') }}</a>
  <input data-testid="remember-me" type="checkbox" />
</form>

<!-- Error messages -->
<div data-testid="email-error" v-if="errors.email">{{ errors.email }}</div>
<div data-testid="password-error" v-if="errors.password">{{ errors.password }}</div>

<!-- Language switcher -->
<select data-testid="language-switcher">
  <option data-testid="language-en" value="en">English</option>
  <option data-testid="language-pt_BR" value="pt_BR">Português</option>
</select>
```

### 4. Language Testing Utilities

```typescript
import { getPageLanguage, setPageLanguage } from '../utils/i18n';

// Detect current language
const language = await getPageLanguage(page);

// Switch language for testing
await setPageLanguage(page, 'pt_BR');
```

## Writing New Tests

1. Create Page Object Models in `pages/` using `data-testid` selectors only
2. Store test data in `fixtures/` without hardcoded text messages  
3. Use `utils/i18n.ts` for language-aware testing
4. Test functionality and behavior, not specific text content

## Current Test Coverage Status

✅ **Translation-compatible:**
- Login form validation  
- Basic page transitions
- Password visibility toggle

❌ **Still needs implementation:**
- Registration flow tests
- Password reset flow tests  
- Logout functionality tests
- Social login provider tests
- Role-based access control tests
- Profile management tests
- Email verification tests