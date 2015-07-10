<?php

namespace Milkwood\LaravelBackupper\Commands;

use Illuminate\Console\Command;
use Milkwood\LaravelBackupper\Classes\DbBackupFile;

class BackupDbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:db {--fileName=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the db via mysql dump.';

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
        $dbBackupFileName = $this->getFileName();
        $dbBackupFile = DbBackupFile::createNew( $dbBackupFileName );

        $this->info( $dbBackupFile->fileName." created, local: ".$dbBackupFile->existsInLocal().", cloud: ".$dbBackupFile->existsInCloud() );
    }

    public function getFileName(){
    
        return ( $this->option('fileName') != "false" ) ?
                $this->option('fileName') :
                false ;

    }
}
