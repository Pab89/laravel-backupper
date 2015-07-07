<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\BackupFile;
	use Milkwood\LaravelBackupper\Classes\DbBackupper;

	class DbBackupFile extends BackupFile{

		public static $fileEnding = '_dump.sql';

		public static function createNew(){

			$dbBackupFile = parent::createNew();

			$dbBackupFile->runMysqlDumpStatement();

			$dbBackupFile->copyLocalFileToCloud();

			return $dbBackupFile;
		
		}


		public static function getFileEnding(){
		
			return static::$fileEnding;
		
		}

		public function getFileNameWithPath(){
		
			return \DbBackupEnviroment::getPath().$this->getFileName();
		
		}

		public function getFileNameWithCloudPath(){
		
			return \DbBackupEnviroment::getCloudPath().$this->getFileName();
		
		}

		public function getFileNameWithFullPath(){
		
			return \DbBackupEnviroment::getFullPath().$this->getFileName();
		
		}

		protected function getMysqlDumpStatement(){

			$dbHost = env('DB_HOST', 'localhost');
			$dbName = env('DB_DATABASE', 'forge');
			$dbUser = env('DB_USERNAME', 'forge');
			$dbPassword = env('DB_PASSWORD', 'secret');
		
			return sprintf("mysqldump --user=%s --password=%s --host=%s %s > %s",$dbUser,$dbPassword,$dbHost,$dbName, $this->getFileNameWithFullPath());
		
		}

		protected function runMysqlDumpStatement(){

			exec($this->getMysqlDumpStatement());

		}

		public function setCloudDisk(){
		
			$this->cloudDisk = \DbBackupEnviroment::getCloudDisk();
		
		}

	}

?>