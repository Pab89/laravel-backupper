Add service provider to config/app Milkwood\LaravelBackupper\LaravelBackupperServiceProvider.php <br>
Add facade to config/app Milkwood\LaravelBackupper\Facades\DbBackupEnviroment <br>
Change filesystem config local path to just storage not /app <br>
Add s3 credencials <br>
php artisan vendor:publish(Both config and view) <br>