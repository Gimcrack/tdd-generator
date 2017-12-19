# TDD Generator
Laravel TDD Generator Helper

## Description

* Adds `Route::apiResource` routes to your specified routes file. 
* Generates stubs for:
* * Controller
* * Events 
* * Factory
* * Migration
* * Model
* * Requests
* * Feature Test 
* * Unit Test

## Usage

```
php artisan tdd:generate 
    { model : The model name to be generated } 
    { routes? : The routes file to use } 
    { --force : Force overwriting existing files }
```

To generate stubs for the `Group` model and add the routes to the `api-admin.php` routes file, you would use the following:

```
php artisan tdd:generate Group api-admin.php
```

