#!/bin/bash

echo "=== THỐNG KÊ DỰ ÁN KHA SOLAR ==="
echo ""

# Plugin files
echo "PLUGIN FILES (kha-solar-shop/):"
echo "  PHP files: $(find kha-solar-shop -name "*.php" | wc -l)"
echo "  CSS files: $(find kha-solar-shop -name "*.css" | wc -l)"
echo "  JS files: $(find kha-solar-shop -name "*.js" | wc -l)"
echo "  MD files: $(find kha-solar-shop -name "*.md" | wc -l)"

# Theme files
echo ""
echo "THEME FILES (khasolar-theme/):"
echo "  PHP files: $(find khasolar-theme -name "*.php" | wc -l)"
echo "  CSS files: $(find khasolar-theme -name "*.css" | wc -l)"
echo "  JS files: $(find khasolar-theme -name "*.js" | wc -l)"

# Total files
echo ""
echo "TOTAL FILES:"
echo "  All files: $(find . -type f \( -name "*.php" -o -name "*.css" -o -name "*.js" -o -name "*.md" \) | wc -l)"

# Lines of code
echo ""
echo "LINES OF CODE:"
echo "  Plugin PHP: $(find kha-solar-shop -name "*.php" -exec wc -l {} + | tail -1 | awk '{print $1}')"
echo "  Plugin CSS: $(find kha-solar-shop -name "*.css" -exec wc -l {} + | tail -1 | awk '{print $1}')"
echo "  Plugin JS: $(find kha-solar-shop -name "*.js" -exec wc -l {} + | tail -1 | awk '{print $1}')"
echo "  Theme PHP: $(find khasolar-theme -name "*.php" -exec wc -l {} + | tail -1 | awk '{print $1}')"
echo "  Theme CSS: $(find khasolar-theme -name "*.css" -exec wc -l {} + | tail -1 | awk '{print $1}')"
echo "  Theme JS: $(find khasolar-theme -name "*.js" -exec wc -l {} + | tail -1 | awk '{print $1}')"
echo "  Documentation: $(find . -name "*.md" -exec wc -l {} + | tail -1 | awk '{print $1}')"
