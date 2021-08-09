Тестовое задание

1. Склонировать репозиторий и выполнить команды
```
composer install -o
cp .env.example .env // Указать в .env параметры подключения к БД и APP_URL
php artisan migrate:fresh
```
Проект требует перенаправление всего на /public/index.php

2. Тесты postman: https://www.getpostman.com/collections/675eb58f6f5507efa7d3
В переменную коллекции {{host}} указать хост, на котором разворачивается задание.
