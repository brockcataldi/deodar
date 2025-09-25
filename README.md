# Deodar

## Installation

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