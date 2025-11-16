# Kha Solar Shop - WordPress E-commerce Plugin

Custom lightweight e-commerce solution for solar equipment with advanced features.

## Version
1.0.0

## Description
Kha Solar Shop is a modern, high-performance WordPress plugin designed specifically for solar equipment businesses. It provides a complete e-commerce solution with advanced features including product comparison, solar system calculator, and smart search.

## Features

### Core Features
- ✅ Custom product post type with full product management
- ✅ Shopping cart system with session support
- ✅ Order management and processing
- ✅ Product categories and tags
- ✅ Product search with autocomplete
- ✅ AJAX-powered cart operations
- ✅ Guest checkout support

### Advanced Features
- 🔍 **Smart Search**: Real-time product search with autocomplete
- ⚖️ **Product Comparison**: Compare up to 4 products side by side
- ⚡ **Solar Calculator**: Calculate optimal solar system size based on energy usage
- 💬 **Chat Integration**: Zalo and Facebook Messenger floating chat bubbles
- 📊 **Product Views Tracking**: Track popular products
- ❤️ **Wishlist**: Save products for later (logged-in users)

### Admin Features
- Full product management with meta boxes
- Technical specifications fields (power, voltage, efficiency, etc.)
- Product gallery management
- Order management dashboard
- Comprehensive settings page
- Order status management
- Email notifications

### Performance Optimizations
- Lazy loading for images
- Minimal database queries
- Efficient AJAX handling
- Session-based cart for guests
- Conditional script loading

## Installation

1. Upload the `kha-solar-shop` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings in 'Settings' > 'Kha Solar Settings'
4. Set up your Zalo and Messenger IDs for chat integration

## System Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher

## File Structure

```
kha-solar-shop/
├── kha-solar-shop.php         # Main plugin file
├── includes/                  # Core PHP classes
│   ├── class-activator.php
│   ├── class-product-post-type.php
│   ├── class-cart.php
│   ├── class-order.php
│   ├── class-ajax-handler.php
│   ├── class-search.php
│   └── class-calculator.php
├── admin/                     # Admin interface
│   ├── class-product-meta-boxes.php
│   ├── class-order-management.php
│   ├── css/admin-style.css
│   └── js/admin-script.js
├── public/                    # Frontend assets
│   ├── css/                   # Stylesheets
│   └── js/                    # JavaScript files
├── templates/                 # Template files
│   ├── single-product.php
│   ├── archive-products.php
│   ├── cart.php
│   ├── checkout.php
│   ├── calculator.php
│   └── comparison.php
└── widgets/                   # Widget classes
    └── class-chat-bubble.php
```

## Database Tables

The plugin creates the following custom tables:

- `wp_kha_cart` - Shopping cart items
- `wp_kha_wishlist` - User wishlists
- `wp_kha_product_views` - Product view tracking
- `wp_kha_orders` - Order records
- `wp_kha_order_items` - Order line items
- `wp_kha_comparisons` - Product comparison tracking

## Created Pages

On activation, the plugin automatically creates:

- Shop (Products archive)
- Cart
- Checkout
- Solar Calculator
- Product Comparison

## Shortcodes

- `[kha_solar_products]` - Display products grid
- `[kha_solar_cart]` - Display cart page
- `[kha_solar_checkout]` - Display checkout page
- `[kha_solar_calculator]` - Display solar calculator
- `[kha_solar_comparison]` - Display product comparison

## Customization

### Currency Settings
Configure in Settings > Kha Solar Settings:
- Currency symbol (default: ₫)
- Currency position
- Decimal separator
- Thousand separator

### Chat Integration
Add your contact IDs in settings:
- Zalo phone number
- Facebook Messenger page ID

## Development

### Coding Standards
- Follows WordPress Coding Standards
- PSR-4 autoloading for classes
- Proper nonce verification
- Input sanitization and output escaping
- Full i18n support for Vietnamese language

### Hooks & Filters
The plugin provides various hooks for customization:
- `kha_solar_before_add_to_cart`
- `kha_solar_after_add_to_cart`
- `kha_solar_order_created`
- And more...

## Support
For support, please contact the Kha Solar Team.

## License
GPL-2.0+

## Author
Kha Solar Team
Website: https://khasolar.com

## Changelog

### 1.0.0 (2024)
- Initial release
- Complete e-commerce functionality
- Solar calculator
- Product comparison
- Chat integration
- Advanced search
