# Deodar

A developer-friendly bridge to the ACF Pro and WordPress APIs, providing a modular and extensible foundation for WordPress development.

## Features

- **Modular Architecture**: Clean, organized codebase with separation of concerns
- **ACF Pro Integration**: Seamless bridge to Advanced Custom Fields Pro functionality
- **Asset Management**: Built-in classes for managing stylesheets and scripts
- **Block Support**: Enhanced support for WordPress blocks, block styles, and block variations
- **Type Safety**: PHP 8.2+ with enum support for better type checking
- **WordPress Standards**: Follows WordPress coding standards and best practices

## Requirements

- WordPress 6.8 or higher
- PHP 8.2 or higher
- ACF Pro (recommended for full functionality)

## Installation

### Manual Installation

1. Download the plugin files
2. Upload to `/wp-content/plugins/deodar` directory
3. Activate the plugin through the 'Plugins' screen in WordPress

### Development Installation

1. Clone the repository:
   ```bash
   git clone <repository-url> wp-content/plugins/deodar
   ```

2. Install development dependencies:
   ```bash
   composer install
   ```

## Development

### Code Standards

This plugin follows WordPress coding standards. Development tools are included:

#### Installing Development Packages
```bash
composer install
```

#### Running Code Sniffer
```bash
vendor/bin/phpcs --ignore=./vendor/ . --standard=WordPress
```

#### Running Code Beautify
```bash
vendor/bin/phpcbf --ignore=./vendor/ . --standard=WordPress
```

### Project Structure

```
deodar/
├── lib/
│   ├── class-deodar.php          # Main plugin class
│   ├── deodar-functions.php      # Utility functions
│   ├── deodar-enums.php          # Enum definitions
│   ├── deodar-models.php         # Model definitions
│   ├── models/                   # Individual model classes and enums
│   │   ├── class-deodar-enqueuable.php
│   │   ├── class-deodar-script.php
│   │   ├── class-deodar-style.php
│   │   ├── class-deodar-block-style.php
│   │   ├── class-deodar-customization.php
│   │   ├── class-deodar-support.php
│   │   ├── enum-deodar-array-type.php
│   │   └── enum-deodar-scan-type.php
├── deodar.php                    # Plugin entry point
└── composer.json                 # Dependencies
```

### Building

#### Packaging for Distribution
```bash
composer archive --format=zip --file=deodar --dir=./build
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes following WordPress coding standards
4. Run the code sniffer to ensure compliance
5. Submit a pull request

## License

This plugin is licensed under the GPL v2 or later.

## Support

For support and questions, please visit [deodar.io](https://deodar.io).

## Changelog

### 2.0.0
- Complete rewrite with modern PHP 8.2+ features
- Modular architecture implementation
- Enhanced ACF Pro integration
- Improved asset management system
- WordPress 6.8 compatibility
- Added block variations support with automatic loading
- Added utility functions for safe file inclusion and array searching
- Reorganized file structure for better maintainability
- Enhanced block style management with type-safe operations