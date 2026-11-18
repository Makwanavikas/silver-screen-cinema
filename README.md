# SilverScreen Cinemas – Movie Ticket Booking System

A PHP + MySQL web application for browsing movies, choosing theaters/shows, selecting seats and booking tickets, with an admin panel and email-OTP verification.

## Features
- User registration with email OTP verification, login, forgot/reset password (OTP)
- Browse movies, trailers, search, movie details
- Select theater, show and seats; payment and ticket confirmation; My Bookings
- Admin panel: manage movies, theaters, shows, trailers and users

## Tech stack
PHP, MySQL (mysqli), HTML/CSS, PHPMailer (Composer)

## Setup (XAMPP)
1. Copy this folder to `C:\xampp\htdocs\cinema-project`.
2. Start Apache and MySQL in XAMPP.
3. In phpMyAdmin create a database `cinema_db` and import `database/cinema_db.sql`.
4. Copy `config/db.example.php` to `config/db.php` and set your DB details.
5. Copy `config/mail.example.php` to `config/mail.php` and add your Gmail address and a Gmail **App Password**.
6. Open `http://localhost/cinema-project/`.
