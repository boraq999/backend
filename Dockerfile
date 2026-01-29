# استخدام نسخة PHP الرسمية مع Apache
FROM php:8.4-apache

# تثبيت الإضافات اللازمة لـ Laravel و Postgres
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_pgsql zip

# تفعيل خاصية mod_rewrite في Apache
RUN a2enmod rewrite

# ضبط المجلد الرئيسي للعمل
WORKDIR /var/www/html

# نسخ ملفات المشروع
COPY . .

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# ضبط الصلاحيات لمجلدات Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# تغيير المجلد الرئيسي لـ Apache ليشير إلى مجلد public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# المنفذ الذي سيعمل عليه السيرفر
EXPOSE 80