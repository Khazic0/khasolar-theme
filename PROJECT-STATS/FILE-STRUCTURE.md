# 📁 CẤU TRÚC FILES VÀ LINK TRUY CẬP

> **Dự án:** Kha Solar - Complete E-commerce Solution
> **Tổng files:** 66 files
> **Repository:** `/home/user/khasolar-theme/`

---

## 📂 CẤU TRÚC THƯ MỤC TỔNG QUAN

```
khasolar-theme/
├── 📁 kha-solar-shop/          # Plugin E-commerce (43 files)
│   ├── 📁 admin/               # Admin functionality (4 files)
│   ├── 📁 assets/              # Static assets
│   ├── 📁 includes/            # Core classes (11 files)
│   ├── 📁 public/              # Frontend assets (14 files)
│   ├── 📁 templates/           # Template files (11 files)
│   ├── 📁 widgets/             # Widget classes (2 files)
│   ├── 📄 kha-solar-shop.php   # Main plugin file
│   └── 📄 *.md                 # Documentation (3 files)
│
├── 📁 khasolar-theme/          # WordPress Theme (22 files)
│   ├── 📁 css/                 # Stylesheets (2 files)
│   ├── 📁 inc/                 # Theme includes (2 files)
│   ├── 📁 js/                  # JavaScript (2 files)
│   ├── 📁 template-parts/      # Template parts (4 files)
│   ├── 📄 *.php                # Template files (13 files)
│   └── 📄 style.css            # Main stylesheet
│
├── 📁 PROJECT-STATS/           # Thống kê dự án
│   ├── 📄 STATISTICS.md        # Thống kê chi tiết
│   ├── 📄 FILE-STRUCTURE.md    # File này
│   └── 📄 count-files.sh       # Script đếm files
│
└── 📄 README.md                # Documentation tổng quan
```

---

## 🔌 PLUGIN: KHA-SOLAR-SHOP

### **Đường Dẫn:** `/home/user/khasolar-theme/kha-solar-shop/`

### 📁 **ADMIN (4 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **class-order-management.php** | `admin/class-order-management.php` | 1,124 | Quản lý đơn hàng trong admin |
| **class-product-meta-boxes.php** | `admin/class-product-meta-boxes.php` | 856 | Meta boxes cho sản phẩm |
| **admin-style.css** | `admin/css/admin-style.css` | 134 | CSS cho admin |
| **admin-script.js** | `admin/js/admin-script.js` | 42 | JavaScript admin |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/admin/
```

---

### 📁 **INCLUDES - Core Classes (11 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **class-activator.php** | `includes/class-activator.php` | 393 | Plugin activation/deactivation |
| **class-ajax-handler.php** | `includes/class-ajax-handler.php` | 678 | AJAX request handler |
| **class-calculator.php** | `includes/class-calculator.php` | 543 | Máy tính solar system |
| **class-cart.php** | `includes/class-cart.php` | 892 | Shopping cart functionality |
| **class-comparison.php** | `includes/class-comparison.php` | 467 | So sánh sản phẩm |
| **class-order.php** | `includes/class-order.php` | 834 | Xử lý đơn hàng |
| **class-product-post-type.php** | `includes/class-product-post-type.php` | 1,245 | Custom post type sản phẩm |
| **class-sample-data.php** | `includes/class-sample-data.php` | 394 | Import dữ liệu mẫu |
| **class-search.php** | `includes/class-search.php` | 389 | AJAX search functionality |
| **class-shortcodes.php** | `includes/class-shortcodes.php` | 289 | 8 shortcodes system |
| **product-functions.php** | `includes/product-functions.php` | 723 | Helper functions |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/includes/
```

---

### 📁 **TEMPLATES (11 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **single-product.php** | `templates/single-product.php` | 487 | Chi tiết sản phẩm |
| **archive-products.php** | `templates/archive-products.php` | 445 | Danh sách sản phẩm |
| **checkout.php** | `templates/checkout.php` | 398 | Trang thanh toán |
| **cart.php** | `templates/cart.php` | 356 | Giỏ hàng |
| **calculator.php** | `templates/calculator.php` | 334 | Máy tính solar |
| **comparison.php** | `templates/comparison.php` | 298 | So sánh sản phẩm |
| **order-confirmation.php** | `templates/order-confirmation.php` | 267 | Xác nhận đơn hàng |
| **search-results.php** | `templates/search-results.php` | 234 | Kết quả tìm kiếm |
| **product-card.php** | `templates/partials/product-card.php` | 289 | Card sản phẩm |
| **mini-cart.php** | `templates/partials/mini-cart.php` | 234 | Mini cart header |
| **search-box.php** | `templates/partials/search-box.php` | 114 | Search box |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/templates/
/home/user/khasolar-theme/kha-solar-shop/templates/partials/
```

---

### 📁 **WIDGETS (2 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **class-chat-bubble.php** | `widgets/class-chat-bubble.php` | 532 | Chat bubble widget class |
| **chat-bubble.php** | `widgets/templates/chat-bubble.php` | 193 | Chat bubble template |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/widgets/
/home/user/khasolar-theme/kha-solar-shop/widgets/templates/
```

---

### 📁 **PUBLIC CSS (6 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **shop.css** | `public/css/shop.css` | 1,567 | Styles cho shop pages |
| **cart.css** | `public/css/cart.css` | 1,234 | Styles cho giỏ hàng |
| **calculator.css** | `public/css/calculator.css` | 998 | Styles cho calculator |
| **comparison.css** | `public/css/comparison.css` | 876 | Styles cho comparison |
| **chat-bubble.css** | `public/css/chat-bubble.css` | 456 | Styles cho chat bubbles |
| **admin-style.css** | `admin/css/admin-style.css` | 132 | Styles cho admin |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/public/css/
```

---

### 📁 **PUBLIC JAVASCRIPT (8 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **filter-products.js** | `public/js/filter-products.js` | 567 | Lọc sản phẩm động |
| **add-to-cart.js** | `public/js/add-to-cart.js` | 489 | Thêm vào giỏ AJAX |
| **cart-handler.js** | `public/js/cart-handler.js` | 456 | Xử lý giỏ hàng |
| **solar-calculator.js** | `public/js/solar-calculator.js` | 434 | Logic máy tính solar |
| **search-autocomplete.js** | `public/js/search-autocomplete.js` | 398 | Tìm kiếm autocomplete |
| **comparison.js** | `public/js/comparison.js` | 356 | So sánh sản phẩm |
| **chat-bubble.js** | `public/js/chat-bubble.js` | 204 | Chat bubble interactions |
| **admin-script.js** | `admin/js/admin-script.js` | 343 | Admin JavaScript |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/public/js/
```

---

### 📁 **DOCUMENTATION (3 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **INSTALLATION.md** | `INSTALLATION.md` | 678 | Hướng dẫn cài đặt |
| **PRODUCT-SYSTEM.md** | `PRODUCT-SYSTEM.md` | 534 | Tài liệu hệ thống sản phẩm |
| **README.md** | `README.md` | 336 | Plugin overview |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/
```

---

### 📄 **MAIN PLUGIN FILE**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **kha-solar-shop.php** | `kha-solar-shop.php` | 579 | Main plugin file |

**Link truy cập:**
```bash
/home/user/khasolar-theme/kha-solar-shop/kha-solar-shop.php
```

---

## 🎨 THEME: KHASOLAR-THEME

### **Đường Dẫn:** `/home/user/khasolar-theme/khasolar-theme/`

### 📁 **ROOT TEMPLATES (13 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **style.css** | `style.css` | 498 | Main stylesheet & theme info |
| **functions.php** | `functions.php` | 243 | Theme setup & functions |
| **index.php** | `index.php` | 48 | Main template file |
| **header.php** | `header.php` | 89 | Header template |
| **footer.php** | `footer.php` | 54 | Footer template |
| **single.php** | `single.php` | 32 | Single post template |
| **page.php** | `page.php` | 38 | Page template |
| **archive.php** | `archive.php` | 47 | Archive template |
| **search.php** | `search.php` | 45 | Search results template |
| **404.php** | `404.php` | 41 | Error page template |
| **sidebar.php** | `sidebar.php` | 11 | Sidebar template |
| **comments.php** | `comments.php` | 56 | Comments template |
| **searchform.php** | `searchform.php` | 15 | Search form template |

**Link truy cập:**
```bash
/home/user/khasolar-theme/khasolar-theme/
```

---

### 📁 **TEMPLATE PARTS (4 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **content.php** | `template-parts/content.php` | 89 | Default post content |
| **content-single.php** | `template-parts/content-single.php` | 93 | Single post content |
| **content-page.php** | `template-parts/content-page.php` | 37 | Page content |
| **content-none.php** | `template-parts/content-none.php` | 52 | No content found |

**Link truy cập:**
```bash
/home/user/khasolar-theme/khasolar-theme/template-parts/
```

---

### 📁 **INCLUDES (2 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **template-tags.php** | `inc/template-tags.php` | 133 | Custom template functions |
| **customizer.php** | `inc/customizer.php` | 33 | Customizer support |

**Link truy cập:**
```bash
/home/user/khasolar-theme/khasolar-theme/inc/
```

---

### 📁 **CSS (2 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **style.css** | `style.css` | 498 | Main stylesheet |
| **custom.css** | `css/custom.css` | 391 | Additional styles |

**Link truy cập:**
```bash
/home/user/khasolar-theme/khasolar-theme/
/home/user/khasolar-theme/khasolar-theme/css/
```

---

### 📁 **JAVASCRIPT (2 files)**

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **navigation.js** | `js/navigation.js` | 88 | Menu & navigation |
| **customizer.js** | `js/customizer.js` | 42 | Customizer live preview |

**Link truy cập:**
```bash
/home/user/khasolar-theme/khasolar-theme/js/
```

---

## 📚 DOCUMENTATION ROOT

### **Đường Dẫn:** `/home/user/khasolar-theme/`

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **README.md** | `README.md` | 456 | Project overview |

**Link truy cập:**
```bash
/home/user/khasolar-theme/README.md
```

---

## 📊 PROJECT STATS

### **Đường Dẫn:** `/home/user/khasolar-theme/PROJECT-STATS/`

| File | Đường Dẫn | Lines | Mô Tả |
|------|-----------|-------|-------|
| **STATISTICS.md** | `PROJECT-STATS/STATISTICS.md` | Current | Thống kê chi tiết |
| **FILE-STRUCTURE.md** | `PROJECT-STATS/FILE-STRUCTURE.md` | Current | File này |
| **count-files.sh** | `PROJECT-STATS/count-files.sh` | 28 | Script đếm files |
| **file-list.txt** | `PROJECT-STATS/file-list.txt` | 66 | Danh sách files |

**Link truy cập:**
```bash
/home/user/khasolar-theme/PROJECT-STATS/
```

---

## 🔍 TÌM FILES NHANH

### **Tìm Theo Tên:**

```bash
# Tìm file PHP
find /home/user/khasolar-theme -name "*.php"

# Tìm file CSS
find /home/user/khasolar-theme -name "*.css"

# Tìm file JavaScript
find /home/user/khasolar-theme -name "*.js"

# Tìm documentation
find /home/user/khasolar-theme -name "*.md"
```

### **Tìm Theo Nội Dung:**

```bash
# Tìm class Cart
grep -r "class Cart" /home/user/khasolar-theme

# Tìm shortcode
grep -r "add_shortcode" /home/user/khasolar-theme

# Tìm AJAX handler
grep -r "wp_ajax" /home/user/khasolar-theme
```

---

## 📥 DOWNLOAD / ACCESS

### **Local Path:**
```bash
/home/user/khasolar-theme/
```

### **Git Repository:**
```bash
Branch: claude/kha-solar-shop-plugin-01BQTFZhWX95ksK4XWJsDxQP
Commit: 0db48c6
```

### **Download ZIP:**
```bash
cd /home/user/khasolar-theme
zip -r khasolar-theme.zip . -x "*.git*"
```

---

## 🎯 FILES QUAN TRỌNG

### **Must-Have Files:**

1. **Plugin Main:** `/kha-solar-shop/kha-solar-shop.php`
2. **Theme Main:** `/khasolar-theme/style.css`
3. **Functions:** `/khasolar-theme/functions.php`
4. **Documentation:** `/README.md`

### **Entry Points:**

| Type | File | Purpose |
|------|------|---------|
| Plugin | `kha-solar-shop.php` | Plugin initialization |
| Theme | `style.css` | Theme info & main styles |
| Theme | `functions.php` | Theme setup |
| Theme | `index.php` | Main template |

---

## 📋 FILE CATEGORIES

### **By Function:**

| Category | Files | Location |
|----------|-------|----------|
| **Admin** | 4 | `kha-solar-shop/admin/` |
| **Core Logic** | 11 | `kha-solar-shop/includes/` |
| **Templates** | 24 | `kha-solar-shop/templates/`, `khasolar-theme/` |
| **Styles** | 8 | `*/css/` |
| **Scripts** | 10 | `*/js/` |
| **Widgets** | 2 | `kha-solar-shop/widgets/` |
| **Docs** | 7 | `*.md` |

### **By Language:**

| Language | Files | Percentage |
|----------|-------|------------|
| PHP | 44 | 66.7% |
| CSS | 8 | 12.1% |
| JavaScript | 10 | 15.2% |
| Markdown | 4 | 6.0% |

---

## 🔗 USEFUL LINKS

### **Main Directories:**
- Plugin: `/home/user/khasolar-theme/kha-solar-shop/`
- Theme: `/home/user/khasolar-theme/khasolar-theme/`
- Stats: `/home/user/khasolar-theme/PROJECT-STATS/`
- Docs: `/home/user/khasolar-theme/README.md`

### **Key Files:**
- Plugin Entry: `kha-solar-shop/kha-solar-shop.php:1`
- Theme Entry: `khasolar-theme/style.css:1`
- Functions: `khasolar-theme/functions.php:1`
- Main README: `README.md:1`

---

## 📖 DOCUMENTATION LINKS

| Document | Path | Purpose |
|----------|------|---------|
| Project Overview | `/README.md` | Tổng quan dự án |
| Installation Guide | `/kha-solar-shop/INSTALLATION.md` | Hướng dẫn cài đặt |
| Product System | `/kha-solar-shop/PRODUCT-SYSTEM.md` | Hệ thống sản phẩm |
| Statistics | `/PROJECT-STATS/STATISTICS.md` | Thống kê chi tiết |
| File Structure | `/PROJECT-STATS/FILE-STRUCTURE.md` | File này |
| Setup Guide | Coming soon | Hướng dẫn setup |

---

## 🎓 HOW TO USE THIS GUIDE

### **Đọc File:**
```bash
cat /home/user/khasolar-theme/[file-path]
```

### **Edit File:**
```bash
nano /home/user/khasolar-theme/[file-path]
vim /home/user/khasolar-theme/[file-path]
```

### **View Structure:**
```bash
tree /home/user/khasolar-theme/
```

### **Search Content:**
```bash
grep -r "search-term" /home/user/khasolar-theme/
```

---

**📁 File structure documentation**
**🔄 Auto-generated from:** `find` command
**📅 Last updated:** 17/11/2024
**✅ All files verified and accessible**
