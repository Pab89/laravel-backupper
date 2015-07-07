<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;
	use Illuminate\Support\Collection;
	use Milkwood\LaravelBackupper\Classes\BackupFile;	
	use Milkwood\LaravelBackupper\Classes\BackupEnviroment;

	class BackupDirectory{

		public $files;
		public $backupEnviroment;

		public $filesToIgnore = ['.','..','.gitignore'];

		public static function getBackupsToKeep(){
		
			return config('laravelBackupper.backupsToKeep');
		
		}


		public function __construct(BackupEnviroment $backupEnviroment){

			$this->backupEnviroment = $backupEnviroment;
			$this->setFileVaribles();
			
		}

		public function setFileVaribles(){
		
			$this->setFiles();
		
		}

		public function setFiles(){

			$this->files = Collection::make([]);

			$localFiles = Storage::files( $this->backupEnviroment->getPath() );
			$cloudFiles = $this->backupEnviroment->getCloudDisk()->files( $this->backupEnviroment->getCloudPath() );

			$this->addToFilesIfFileNameDoesIsNotAlreadyInFiles( $localFiles );
			$this->addToFilesIfFileNameDoesIsNotAlreadyInFiles( $cloudFiles );

			$this->files = $this->files->sortBy('createdAt') ;
		
		}

		public function addToFilesIfFileNameDoesIsNotAlreadyInFiles( $files ){
		
			foreach($files as $file){

				$fileWithoutPath = BackupFile::removePath( $file );

				if( ! in_array($fileWithoutPath, $this->filesToIgnore) && ! in_array( $fileWithoutPath, $this->files->lists('fileName')->toArray() ) ){

					$backupFileClass = $this->backupEnviroment->backupFileClass;
					$this->files->push( new $backupFileClass( $file ) );

				}

			}
		
		}

		public function cleanUp(){
		
			$numberOfOldBackupsToDelete = $this->files->count() - static::getBackupsToKeep();
			$backupsToDelete = $this->files->splice(0, $numberOfOldBackupsToDelete);

			$backupsToDelete->each( function($backupFile){

				$backupFile->delete();

			} );
		
		}

	}

?>