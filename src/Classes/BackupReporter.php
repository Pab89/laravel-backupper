<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

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
		
			$this->setdbBackupFiles();
			$this->sendTheReport();
		
		}

		protected function setdbBackupFiles(){
		
			$files = Storage::files( DbBackupFile::getPath() );

			foreach($files as $file){

				if( DbBackupFile::isAValidFile($file) ){

					$this->dbBackupFiles[] = new DbBackupFile($file);

				}

			}

		
		}

		protected function sendTheReport(){

			// LocalDbBackupsCount get -1 due to .gitignore file
			$localDbBackupsCount = count( Storage::files( DbBackupFile::getPath() ) ) - 1;
			$cloudDbBackupsCount = count( BackupEnviroment::getCloudDisk()->files( DbBackupFile::getCloudPath() ) );

			$viewVaribles = compact('localDbBackupsCount','cloudDbBackupsCount');
			$viewVaribles['dbBackupFiles'] = $this->dbBackupFiles;
		
			\Mail::send('laravelBackupper::emails.backupReport',$viewVaribles,
						function($message){
							$message->to( $this->recipiant->email, $this->recipiant->name )->subject( $this->getSubject() );
						});
		
		}

		protected function getSubject(){
		
			return config('laravelBackupper.projectName').' backup report';
		
		}

	}

?>