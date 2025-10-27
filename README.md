
# Course Creation Web App (Laravel)

A Laravel-based web application for creating and managing courses with multiple modules and nested contents.
Built for the Laravel Job Interview Task Assessment — using HTML, CSS, Vanilla JS, jQuery, and Laravel.

# Features

Create courses with title, description, category & feature video upload

Add unlimited modules per course

Add multiple contents (text, image, video, link) per module

Frontend + backend validation

Clean, nested view of Course → Module → Content

Transaction-safe database storage

Responsive UI with jQuery-based dynamic fields

# Tech Stack

Frontend: HTML, CSS, JS, jQuery
Backend: Laravel 10+
Database: MySQL
Storage: Laravel Storage (for videos & images)

# Setup
git clone https://github.com/<your-username>/course-creator.git
cd course-creator
composer install
npm install && npm run dev
cp .env.example .env
php artisan migrate
php artisan storage:link
php artisan serve


Visit http://localhost:8000/courses

🗄 Database Relations

Course → hasMany → Module

Module → hasMany → Content

# Highlights

Uses Laravel validation (StoreCourseRequest)

Scalable controller design with helper methods

Uses createMany() for efficient bulk inserts

Graceful error handling and user-friendly feedback

# Submission

Public GitHub repo with:

Working project

This README.md

Optional screenshots

Author: Md. Tarikul Aziz
📧 tarikaziz0933@gmail.com

# Screenshots
<img width="880" height="335" alt="image" src="https://github.com/user-attachments/assets/80d5f4dd-1b7d-437c-b785-2591789cf590" />

<img width="550" height="626" alt="image" src="https://github.com/user-attachments/assets/4aa784f8-72f3-43a9-8a04-f354cd1c5025" />

<img width="569" height="190" alt="image" src="https://github.com/user-attachments/assets/f31726eb-b6e7-46a9-abe6-36d4d02c638c" />



