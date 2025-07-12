FROM php:8.2-apache
WORKDIR /var/www/html
#COPY ./CRUD-Pesqueiro /var/www/html/CRUD-Pesqueiro 
RUN a2enmod rewrite
EXPOSE 80
CMD ["apache2-foreground"]