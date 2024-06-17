Invoice Management System
This repository contains the code for an Invoice Management System built with Laravel (backend API) and Flutter (frontend). This system allows users to create invoices, initiate payments, and view transaction histories.

Technologies Used

Laravel (Backend)
Framework: Laravel 8.x
Database: MySQL (can be configured to other databases supported by Laravel)
External Services: M-Pesa (for payment processing in sandbox mode)
Tools: ngrok (for exposing localhost to generate callback URLs)
Flutter (Frontend)
Framework: Flutter 2.x
State Management: Stateless widgets and FutureBuilder for asynchronous operations
HTTP Client: http package for making API requests to Laravel backend


Installation

1. Clone the repository using terminal or download zip file:
            git clone https://github.com/danielwambo/invoice-backend.git
2. cd invoice-backend

3. Install dependencies:
composer install

4. Set up environment variables:
Rename .env.example to .env.
Configure your database connection (DB_* variables) and M-Pesa sandbox credentials (MPESA_* variables).

5. Generate application key:
php artisan key:generate

6. Run database migrations
php artisan migrate

7. Start the Laravel development server:
php artisan serve

8. Expose localhost using ngrok (if needed for M-Pesa callbacks):
ngrok http 8000

9. Update callback URLs:
Update M-Pesa callback URLs (CallBackURL) in TransactionController.php and ensure ngrok URL is used during development.
