# Commit Message Convention

This project follows the [Conventional Commits](https://www.conventionalcommits.org/) specification.

## Commit Message Format

```
<type>[optional scope]: <description>
```

## Types

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation only changes
- **style**: Changes that do not affect the meaning of the code (white-space, formatting, etc)
- **refactor**: A code change that neither fixes a bug nor adds a feature
- **perf**: A code change that improves performance
- **test**: Adding missing tests or correcting existing tests
- **chore**: Changes to the build process or auxiliary tools and libraries
- **ci**: Changes to CI configuration files and scripts
- **build**: Changes that affect the build system or external dependencies
- **revert**: Reverts a previous commit

## Examples

```bash
feat: add user authentication
fix: resolve login redirect issue
docs: update API documentation
style: format code with prettier
refactor: extract user service
perf: optimize database queries
test: add user registration tests
chore: update dependencies
ci: add GitHub Actions workflow
build: configure webpack for production
```

## Scopes (optional)

Use scopes to specify the area of change:

```bash
feat(auth): add login functionality
fix(ui): correct button alignment
docs(api): update endpoint documentation
```

## How to Commit

### Option 1: Interactive Mode (Recommended)

```bash
npm run commit
```

This will guide you through creating a proper commit message.

### Option 2: Manual Mode

```bash
git commit -m "feat: add user authentication"
```

## Validation

All commit messages are automatically validated. Invalid messages will be rejected with helpful error messages.

## Breaking Changes

For breaking changes, add an exclamation mark after the type:

```bash
feat!: remove deprecated API endpoints
```

Or include `BREAKING CHANGE:` in the footer:

```bash
feat: add new authentication system

BREAKING CHANGE: The old authentication API has been removed
```
