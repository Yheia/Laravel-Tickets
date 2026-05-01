#!/bin/bash
composer install
php artisan migrate
php artisan storage:link
php artisan tinker --execute="App\Models\User::create(['name'=>'admin','email'=>'test@gmail.com','password'=>bcrypt('12345678'),'role'=>'supervisor','sector'=>'general']);"
php-fpm
