# Installation

###Step 1

Add service provider to config/app.php

''Milkwood\LaravelBackupper\LaravelBackupperServiceProvider.php''

###Step 2

Add facade to config/app.php
Milkwood\LaravelBackupper\Facades\DbBackupEnviroment

###Step 3
Change filesystem config local path to just storage not /app

###Step 4
Add s3 credencials

php artisan vendor:publish(Both config and view) <br>