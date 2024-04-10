
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Guideline
### Step 1: Install PHP 8.1, Composer and Node.js
- [Composer download](https://getcomposer.org/download/)
- [Nodejs download](https://getcomposer.org/download/)
### Step 2: Clone source code from Git
- [GitHub - anvu1113/fruit-shop](https://github.com/anvu1113/fruit-shop.git)
### Step 3: Install dependent packages
```bash
composer install
```
```bash
npm install
```
### Step 4: Create file .env
#### Window
```bash
copy .env.example .env
```
#### Mac
```bash
cp .env.example .env
```
#### - Create 1 database then config database in file .env
### Step 5: Run the Laravel project
```bash
php artisan key:generate
```
```bash
php artisan migrate
```
```bash
php artisan serve
```
```bash
npm run dev
```





