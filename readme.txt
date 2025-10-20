=== Deodar ===
Contributors: brockcataldi
Tags: acf, acf pro, modular bridge, developer tools, wordpress api
Requires at least: 6.8
Tested up to: 6.8
Requires PHP: 8.2
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Developer friendly bridge to the ACF Pro and WordPress APIs with modular architecture and modern PHP features.

== Description ==

Deodar is a powerful WordPress plugin designed to bridge the gap between Advanced Custom Fields Pro and the WordPress API, providing developers with a clean, modular foundation for building custom WordPress solutions.

**Key Features:**

* **Modular Architecture**: Clean, organized codebase with separation of concerns
* **ACF Pro Integration**: Seamless bridge to Advanced Custom Fields Pro functionality  
* **Asset Management**: Built-in classes for managing stylesheets and scripts
* **Block Support**: Enhanced support for WordPress blocks and block styles
* **Type Safety**: PHP 8.2+ with enum support for better type checking
* **WordPress Standards**: Follows WordPress coding standards and best practices

**Perfect for:**

* WordPress developers building custom themes and plugins
* Teams working with ACF Pro who need better organization
* Projects requiring modern PHP features and type safety
* Developers who want a solid foundation for WordPress development

**What makes Deodar special:**

Unlike other WordPress plugins that add functionality, Deodar provides a developer-friendly framework that makes working with WordPress and ACF Pro more organized and maintainable. It's built with modern PHP 8.2+ features and follows WordPress coding standards, making it perfect for professional development workflows.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/deodar` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. That's it! The plugin automatically initializes and is ready to use.

**Requirements:**
* WordPress 6.8 or higher
* PHP 8.2 or higher
* ACF Pro (recommended for full functionality)

== Frequently Asked Questions ==

= Does this work with the latest WordPress? =
Yes, Deodar is tested and compatible with WordPress 6.8.

= What PHP version do I need? =
Deodar requires PHP 8.2 or higher to take advantage of modern PHP features like enums and typed properties.

= Do I need ACF Pro? =
While Deodar works without ACF Pro, it's designed to enhance ACF Pro functionality. For the best experience, we recommend using ACF Pro alongside Deodar.

= Is this plugin for developers only? =
Yes, Deodar is specifically designed for WordPress developers who want a better foundation for building custom solutions. It's not intended for end-users who just want to add functionality to their site.

= How is this different from other WordPress frameworks? =
Deodar focuses specifically on bridging ACF Pro and WordPress APIs with a modular, type-safe approach. It's lightweight, follows WordPress standards, and provides just the foundation you need without unnecessary bloat.

== Screenshots ==

1. Clean, modular codebase structure
2. Asset management classes in action
3. Block style registration examples
4. Type-safe enum implementations

== Changelog ==

= 2.0.0 =
* Complete rewrite with modern PHP 8.2+ features
* Modular architecture implementation
* Enhanced ACF Pro integration
* Improved asset management system
* WordPress 6.8 compatibility
* Added comprehensive type safety with enums
* Implemented proper WordPress coding standards
* Added utility functions for array and content type detection

= 1.0.0 =
* Initial release with basic ACF Pro bridge functionality

== Upgrade Notice ==

= 2.0.0 =
Major version update with complete rewrite. Modern PHP 8.2+ required. Full backward compatibility maintained for existing implementations.

= 1.0.0 =
Initial release of Deodar plugin.

== Contributing ==

Deodar is open source and welcomes contributions! Here's how to get started:

**Development Setup:**
1. Clone the repository
2. Run `composer install` to install development dependencies
3. Use `vendor/bin/phpcs` to check code standards
4. Use `vendor/bin/phpcbf` to auto-fix code formatting

**Code Standards:**
* Follow WordPress coding standards
* Use PHP 8.2+ features appropriately
* Include proper documentation
* Test your changes thoroughly

**Getting Help:**
* Visit [deodar.io](https://deodar.io) for documentation
* Contact the author at [brockcataldi.com](https://brockcataldi.com)
* Check the GitHub repository for issues and discussions