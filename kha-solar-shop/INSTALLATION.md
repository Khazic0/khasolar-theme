# Kha Solar Shop - Installation & User Guide

## Overview

Kha Solar Shop is a comprehensive WordPress e-commerce solution specifically designed for solar equipment businesses. It includes advanced features like product comparison, solar system calculator, smart search, and integrated chat support.

## Features

### Core E-commerce
- ✅ Custom product management system
- ✅ Shopping cart with session storage
- ✅ Complete checkout process
- ✅ Order management system
- ✅ Product categories and brands
- ✅ Stock management

### Advanced Features
- ✅ **Solar System Calculator** - Calculate recommended system size based on electricity usage
- ✅ **Product Comparison** - Side-by-side comparison of up to 4 products
- ✅ **Smart Search** - AJAX-powered autocomplete search
- ✅ **Product Filtering** - Dynamic filters by category, brand, price, power
- ✅ **Chat Integration** - Floating buttons for Zalo, Messenger, and phone support
- ✅ **Product Gallery** - Multiple image support with lightbox
- ✅ **Wishlist System** - Save products for later
- ✅ **Product Views Tracking** - Track popular products

### Vietnamese Localization
- All text in Vietnamese
- Vietnamese currency formatting (₫)
- Vietnamese date formats
- Translation-ready with .pot file

## Installation

### Requirements

- WordPress 5.8 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- Recommended: 128MB PHP memory limit

### Step 1: Upload Plugin

1. Download the `kha-solar-shop` folder
2. Upload to `/wp-content/plugins/` directory
3. Or upload via WordPress admin: Plugins → Add New → Upload Plugin

### Step 2: Activate Plugin

1. Go to WordPress Admin → Plugins
2. Find "Kha Solar Shop" in the list
3. Click "Activate"

### Step 3: Automatic Setup

Upon activation, the plugin automatically:
- Creates necessary database tables
- Creates default pages (Shop, Cart, Checkout, Calculator, Comparison)
- Sets up product categories and brands
- Configures default settings

### Step 4: Import Sample Data (Optional)

1. Go to Tools → Import Sample Data
2. Click "Import Demo Products"
3. 10 sample products will be created with complete specifications

## Configuration

### Basic Settings

#### Currency Settings
Default settings are configured for Vietnamese Dong (₫):
- Symbol: ₫
- Position: Right
- Thousand separator: .
- Decimal separator: ,
- Decimals: 0

#### Page Configuration
The plugin creates these pages automatically:
- **Sản Phẩm** (`/san-pham`) - Shop page
- **Giỏ Hàng** (`/gio-hang`) - Cart page
- **Thanh Toán** (`/thanh-toan`) - Checkout page
- **Tính Toán Solar** (`/tinh-toan-solar`) - Calculator page
- **So Sánh** (`/so-sanh`) - Comparison page

#### Chat Bubble Settings
1. Go to Settings → Kha Solar Chat
2. Configure:
   - Enable/disable chat bubbles
   - Zalo phone number
   - Facebook Page ID
   - Phone hotline
   - Position (bottom-right or bottom-left)
   - Display rules (all pages, shop only, exclude cart/checkout)
   - Greeting message
   - Animation style (pulse, bounce, none)

## Adding Products

### Manual Product Creation

1. Go to Kha Products → Add New
2. Fill in product details:
   - **Title** - Product name
   - **Description** - Full product description
   - **Price** - Regular price in VND
   - **Sale Price** (optional) - Discounted price
   - **SKU** - Stock Keeping Unit
   - **Stock Quantity** - Number in stock
   - **Stock Status** - In Stock / Out of Stock

3. Product Specifications (varies by category):
   - **Power (W)** - For solar panels and inverters
   - **Voltage** - Operating voltage
   - **Efficiency** - System efficiency percentage
   - **MPPT Trackers** - Number of MPPT trackers
   - **Warranty** - Warranty period

4. Set Product Category:
   - Inverter (Biến tần)
   - Pin Năng Lượng Mặt Trời
   - Tấm Pin Mặt Trời
   - Phụ Kiện & Thiết Bị Kèm Theo
   - Combo & Gói Lắp Đặt

5. Set Brand (optional):
   - Growatt
   - Deye
   - SMA
   - Huawei
   - Jinko Solar
   - Longi Solar

6. Upload Images:
   - Featured Image - Main product image
   - Product Gallery - Additional product images

7. Publish

### Bulk Product Import

For bulk product creation, use the sample data importer as reference and modify the `includes/class-sample-data.php` file with your product data.

## Using Shortcodes

### [kha_products]
Display products grid

**Attributes:**
- `category` - Filter by category slug (e.g., "inverter")
- `limit` - Number of products to show (default: 12)
- `columns` - Grid columns (default: 4)
- `orderby` - Order by: date, title, price (default: date)
- `order` - ASC or DESC (default: DESC)

**Examples:**
```
[kha_products]
[kha_products category="inverter" limit="8" columns="4"]
[kha_products orderby="price" order="ASC" limit="12"]
```

### [kha_product_categories]
Display product category grid

**Attributes:**
- `columns` - Grid columns (default: 3)
- `show_count` - Show product count: yes/no (default: yes)

**Examples:**
```
[kha_product_categories]
[kha_product_categories columns="4" show_count="no"]
```

### [kha_cart]
Display shopping cart (automatically used on Cart page)

### [kha_checkout]
Display checkout form (automatically used on Checkout page)

### [kha_calculator]
Display solar system calculator (automatically used on Calculator page)

### [kha_comparison]
Display product comparison table (automatically used on Comparison page)

### [kha_search_box]
Display standalone search box

**Example:**
```
[kha_search_box]
```

### [kha_featured_products]
Display only featured products

**Attributes:**
- `limit` - Number of products (default: 8)
- `columns` - Grid columns (default: 4)

**Examples:**
```
[kha_featured_products]
[kha_featured_products limit="6" columns="3"]
```

## Managing Orders

### Viewing Orders

1. Go to Kha Orders
2. View list of all orders with:
   - Order number
   - Customer name
   - Total amount
   - Status
   - Date

### Order Statuses

- **Pending** - New order, awaiting processing
- **Processing** - Order being prepared
- **Completed** - Order fulfilled
- **Cancelled** - Order cancelled

### Order Details

Click on any order to view:
- Customer information
- Shipping address
- Products ordered
- Payment details
- Order notes

### Changing Order Status

1. Open order details
2. Select new status from dropdown
3. Save changes
4. Customer receives email notification (if enabled)

## Theme Integration

### Adding Mini Cart to Theme

Add this code to your theme's header.php:

```php
<?php
if (function_exists('kha_mini_cart')) {
    kha_mini_cart();
}
?>
```

### Custom Template Hooks

Available action hooks for customization:

```php
// Before shop loop
do_action('kha_before_shop_loop');

// After shop loop
do_action('kha_after_shop_loop');

// Before single product
do_action('kha_before_single_product');

// After single product content
do_action('kha_after_single_product_content');

// Before cart table
do_action('kha_before_cart_table');

// Before checkout form
do_action('kha_before_checkout_form');

// After order placed
do_action('kha_order_placed', $order_id);
```

### Helper Functions

```php
// Get cart count
kha_get_cart_count();

// Format price
kha_solar_format_price(1500000); // Returns: 1.500.000₫

// Get page URL
kha_solar_get_page_url('cart'); // Returns cart page URL
kha_solar_get_page_url('checkout');
kha_solar_get_page_url('shop');
```

## Calculator Setup

The solar calculator is pre-configured with Vietnam-specific defaults:

- **Average electricity rate:** 2,500 VND per kWh
- **Average sun hours:** 4.5 hours per day
- **System efficiency:** 80%
- **Panel area per kW:** 7 m²
- **Cost per kW:** 20,000,000 VND

To modify these defaults, edit `includes/class-calculator.php` and update the private properties.

## Troubleshooting

### Products Not Displaying

1. Check if products are published
2. Flush permalinks: Settings → Permalinks → Save Changes
3. Check theme compatibility

### Cart Issues

1. Check PHP session is enabled
2. Clear browser cookies
3. Check database tables were created

### Search Not Working

1. Ensure jQuery is loaded
2. Check browser console for JavaScript errors
3. Verify AJAX URL is correct

### Images Not Loading

1. Check file permissions on uploads folder
2. Regenerate thumbnails using plugin
3. Verify image sizes are registered

## Performance Optimization

### Recommended Settings

1. **Caching Plugin:** Install WP Super Cache or W3 Total Cache
2. **Image Optimization:** Install Smush or EWWW Image Optimizer
3. **Database:** Optimize database monthly
4. **PHP:** Use PHP 8.0+ for better performance

### Best Practices

- Limit products per page to 12-24
- Optimize product images before upload
- Use CDN for static assets
- Enable GZIP compression
- Minify CSS/JS files

## Security

### Built-in Security Features

- ✅ Nonce verification on all forms
- ✅ Input sanitization
- ✅ Output escaping
- ✅ Prepared SQL statements
- ✅ CSRF protection
- ✅ XSS prevention

### Additional Recommendations

1. Use strong passwords
2. Keep WordPress updated
3. Install security plugin (Wordfence, iThemes Security)
4. Use SSL certificate (HTTPS)
5. Regular backups

## Support

### Getting Help

For technical support:
- Email: support@khasolar.vn
- Documentation: Check this file and code comments
- GitHub Issues: Report bugs on GitHub

### Common Questions

**Q: Can I use this with WooCommerce?**
A: This is a standalone e-commerce solution. It's designed to replace WooCommerce for solar businesses.

**Q: Is it multisite compatible?**
A: Not tested on multisite. Use with caution.

**Q: Can I translate to other languages?**
A: Yes, use the .pot file in /languages folder with Poedit.

**Q: Does it work on shared hosting?**
A: Yes, it's optimized for shared hosting environments.

## Changelog

### Version 1.0.0 - Initial Release

**Features:**
- Complete e-commerce system
- Product management
- Cart and checkout
- Order management
- Solar calculator
- Product comparison
- Smart search and filters
- Chat integration (Zalo, Messenger, Phone)
- Sample data importer
- Vietnamese localization

## Credits

Developed by Kha Solar Team for the Vietnamese solar industry.

## License

GPL-2.0+ License - Free to use and modify.
