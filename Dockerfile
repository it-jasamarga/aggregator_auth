FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    libmcrypt-dev \
    curl \
    libaio1 \
    redis-server \
    && docker-php-ext-install -j$(nproc) pdo \
    && docker-php-ext-install  mbstring

# ORACLE oci 
RUN mkdir /opt/oracle \
    && cd /opt/oracle     
    
ADD instantclient-basic-linux.x64-12.1.0.2.0.zip /opt/oracle
ADD instantclient-sdk-linux.x64-12.1.0.2.0.zip /opt/oracle

# Install Oracle Instantclient
RUN  unzip /opt/oracle/instantclient-basic-linux.x64-12.1.0.2.0.zip -d /opt/oracle \
    && unzip /opt/oracle/instantclient-sdk-linux.x64-12.1.0.2.0.zip -d /opt/oracle \
    && ln -s /opt/oracle/instantclient_12_1/libclntsh.so.12.1 /opt/oracle/instantclient_12_1/libclntsh.so \
    && ln -s /opt/oracle/instantclient_12_1/libclntshcore.so.12.1 /opt/oracle/instantclient_12_1/libclntshcore.so \
    && ln -s /opt/oracle/instantclient_12_1/libocci.so.12.1 /opt/oracle/instantclient_12_1/libocci.so \
    && rm -rf /opt/oracle/*.zip
    
# set env for oci8
ENV LD_LIBRARY_PATH  /opt/oracle/instantclient_12_1:${LD_LIBRARY_PATH}
    
# Install Oracle extensions
RUN echo 'instantclient,/opt/oracle/instantclient_12_1/' | pecl install oci8-2.2.0 \ 
      && docker-php-ext-enable \
               oci8 \ 
       && docker-php-ext-configure pdo_oci --with-pdo-oci=instantclient,/opt/oracle/instantclient_12_1,12.1 \
       && docker-php-ext-install \
               pdo_oci 

RUN apt-get install supervisor -y

RUN apt-get install -y nginx  && \
    rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html
WORKDIR /var/www/html

RUN chmod +rx /usr/local/bin/composer

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# update memory limit
RUN echo 'memory_limit = -1' >> "$PHP_INI_DIR/conf.d/docker-php-ram-limit.ini"

# run in verbose biar keliatan progressnya
RUN composer update -vvv

#RUN rm /etc/nginx/sites-enabled/default

COPY docker/deploy.conf /etc/nginx/conf.d/default.conf

RUN mv /etc/nginx/nginx.conf /etc/nginx/nginx.conf.backup

COPY nginx.conf /etc/nginx/

RUN mv /usr/local/etc/php-fpm.d/www.conf /usr/local/etc/php-fpm.d/www.conf.backup
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf

COPY .env.dev /var/www/html/.env

RUN usermod -a -G www-data root
RUN chgrp -R www-data storage

RUN chown -R www-data:www-data ./storage
RUN chmod -R 777 ./storage
RUN chmod -R 777 bootstrap/cache

# RUN php artisan cache:clear
# RUN php artisan config:clear
# RUN php artisan route:clear
# RUN php artisan view:clear
# RUN php artisan route:cache
# RUN php artisan config:cache
# # RUN ln -s ./secret/.env .env

RUN chmod +x ./docker/run

ENTRYPOINT ["bash", "docker/run"]

EXPOSE 8181
