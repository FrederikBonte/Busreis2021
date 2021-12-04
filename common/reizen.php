<?php
require_once "database.php";
require_once "debug.php";

function print_reizen()
{
	global $database;
	
	// read the destination and the driver table only once into this object.
	// Each is responsible for printing the select one structure.
	$BS = new DestinationSelector();
	$CS = new DriverSelector();
	
	$sql = "SELECT * FROM reis";
	debug_log($sql);
	
	$stmt = $database->query($sql);
	
?>
	<table id="tbl_reizen">
		<tr><th>Reisnummer</th><th>Bestemming</th><th>Chauffeur</th><th>Datum</th><th>Active</th></tr>
<?php
	
	while ($record = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		print_reis($record, $BS, $CS);
	}
?>
	<form method="POST">
	<tr>
		<td></td>
		<td><?php $BS->print_select_destination(); ?></td>
		<td><?php $CS->print_select_driver(); ?></td>
		<td><input type="date" name="date" pattern="\d{4}-\d{2}-\d{2}" /></td>
		<td><button name="add" type="submit">Toevoegen</button></td>
	</tr>
	</form>
	</table>
<?php
}

function print_reis($record, $BS, $CS)
{
	//debug_dump($record);
	
	$number = $record["reisnummer"];
	$destination = $record["bestemmingscode"];
	$driver = $record["chauffeurscode"];
	$date = $record["date"];
?>
	<form method="POST">
	<tr>
		<td><input type="hidden" name="number" value="<?=$number?>" /><?=$number?></td>
		<td><?php $BS->print_select_destination($destination); ?></td>
		<td><?php $CS->print_select_driver($driver); ?></td>
		<td><input type="date" required name="date" value="<?=$date?>" /></td>
		<td><button name="update" type="submit">Wijzigen</button></td>
	</tr>
	</form>
<?php
}

function add_reis($driver, $destination, $date)
{
	global $database;

	$sql = "INSERT INTO reis (chauffeurscode, bestemmingscode, date) VALUES (:field1, :field2, :field3)";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	
	
	$data = [
		"field1" => $driver,
		"field2" => $destination,
		"field3" => $date
	];
	
	try {
		
		if ($stmt->execute($data))
		{
			echo "Reis toegevoegd.";
		}
		else
		{
			debug_warning("Reis niet toegevoegd.");
		}
	} 
	catch (Exception $ex)
	{
		debug_error("Failed to add reis because ", $ex);
	}
	
}

function update_reis($number, $driver, $destination, $date)
{
	global $database;

	$sql = 
		"UPDATE reis ".
		"SET chauffeurscode=:field1, ".
		"    bestemmingscode=:field2, ".
		"    date=:field3 ".
		"WHERE reisnummer=:id";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	
	
	$data = [
		"id" => $number,
		"field1" => $driver,
		"field2" => $destination,
		"field3" => $date
	];
	
	try {		
		if ($stmt->execute($data))
		{
			echo "Reis gewijzigd.";
		}
		else
		{
			debug_warning("Reis niet gewijzigd.");
		}	
	}
	catch (Exception $ex)
	{
		debug_error("Failed to update reis because ", $ex);
	}
}
?>