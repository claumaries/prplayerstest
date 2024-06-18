# PR Players test

You can find the requirements for this test here: [Requirements](docs/Requirements.md)


## Prerequisites

Before you begin, ensure you have met the following requirements:
- **PHP**: Version 8.2 or newer.
- **MySQL**: Version 8.0 is recommended.
- **Composer**: Ensure Composer 2 is installed for managing dependencies.
- **NodeJs**: Version 20.2.0 or newer.
- **npm** Version 9.6.6 or newer (or yarn).


## Installation
Please refer to the following document for detailed installation instructions: [Installation](docs/Installation.md).

## Tests
The tests use the SQLite in-memory database, so please ensure the sqlite3 extension is installed and enabled.
```bash
php artisan test
```
Or use:
```bash
./vendor/bin/phpunit 
```

## Test user
You can run the User seeder in order to create a test user (email: test@test.com and password: testPassword24!)
```bash
php artisan db:seed --class=UserSeeder
```
or, create a user using the Register page http://127.0.0.1:8000/register