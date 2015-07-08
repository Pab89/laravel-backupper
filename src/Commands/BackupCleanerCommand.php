<?php

namespace Milkwood\LaravelBackupper\Commands;

use Illuminate\Console\Command;
use Milkwood\LaravelBackupper\Classes\DbBackupFile;
use Milkwood\LaravelBackupper\Interfaces\DbBackupEnviromentInterface;

class BackupCleanerCommand extends Command
{

    public $dbEnviroment;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up the backups delete old backups not needed anymore.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(DbBackupEnviromentInterface $dbEnviroment)
    {
        parent::__construct();
        $this->dbEnviroment = $dbEnviroment;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dbEnviroment->cleanUp();

        $this->info('Backups cleaned');

    }
}
