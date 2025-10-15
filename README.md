# event-booking-backend
This is an event booking backend created with Laravel 10

# Laravel 10 Project Setup Guide

This guide explains how to clone and run this Laravel 10 application on your local environment.

---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

## 1. Clone the Repository

git clone https://github.com/ChamodiKu/event-booking-backend

Then navigate into the project directory:
cd event-booking-backend

## 2. Install Dependencies
Install all PHP dependencies using Composer:
composer install

## 3. Create Environment File
Duplicate the example .env file:
cp .env.example .env

Then open .env and update the following values according to your setup:
APP_NAME="Event Booking"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=event_booking
DB_USERNAME=root
DB_PASSWORD=

## 4. Generate Application Key
php artisan key:generate

## 5. Run Migrations and Seeders
php artisan migrate --seed

## 6. Run the Application
Start the Laravel development server:
php artisan serve

The project will be available at:
http://127.0.0.1:8000
