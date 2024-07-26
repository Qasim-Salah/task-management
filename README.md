# Task Management System

## Project Description

The Task Management System is designed to handle various projects, each containing multiple tasks assigned to users.
This project leverages Laravel, Livewire, and Tailwind CSS to provide a robust and efficient task management solution.

## Technologies Used

- **Laravel**: A PHP framework for web artisans.
- **Livewire**: A full-stack framework for Laravel that simplifies building dynamic interfaces without leaving the
  comfort of Laravel.
- **Breeze**: Provides simple and minimal authentication scaffolding.
- **Sanctum**: Facilitates API authentication.
- **Tailwind CSS**: A utility-first CSS framework for rapid UI development.

## Installation

1. **Clone the repository:**

    ```sh
    git clone https://github.com/Qasim-Salah/task-management.git
    cd task-management
    ```

2. **Checkout the `develop` branch:**

    ```sh
    git checkout develop
    ```

3. **Pull the latest changes from the `develop` branch:**

    ```sh
    git pull origin develop
    ```

4. **Create your database and run migrations:**

    - Create a new database in your preferred database management system.
    - Update the `.env` file with your database credentials.

    ```sh
    php artisan migrate
    ```

5. **Generate application key:**

    ```sh
    php artisan key:generate
    ```

6. **Serve the application:**

    ```sh
    php artisan serve
    ```
7. **npm run dev:**

## Usage

1. **Access the application:**
    - Open your browser and navigate to `http://localhost:8000`.

2. **Login or register:**
    - Use the authentication scaffolding provided by Breeze to log in or create a new account.

3. **Create and manage projects and tasks:**
    - Utilize the intuitive interface to create new projects and assign tasks to users.

Postman collection link: https://documenter.getpostman.com/view/15222347/2sA3kYjLEx

