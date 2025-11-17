# 🚀 HƯỚNG DẪN CÀI ĐẶT TỪ ĐẦU ĐẾN CUỐI

> **Dự án:** Kha Solar - Complete E-commerce Solution
> **Version:** 1.0.0
> **Ngày:** 17/11/2024

---

## 📋 MỤC LỤC

1. [Yêu Cầu Hệ Thống](#yêu-cầu-hệ-thống)
2. [Chuẩn Bị](#chuẩn-bị)
3. [Cài Đặt WordPress](#cài-đặt-wordpress)
4. [Upload Files](#upload-files)
5. [Kích Hoạt Plugin & Theme](#kích-hoạt-plugin--theme)
6. [Cấu Hình Ban Đầu](#cấu-hình-ban-đầu)
7. [Import Dữ Liệu Mẫu](#import-dữ-liệu-mẫu)
8. [Cấu Hình Theme](#cấu-hình-theme)
9. [Cấu Hình Plugin](#cấu-hình-plugin)
10. [Kiểm Tra & Testing](#kiểm-tra--testing)
11. [Tối Ưu Hóa](#tối-ưu-hóa)
12. [Troubleshooting](#troubleshooting)

---

## 🎯 YÊU CẦU HỆ THỐNG

### **Tối Thiểu:**
- ✅ WordPress 5.8 trở lên
- ✅ PHP 7.4 trở lên
- ✅ MySQL 5.6 trở lên
- ✅ 128MB RAM
- ✅ 100MB disk space

### **Khuyến Nghị:**
- 🌟 WordPress 6.0+
- 🌟 PHP 8.0+
- 🌟 MySQL 8.0+
- 🌟 256MB RAM
- 🌟 500MB disk space
- 🌟 SSL Certificate (HTTPS)

### **Server Requirements:**
```
PHP Extensions:
- mysqli
- curl
- gd hoặc imagick
- zip
- xml
- mbstring
- json

Apache Modules:
- mod_rewrite (required)
- mod_headers (recommended)
```

---

## 🛠️ CHUẨN BỊ

### **Bước 1: Tải Files Dự Án**

#### **Option 1: Clone từ Git**
```bash
git clone [repository-url] khasolar-theme
cd khasolar-theme
```

#### **Option 2: Download ZIP**
```bash
# Nếu có file ZIP
unzip khasolar-theme.zip
cd khasolar-theme
```

#### **Option 3: Copy từ Local**
```bash
# Files đã có tại:
/home/user/khasolar-theme/
```

### **Bước 2: Kiểm Tra Files**

```bash
# Kiểm tra cấu trúc
ls -la

# Kết quả mong đợi:
# kha-solar-shop/  (Plugin)
# khasolar-theme/  (Theme)
# README.md
# PROJECT-STATS/
```

### **Bước 3: Nén Files để Upload**

```bash
# Nén plugin
cd kha-solar-shop
zip -r ../kha-solar-shop.zip .
cd ..

# Nén theme
cd khasolar-theme
zip -r ../khasolar-theme.zip .
cd ..

# Kết quả:
# kha-solar-shop.zip (~850 KB)
# khasolar-theme.zip (~120 KB)
```

---

## 💻 CÀI ĐẶT WORDPRESS

### **Option A: Cài Đặt Local (XAMPP/MAMP)**

#### **1. Cài XAMPP (Windows/Mac/Linux)**

**Download:**
- Windows: https://www.apachefriends.org/download.html
- Mac: https://www.apachefriends.org/download.html
- Linux: `sudo apt-get install xampp`

**Khởi động:**
```bash
# Start Apache và MySQL
sudo /opt/lampp/lampp start

# Hoặc qua XAMPP Control Panel
```

#### **2. Tạo Database**

```bash
# Truy cập phpMyAdmin
http://localhost/phpmyadmin

# Tạo database mới:
Database name: khasolar_db
Collation: utf8mb4_unicode_ci
```

#### **3. Download WordPress**

```bash
cd /opt/lampp/htdocs/
wget https://wordpress.org/latest.zip
unzip latest.zip
mv wordpress khasolar
cd khasolar
```

#### **4. Cài Đặt WordPress**

```
Truy cập: http://localhost/khasolar

Điền thông tin:
- Database Name: khasolar_db
- Username: root
- Password: (để trống hoặc root)
- Database Host: localhost
- Table Prefix: wp_

Click "Run installation"

Site info:
- Site Title: Kha Solar
- Username: admin
- Password: (mật khẩu mạnh)
- Email: youremail@example.com

Click "Install WordPress"
```

### **Option B: Cài Đặt trên Hosting**

#### **1. Login cPanel**

```
URL: https://yourdomain.com:2083
Username: [hosting username]
Password: [hosting password]
```

#### **2. Tạo Database**

```
cPanel → MySQL Databases

Create Database:
- Database Name: yourusername_khasolar
- Click "Create Database"

Create User:
- Username: yourusername_admin
- Password: [generate strong password]
- Click "Create User"

Add User to Database:
- User: yourusername_admin
- Database: yourusername_khasolar
- Privileges: ALL PRIVILEGES
- Click "Add"
```

#### **3. Upload & Install WordPress**

**Option 3a: Softaculous (Recommended)**
```
cPanel → Softaculous Apps Installer → WordPress

Choose Domain: yourdomain.com
Directory: (leave blank for root)

Site Settings:
- Site Name: Kha Solar
- Admin Username: admin
- Admin Password: [strong password]
- Admin Email: youremail@example.com

Advanced Options:
- Database Name: yourusername_khasolar

Click "Install"
```

**Option 3b: Manual Upload**
```bash
# Download WordPress
wget https://wordpress.org/latest.zip
unzip latest.zip

# Upload via FTP
# Host: ftp.yourdomain.com
# Username: [ftp username]
# Password: [ftp password]
# Upload folder: /public_html/

# Configure wp-config.php
define('DB_NAME', 'yourusername_khasolar');
define('DB_USER', 'yourusername_admin');
define('DB_PASSWORD', '[your password]');
define('DB_HOST', 'localhost');
```

---

## 📤 UPLOAD FILES

### **Method 1: Via WordPress Admin (Recommended)**

#### **1. Login WordPress Admin**

```
URL: http://localhost/khasolar/wp-admin
     hoặc https://yourdomain.com/wp-admin

Username: admin
Password: [your password]
```

#### **2. Upload Plugin**

```
Dashboard → Plugins → Add New → Upload Plugin

Click "Choose File"
Select: kha-solar-shop.zip
Click "Install Now"
Wait for upload to complete
DON'T activate yet!
```

#### **3. Upload Theme**

```
Dashboard → Appearance → Themes → Add New → Upload Theme

Click "Choose File"
Select: khasolar-theme.zip
Click "Install Now"
Wait for upload to complete
DON'T activate yet!
```

### **Method 2: Via FTP/File Manager**

#### **Upload Plugin via FTP:**

```bash
# Connect to FTP
Host: ftp.yourdomain.com
User: [ftp-user]
Pass: [ftp-pass]

# Navigate to:
/public_html/wp-content/plugins/

# Upload extracted folder:
kha-solar-shop/

# Final path:
/public_html/wp-content/plugins/kha-solar-shop/
```

#### **Upload Theme via FTP:**

```bash
# Navigate to:
/public_html/wp-content/themes/

# Upload extracted folder:
khasolar-theme/

# Final path:
/public_html/wp-content/themes/khasolar-theme/
```

### **Method 3: Via cPanel File Manager**

```
cPanel → File Manager

Navigate to: public_html/wp-content/plugins/
Click "Upload"
Select: kha-solar-shop.zip
Wait for upload
Right-click → Extract

Navigate to: public_html/wp-content/themes/
Click "Upload"
Select: khasolar-theme.zip
Wait for upload
Right-click → Extract
```

---

## ⚡ KÍCH HOẠT PLUGIN & THEME

### **Bước 1: Kích Hoạt Plugin**

```
Dashboard → Plugins → Installed Plugins

Tìm "Kha Solar Shop"
Click "Activate"

✅ Thông báo: "Plugin activated"

Plugin sẽ tự động:
- Tạo 6 database tables
- Tạo 5 pages (Sản phẩm, Giỏ hàng, Thanh toán, Calculator, So sánh)
- Tạo categories & brands mặc định
- Thiết lập permalinks
- Cấu hình settings mặc định
```

### **Bước 2: Kích Hoạt Theme**

```
Dashboard → Appearance → Themes

Tìm "Kha Solar Theme"
Click "Activate"

✅ Thông báo: "New theme activated"

Theme sẽ tự động:
- Đăng ký menu locations (2)
- Đăng ký widget areas (4)
- Thiết lập image sizes
- Load styles & scripts
```

### **Bước 3: Kiểm Tra Activation**

```bash
# Check plugin status
Dashboard → Plugins
→ "Kha Solar Shop" should show "Deactivate" button

# Check theme status
Dashboard → Appearance → Themes
→ "Kha Solar Theme" should have "Active" badge

# Check pages created
Dashboard → Pages → All Pages
→ Should see 5 new pages:
  - Sản Phẩm
  - Giỏ Hàng
  - Thanh Toán
  - Tính Toán Hệ Thống Solar
  - So Sánh Sản Phẩm
```

---

## ⚙️ CẤU HÌNH BAN ĐẦU

### **1. Permalinks (BẮT BUỘC)**

```
Dashboard → Settings → Permalinks

Select: "Post name"
Custom Structure: /%postname%/

Click "Save Changes"

✅ Quan trọng: Bước này bắt buộc để URLs hoạt động đúng!
```

### **2. Time & Date**

```
Dashboard → Settings → General

Timezone: UTC+7 (Bangkok, Hanoi, Jakarta)
Date Format: d/m/Y  (17/11/2024)
Time Format: H:i  (23:45)
Week Starts On: Monday

Click "Save Changes"
```

### **3. Reading Settings**

```
Dashboard → Settings → Reading

Your homepage displays:
→ Select "A static page"

Homepage: [Select a page or create new]
Posts page: Blog

Search engine visibility:
→ Uncheck (để Google index)

Click "Save Changes"
```

### **4. Discussion Settings**

```
Dashboard → Settings → Discussion

Default post settings:
→ Check "Allow people to submit comments on new posts"

Before a comment appears:
→ Check "Comment must be manually approved"

Click "Save Changes"
```

---

## 📦 IMPORT DỮ LIỆU MẪU

### **Bước 1: Truy Cập Sample Data Importer**

```
Dashboard → Tools → Import Sample Data
```

### **Bước 2: Import Products**

```
Click button: "Import Demo Products"

✅ Wait for completion message:
"Sample data imported successfully!"
```

### **Bước 3: Kiểm Tra Products**

```
Dashboard → Kha Products → All Products

Should see 10 products:
INVERTERS (3):
- Inverter Growatt 3kW - MIN 3000TL-X
- Inverter Deye 5kW - SUN-5K-SG04LP3-EU
- Inverter SMA 10kW - Sunny Tripower 10.0

SOLAR PANELS (3):
- Tấm Pin Jinko 450W - Tiger Neo
- Tấm Pin Longi 550W - Hi-MO 5
- Tấm Pin Jinko 380W - Cheetah Plus

BATTERIES (2):
- Pin Lưu Trữ Pylontech 5.12kWh - US3000C
- Pin Lưu Trữ Huawei 10kWh - LUNA2000

ACCESSORIES (2):
- Bộ Giá Đỡ Tấm Pin Mái Ngói - Aluminum
- Cáp DC Solar 4mm² - 50m
```

### **Bước 4: Kiểm Tra Categories**

```
Dashboard → Kha Products → Categories

Should see:
- Inverter (Biến tần) - 3 products
- Pin Năng Lượng Mặt Trời - 2 products
- Tấm Pin Mặt Trời - 3 products
- Phụ Kiện & Thiết Bị Kèm Theo - 2 products
```

### **Bước 5: Kiểm Tra Brands**

```
Dashboard → Kha Products → Brands

Should see:
- Growatt - 1 product
- Deye - 1 product
- SMA - 1 product
- Jinko Solar - 2 products
- Longi Solar - 1 product
- Huawei - 1 product
```

---

## 🎨 CẤU HÌNH THEME

### **1. Site Identity**

```
Dashboard → Appearance → Customize → Site Identity

Site Title: Kha Solar
Tagline: Giải pháp năng lượng mặt trời toàn diện

Logo: Upload your logo
- Recommended size: 400x100 pixels
- Format: PNG with transparent background

Site Icon (Favicon):
- Upload icon
- Size: 512x512 pixels
- Format: PNG or ICO

Click "Publish"
```

### **2. Menus**

```
Dashboard → Appearance → Menus

Create new menu: "Main Menu"

Add Items:
- Custom Links:
  → Home: /
- Pages:
  → Sản Phẩm
  → Tính Toán Solar
  → So Sánh Sản Phẩm
- Custom Links:
  → Blog: /blog
  → Liên Hệ: /lien-he

Display location:
→ Check "Primary Menu"

Click "Save Menu"

Create another menu: "Footer Menu"
Add Items:
- Pages: Giỏ Hàng, Thanh Toán
- Custom Links: Về Chúng Tôi, Chính Sách, Điều Khoản

Display location:
→ Check "Footer Menu"

Click "Save Menu"
```

### **3. Widgets**

```
Dashboard → Appearance → Widgets

SIDEBAR:
Drag and drop:
- Search Widget
- Recent Posts Widget
- Categories Widget
- Tag Cloud Widget

FOOTER WIDGET 1:
- Text Widget
  Title: Về Kha Solar
  Content: [Company description]

FOOTER WIDGET 2:
- Navigation Menu Widget
  Title: Liên Kết Nhanh
  Menu: Footer Menu

FOOTER WIDGET 3:
- Text Widget
  Title: Liên Hệ
  Content:
    📧 Email: info@khasolar.com
    📞 Hotline: 0901234567
    📍 Địa chỉ: [Your address]

Click "Save"
```

### **4. Homepage Settings**

```
Dashboard → Appearance → Customize → Homepage Settings

Your homepage displays:
→ Select "A static page"

Homepage: Create new page "Trang Chủ"

Add shortcodes:
[kha_featured_products limit="8" columns="4"]
[kha_product_categories columns="3"]
[kha_products category="inverter" limit="4"]

Posts page: Select "Blog"

Click "Publish"
```

### **5. Colors (Optional)**

```
Dashboard → Appearance → Customize → Colors

Primary Color: #0066cc (Blue)
Secondary Color: #6c757d (Gray)
Link Color: #0066cc
Hover Color: #004499

Click "Publish"
```

---

## 🔧 CẤU HÌNH PLUGIN

### **1. Chat Bubble Settings**

```
Dashboard → Settings → Kha Solar Chat

Enable Chat Bubbles:
→ Check "Enable chat bubbles"

Contact Information:
- Zalo Phone: 0901234567
- Facebook Page ID: your-page-id
- Phone Hotline: 0901234567

Display Settings:
Position: Bottom-right
Show on: All pages
Greeting Message: "👋 Xin chào! Cần tư vấn về thiết bị solar?"
Animation: Pulse

Click "Save Changes"
```

### **2. Currency Settings**

```
Dashboard → Settings → General (hoặc Settings → Kha Solar nếu có)

Currency Symbol: ₫
Currency Position: Right
Thousand Separator: .
Decimal Separator: ,
Number of Decimals: 0

Example: 15.000.000₫

Click "Save Changes"
```

### **3. Calculator Settings**

```
Settings already configured by default:
- Average Daily Usage: 15 kWh
- Average Sunlight Hours: 4.5 hours
- System Efficiency: 80%
- Electricity Rate: 2,500 VND/kWh
- Cost per kW: 20,000,000 VND

To change:
Dashboard → Settings → Kha Solar → Calculator
(if settings page exists)
```

### **4. Cart Settings**

```
Default settings:
- Session Timeout: 7 days
- Guest Checkout: Enabled
- Order Prefix: KHS-

No action required unless you want to change.
```

---

## ✅ KIỂM TRA & TESTING

### **1. Frontend Testing**

#### **A. Homepage**
```
Visit: http://localhost/khasolar
      hoặc https://yourdomain.com

Check:
☐ Logo hiển thị đúng
☐ Menu hoạt động
☐ Shortcodes hiển thị sản phẩm
☐ Footer widgets hiển thị
☐ Chat bubbles xuất hiện (bottom-right)
☐ Responsive trên mobile
```

#### **B. Shop Page**
```
Visit: /san-pham

Check:
☐ Hiển thị 10 sản phẩm
☐ Product cards có ảnh, giá, button
☐ Filters hoạt động (category, brand, price)
☐ Search box hoạt động
☐ Pagination hiển thị (nếu >12 products)
```

#### **C. Single Product**
```
Click vào 1 sản phẩm

Check:
☐ Ảnh lớn hiển thị
☐ Gallery thumbnails
☐ Giá hiển thị đúng format
☐ Thông số kỹ thuật
☐ Button "Thêm vào giỏ" hoạt động
☐ AJAX add to cart (không reload)
☐ Mini cart update số lượng
```

#### **D. Shopping Cart**
```
Visit: /gio-hang

Check:
☐ Sản phẩm trong giỏ hiển thị
☐ Tăng/giảm số lượng hoạt động
☐ Xóa sản phẩm hoạt động
☐ Tổng tiền tính đúng
☐ Button "Thanh toán" hoạt động
```

#### **E. Checkout**
```
Visit: /thanh-toan

Check:
☐ Form hiển thị đầy đủ fields
☐ Required fields có validation
☐ Email validation
☐ Phone validation
☐ Tổng đơn hàng đúng
☐ Button "Đặt hàng" hoạt động
```

#### **F. Calculator**
```
Visit: /tinh-toan-solar

Check:
☐ Form inputs hoạt động
☐ Calculate button tính toán đúng
☐ Hiển thị kết quả:
  - Công suất khuyến nghị
  - ROI (thời gian hoàn vốn)
  - Tiết kiệm hàng năm
  - Chi phí ước tính
```

#### **G. Comparison**
```
Visit: /so-sanh

Check:
☐ Add products to compare (max 4)
☐ Comparison table hiển thị
☐ Specs compare đúng
☐ Remove product hoạt động
☐ Clear all hoạt động
```

#### **H. Search**
```
Use search box

Check:
☐ Autocomplete suggestions
☐ AJAX real-time search
☐ Click suggestion → go to product
☐ Enter → search results page
```

#### **I. Chat Bubbles**
```
Check bottom-right corner

Check:
☐ 3 buttons hiển thị (Zalo, Messenger, Phone)
☐ Greeting bubble xuất hiện sau 3s
☐ Greeting tự động ẩn sau 10s
☐ Click Zalo → open Zalo (mobile) hoặc zalo.me (desktop)
☐ Click Messenger → open m.me/page-id
☐ Click Phone → call (mobile) hoặc show modal (desktop)
☐ Cookie hoạt động (greeting không show lại trong 24h)
```

### **2. Admin Testing**

#### **A. Products Management**
```
Dashboard → Kha Products

Check:
☐ List all products
☐ Quick edit hoạt động
☐ Bulk actions hoạt động
☐ Search products
☐ Filter by category
☐ Add new product
☐ Upload product images
☐ Edit product
☐ Delete product
```

#### **B. Orders Management**
```
Dashboard → Kha Orders

After placing a test order:

Check:
☐ Order appears in list
☐ Order number format: KHS-XXXXXX
☐ Customer info correct
☐ Products correct
☐ Total correct
☐ Status can be changed
☐ Order details view
☐ Search orders
☐ Filter by status
```

#### **C. Categories & Brands**
```
Dashboard → Kha Products → Categories
Dashboard → Kha Products → Brands

Check:
☐ Add new category
☐ Edit category
☐ Delete category
☐ Category hierarchy
☐ Add brand
☐ Edit brand
☐ Delete brand
```

### **3. Performance Testing**

#### **Page Load Speed:**
```
Tools: Google PageSpeed Insights
       GTmetrix
       Pingdom

Target:
- Desktop: < 2s
- Mobile: < 3s
- Performance Score: > 90
```

#### **Database Queries:**
```
Install: Query Monitor plugin

Check:
- Homepage: < 50 queries
- Product page: < 30 queries
- Shop page: < 40 queries

Target: < 0.5s query time
```

### **4. Browser Testing**

Test on:
```
☐ Google Chrome (latest)
☐ Mozilla Firefox (latest)
☐ Safari (if Mac)
☐ Microsoft Edge (latest)
☐ Mobile Safari (iPhone)
☐ Mobile Chrome (Android)
```

### **5. Security Testing**

```
Check:
☐ SQL Injection: Try inject SQL in forms
☐ XSS: Try inject JavaScript
☐ CSRF: Check nonce on all forms
☐ File Upload: Try upload PHP file (should reject)
☐ Authentication: Try access admin without login
```

---

## ⚡ TỐI ƯU HÓA

### **1. Caching Plugin**

**Install WP Super Cache:**
```
Dashboard → Plugins → Add New
Search: "WP Super Cache"
Install → Activate

Settings → WP Super Cache
→ Caching: ON
→ Recommended Settings
→ Update Status

Clear cache after any changes!
```

**Alternative: W3 Total Cache**
```
More advanced but complex
Good for high-traffic sites
```

### **2. Image Optimization**

**Install Smush:**
```
Dashboard → Plugins → Add New
Search: "Smush"
Install → Activate

Smush → Bulk Smush Now
→ Optimize all existing images

Settings:
→ Enable Automatic compression
→ Enable Lazy load
→ Max width: 1200px
```

### **3. Database Optimization**

**Install WP-Optimize:**
```
Dashboard → Plugins → Add New
Search: "WP-Optimize"
Install → Activate

WP-Optimize → Database
→ Clean all post revisions
→ Clean auto-drafts
→ Remove spam comments
→ Remove trashed comments
→ Optimize database tables

Schedule: Weekly
```

### **4. CDN Setup (Optional)**

**Cloudflare (Free):**
```
1. Sign up: cloudflare.com
2. Add your domain
3. Change nameservers
4. Enable:
   - Auto Minify (CSS, JS, HTML)
   - Brotli compression
   - Rocket Loader
   - Caching Level: Standard
```

### **5. PHP OPcache**

**Enable in php.ini:**
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
```

### **6. GZIP Compression**

**Add to .htaccess:**
```apache
# Gzip compression
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/json
</IfModule>
```

---

## 🐛 TROUBLESHOOTING

### **Problem 1: Plugin không activate được**

**Giải pháp:**
```bash
Check PHP version:
php -v
→ Must be 7.4+

Check PHP extensions:
php -m | grep mysqli
php -m | grep gd

Check file permissions:
chmod 755 wp-content/plugins/kha-solar-shop/
chmod 644 wp-content/plugins/kha-solar-shop/*.php
```

### **Problem 2: Theme không hiển thị đúng**

**Giải pháp:**
```
1. Dashboard → Settings → Permalinks
   → Click "Save Changes"

2. Check theme files:
   wp-content/themes/khasolar-theme/style.css (must exist)

3. Clear browser cache:
   Ctrl + Shift + Delete

4. Clear WordPress cache:
   Dashboard → WP Super Cache → Delete Cache
```

### **Problem 3: Products không hiển thị**

**Giải pháp:**
```
1. Flush rewrite rules:
   Dashboard → Settings → Permalinks → Save

2. Check if products exist:
   Dashboard → Kha Products → All Products

3. Check shortcode:
   [kha_products] (correct)
   [kha_solar_products] (old, incorrect)

4. Check page template:
   Edit page → Template: Default
```

### **Problem 4: Images không load**

**Giải pháp:**
```bash
Check uploads folder permissions:
chmod 755 wp-content/uploads/
chmod 644 wp-content/uploads/*.*

Check .htaccess:
Must contain WordPress rewrite rules

Regenerate thumbnails:
Dashboard → Tools → Regenerate Thumbnails
```

### **Problem 5: Chat bubbles không xuất hiện**

**Giải pháp:**
```
1. Check settings:
   Dashboard → Settings → Kha Solar Chat
   → Enable chat bubbles: Checked
   → At least one contact method filled

2. Check JavaScript errors:
   Open browser console (F12)
   → Should not have errors

3. Check jQuery loaded:
   View page source → Search "jquery"
   → Should find jquery.js

4. Clear cache:
   Browser cache + WordPress cache
```

### **Problem 6: Calculator không tính toán**

**Giải pháp:**
```
1. Check AJAX:
   Browser console → Network tab
   → Should see POST to admin-ajax.php

2. Check nonce:
   Error: "Security check failed"
   → Clear cache and retry

3. Check JavaScript:
   public/js/solar-calculator.js exists
   → Check file permissions: 644
```

### **Problem 7: Orders không lưu**

**Giải pháp:**
```
1. Check database tables:
   phpMyAdmin → wp_kha_orders table exists

2. Check nonce on checkout:
   View source → Search "kha_checkout_nonce"

3. Check PHP errors:
   Enable debugging in wp-config.php:
   define('WP_DEBUG', true);
   define('WP_DEBUG_LOG', true);

4. Check error log:
   wp-content/debug.log
```

### **Problem 8: Slow Performance**

**Giải pháp:**
```
1. Install Query Monitor:
   Dashboard → Plugins → Add New → "Query Monitor"

2. Check slow queries:
   Query Monitor → Queries by Component

3. Optimize images:
   Install Smush → Bulk optimize

4. Enable caching:
   Install WP Super Cache

5. Disable unused plugins:
   Deactivate plugins you don't need

6. Upgrade hosting:
   Consider better hosting plan
```

---

## 📚 TÀI LIỆU THAM KHẢO

### **Plugin Documentation:**
- Installation Guide: `/kha-solar-shop/INSTALLATION.md`
- Product System: `/kha-solar-shop/PRODUCT-SYSTEM.md`
- Plugin README: `/kha-solar-shop/README.md`

### **Project Documentation:**
- Project Overview: `/README.md`
- Statistics: `/PROJECT-STATS/STATISTICS.md`
- File Structure: `/PROJECT-STATS/FILE-STRUCTURE.md`
- Setup Guide: `/SETUP-GUIDE.md` (this file)

### **Online Resources:**
- WordPress Codex: https://codex.wordpress.org/
- PHP Manual: https://www.php.net/manual/
- MySQL Documentation: https://dev.mysql.com/doc/

---

## ✅ HOÀN TẤT CÀI ĐẶT

### **Checklist Cuối Cùng:**

```
☐ WordPress installed & configured
☐ Plugin activated
☐ Theme activated
☐ Sample data imported
☐ Permalinks saved
☐ Menus created & assigned
☐ Widgets configured
☐ Chat bubbles configured
☐ All pages working
☐ Products display correctly
☐ Cart functions properly
☐ Checkout works
☐ Calculator calculates
☐ Comparison works
☐ Search works
☐ Orders save correctly
☐ Admin functions work
☐ No JavaScript errors
☐ No PHP errors
☐ Performance optimized
☐ Caching enabled
☐ Images optimized
☐ Security checked
☐ Backup created
```

---

## 🎉 CHÚC MỪNG!

Bạn đã hoàn tất cài đặt **Kha Solar - Complete E-commerce Solution**!

### **Next Steps:**

1. **Customize Content:**
   - Add real products
   - Create blog posts
   - Add company info pages

2. **Configure Advanced:**
   - Set up email notifications
   - Configure payment gateways (if adding)
   - Set up analytics (Google Analytics)

3. **Marketing:**
   - SEO optimization
   - Social media integration
   - Email marketing

4. **Maintenance:**
   - Regular backups
   - Plugin/theme updates
   - Security monitoring

---

## 📞 HỖ TRỢ

**Cần giúp đỡ?**

- 📧 Email: support@khasolar.vn
- 📚 Documentation: /README.md
- 🐛 Report bugs: GitHub Issues

---

**🚀 Setup Guide v1.0.0**
**📅 Last updated: 17/11/2024**
**✅ Tested on WordPress 6.0+ / PHP 8.0+**
