# Student Record Management System

A simple web-based system to manage student records. Add, edit, delete and search student information with automatic grade calculation.

## Login

Username: `admin`
Password: `admin123`

Password must be at least 4 characters and contain a number.

## Setup

1. Place the project in XAMPP htdocs folder
2. Create a database named `student_db`
3. Import the SQL file into the database
4. Visit `http://localhost/assesment2/public/login.php`

## Features

- Add new student records
- View all students in a table
- Search students by name (real-time AJAX search)
- Edit student information
- Delete student records
- Automatic grade calculation (A, B, F)
- Color-coded grades and attendance tracking
- Calculate average marks and performance remarks

## Database

The system uses a MySQL database with a `students` table that stores:
- Student name
- Class (Grade 1-10)
- Marks for 5 subjects (English, Math, Science, Nepali, Social)
- Attendance percentage

## Files

- `public/login.php` - Login page
- `public/index.php` - Main dashboard
- `public/add.php` - Add student
- `public/edit.php` - Edit student
- `public/delete.php` - Delete student
- `public/search.php` - Search functionality
- `config/db.php` - Database connection
- `assets/css/style.css` - Styling
- `assets/js/script.js` - Search script
- `includes/header.php` - Page header
- `includes/footer.php` - Page footer

## Known Issues

None at the moment.
