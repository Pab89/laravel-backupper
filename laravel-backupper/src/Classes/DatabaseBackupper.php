<?php

	namespace LaravelBackupper\Classes;

	use Carbon\Carbon;
	use LaravelBackupper\Classes\DbBackupFile;

	class DatabaseBackupper{

		public $dbName;
		public $dbHost;
		public $dbUser;
		public $dbPassword;

		public $backupFileName;


		public function __construct(){
			
			$this->dbHost = env('DB_HOST', 'localhost');
			$this->dbName = env('DB_DATABASE', 'forge');
			$this->dbUser = env('DB_USERNAME', 'forge');
			$this->dbPassword = env('DB_PASSWORD', 'secret');
			
		}

		public function backupDb(){
		
			\DbBackupEnviroment::prepareEnviroment();
			$this->runMysqlDumpStatement();

		
		}

		protected function runMysqlDumpStatement(){
		
			$statement = $this->getMysqlDumpStatement();
			exec($statement);
		
		}

		protected function getMysqlDumpStatement(){
			
			$backupFile = new DbBackupFile;
			return sprintf("mysqldump --user=%s --password=%s --host=%s %s > %s",$this->dbUser,$this->dbPassword,$this->dbHost,$this->dbName, $backupFile->getFileNameWithFullPath());
		
		}

	}

?>