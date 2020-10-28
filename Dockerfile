FROM php:fpm-alpine
RUN docker-php-ext-install pdo_mysql
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'c31c1e292ad7be5f49291169c0ac8f683499edddcfd4e42232982d0fd193004208a58ff6f353fde0012d35fdd72bc394') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"; \
    php composer-setup.php; \
    php -r "unlink('composer-setup.php');"; \
    mv composer.phar /usr/local/bin/composer
RUN mkdir /run/php
WORKDIR /run/php
COPY . .
RUN composer install
CMD bin/console --no-interaction doctrine:migrations:migrate; bin/console --no-interaction doctrine:fixtures:load; php-fpm
EXPOSE 9000
