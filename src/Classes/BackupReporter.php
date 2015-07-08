<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;
	use Milkwood\LaravelBackupper\Interfaces\DbBackupEnviromentInterface;

	class BackupReporter{

		public $recipiant;
		public $dbEnviroment;


		public function __construct($recipiant, DbBackupEnviromentInterface $dbEnviroment){
			
			$this->dbEnviroment = $dbEnviroment;
			$this->recipiant = $recipiant;
			
		}

		public function sendBackupReport(){
		
			$this->createBackupReport();
		
		}

		public function createBackupReport(){
		
			$this->sendTheReport();
		
		}

		protected function sendTheReport(){

			$viewVaribles = compact('localDbBackupsCount','cloudDbBackupsCount');
			$viewVaribles['dbBackupFiles'] = $this->dbEnviroment->getBackupDirectory()->files->toArray();
		
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