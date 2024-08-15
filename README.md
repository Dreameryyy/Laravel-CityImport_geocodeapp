# Laravel City Import and Geolocation Application

This Laravel application imports and stores data for cities/municipalities located in the Nitra region from [e-obce.sk](https://www.e-obce.sk/kraj/NR.html). It also geolocates the cities based on their addresses and provides a front-end for displaying city details.

## Requirements

- **PHP**: Version 8.2.4
- **Laravel**: Version 11.19.0
- **Composer**: For managing PHP dependencies
- **Node.js & npm**: For managing front-end dependencies
- **MySQL**: As the database engine (configured via `.env` file)
- **Google Maps API Key**: For geocoding

## Installation
 
### Step 1: Clone the Repository

```bash
git clone https://github.com/Dreameryyy/Laravel-CityImport_geocodeapp
cd Laravel-CityImport_geocodeapp
```

### Step 2: Set Up the Environment

1. Copy the `.env` file:
Copy the `.env.example` file to `.env` and update necessary environment variables, such as the database credentials and the Google Maps API key.
```bash
cp .env.example .env
```
2. Install PHP dependencies: 
Install the required PHP packages using Composer.
```bash
composer install
```
3. Generate Application Key:
Generate a new application key for your Laravel application.
```bash
php artisan key:generate
```
### Step 3: Set Up the Database
1. Create the Database:
Create a MySQL database that matches the credentials in your `.env` file.
2. Run Migrations:
Run the database migrations to set up the necessary tables.
```bash
php artisan migrate
```

### Step 4: Install Node.js Dependencies
Install the required Node.js packages using npm.
```bash
npm install
```

### Step 5: Build Frontend Assets
Compile the frontend assets (CSS, JS) using Laravel Vite.
```bash
npm run dev
```

### Step 6: Run the application
Start the Laravel development server.
```bash
php artisan serve
```
If everything is set up correctly, you should be able to access the application at `http://localhost:8000`.

### Step 7: Import City Data
Run the `data:import` command to scrape city data from the e-obce.sk website and store it in the database.
```bash
php artisan data:import
```

### Step 8: Geocode Cities
Run the `data:geocode` command to geolocate the cities based on their addresses.
```bash
php artisan data:geocode
```

## Notes

* Ensure that your MySQL server is running before attempting to run migrations or access the application.
* The `data:import` command can be run multiple times without duplicating data.
* You must have a valid Google Maps API key for the geocoding functionality to work.

## Known Issues

### City: Zbrojníky

- **Issue**: When importing data for the city "Zbrojníky," the mayor's name is not displayed in full. This issue occurs during the data import process, where the mayor's name is truncated or not parsed correctly from the source website.
- **Workaround**: Currently, there is no workaround for this issue within the import script itself. However, after the import, you can manually update the mayor's name in the database if needed.
- **Status**: This issue is under investigation, and a fix will be applied in future.

