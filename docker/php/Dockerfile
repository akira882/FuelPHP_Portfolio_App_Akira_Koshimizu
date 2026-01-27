# このファイルは、PHPが動く「料理ロボット（コンテナ）」のスペックを決める「レシピ」です。

# 1. 基礎となるPHPのバージョンを決めます（ここでは8.2を使います）。
FROM php:8.2-fpm

# 2. PHPを動かすために必要な「追加のツール（ライブラリ）」をインストールします。
# 料理でいう「包丁」や「まな板」を揃える作業だと思ってください。
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring gd

# 3. 「Composer（コンポーザー）」というツールを入れます。
# これは、FuelPHPなどの「他人が作った便利な部品」を簡単に取り入れるための道具です。
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. 作業をする場所（フォルダ）を決めます。
# これ以降の命令は、この場所で行われます。
WORKDIR /var/www
