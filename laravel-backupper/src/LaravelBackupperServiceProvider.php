<?php

namespace LaravelBackupper;

use Illuminate\Support\ServiceProvider;
use LaravelBackupper\Classes\DbBackupEnviroment;
use LaravelBackupper\Classes\BackupReporter;

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
        $this->bindDbBackupEnviroment();
        $this->bindBackReporter();
    }

    public function registerBackupDb(){
    
        $this->commands('LaravelBackupper\Commands\BackupDbCommand');
    
    }

    public function registeBackupReport(){
    
        $this->commands('LaravelBackupper\Commands\BackupReportCommand');
    
    }

    public function bindDbBackupEnviroment(){
    
        $this->app->singleton('dbBackupEnviroment', function ($app) {

            return new DbBackupEnviroment;

        });
    
    }

    public function bindBackReporter(){
    
        $this->app->singleton('backupReporter', function ($app) {

            return new BackupReporter;

        });
    
    }
}
