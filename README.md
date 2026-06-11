# Shield Basketball Services

## Project Overview

Shield Basketball Services is a Laravel-based API for managing basketball club operations, including player management, training schedules, evaluations, and reports. The system supports roles like Admin, Coach, and Parent/Player, providing features for user authentication, player biodata input, training management, attendance tracking, performance evaluations, and progress reporting.

## Tech

Shield Basketball Services uses the following technologies:

- [Laravel 13.6.0] - PHP web framework for building robust APIs.
- [PHP 8.4.0] - Server-side scripting language.
- [Composer] - Dependency manager for PHP.
- [MySQL/PostgreSQL] - Database (configurable via .env).
- [Postman] - API testing and documentation.
    Postman URL : https://documenter.getpostman.com/view/51016306/2sBXVZnu4j

## Branching Strategy

- **main**: Production branch. Code here is deployed to production.
- **dev**: Development branch. All development work merges here.
- **Feature branches**: Create new branches from `dev` for implementing new features. Use naming like `feature/player-management` or `bugfix/login-issue`. Merge back to `dev` via pull requests.

## How To Run & Build

### Prerequisites
- PHP 8.4.0 or higher
- Composer
- Git
- PostgreSQL database

### Installation Steps
1. Clone the repository:
    ```console
    git clone https://github.com/your-repo/shield-basketball-servicesv2.git
    cd shield-basketball-servicesv2
    ```

2. Install dependencies:
    ```console
    composer install
    ```

3. Copy the environment file and configure it:
    ```console
    cp .env.example .env
    ```
    Edit `.env` to set your database credentials and other configurations.

4. Generate application key:
    ```console
    php artisan key:generate
    ```

5. Run database migrations (if applicable):
    ```console
    php artisan migrate
    ```

6. Start the development server:
    ```console
    php artisan serve
    ```
    The API will be available at `http://127.0.0.1:8000`.

**Happy Coding, Hell Yeah!**
