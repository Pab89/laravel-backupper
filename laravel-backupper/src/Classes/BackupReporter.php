<?php

	namespace LaravelBackupper\Classes;

	use LaravelBackupper\Classes\DbBackupFile;

	class BackupReporter{

		public $recipiant;

		public $dbBackupFiles = [];


		public function __construct($recipiant){
			
			$this->recipiant = $recipiant;
			
		}

		public function sendBackupReport(){
		
			$this->createBackupReport();
		
		}

		public function createBackupReport(){
		
			$this->setDbBackupFiles();
			$this->sendTheReport();
		
		}

		protected function setDbBackupFiles(){
		
			if( $backupDir = opendir( DbBackupFile::getPath() ) ){

				while( ($entry = readdir($backupDir)) !== false){

					if( DbBackupFile::isAValidFile($entry) ){
						$this->dbBackupFiles[] = new DbBackupFile($entry);
					}

				} 

			}
		
		}

		protected function sendTheReport(){
		
			\Mail::send('laravelBackupper::emails.backupReport',
						['dbBackupFiles' => $this->dbBackupFiles],
						function($message){
							$message->to( $this->recipiant->email, $this->recipiant->name )->subject('Backup report');
						});
		
		}

	}

?>