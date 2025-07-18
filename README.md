
![Build Status](https://github.com/AmeerFaisalAdanan/laravel-instantclient-oci-base/actions/workflows/testbuild.yml/badge.svg)

Update: Now support for arm64 dockerfile.

# Running instantclient on your mac (windows and linux too)

I hate installing and configuring oracle products so I create this repo for everyone who need. I appreciate every comment / issue to make this repo better for everyone in need of quick and lazy way to start developing (mostly laravel project) with Oracle DB 


## Pull Request

I open this repo for any enhancement or any fix to make this dockerfile more accessible and stay up to date with new oracle instantclient version. Make a PR to contribute.


## Component

This repo is setup and configure the following

    1. php (using 8.1)
    2. composer 
    3. oci8 
    4. instantclient (using 19.23)

Feel free to modify the files (or add any services e.g oracle database) inside /php/Dockerfile.php with your preferred configuration. No database image provided since I used remote oracle db for my work and I dont think I needed to setup another database image with this container.


s## Deployment

Create your project at the root of you project using `composer create` command if you are using laravel. After configuring things inside this repository, just run `docker compose up -d` to start the container