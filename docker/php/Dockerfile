FROM php:7.3-fpm-alpine
LABEL maintainer="ikuma"

# tinker(psysh)
ARG PSYSH_DIR=/usr/local/share/psysh
ARG PHP_MANUAL_URL=http://psysh.org/manual/ja/php_manual.sqlite

# timezone
ARG TZ

# composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer

RUN set -eux && \
  apk update && \
  apk add --update --no-cache --virtual=.build-dependencies \
    autoconf \
    gcc \
    g++ \
    make \
    tzdata \
    git && \
  apk add --update --no-cache \
    icu-dev \
    libzip-dev && \
  #Mecab,ipadic, php-mecabのインストール
  cd /tmp && \
  wget "https://drive.google.com/uc?export=download&id=0B4y35FiV1wh7cENtOXlicTFaRUE" -O mecab-0.996.tar.gz && \
  tar zxf mecab-0.996.tar.gz && \
  cd mecab-0.996 && \
  ./configure --enable-utf8-only --with-charset=utf8 && \
  make && \
  make install && \
  cd /tmp && \
  wget "https://drive.google.com/uc?export=download&id=0B4y35FiV1wh7MWVlSDBCSXZMTXM" -O mecab-ipadic-2.7.0-20070801.tar.gz && \
  tar zxf mecab-ipadic-2.7.0-20070801.tar.gz && \
  cd mecab-ipadic-2.7.0-20070801 && \
  ./configure --with-charset=utf8  && \
  make && \
  make install && \
  cd /tmp && \
  git clone https://github.com/rsky/php-mecab.git && \
  git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/redis && \
  cd php-mecab/mecab && \
  phpize && \
  ./configure --with-mecab=/usr/local/bin/mecab-config && \
  make && \
  make install && \
  echo 'extension=mecab.so' > /usr/local/etc/php/conf.d/docker-php-ext-mecab.ini && \
  rm -rf \
    mecab-0.996* \
    mecab-ipadic-2.7.0-20070801* \
    php-mecab && \
  #OSの設定
  cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
  echo ${TZ} > /etc/timezone && \
  pecl install xdebug && \
  apk del .build-dependencies && \
  docker-php-ext-install intl pdo_mysql mbstring zip bcmath redis && \
  docker-php-ext-enable xdebug && \
  mkdir $PSYSH_DIR && wget $PHP_MANUAL_URL -P $PSYSH_DIR && \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
  composer config -g repos.packagist composer https://packagist.jp && \
  composer global require hirak/prestissimo
