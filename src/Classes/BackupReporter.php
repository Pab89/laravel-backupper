<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	class BackupReporter{

		public $recipiant;


		public function __construct($recipiant){
			
			$this->recipiant = $recipiant;
			
		}

		public function sendBackupReport(){
		
			$this->createBackupReport();
		
		}

		public function createBackupReport(){
		
			$this->sendTheReport();
		
		}

		protected function sendTheReport(){

			$localDbBackupsCount = count( \DbBackupEnviroment::getLocalFiles() );
			$cloudDbBackupsCount = count( \DbBackupEnviroment::getCloudFiles() );

			$viewVaribles = compact('localDbBackupsCount','cloudDbBackupsCount');
			$viewVaribles['dbBackupFiles'] = \DbBackupEnviroment::getBackupDirectory()->files->toArray();
		
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