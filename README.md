# Uber-Cueillette

A PHP/MySQL web application that connects agricultural employers with seasonal harvest workers. Built as a Web 2 academic  project by Abdelmoemen Riahi

## Features

- Separate registration and login flows for farmers and workers
- Farmers can publish and manage harvest offers
- Workers can browse offers and submit applications
- Farmers can accept or reject applications
- Work history, ratings, comments, and earnings views
- PHP sessions for authenticated user flows
- MySQL access through PDO and parameterized queries
- Password hashing and verification using PHP's password API
- HTML/CSS interface with JavaScript form validation

## Tech stack

- PHP 8+
- MySQL 8+ (or compatible MySQL/MariaDB version)
- PDO MySQL extension
- HTML, CSS, JavaScript
- Apache via XAMPP, WAMP, or a similar local PHP stack

## Local setup

1. Install a local PHP/MySQL environment such as XAMPP.
2. Start Apache and MySQL.
3. Create a database named `uber_cueillette`.
4. Import [`database/schema.sql`](database/schema.sql) using phpMyAdmin or the MySQL client.
5. Configure the database settings for your local environment. `config.php` reads `UBER_DB_HOST`, `UBER_DB_NAME`, `UBER_DB_USER`, and `UBER_DB_PASSWORD` from environment variables. For a typical local XAMPP setup, the defaults are `127.0.0.1`, `uber_cueillette`, `root`, and an empty password; adjust them to match your own setup.
6. Copy the project folder into your web server's document root (for example, `htdocs/uber-cueillette`).
7. Open `http://localhost/uber-cueillette/` in your browser.
8. Register a new farmer or worker account to explore the application. The public SQL schema intentionally does not include the original personal accounts, profile photos, offers, or applications.

## Database and privacy

The SQL file is a schema/reference-data export for portfolio use. Personal/demo account data, CIN values, emails, profile photos, and application/offer records have been removed. Never commit real user records, passwords, API keys, or local environment files.

## Project structure

- `index.php` — landing page
- `login.php`, `registerfarmer.php`, `registerworker.php` — authentication and registration
- `farmer/` — farmer dashboard, offers, applications, and evaluation pages
- `worker/` — worker dashboard, offers, applications, profile, and history pages
- `css/` — stylesheets
- `javascript/script.js` — client-side validation and interactions
- `database/schema.sql` — database schema and non-personal lookup data

## Notes

This repository documents an academic project and is not presented as a production-ready recruitment service. Review authorization checks, session hardening, upload validation, and database permissions before deploying it publicly.

## Author

- Abdelmoemen Riahi

