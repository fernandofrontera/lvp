FROM php:7.4-apache-bullseye

RUN docker-php-ext-install pdo pdo_mysql
RUN apt-get update && apt-get install -y unzip

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php --install-dir=/usr/local/bin --filename composer
RUN php -r "unlink('composer-setup.php');"

USER www-data

RUN composer create-project symfony/skeleton lvp "~5.4"

WORKDIR lvp

COPY --chown=www-data:www-data lvp/composer.* lvp/phpunit.xml.dist ./
RUN composer config extra.symfony.allow-contrib true && composer update && composer sync-recipes

USER root

COPY lvp.conf /etc/apache2/sites-enabled/000-default.conf
RUN service apache2 restart

COPY --chown=www-data:www-data migrate test /usr/bin/
RUN chmod +x /usr/bin/migrate /usr/bin/test

COPY --chown=www-data:www-data lvp/config ./config
COPY --chown=www-data:www-data lvp/src ./src
COPY --chown=www-data:www-data lvp/tests ./tests

CMD ["apache2-foreground"]