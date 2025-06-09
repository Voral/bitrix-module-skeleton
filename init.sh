#!/bin/bash

# Ввод данных
read -p "Enter your vendor name (e.g., Developer): " VENDOR
read -p "Enter module name (e.g., Connector): " MODULE

# Формирование namespace
NAMESPACE="${VENDOR}\\${MODULE}"

# Преобразование в разные форматы
UPPER_CASE=$(echo "${VENDOR}_${MODULE}" | tr '[:lower:]' '[:upper:]')
LOWER_CASE=$(echo "${VENDOR}_${MODULE}" | tr '[:upper:]' '[:lower:]')
DOT_NOTATION=$(echo "${VENDOR}.${MODULE}" | tr '[:upper:]' '[:lower:]')

# Директории для поиска
DIRECTORIES=(src tests .)

# Поиск и замена во всех файлах
for dir in "${DIRECTORIES[@]}"; do
  find "$dir" -type f -name "*.php" -o -name "*.php.dist" -o -name "*.neon" -o -name "*.xml" -o -name "composer.json" | while IFS= read file; do
    echo "[INFO] Replacing in $file"
    if [ -f "$file" ] && [ -w "$file" ]; then
     sed -i "s/VENDOR_SKELETON/${UPPER_CASE}/g" "$file"
     sed -i "s/vendor_skeleton/${LOWER_CASE}/g" "$file"
     sed -i "s/vendor\.skeleton/${DOT_NOTATION}/g" "$file"
     sed -i "s/Vendor\\\\Skeleton/${NAMESPACE//\\/\\\\}/g" "$file"
    else
     echo "[ERROR] Cannot process $file (not a writable file)"
    fi
  done
done

echo -e "\n.vs-mock-builder.local.php" >> .gitignore

# Обновляем README.md
cat <<EOL >> README.md
# ${VENDOR} \\ ${MODULE}

Этот модуль реализует функционал для 1С-Битрикс.

## Установка

Требуется PHP 8.1+

## Тестирование

\`\`\`bash
composer test
\`\`\`

## Лицензия

MIT
EOL

# Удаляем старую папку .git
rm -rf .git
git init
git add .
git commit -m "Initial commit"

echo "Namespace replaced to: ${NAMESPACE}"
echo "Module name replaced to: ${MODULE}"
echo "Now you can start developing your module!"