# SED international E-SHOP

# Funding

SED E-shop is web store selling many electronic devices 

---

# Overview

Used Symfony as core framework

----

# Debian Requirements

    * sudo apt-get install php7.0 php7.0-dev php7.0-zip php7.0-mysql php-pear php7.0-xml php7.0-mbstring php7.0-soap

# Installation

Install system
Install using `pip`...
    
    php7.0 /usr/local/bin/composer install
    
    php7.0 bin/console ckeditor:install
 
    php7.0 bin/console assets:install
    
    php7.0 bin/console doctrine:generate:entity

Database:

    sudo su postgres
    
    psql
    
    CREATE USER leon WITH PASSWORD 'leon#1234';
    
    CREATE DATABASE zina_flow OWNER leon;

This "parameters.yml" file is auto-generated during the composer install

    parameters:
        database_host: 127.0.0.1
        database_port: null
        database_name: web_sed_iri
        database_user: root
        database_password: ''
        secret: ThisTokenIsNotSoSecretChangeIt`
    
Create a super-user

    php7.0 bin/console fos:user:create adminuser --super-admin
    
Run server
    
    php7.0 bin/console server:run
    
Utils

    php7.0 bin/console doctrine:generate:entity # Create entity
    
    php7.0 bin/console doctrine:generate:entity
