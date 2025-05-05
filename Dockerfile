# 1️⃣ Base image
FROM php:8.2-fpm-bullseye 

# 2️⃣ Set working directory
WORKDIR /var/www

# 3️⃣ Fix slow/failed package install by using valid mirrors and install dependencies

RUN apt-get update && \
    apt-get install -y --no-install-recommends \ 
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libvips-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl


# 4️⃣ Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# 5️⃣ Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# 6️⃣ Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# 7️⃣ Copy app files
COPY . .

# 8️⃣ Expose php-fpm default port
EXPOSE 9000

# 9️⃣ Start PHP-FPM
CMD ["php-fpm"]
