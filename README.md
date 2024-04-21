<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Alina Group test project (based on https://github.com/alinakz/backend)

## Requirements

- Docker engine

## Installation and Usage

- copy .env.example .env
- composer install
- ./vendor/bin/sail up -d
- run: "php artisan migrate" and "php artisan db:seed" inside sail container

## API

- url: http://localhost/api/documentation (Swagger documentation)
- use API login in authorize it will return token
- copy and add that token as bearer token to swagger by "authorize" green button

## Tests

- ./vendor/bin/phpunit
