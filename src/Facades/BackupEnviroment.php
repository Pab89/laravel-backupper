<?php

	namespace Milkwood\LaravelBackupper\Facades;

	use Illuminate\Support\Facades\Facade;

	class BackupEnviroment extends Facade{

		protected static function getFacadeAccessor() {

			return 'backupEnviroment'; 

		}

	}

?>