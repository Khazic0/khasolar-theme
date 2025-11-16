# Kha Solar Product System Documentation

Complete documentation for the comprehensive product management system.

## Overview

The Kha Solar product system provides a full-featured product management solution specifically designed for solar equipment e-commerce with Vietnamese language support.

## Custom Post Type: `kha_product`

### Configuration
- **Slug**: `san-pham` (Vietnamese for "products")
- **Labels**: Full Vietnamese localization
- **Menu Icon**: `dashicons-archive`
- **Supports**: title, editor, thumbnail, excerpt
- **REST API**: Enabled
- **Hierarchical**: No
- **Has Archive**: Yes

### URL Structure
- Single Product: `https://example.com/san-pham/product-name/`
- Archive: `https://example.com/san-pham/`
- Category: `https://example.com/danh-muc/category-name/`
- Tag: `https://example.com/tu-khoa/tag-name/`
- Brand: `https://example.com/thuong-hieu/brand-name/`

## Taxonomies

### 1. Product Categories (`kha_product_cat`)
- **Type**: Hierarchical
- **Slug**: `danh-muc`
- **REST API**: Enabled

#### Default Categories
| Slug | Name (Vietnamese) |
|------|-------------------|
| `inverter` | Inverter (Biến tần) |
| `solar-battery` | Pin Năng Lượng Mặt Trời |
| `solar-panel` | Tấm Pin Mặt Trời |
| `accessories` | Phụ Kiện & Thiết Bị Kèm Theo |
| `combo-packages` | Combo & Gói Lắp Đặt |

### 2. Product Tags (`kha_product_tag`)
- **Type**: Non-hierarchical
- **Slug**: `tu-khoa`
- **REST API**: Enabled

### 3. Brand (`kha_brand`)
- **Type**: Non-hierarchical
- **Slug**: `thuong-hieu`
- **REST API**: Enabled

#### Default Brands
- Growatt
- Deye
- SMA
- Huawei
- Jinko Solar
- Longi Solar

## Meta Fields

All meta fields are registered with REST API support and can be accessed via WordPress REST API.

### Pricing Fields

#### `_regular_price`
- **Type**: Number
- **Description**: Regular product price
- **Required**: Recommended
- **Example**: `15000000` (15 million VND)

#### `_sale_price`
- **Type**: Number
- **Description**: Sale/promotional price
- **Optional**: Yes
- **Example**: `12000000` (12 million VND)

#### `_price`
- **Type**: Number
- **Description**: Active price (auto-calculated)
- **Auto-calculated**: Yes (sale_price if available, otherwise regular_price)
- **Read-only**: Managed by system

### Inventory Fields

#### `_sku`
- **Type**: String
- **Description**: Stock Keeping Unit (unique identifier)
- **Unique**: Should be unique
- **Example**: `"INV-GRW-3KW-001"`

#### `_stock_quantity`
- **Type**: Number
- **Description**: Available stock quantity
- **Example**: `50`

#### `_stock_status`
- **Type**: String
- **Values**: `instock`, `outofstock`, `onbackorder`
- **Default**: `instock`

#### `_manage_stock`
- **Type**: Boolean
- **Description**: Enable stock management
- **Default**: `false`

#### `_low_stock_threshold`
- **Type**: Number
- **Description**: Trigger low stock warning at this quantity
- **Default**: `5`

### Technical Specifications

#### `_power_output`
- **Type**: Number
- **Unit**: Watts
- **Description**: Power output capacity
- **Example**: `3000` (3kW inverter)
- **Display**: Auto-converts to kW if >= 1000

#### `_voltage`
- **Type**: String
- **Description**: Operating voltage
- **Example**: `"220V"` or `"48V"`

#### `_warranty_years`
- **Type**: Number
- **Description**: Warranty period in years
- **Example**: `10`

#### `_origin_country`
- **Type**: String
- **Description**: Country of manufacture
- **Example**: `"Trung Quốc"` or `"Đức"`

#### `_efficiency`
- **Type**: Number
- **Unit**: Percentage
- **Description**: Product efficiency rating
- **Example**: `97.5`

#### `_dimensions`
- **Type**: String
- **Description**: Physical dimensions
- **Example**: `"600x400x200mm"`

#### `_weight`
- **Type**: Number
- **Unit**: Kilograms
- **Description**: Product weight
- **Example**: `12.5`

### Additional Fields

#### `_product_type`
- **Type**: String
- **Values**: `simple`, `bundle`
- **Default**: `simple`
- **Description**: Product type classification

#### `_bundle_products`
- **Type**: Array
- **Description**: Array of product IDs included in bundle
- **Example**: `[123, 456, 789]`
- **Condition**: Only used if `_product_type` is `bundle`

#### `_bundle_discount`
- **Type**: Number
- **Unit**: Percentage
- **Description**: Discount percentage for bundle
- **Example**: `15` (15% discount)

#### `_featured_product`
- **Type**: Boolean
- **Description**: Mark product as featured
- **Default**: `false`

#### `_product_gallery`
- **Type**: Array
- **Description**: Array of attachment IDs for gallery images
- **Example**: `[101, 102, 103]`

## Helper Functions

All helper functions are available globally after plugin activation.

### Price Functions

#### `kha_get_product_price( $product_id, $formatted = true )`
Get product price with optional HTML formatting.

```php
// Get formatted HTML price
echo kha_get_product_price( 123 );
// Output: <div class="kha-product-price">
//   <span class="kha-price-current">12.000.000 ₫</span>
//   <span class="kha-price-regular">15.000.000 ₫</span>
//   <span class="kha-sale-badge">Sale</span>
// </div>

// Get raw price value
$price = kha_get_product_price( 123, false );
// Output: 12000000
```

**Parameters:**
- `$product_id` (int) - Product ID
- `$formatted` (bool) - Return formatted HTML (default: true)

**Returns:** String (HTML) or Float (raw price)

#### `kha_is_product_on_sale( $product_id )`
Check if product has an active sale price.

```php
if ( kha_is_product_on_sale( 123 ) ) {
    echo 'This product is on sale!';
}
```

**Returns:** Boolean

#### `kha_get_discount_percentage( $product_id )`
Calculate discount percentage.

```php
$discount = kha_get_discount_percentage( 123 );
echo "Save {$discount}%!";
```

**Returns:** Integer (percentage) or 0

### Stock Functions

#### `kha_get_product_stock_status( $product_id )`
Get formatted stock status HTML badge.

```php
echo kha_get_product_stock_status( 123 );
// Output: <span class="kha-stock-badge kha-stock-instock">
//   <span class="kha-stock-icon">✓</span> Còn Hàng
// </span>
```

**Returns:** String (HTML)

#### `kha_is_product_in_stock( $product_id )`
Check if product is available for purchase.

```php
if ( kha_is_product_in_stock( 123 ) ) {
    echo '<button>Add to Cart</button>';
}
```

**Returns:** Boolean

### Specification Functions

#### `kha_get_product_specs( $product_id )`
Get all technical specifications as an array.

```php
$specs = kha_get_product_specs( 123 );

foreach ( $specs as $spec ) {
    echo "<tr>";
    echo "<td>{$spec['label']}</td>";
    echo "<td>{$spec['value']}</td>";
    echo "</tr>";
}
```

**Returns:** Array of specifications
```php
[
    [
        'label' => 'Công Suất',
        'value' => '3 kW',
        'raw' => 3000,
        'key' => '_power_output'
    ],
    [
        'label' => 'Điện Áp',
        'value' => '220V',
        'raw' => '220V',
        'key' => '_voltage'
    ]
]
```

### Product Relationship Functions

#### `kha_get_related_products( $product_id, $limit = 4 )`
Get related products based on categories and tags.

```php
$related = kha_get_related_products( 123, 4 );

foreach ( $related as $product ) {
    echo $product->post_title;
}
```

**Returns:** Array of WP_Post objects

#### `kha_get_featured_products( $limit = 8 )`
Get products marked as featured.

```php
$featured = kha_get_featured_products( 6 );

foreach ( $featured as $product ) {
    echo get_the_post_thumbnail( $product->ID );
    echo $product->post_title;
}
```

**Returns:** Array of WP_Post objects

### Utility Functions

#### `kha_get_product_sku( $product_id )`
Get product SKU.

```php
$sku = kha_get_product_sku( 123 );
echo "SKU: {$sku}";
```

**Returns:** String

#### `kha_get_product_brand( $product_id )`
Get product brand term.

```php
$brand = kha_get_product_brand( 123 );
if ( $brand ) {
    echo "Brand: {$brand->name}";
    echo "URL: " . get_term_link( $brand );
}
```

**Returns:** WP_Term object or false

#### `kha_get_product_gallery( $product_id )`
Get product gallery image IDs.

```php
$gallery = kha_get_product_gallery( 123 );

foreach ( $gallery as $image_id ) {
    echo wp_get_attachment_image( $image_id, 'large' );
}
```

**Returns:** Array of attachment IDs

### Bundle Functions

#### `kha_get_bundle_products( $product_id )`
Get products included in a bundle.

```php
$bundle_items = kha_get_bundle_products( 123 );

foreach ( $bundle_items as $item_id ) {
    echo get_the_title( $item_id );
}
```

**Returns:** Array of product IDs or empty array

#### `kha_get_bundle_discount( $product_id )`
Get bundle discount percentage.

```php
$discount = kha_get_bundle_discount( 123 );
echo "Save {$discount}% when buying this bundle!";
```

**Returns:** Integer (percentage) or 0

## Usage Examples

### Creating a Product Programmatically

```php
// Create product
$product_id = wp_insert_post( [
    'post_title' => 'Inverter Growatt 3kW',
    'post_type' => 'kha_product',
    'post_status' => 'publish',
    'post_content' => 'Product description here...'
] );

// Set pricing
update_post_meta( $product_id, '_regular_price', 15000000 );
update_post_meta( $product_id, '_sale_price', 12000000 );

// Set inventory
update_post_meta( $product_id, '_sku', 'INV-GRW-3KW-001' );
update_post_meta( $product_id, '_stock_status', 'instock' );
update_post_meta( $product_id, '_stock_quantity', 50 );
update_post_meta( $product_id, '_manage_stock', true );

// Set technical specs
update_post_meta( $product_id, '_power_output', 3000 );
update_post_meta( $product_id, '_voltage', '220V' );
update_post_meta( $product_id, '_warranty_years', 10 );
update_post_meta( $product_id, '_efficiency', 97.5 );
update_post_meta( $product_id, '_origin_country', 'Trung Quốc' );

// Set category
wp_set_object_terms( $product_id, 'inverter', 'kha_product_cat' );

// Set brand
wp_set_object_terms( $product_id, 'growatt', 'kha_brand' );
```

### Displaying Product Information

```php
<?php
$product_id = get_the_ID();

// Display price
echo kha_get_product_price( $product_id );

// Display stock status
echo kha_get_product_stock_status( $product_id );

// Display specifications
$specs = kha_get_product_specs( $product_id );
?>
<table class="product-specs">
    <?php foreach ( $specs as $spec ) : ?>
        <tr>
            <th><?php echo esc_html( $spec['label'] ); ?></th>
            <td><?php echo esc_html( $spec['value'] ); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
// Display brand
$brand = kha_get_product_brand( $product_id );
if ( $brand ) {
    echo '<p>Brand: <a href="' . get_term_link( $brand ) . '">' . $brand->name . '</a></p>';
}

// Display related products
$related = kha_get_related_products( $product_id, 4 );
?>
<div class="related-products">
    <h3>Related Products</h3>
    <?php foreach ( $related as $product ) : ?>
        <div class="related-product">
            <h4><?php echo $product->post_title; ?></h4>
            <?php echo kha_get_product_price( $product->ID ); ?>
        </div>
    <?php endforeach; ?>
</div>
```

### REST API Usage

```javascript
// Get product via REST API
fetch('/wp-json/wp/v2/kha_product/123')
    .then(response => response.json())
    .then(product => {
        console.log('Price:', product.meta._price);
        console.log('Stock:', product.meta._stock_status);
        console.log('Power:', product.meta._power_output);
    });

// Query products with filters
fetch('/wp-json/wp/v2/kha_product?kha_brand=growatt&per_page=10')
    .then(response => response.json())
    .then(products => {
        products.forEach(product => {
            console.log(product.title.rendered);
        });
    });
```

## CSS Classes Reference

### Price Display
- `.kha-product-price` - Price container
- `.kha-price-current` - Active price
- `.kha-price-regular` - Regular price (strikethrough when on sale)
- `.kha-sale-price` - Sale price indicator
- `.kha-sale-badge` - Sale badge
- `.kha-price-contact` - "Contact for price" text

### Stock Status
- `.kha-stock-badge` - Stock badge container
- `.kha-stock-instock` - In stock status
- `.kha-stock-outofstock` - Out of stock status
- `.kha-stock-onbackorder` - Back order status
- `.kha-stock-icon` - Stock icon
- `.kha-low-stock` - Low stock warning

## Filter Hooks

The system provides several filter hooks for customization:

```php
// Modify product price display
add_filter( 'kha_product_price_html', function( $html, $product_id ) {
    // Custom price HTML
    return $html;
}, 10, 2 );

// Modify related products query
add_filter( 'kha_related_products_args', function( $args, $product_id ) {
    $args['posts_per_page'] = 8;
    return $args;
}, 10, 2 );
```

## Action Hooks

```php
// After product meta is saved
add_action( 'kha_product_meta_saved', function( $product_id ) {
    // Custom actions after save
}, 10, 1 );
```

## Performance Considerations

1. **Caching**: All helper functions query database directly. Consider implementing caching for high-traffic sites.
2. **REST API**: Meta fields are available via REST API. Use sparingly for large datasets.
3. **Gallery Images**: Use appropriate image sizes to avoid loading full-resolution images.
4. **Related Products**: Limit the number of related products to improve query performance.

## Troubleshooting

### Products not showing on frontend
1. Check if permalinks are flushed (Settings → Permalinks → Save)
2. Verify product status is 'publish'
3. Check template files exist in `/templates/` directory

### Meta fields not saving
1. Verify current user has edit_post capability
2. Check for JavaScript errors in admin
3. Ensure nonces are verified in admin forms

### Taxonomy terms not appearing
1. Re-activate plugin to create default terms
2. Check taxonomy is registered (use `get_taxonomies()`)
3. Verify slug names match exactly

## Version History

### 1.0.0 (Current)
- Initial release with comprehensive product system
- 21 meta fields with REST API support
- 13 helper functions
- 3 taxonomies (Categories, Tags, Brands)
- Default terms creation on activation
- Full Vietnamese localization
