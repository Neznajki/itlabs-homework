FROM php:8.3-apache
MAINTAINER Maris Locmelis

RUN rm -f /etc/localtime
RUN ln -s /usr/share/zoneinfo/UTC /etc/localtime

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    curl \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    libxslt-dev \
    libpq-dev \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        intl \
        xml \
        xsl \
        bcmath \
        opcache \
        zip

ENV USER=developer USER_ID=1000 USER_GID=1000

#install latest composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === 'c8b085408188070d5f52bcfe4ecfbee5f727afa458b2573b8eaaf77b3419b0bf2768dc67c86944da1544f06fa544fd47') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# now creating user
RUN groupadd --gid "${USER_GID}" "${USER}" && \
    useradd \
      --uid ${USER_ID} \
      --gid ${USER_GID} \
      --create-home \
      --shell /bin/bash \
      ${USER}

RUN usermod -a -G www-data developer

COPY conf/system/sudoers /etc/sudoers

RUN mkdir -p /opt/php
RUN mkdir -p /opt/xdebug
RUN chmod -R 0777 /opt/xdebug

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_DOCUMENT_ROOT=/www/default

COPY conf/apache/apache2.conf /etc/apache2/apache2.conf
COPY conf/apache/apache_default /etc/apache2/sites-available/000-default.conf
COPY conf/apache/apache_status.conf /etc/apache2/mods-enabled/status.conf
COPY conf/php/local_dockerfile.ini  /usr/local/etc/php/conf.d/custom.ini
COPY apache_root_config.sh /usr/local/bin/apache_root_config.sh
RUN chmod +x /usr/local/bin/apache_root_config.sh && \
    chmod +x /usr/local/bin/composer && \
    chown -R developer /tmp && \
    chown -R developer /etc/apache2/sites-enabled && \
    chown -R developer /etc/apache2/envvars && \
    chown -R developer /usr/sbin/apache2

RUN a2enmod rewrite
RUN a2enmod vhost_alias

WORKDIR ${APACHE_DOCUMENT_ROOT}
USER developer

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/apache_root_config.sh"]