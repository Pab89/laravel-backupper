<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\BackupFile;
	use Milkwood\LaravelBackupper\Classes\DbBackupper;

	use Milkwood\LaravelBackupper\Interfaces\DbBackupEnviromentInterface;

	class DbBackupFile extends BackupFile{

		public static $fileEnding = '_dump.sql';

		public function __construct($fileName = false, DbBackupEnviromentInterface $enviroment){
			
			parent::__construct($fileName,$enviroment);
			
		}

		public static function createNew($fileName = false){

			$dbBackupFile = parent::createNew($fileName);

			$dbBackupFile->save();

			return $dbBackupFile;
		
		}

		protected function getMysqlDumpStatement(){

			$dbHost = env('DB_HOST', 'localhost');
			$dbName = env('DB_DATABASE', 'forge');
			$dbUser = env('DB_USERNAME', 'forge');
			$dbPassword = env('DB_PASSWORD', 'secret');
		
			return sprintf("mysqldump --user=%s --password=%s --host=%s %s > %s",$dbUser,$dbPassword,$dbHost,$dbName, $this->getFileNameWithFullPath());
		
		}

		protected function save(){

			$this->saveLocal();
			$this->copyLocalFileToCloud();

		}

		protected function saveLocal(){

			exec($this->getMysqlDumpStatement());

		}

	}

?>