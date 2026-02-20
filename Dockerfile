FROM php:8.2-apache

# Enable mod_rewrite (in case needed)
RUN a2enmod rewrite

# Set ServerName to suppress warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copy project files into web root
COPY . /var/www/html/

# Set correct ownership
RUN chown -R www-data:www-data /var/www/html/

# Copy and install entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["apache2-foreground"]
