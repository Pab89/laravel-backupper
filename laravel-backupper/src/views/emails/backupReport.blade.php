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
		
		Email report for: {{ base_path() }}

		<table>

			<caption>Db Backup Files</caption>

			<tr>
				<th>Filnavn</th>
				<th>Fra i dag</th>
				<th>Dato</th>
				<th>Tidspunkt</th>
				<th>Størrelse</th>
			</tr>

			@foreach($dbBackupFiles as $dbBackupFile)

				<tr>
					<td>{{ $dbBackupFile->fileNameWithoutDateTime }}</td>
					<td>{{ $dbBackupFile->createdAt->diffForHumans() }}</td>
					<td>{{ $dbBackupFile->createdAt->format( LaravelBackupper\Classes\dbBackupFile::getFileDateFormat() ) }}</td>
					<td>{{ $dbBackupFile->createdAt->format( LaravelBackupper\Classes\dbBackupFile::getFileTimeFormat() ) }}</td>
					<td>{{ $dbBackupFile->getFileSizeWithUnits() }}</td>
				</tr>

			@endforeach

		</table>

	</body>
</html>