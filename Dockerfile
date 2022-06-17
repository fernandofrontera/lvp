FROM php:7.4-apache-bullseye

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN apt-get update && apt-get install -y unzip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename composer
RUN php -r "unlink('composer-setup.php');"

USER www-data

RUN composer create-project symfony/skeleton lvp "~5.4"

WORKDIR lvp

COPY --chown=www-data:www-data lvp/composer.* ./
RUN composer update && composer sync-recipes

USER root

COPY lvp.conf /etc/apache2/sites-enabled/000-default.conf
RUN service apache2 restart

COPY --chown=www-data:www-data lvp/config ./config
COPY --chown=www-data:www-data lvp/src ./src

CMD ["apache2-foreground"]