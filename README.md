# Deodar

## Installation

1. Upload the plugin files to the `/wp-content/plugins/deodar` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. That’s it.

## Contributing 

### Installing Development Packages
```bash
composer update
```

### Running Code Sniffer
```bash
vendor/bin/phpcs --ignore=./vendor/ . --standard=WordPress
```

### Running Code Beautify
```bash
vendor/bin/phpcbf --ignore=./vendor/ . --standard=WordPress
```

### Packaging 
```bash
composer archive --format=zip --file=deodar --dir=./build
```