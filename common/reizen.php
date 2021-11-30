<?php
require_once "database.php";
require_once "debug.php";

function print_reizen()
{
	global $database;
	
	$sql = "SELECT * FROM reis";
	debug_log($sql);
	
	$stmt = $database->query($sql);
	
?>
	<table id="tbl_reizen">
		<tr><th>Reisnummer</th><th>Bestemming</th><th>Chauffeur</th><th>Datum</th><th>Active</th></tr>
<?php
	
	while ($record = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		print_reis($record);
	}
?>
	<form method="POST">
	<tr>
		<td></td>
		<td><?php print_select_bestemming(); ?></td>
		<td><?php print_select_chauffeur(); ?></td>
		<td><input type="date" name="datum" pattern="\d{4}-\d{2}-\d{2}" /></td>
		<td><button name="add" type="submit">Toevoegen</button></td>
	</tr>
	</form>
	</table>
<?php
}

function print_reis($record)
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
		<td><?php print_select_bestemming($destination); ?></td>
		<td><?php print_select_chauffeur($driver); ?></td>
		<td><input type="date" required name="date" value="<?=$date?>" /></td>
		<td><button name="update" type="submit">Wijzigen</button></td>
	</tr>
	</form>
<?php
}

function add_reis($code, $name, $phone)
{
	global $database;

	$sql = "INSERT INTO chauffeur (code, naam, telefoonnummer) VALUES (:id, :field1, :field2)";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	
	
	$data = [
		"id" => $code,
		"field1" => $name,
		"field2" => $phone
	];
	
	try {
		
		if ($stmt->execute($data))
		{
			echo "Chauffeur toegevoegd.";
		}
		else
		{
			debug_warning("Chauffeur niet toegevoegd.");
		}
	} 
	catch (Exception $ex)
	{
		if ($ex->getCode()==23000)
		{
			debug_warning("Chauffeur niet toegevoegd, omdat een andere chauffeur dezelfde code gebruikt: '$code'");
		}
		else 
		{
		//print_r($ex);
		
			debug_error("Failed to add chauffeur because ", $ex);
		}
	}
	
}

function update_reis($code, $name, $phone)
{
	global $database;

	$sql = 
		"UPDATE chauffeur ".
		"SET naam=:field1, ".
		"    telefoonnummer=:field2 ".
		"WHERE code=:id";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	
	
	$data = [
		"id" => $code,
		"field1" => $name,
		"field2" => $phone,
	];
	
	try {		
		if ($stmt->execute($data))
		{
			echo "Chauffeur gewijzigd.";
		}
		else
		{
			debug_warning("Chauffeur niet gewijzigd.");
		}	
	}
	catch (Exception $ex)
	{
		debug_error("Failed to add chauffeur because ", $ex);
	}
}
?>