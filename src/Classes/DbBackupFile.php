<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Carbon\Carbon;
	use Milkwood\LaravelBackupper\Classes\BackupFile;
	use Milkwood\LaravelBackupper\Classes\DatabaseBackupper;

	class DbBackupFile extends BackupFile{

		public static $fileEnding = '_dump.sql';


		public static function getFileEnding(){
		
			return static::$fileEnding;
		
		}

		public static function isAValidFile($file){
		
			return ( strpos($file, ".sql") !== false);
		
		}

		public function setCloudDisk(){
		
			$this->cloudDisk = \DbBackupEnviroment::getCloudDisk();
		
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

	}

?>