<?php

namespace Milkwood\LaravelBackupper;

use Illuminate\Support\ServiceProvider;
use Milkwood\LaravelBackupper\Classes\DbBackupEnviroment;
use Milkwood\LaravelBackupper\Classes\DbBackupDirectory;
use Milkwood\LaravelBackupper\Classes\BackupReporter;

use Milkwood\LaravelBackupper\Interfaces\DbBackupEnviromentInterface;
use Milkwood\LaravelBackupper\Interfaces\DbBackupDirectoryInterface;

class LaravelBackupperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfigFiles();

        $this->publishViews();
    }

    public function publishConfigFiles(){
    
        $this->publishes([

            __DIR__.'/config' => config_path()

        ]);
    
    }

    public function publishViews(){

        $this->loadViewsFrom(__DIR__.'/views/','laravelBackupper');

        $this->publishes([

            __DIR__.'/views/' => base_path('resources/views/vendor/laravelBackupper/')

        ]);
    
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBackupDb();
        $this->registeBackupReport();
        $this->registerBackupCleaner();
        $this->bindBackupEnviroments();
        $this->bindBackupDirectories();
    }

    public function registerBackupDb(){
    
        $this->commands('Milkwood\LaravelBackupper\Commands\BackupDbCommand');
    
    }

    public function registeBackupReport(){
    
        $this->commands('Milkwood\LaravelBackupper\Commands\BackupReportCommand');
    
    }

    public function registerBackupCleaner(){
    
        $this->commands('Milkwood\LaravelBackupper\Commands\BackupCleanerCommand');
    
    }

    public function bindBackupEnviroments(){

        $this->app->singleton(DbBackupEnviromentInterface::class, DbBackupEnviroment::class);
    
    }

    public function bindBackupDirectories(){
    
        $this->app->singleton(DbBackupDirectoryInterface::class, DbBackupDirectory::class);    
    
    }

}
