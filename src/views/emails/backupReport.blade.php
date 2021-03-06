<!DOCTYPE html>
<html>
	<head>
		<title>Backup Report</title>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			table{
				width: 100%;
			}

			table tr td{
				border-collapse: collapse;
				border-top: solid 1px #000;
				text-align: center;
			}
		</style>
	</head>
	<body>
		
		Email report for: {{ base_path() }} <br>

		<table>

			<caption>Db Backup Files</caption>

			<tr>
				<th>Filnavn</th>
				<th>Dato/Tid</th>
				<th>Størrelse</th>
				<th>Lokal</th>
				<th>Cloud</th>
			</tr>

			@foreach($dbBackupFiles as $dbBackupFile)

				<tr>
					<td>{{ $dbBackupFile->fileNameWithoutDateTime }}</td>
					<td>{{ $dbBackupFile->createdAt->format( Milkwood\LaravelBackupper\Classes\dbBackupFile::getDateTimeFormat() ) }}</td>
					<td>{{ $dbBackupFile->getFileSizeWithUnits() }}</td>
					<td>{{ $dbBackupFile->existsInLocal() }}</td>
					<td>{{ $dbBackupFile->existsInCloud() }}</td>
				</tr>

			@endforeach

		</table>

	</body>
</html>