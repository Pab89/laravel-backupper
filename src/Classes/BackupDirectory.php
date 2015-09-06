<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;
	use Illuminate\Support\Collection;
	use Milkwood\LaravelBackupper\Classes\BackupFile;	
	use Milkwood\LaravelBackupper\Classes\BackupEnviroment;

	use Milkwood\LaravelBackupper\Interfaces\BackupEnviromentInterface;

	abstract class BackupDirectory{

		public $files;
		public $enviroment;

		public $filesToIgnore = ['.','..','.gitignore'];

		public function __construct(BackupEnviromentInterface $enviroment){

			$this->enviroment = $enviroment;
			$this->setFileVaribles();
			
		}

		public function getBackupsToKeep(){
			
			return config('laravelBackupper.backupsToKeep');
		
		}

		public function setFileVaribles(){
		
			$this->setFiles();
		
		}

		public function setFiles(){

			$this->files = Collection::make([]);

			$localFiles = Storage::files( $this->enviroment->getPath() );
			$cloudFiles = $this->enviroment->cloudDisk->files( $this->enviroment->getCloudPath() );

			$this->addToFilesIfValid( $localFiles );
			$this->addToFilesIfValid( $cloudFiles );

			$this->files = $this->files->sortBy('createdAt') ;
		
		}

		public function addToFilesIfValid( $files ){
		
			foreach($files as $file){

				if( ! $this->isFileToIgnore($file)  && ! $this->isAlreadyInFiles($file) ){

					$this->files->push( app( $this->enviroment->backupFileClass, [ $file ] ) );

				}

			}
		
		}

		public function cleanUp(){
		
			$numberOfOldBackupsToDelete = $this->files->count() - $this->getBackupsToKeep();
			$numberOfOldBackupsToDelete = ($numberOfOldBackupsToDelete < 0) ? 0 : $numberOfOldBackupsToDelete;
			$backupsToDelete = $this->files->splice(0, $numberOfOldBackupsToDelete);

			$backupsToDelete->each( function($backupFile){

				$backupFile->delete();

			} );
		
		}

		protected function isFileToIgnore($file){
		
			$fileWithoutPath = BackupFile::removePath( $file );
			return in_array($fileWithoutPath, $this->filesToIgnore);
		
		}

		protected function isAlreadyInFiles($file){
		
			$fileWithoutPath = BackupFile::removePath( $file );
			return in_array( $fileWithoutPath, $this->files->lists('fileName')->toArray() );
		
		}

	}

?>