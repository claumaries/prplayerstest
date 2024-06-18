# Installation

Follow these steps to get your development environment running:

1. **Clone the repository**:
    ```bash
    git clone https://github.com/claumaries/prplayers
    ```

2. **Navigate to the project directory**:
    ```bash
    cd yourprojectname
    ```

3. **Install dependencies**:
    ```bash
    composer install
    ```   

4. **Copy the example env file and make the necessary configuration adjustments**:
    ```bash
    cp .env.example .env
    ```
   
5. **Create a new database called prplayers, update the .end file**:
    ```dotenv
    DB_DATABASE=prplayers
     
    // Add your DB connection   
    DB_HOST=localhost
    DB_PORT=3306
    DB_USERNAME=root
    DB_PASSWORD=
    ```

6. **Generate an application key**:
    ```bash
    php artisan key:generate
    ```

7. **Run the database migrations (Set the database connection in .env before migrating)**:
    ```bash
    php artisan migrate
    ```

8. **Install the npm packages**:
    ```bash
    npm install
    ```
9. **Install the npm packages**:
    ```bash
    php artisan storage:link
    ```

10 **Start the development server**:

This will start the local development server at http://localhost:8000

   ```bash
       php artisan serve
   ```


11. **Run npm dev**:
    ```bash
    npm run dev
    ```