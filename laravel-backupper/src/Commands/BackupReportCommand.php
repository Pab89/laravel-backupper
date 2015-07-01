<?php

namespace LaravelBackupper\Commands;

use Illuminate\Console\Command;
use LaravelBackupper\Classes\BackupReporter;

class BackupReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:report {email} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send out a report showing the last backups that has run.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $recipiant = new \stdClass;
        $recipiant->email = $this->argument('email');
        $recipiant->name = $this->argument('name');
        $backupReporter = new BackupReporter( $recipiant );
        $backupReporter->sendBackupReport();
        
        $this->info('Backup report send to '.$recipiant->email);

    }
}
