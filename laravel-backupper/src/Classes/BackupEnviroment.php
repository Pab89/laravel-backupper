<?php

	namespace LaravelBackupper\Classes;

	class BackupEnviroment{

		public function createIfDontExist($path){
		
			if( !file_exists( $path )){

				if( $this->pathIsAFile($path) ){

					$file = fopen($path,'w');
					return $file;

				}else{

					$dir = mkdir($path,0777,true);
					return $dir;

				}
			}
		
		}

		public function pathIsAFile($path){
		
			if( strpos($path,'.') !== false ){

				return true;

			}

			return false;
		
		}

	}

?>