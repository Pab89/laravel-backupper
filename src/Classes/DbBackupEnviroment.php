<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Milkwood\LaravelBackupper\Classes\BackupEnviroment;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	class DbBackupEnviroment extends BackupEnviroment{

		public $backupFileClass = 'Milkwood\LaravelBackupper\Classes\DbBackupFile';

		public function getPath(){
		
			$parentPath = parent::getPath();
			return $parentPath.config('laravelBackupper.dbBackupPath');
		
		}

		public function prepareEnviroment(){
		
			if( ! $this->enviromentExists() ){

				$this->createEnviroment();

			}

			return true;
		
		}

		public function enviromentExists(){
		
			return Storage::exists( $this->getPath().'.gitignore' );
		
		}

		public function createEnviroment(){
		
			$this->createIfDontExist( $this->getPath() );
			$this->createGitIgnore();
		
		}

		public function createGitIgnore(){

			Storage::put( $this->getPath().'.gitignore', '*' );
		
		}

	}

?>