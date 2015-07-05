<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	class DatabaseBackupper{

		public $dbName;
		public $dbHost;
		public $dbUser;
		public $dbPassword;

		public $dbBackupFile;


		public function __construct(){
			
			$this->dbHost = env('DB_HOST', 'localhost');
			$this->dbName = env('DB_DATABASE', 'forge');
			$this->dbUser = env('DB_USERNAME', 'forge');
			$this->dbPassword = env('DB_PASSWORD', 'secret');
			
		}

		public function backupDb(){
		
			\DbBackupEnviroment::prepareEnviroment();
			$this->runMysqlDumpStatement();
			$this->copyNewestBackupFileToCloud();

		
		}

		protected function runMysqlDumpStatement(){
		
			$statement = $this->getMysqlDumpStatement();
			exec($statement);
		
		}

		protected function getMysqlDumpStatement(){
			
			$this->dbBackupFile = new DbBackupFile;

			return sprintf("mysqldump --user=%s --password=%s --host=%s %s > %s",$this->dbUser,$this->dbPassword,$this->dbHost,$this->dbName, $this->dbBackupFile->getFileNameWithFullPath());
		
		}

		protected function copyNewestBackupFileToCloud(){
		
			$localFileContents = Storage::get( $this->dbBackupFile->getFileNameWithPath() );
			BackupEnviroment::getCloudDisk()->put( $this->dbBackupFile->getFileNameWithCloudPath(), $localFileContents );
		
		}

	}

?>