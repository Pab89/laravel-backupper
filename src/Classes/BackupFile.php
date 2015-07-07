<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Storage;
	use Carbon\Carbon;

	use Milkwood\LaravelBackupper\Classes\DbBackupFile;

	use Milkwood\LaravelBackupper\Interfaces\BackupFileInterface;

	abstract class BackupFile implements BackupFileInterface{

		public $fileName;
		public $fileSize;
		public $createdAt;
		public $fileNameWithoutDateTime;

		public $cloudDisk;

		/**
		***	Static Functions
		**/

		public static function removePath($file){
		
			preg_match('/[^\/]+$/', $file, $matches);
			return $matches[0];
		
		}

		public static function removeFileNameFromPath($file){
		
			$path = preg_replace('/[^\/]+$/','',$file);
			return $path;
		
		}

		public static function getDateTimeFormat(){
		
			return config('laravelBackupper.fileTimeFormat');	
		
		}

		public static function getFileDateFormat(){
		
			$dateTimeExploded = explode("_", static::getDateTimeFormat() );
			return $dateTimeExploded[0];
		
		}

		public static function getFileTimeFormat(){
		
			$dateTimeExploded = explode("_", static::getDateTimeFormat() );
			return $dateTimeExploded[1];
		
		}

		public static function getFormattedDateTime(){

			$nowFormatted = Carbon::now()->format( static::getDateTimeFormat() );
			return $nowFormatted;

		}

		public static function createNew(){
		
			return new static( static::getFormattedDateTime().static::getFileEnding() );
		
		}

		/**
		***	Non-static Functions
		**/

		public function __construct($fileName){

			$this->setCloudDisk();
			$this->setFileName($fileName);
			$this->splitFileToParts();
			
		}

		protected function splitFileToParts(){
		
			$this->setFileSize();
			$this->setCreatedAt();
			$this->setFileNameWithoutDateTime();
			
		}


		public function copyLocalFileToCloud(){
		
			$this->cloudDisk->put( $this->getFileNameWithCloudPath(), $this->getLocalFileContent() );
		
		}

		/**
		***	Exists Functions
		**/

		public function existsInCloud(){
		
			return $this->cloudDisk->exists( $this->getFileNameWithCloudPath() );
		
		}

		public function existsInLocal(){
		
			return Storage::exists( $this->getFileNameWithPath() );
		
		}

		/**
		***	Get Functions
		**/

		public function getLocalFileContent(){
		
			return Storage::get( $this->getFileNameWithPath() );	
		
		}

		public function getFileName(){

			return $this->fileName;
		
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

			$this->fileName = static::removePath( $fileName );
		
		}

		protected function setFileNameWithoutDateTime(){
		
			$this->fileNameWithoutDateTime = preg_replace('/^.*_/','',$this->fileName);
		
		}

		protected function setFileSize(){
		
			$this->fileSize = ( $this->existsInLocal() ) ? Storage::size( $this->getFileNameWithPath() ) : $this->cloudDisk->size( $this->getFileNameWithCloudPath() ) ;

		}

		protected function setCreatedAt(){
		
			$createdAt = preg_replace('/_[^_]*$/', '', $this->fileName);
			$createdAt = Carbon::createFromFormat( static::getDateTimeFormat() ,$createdAt);
			$this->createdAt = $createdAt;
		
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

				$this->cloudDisk->delete( $this->getFileNameWithCloudPath() );
				
			}

		}


	}

?>