<?php

	namespace Milkwood\LaravelBackupper\Classes;

	use Milkwood\LaravelBackupper\Interfaces\DbBackupDirectoryInterface;
	use Milkwood\LaravelBackupper\Interfaces\DbBackupEnviromentInterface;

	class DbBackupDirectory extends BackupDirectory implements DbBackupDirectoryInterface{

		public function __construct(DbBackupEnviromentInterface $enviroment){

			parent::__construct($enviroment);
			
		}

	}

?>