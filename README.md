# Kha Solar - Complete WordPress E-commerce Solution

Hệ thống e-commerce hoàn chỉnh cho kinh doanh thiết bị năng lượng mặt trời tại Việt Nam.

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/wordpress-5.8%2B-blue.svg)
![PHP](https://img.shields.io/badge/php-7.4%2B-purple.svg)
![License](https://img.shields.io/badge/license-GPL--2.0%2B-green.svg)

## 📋 Tổng Quan

Kha Solar bao gồm:
- **Kha Solar Theme**: Theme WordPress chuyên dụng, responsive, tối ưu hóa
- **Kha Solar Shop Plugin**: Plugin e-commerce đầy đủ tính năng

Được thiết kế dành riêng cho thị trường năng lượng mặt trời Việt Nam với đầy đủ tính năng cần thiết cho việc kinh doanh online.

## ✨ Tính Năng Chính

### 🛒 E-commerce Core
- ✅ Quản lý sản phẩm không giới hạn
- ✅ Giỏ hàng với session storage
- ✅ Checkout hoàn chỉnh
- ✅ Quản lý đơn hàng
- ✅ Danh mục & thương hiệu
- ✅ Quản lý kho hàng

### 🔧 Tính Năng Nâng Cao
- ✅ **Máy Tính Solar**: Tính toán công suất hệ thống, ROI, tiết kiệm
- ✅ **So Sánh Sản Phẩm**: So sánh tối đa 4 sản phẩm cùng lúc
- ✅ **Tìm Kiếm Thông Minh**: AJAX autocomplete search
- ✅ **Lọc Động**: Lọc theo danh mục, thương hiệu, giá, công suất
- ✅ **Gallery Ảnh**: Hiển thị nhiều ảnh sản phẩm với lightbox
- ✅ **Wishlist**: Lưu sản phẩm yêu thích
- ✅ **Tracking Views**: Theo dõi sản phẩm được xem nhiều

### 💬 Tích Hợp Chat
- ✅ Nút chat nổi Zalo, Messenger, Điện thoại
- ✅ Tin nhắn chào tự động
- ✅ Tích hợp Google Analytics & Facebook Pixel
- ✅ Tối ưu cho mobile và desktop

### 📱 Vietnamese Localization
- ✅ Giao diện tiếng Việt đầy đủ
- ✅ Định dạng tiền tệ Việt Nam (₫)
- ✅ Định dạng ngày tháng Việt Nam
- ✅ Sẵn sàng dịch thuật (translation-ready)

### 🎨 Theme Features
- ✅ Responsive design (mobile-first)
- ✅ Tùy chỉnh logo, màu sắc
- ✅ Widget areas (sidebar, footer)
- ✅ Custom menus
- ✅ Breadcrumbs navigation
- ✅ SEO-friendly
- ✅ Fast loading

## 📦 Cấu Trúc Dự Án

```
khasolar-theme/
├── kha-solar-shop/          # Plugin E-commerce
│   ├── admin/               # Admin functionality
│   │   ├── class-order-management.php
│   │   ├── class-product-meta-boxes.php
│   │   ├── css/
│   │   └── js/
│   ├── includes/            # Core classes
│   │   ├── class-activator.php
│   │   ├── class-ajax-handler.php
│   │   ├── class-calculator.php
│   │   ├── class-cart.php
│   │   ├── class-comparison.php
│   │   ├── class-order.php
│   │   ├── class-product-post-type.php
│   │   ├── class-sample-data.php
│   │   ├── class-search.php
│   │   ├── class-shortcodes.php
│   │   └── product-functions.php
│   ├── public/              # Frontend assets
│   │   ├── css/
│   │   └── js/
│   ├── templates/           # Template files
│   │   ├── archive-products.php
│   │   ├── calculator.php
│   │   ├── cart.php
│   │   ├── checkout.php
│   │   ├── comparison.php
│   │   ├── order-confirmation.php
│   │   ├── search-results.php
│   │   ├── single-product.php
│   │   └── partials/
│   ├── widgets/             # Widget classes
│   │   ├── class-chat-bubble.php
│   │   └── templates/
│   ├── INSTALLATION.md      # Plugin documentation
│   ├── PRODUCT-SYSTEM.md    # Product system docs
│   ├── README.md            # Plugin readme
│   └── kha-solar-shop.php   # Main plugin file
│
└── khasolar-theme/          # WordPress Theme
    ├── css/
    │   └── custom.css
    ├── inc/
    │   ├── customizer.php
    │   └── template-tags.php
    ├── js/
    │   ├── customizer.js
    │   └── navigation.js
    ├── template-parts/
    │   ├── content.php
    │   ├── content-none.php
    │   ├── content-page.php
    │   └── content-single.php
    ├── 404.php
    ├── footer.php
    ├── functions.php
    ├── header.php
    ├── index.php
    ├── page.php
    ├── sidebar.php
    ├── single.php
    └── style.css
```

## 🚀 Cài Đặt

### Yêu Cầu Hệ Thống

- WordPress 5.8 trở lên
- PHP 7.4 trở lên
- MySQL 5.6 trở lên
- RAM khuyến nghị: 128MB

### Bước 1: Upload Files

**Plugin:**
```bash
# Upload folder kha-solar-shop vào /wp-content/plugins/
wp-content/
└── plugins/
    └── kha-solar-shop/
```

**Theme:**
```bash
# Upload folder khasolar-theme vào /wp-content/themes/
wp-content/
└── themes/
    └── khasolar-theme/
```

### Bước 2: Kích Hoạt

1. Đăng nhập WordPress Admin
2. Vào **Plugins** → Tìm "Kha Solar Shop" → Click **Activate**
3. Vào **Appearance** → **Themes** → Chọn "Kha Solar Theme" → Click **Activate**

### Bước 3: Thiết Lập Tự Động

Plugin sẽ tự động:
- Tạo bảng database
- Tạo các trang cần thiết (Sản phẩm, Giỏ hàng, Thanh toán, etc.)
- Thiết lập permalinks
- Tạo danh mục và thương hiệu mặc định

### Bước 4: Import Dữ Liệu Mẫu (Tùy chọn)

1. Vào **Tools** → **Import Sample Data**
2. Click **"Import Demo Products"**
3. 10 sản phẩm mẫu sẽ được tạo tự động

## 🎯 Sử Dụng

### Thêm Sản Phẩm

1. Vào **Kha Products** → **Add New**
2. Điền thông tin sản phẩm:
   - Tên sản phẩm
   - Mô tả
   - Giá (VND)
   - SKU
   - Số lượng tồn kho
3. Chọn danh mục và thương hiệu
4. Upload ảnh sản phẩm
5. Click **Publish**

### Sử Dụng Shortcodes

**Hiển thị sản phẩm:**
```
[kha_products]
[kha_products category="inverter" limit="8" columns="4"]
[kha_products orderby="price" order="ASC"]
```

**Hiển thị danh mục:**
```
[kha_product_categories]
[kha_product_categories columns="4" show_count="yes"]
```

**Sản phẩm nổi bật:**
```
[kha_featured_products limit="6" columns="3"]
```

**Khác:**
```
[kha_cart]          - Giỏ hàng
[kha_checkout]      - Thanh toán
[kha_calculator]    - Máy tính solar
[kha_comparison]    - So sánh sản phẩm
[kha_search_box]    - Ô tìm kiếm
```

### Cấu Hình Chat Bubbles

1. Vào **Settings** → **Kha Solar Chat**
2. Cấu hình:
   - ☑ Enable chat bubbles
   - **Zalo**: Số điện thoại (0901234567)
   - **Messenger**: Facebook Page ID
   - **Phone**: Số hotline
   - **Vị trí**: Bottom-right hoặc Bottom-left
   - **Hiển thị**: All pages / Shop only / Exclude cart
   - **Tin nhắn chào**: Tùy chỉnh
   - **Animation**: Pulse / Bounce / None
3. Click **Save Changes**

### Quản Lý Đơn Hàng

1. Vào **Kha Orders**
2. Xem danh sách đơn hàng
3. Click vào đơn hàng để xem chi tiết
4. Thay đổi trạng thái:
   - **Pending**: Chờ xử lý
   - **Processing**: Đang xử lý
   - **Completed**: Hoàn thành
   - **Cancelled**: Đã hủy

## 🎨 Tùy Chỉnh Theme

### Customizer

1. Vào **Appearance** → **Customize**
2. Tùy chỉnh:
   - Logo
   - Site title & tagline
   - Colors
   - Menus
   - Widgets
   - Homepage settings

### Widgets

Theme hỗ trợ 4 widget areas:
- **Sidebar**: Main sidebar
- **Footer Widget 1**: Footer column 1
- **Footer Widget 2**: Footer column 2
- **Footer Widget 3**: Footer column 3

### Menus

1. Vào **Appearance** → **Menus**
2. Tạo menu mới hoặc chỉnh sửa
3. Assign vào:
   - **Primary Menu**: Header menu
   - **Footer Menu**: Footer menu

## 🔌 Hook & Filter cho Developer

### Action Hooks

```php
// Before/after shop loop
do_action('kha_before_shop_loop');
do_action('kha_after_shop_loop');

// Before/after single product
do_action('kha_before_single_product');
do_action('kha_after_single_product_content');

// Before cart/checkout
do_action('kha_before_cart_table');
do_action('kha_before_checkout_form');

// After order placed
do_action('kha_order_placed', $order_id);
```

### Template Functions

```php
// Get cart count
kha_get_cart_count();

// Format price
kha_solar_format_price(1500000); // Returns: 1.500.000₫

// Get page URL
kha_solar_get_page_url('cart');
kha_solar_get_page_url('checkout');

// Mini cart
kha_mini_cart();

// Breadcrumbs
khasolar_breadcrumbs();

// Pagination
khasolar_pagination();
```

## 📊 Performance

### Tối Ưu Hóa Được Áp Dụng

- ✅ Lazy loading images
- ✅ Minified CSS/JS
- ✅ Conditional asset loading
- ✅ Database query optimization
- ✅ Session-based cart (không dùng cookies)
- ✅ Caching ready
- ✅ CDN friendly

### Khuyến Nghị

1. **Caching Plugin**: WP Super Cache / W3 Total Cache
2. **Image Optimization**: Smush / EWWW Image Optimizer
3. **CDN**: Cloudflare / BunnyCDN
4. **PHP**: Sử dụng PHP 8.0+ để tăng hiệu suất

## 🔒 Bảo Mật

### Tính Năng Bảo Mật Có Sẵn

- ✅ Nonce verification trên tất cả forms
- ✅ Input sanitization
- ✅ Output escaping
- ✅ Prepared SQL statements
- ✅ CSRF protection
- ✅ XSS prevention

### Khuyến Nghị Bổ Sung

1. Sử dụng SSL certificate (HTTPS)
2. Cài đặt security plugin (Wordfence, iThemes Security)
3. Cập nhật WordPress, theme, plugin thường xuyên
4. Backup database định kỳ
5. Sử dụng mật khẩu mạnh

## 🐛 Troubleshooting

### Sản phẩm không hiển thị

**Giải pháp:**
1. Vào **Settings** → **Permalinks**
2. Click **Save Changes** để flush rewrite rules
3. Kiểm tra sản phẩm đã publish chưa

### Giỏ hàng không hoạt động

**Giải pháp:**
1. Kiểm tra PHP session đã enable chưa
2. Xóa cookies trình duyệt
3. Kiểm tra database tables đã tạo chưa

### Ảnh không load

**Giải pháp:**
1. Kiểm tra permissions folder `wp-content/uploads/`
2. Regenerate thumbnails bằng plugin
3. Kiểm tra image sizes đã register chưa

### Search không hoạt động

**Giải pháp:**
1. Kiểm tra jQuery đã load chưa
2. Xem console lỗi JavaScript
3. Kiểm tra AJAX URL đúng chưa

## 📝 Changelog

### Version 1.0.0 - 17/11/2024

**Initial Release:**
- ✅ Complete e-commerce system
- ✅ Solar calculator with ROI analysis
- ✅ Product comparison feature
- ✅ Smart search and filters
- ✅ Chat integration (Zalo, Messenger, Phone)
- ✅ Sample data importer
- ✅ Full Vietnamese localization
- ✅ Responsive theme
- ✅ Complete documentation

## 🤝 Đóng Góp

Mọi đóng góp đều được hoan nghênh!

### Cách Đóng Góp

1. Fork repository
2. Tạo branch mới (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

Dự án sử dụng GPL-2.0+ License. Xem file `LICENSE` để biết thêm chi tiết.

## 📞 Hỗ Trợ

- **Email**: support@khasolar.vn
- **Documentation**: Xem file `INSTALLATION.md` trong thư mục plugin
- **Issues**: Báo lỗi trên GitHub Issues

## 👥 Credits

**Developed by**: Kha Solar Team
**For**: Vietnamese Solar Industry
**Powered by**: WordPress, PHP, jQuery

## 🎉 Tính Năng Sắp Có

- [ ] Multi-language support
- [ ] Payment gateway integration (VNPay, MoMo)
- [ ] Email notifications
- [ ] PDF invoice generation
- [ ] Advanced analytics dashboard
- [ ] Product reviews & ratings
- [ ] Coupon & discount system
- [ ] Advanced inventory management

---

**Made with ❤️ for the Solar Industry in Vietnam**

![Kha Solar](https://via.placeholder.com/1200x300/0066cc/ffffff?text=Kha+Solar+-+Complete+E-commerce+Solution)
