<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;

	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	use Milkwood\LaravelBackupper\Traits\Timestampable;

	use Milkwood\LaravelBackupper\Interfaces\BackupFileInterface;
	use Milkwood\LaravelBackupper\Interfaces\BackupEnviromentInterface;

	abstract class BackupFile implements BackupFileInterface{

		use Timestampable;

		public $fileName;
		public $fileSize;
		public $fileNameWithoutDateTime;

		public $enviroment;

		/**
		***	Static Functions
		**/

		public static function removePath($file){
		
			return pathinfo($file,PATHINFO_BASENAME);
		
		}

		public static function createNew(){
		
			return app( static::class );
		
		}

		/**
		***	Non-static Functions
		**/

		public function __construct($fileName = false, BackupEnviromentInterface $enviroment){

			$this->enviroment = $enviroment;
			$this->setFileName($fileName);
			$this->splitFileToParts();
			
		}

		protected function splitFileToParts(){
		
			$this->setFileSize();
			$this->setCreatedAtFromFileName();
			$this->setFileNameWithoutDateTime();
			
		}


		public function copyLocalFileToCloud(){
		
			$this->enviroment->cloudDisk->put( $this->getFileNameWithCloudPath(), $this->getLocalFileContent() );
		
		}

		/**
		***	Exists Functions
		**/

		public function existsInCloud(){
		
			return $this->enviroment->cloudDisk->exists( $this->getFileNameWithCloudPath() );
		
		}

		public function existsInLocal(){
		
			return Storage::exists( $this->getFileNameWithPath() );
		
		}

		/**
		***	Get Functions
		**/

		public function getFileEnding(){
		
			return static::$fileEnding;
		
		}

		public function getFileNameWithPath(){
		
			return $this->enviroment->getPath().$this->getFileName();
		
		}

		public function getFileNameWithCloudPath(){
		
			return $this->enviroment->getCloudPath().$this->getFileName();
		
		}

		public function getFileNameWithFullPath(){
		
			return $this->enviroment->getFullPath().$this->getFileName();
		
		}

		public function getLocalFileContent(){
		
			return Storage::get( $this->getFileNameWithPath() );	
		
		}

		public function getFileName(){

			return $this->fileName;

		}

		public function getNewFileName(){

			return static::getNowFormattedForFiles().$this->getFileEnding();
		
		}

		public function getFileSizeWithUnits(){

			$fileUnits = [ 'GB' => 1000000000, 'MB' => 1000000, 'KB' => 1000, 'B' => 1 ];

			foreach($fileUnits as $unit => $numberOfBytesInUnit){

				if( $this->fileSize > $numberOfBytesInUnit ){

					$bytesDivededWithUnit = $this->fileSize / $numberOfBytesInUnit;
					return number_format($bytesDivededWithUnit,'2',',','.') . " " .$unit;

				}				

			}

		}

		/**
		***	Set Functions
		**/

		protected function setFileName($fileName){

			$this->fileName = ( $fileName ) ? static::removePath( $fileName ) : $this->getNewFileName();
		
		}

		protected function setFileNameWithoutDateTime(){
		
			$this->fileNameWithoutDateTime = preg_replace('/^.*_/','',$this->fileName);
		
		}

		protected function setFileSize(){
			
			$size = 0;
			$size = $this->existsInLocal() ? Storage::size( $this->getFileNameWithPath() ) : $size;
			$size = $this->existsInCloud() ? $this->enviroment->cloudDisk->size( $this->getFileNameWithCloudPath() ) : $size;

			$this->fileSize = $size;

		}

		/**
		***	Delete functions
		**/

		public function delete(){
		
			$this->deleteLocal();
			$this->deleteCloud();
		
		}

		public function deleteLocal(){
		
			if($this->existsInLocal() ){
				Storage::delete( $this->getFileNameWithPath() );
			}
		
		}

		public function deleteCloud(){

			if($this->existsInCloud()){

				$this->enviroment->cloudDisk->delete( $this->getFileNameWithCloudPath() );
				
			}

		}


	}

?>