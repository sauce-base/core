# Icon Registry Generator Plugin

Automatic icon registry generation for Laravel + Vite + unplugin-icons projects.

## Plugin Structure

```
vite/plugins/icon-registry/
├── index.js       # Main plugin implementation
├── template.js    # TypeScript registry template generator
└── README.md      # This file
```

## What It Does

Scans PHP files for icon references and automatically generates a TypeScript registry with icon imports and mappings. No manual icon registration needed!

## Usage

### Installation

Already integrated in vite.config.js:

```javascript
import { iconRegistryGenerator } from './vite/plugins/icon-registry';

export default defineConfig({
    plugins: [
        iconRegistryGenerator({
            scanPaths: ['app', 'modules/*/app'],
            outputPath: 'resources/js/generated/icon-registry.ts',
            debounceMs: 300,
        }),
        // ... other plugins
    ],
    resolve: {
        alias: {
            '@generated': path.resolve(__dirname, 'resources/js/generated'),
            // ... other aliases
        },
    },
});
```

### Adding Icons

Simply add icons to your PHP files:

```php
Navigation::add('Dashboard', route('dashboard'), function (Section $section) {
    $section->attributes([
        'icon' => 'lucide:square-terminal',  // That's it!
    ]);
});
```

The plugin will:

- ✅ Auto-detect the icon reference
- ✅ Generate TypeScript imports
- ✅ Update the registry automatically
- ✅ Trigger HMR for immediate updates

### Supported Icon Libraries

Works with any iconify collection via unplugin-icons:

- lucide
- heroicons
- mdi (Material Design Icons)
- circle-flags
- fluent
- And 150+ other collections

### Configuration Options

| Option       | Type       | Default                                        | Description                                         |
| ------------ | ---------- | ---------------------------------------------- | --------------------------------------------------- |
| `scanPaths`  | `string[]` | `['app', 'modules/*/app']`                     | Paths to scan for PHP files                         |
| `outputPath` | `string`   | `'resources/js/generated/icon-registry.ts'`    | Where to generate the registry                      |
| `debounceMs` | `number`   | `300`                                          | Debounce delay for file watching                    |

### How It Works

1. **Build Start**: Scans all PHP files in configured paths
2. **Icon Extraction**: Uses regex to find `'icon' => 'library:icon-name'` patterns
3. **Registry Generation**: Creates TypeScript file with imports and mappings
4. **File Watching**: Monitors PHP files for changes (dev mode only)
5. **HMR**: Regenerates registry when icons are added/removed

### Module System Integration

- Reads `modules_statuses.json` to only scan enabled modules
- Skips disabled modules automatically
- Follows Laravel modular architecture patterns

### Generated Output

Example of generated `resources/js/generated/icon-registry.ts`:

```typescript
import type { Component } from 'vue';
import IconSettings from '~icons/lucide/settings';
import IconGithub from '~icons/mdi/github';

export const iconRegistry: Record<string, Component> = {
    'lucide:settings': IconSettings,
    'mdi:github': IconGithub,
};

export function isIconRegistered(icon: string): boolean {
    return icon in iconRegistry;
}
```

### Performance

- **Initial scan**: <100ms overhead on Vite startup
- **File watching**: 300ms debounce prevents excessive regeneration
- **Production**: Zero runtime overhead (static imports)
- **Dependencies**: Zero! Uses only native Node.js APIs

### Customizing the Template

The generated TypeScript file format can be customized by modifying `template.js`:

```javascript
// template.js
export function generateIconRegistryTemplate(parsedIcons) {
    // Customize the generated code structure here
    // parsedIcons contains: { identifier, library, name, importPath, importName }
}
```

This separation makes it easy to:

- Modify the generated code format
- Add custom exports or utilities
- Change the TypeScript interface structure
- Add JSDoc comments or type annotations

### Future npm Package

This plugin is designed to be extractable as `unplugin-icon-registry` in the future:

- Clean, focused functionality
- Framework-agnostic design potential
- Well-documented and tested
- Separated template for easy customization
