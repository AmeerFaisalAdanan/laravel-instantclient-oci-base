# Use PHP 8.2 FPM base image
FROM php:8.2-fpm

# Set build-time architecture (passed automatically by docker buildx or docker-compose)
ARG TARGETARCH

# Install general dependencies
RUN apt-get update -y && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    wget \
    nano \
    libaio1 \
    iputils-ping \
    telnet

# Install Node.js 20 (multi-arch safe) + latest npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest

# Debug Node versions
RUN node -v && npm -v

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

# ==========================
# Install Oracle Instant Client
# ==========================
ENV LD_LIBRARY_PATH=/opt/oracle/instantclient_19_23

# Download and install Oracle Instant Client based on architecture
RUN mkdir -p /home/downloads && cd /home/downloads && \
    if [ "$TARGETARCH" = "arm64" ]; then \
        wget -q -O instantclient-basic.zip https://download.oracle.com/otn_software/linux/instantclient/1923000/instantclient-basic-linux.arm64-19.23.0.0.0dbru.zip && \
        wget -q -O instantclient-sqlplus.zip https://download.oracle.com/otn_software/linux/instantclient/1923000/instantclient-sqlplus-linux.arm64-19.23.0.0.0dbru.zip && \
        wget -q -O instantclient-sdk.zip https://download.oracle.com/otn_software/linux/instantclient/1923000/instantclient-sdk-linux.arm64-19.23.0.0.0dbru.zip ; \
    else \
        wget -q -O instantclient-basic.zip https://download.oracle.com/otn_software/linux/instantclient/1919000/instantclient-basic-linux.x64-19.19.0.0.0dbru.el9.zip && \
        wget -q -O instantclient-sqlplus.zip https://download.oracle.com/otn_software/linux/instantclient/1919000/instantclient-sqlplus-linux.x64-19.19.0.0.0dbru.el9.zip && \
        wget -q -O instantclient-sdk.zip https://download.oracle.com/otn_software/linux/instantclient/1919000/instantclient-sdk-linux.x64-19.19.0.0.0dbru.el9.zip ; \
    fi && \
    unzip -o instantclient-basic.zip && \
    unzip -o instantclient-sqlplus.zip && \
    unzip -o instantclient-sdk.zip && \
    mkdir -p /opt/oracle && \
    cp -r instantclient_* /opt/oracle/instantclient_19_23

ENV ORACLE_HOME=/opt/oracle/instantclient_19_23 
ENV LD_LIBRARY_PATH="$ORACLE_HOME"
ENV PATH="$ORACLE_HOME:$PATH"

# Install OCI8 PHP extension
RUN docker-php-ext-configure oci8 --with-oci8=instantclient,/opt/oracle/instantclient_19_23 && \
    docker-php-ext-install oci8

# Link libraries
RUN echo /opt/oracle/instantclient_19_23 > /etc/ld.so.conf.d/oracle-instantclient.conf && ldconfig

# ==========================
# Install Composer (latest)
# ==========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ==========================
# App setup
# ==========================
COPY . /var/www
WORKDIR /var/www

# Create app user to avoid root
ARG user=app
ARG uid=1000
ARG gid=1000
RUN groupadd -g $gid $user && useradd -u $uid -g $gid -m $user

# Fix permissions
RUN chown -R $user:$user /var/www

# Switch to non-root user
USER $user

# Install PHP dependencies
# RUN composer install && php artisan key:generate

# Uncomment if you want to build frontend assets
# RUN npm install && npm run build

EXPOSE 9000
CMD ["php-fpm"]
