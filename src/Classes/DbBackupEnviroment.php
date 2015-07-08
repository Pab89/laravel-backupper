<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Milkwood\LaravelBackupper\Classes\DbBackupEnviroment;
	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	use Milkwood\LaravelBackupper\Interfaces\DbBackupEnviromentInterface;
	use Milkwood\LaravelBackupper\Interfaces\DbBackupDirectoryInterface;

	class DbBackupEnviroment extends BackupEnviroment implements DbBackupEnviromentInterface{

		public $backupFileClass = 'Milkwood\LaravelBackupper\Classes\DbBackupFile';


		public function __construct(){
			
			$this->setCloudDisk();
			$this->prepareEnviroment();
		}

		public function setBackupDirectory(){
		
			$this->backupDirectory = app( DbBackupDirectoryInterface::class );
		
		}

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