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

		/**
		***	Static Functions
		**/

		public static function removePathFromFile($file){
		
			preg_match('/[^\/]+$/', $file, $matches);
			return $matches[0];
		
		}

		public static function removeFileNameFromPath($file){
		
			$path = preg_replace('/[^\/]+$/','',$file);
			return $path;
		
		}

		public static function getFileDateTimeFormat(){
		
			return config('laravelBackupper.fileTimeFormat');	
		
		}

		public static function getFileDateFormat(){
		
			$dateTimeExploded = explode("_", static::getFileDateTimeFormat() );
			return $dateTimeExploded[0];
		
		}

		public static function getFileTimeFormat(){
		
			$dateTimeExploded = explode("_", static::getFileDateTimeFormat() );
			return $dateTimeExploded[1];
		
		}

		public static function getFormattedDateTime(){

			$nowFormatted = Carbon::now()->format( static::getFileDateTimeFormat() );
			return $nowFormatted;

		}

		public static function createCorrectChildFromFileName($fileWithPath){

			if( strpos( $fileWithPath, DbBackupFile::getPath()) !== false ){

				return new DbBackupFile($fileWithPath);

			}
		
		}

		/**
		***	Non-static Functions
		**/

		public function __construct($fileName = false){
			
			if($fileName){
				$this->setFileName($fileName);
				$this->splitFileToParts();
			}
			
		}

		public function splitFileToParts(){
		
			$this->setFileSize();
			$this->setCreatedAt();
			$this->setFileNameWithoutDateTime();
			
		}

		protected function createFileName(){
		
			$nowFormatted = static::getFormattedDateTime();
			$this->fileName = $nowFormatted.static::getFileEnding();
		
		}

		public function existsInCloud(){
		
			return \BackupEnviroment::getCloudDisk()->exists( $this->getFileNameWithCloudPath() );
		
		}

		/**
		***	Get Functions
		**/

		public function getFileName(){
		
			if( strlen($this->fileName) == 0 ){
				$this->createFileName();
			}

			return $this->fileName;
		
		}

		public function getFileNameWithPath(){
		
			return static::getPath().$this->getFileName();
		
		}

		public function getFileNameWithCloudPath(){
		
			return static::getCloudPath().$this->getFileName();
		
		}

		public function getFileNameWithFullPath(){
		
			return static::getFullPath().$this->getFileName();
		
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

		public function setFileName($fileName){

			$this->fileName = static::removePathFromFile( $fileName );
		
		}

		protected function setFileNameWithoutDateTime(){
		
			$this->fileNameWithoutDateTime = preg_replace('/^.*_/','',$this->fileName);
		
		}

		protected function setFileSize(){
		
			$this->fileSize = Storage::size( $this->getFileNameWithPath() );

		}

		protected function setCreatedAt(){
		
			$createdAt = preg_replace('/_[^_]*$/', '', $this->fileName);
			$createdAt = Carbon::createFromFormat( static::getFileDateTimeFormat() ,$createdAt);
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
		
			Storage::delete( $this->getFileNameWithPath() );
		
		}

		public function deleteCloud(){

			if($this->existsInCloud()){

				\BackupEnviroment::getCloudDisk()->delete( $this->getFileNameWithCloudPath() );
				
			}

		}


	}

?>