<?php
require_once "database.php";
require_once "debug.php";
require_once "common.php";

class DestinationSelector 
{
	private $records;
	
	function __construct()
	{
		global $database;

		$sql = "SELECT bestemmingscode, naam FROM bestemming ORDER BY naam, land";
		// Try to get all authors from the database...
		try {
			// Prepare a query "statement"
			$stmt = $database->query($sql);
			// Activate the query...
			$stmt->execute();		
			// Store all data
			$this->records = $stmt->fetchAll(PDO::FETCH_NUM);
			// Show the content.
			//debug_dump($this->records);
		}
		catch (Exception $ex)
		{
			echo "Failed to read destination records from the database : ".$ex->getMessage();
		}
	}
	
	function print_select_destination($selectedId = -1, $selectName = "destination")
	{
		//debug_dump($this->records);
?>
		<select name="<?=$selectName?>">
			<option value="-1" disabled selected>Selecteer bestemming</option>
<?php		

		// Loop through all the records and
		// store each record in the $row variable.
		foreach ($this->records as $row) 
		{
			// Store the number of each author.
			$id = $row[0];  
			// Build a string with the authors name
			$name = $row[1];  
			// Check if this record should be selected.
			$selected = "";
			if ($selectedId==$id)
			{
				$selected = "selected";
			}
	
			// Print each option
?>
			<option value="<?=$id?>" <?=$selected?>><?=$name?></option>
<?php
		} 		
		// Print the closing tag for the select.
		echo "</select>";		
	}
}
/*
// This function builds a combobox for selecting anything.
function print_select_bestemming($selectedId = -1, $selectName = "destination")
{
	printSelect(
		$selectName, 
		"SELECT bestemmingscode, naam FROM bestemming ORDER BY naam, land",
		"Kies een bestemming",
		$selectedId);
}
*/
function print_bestemmingen()
{
	global $database;
	
	$sql = "SELECT * FROM bestemming";
	debug_log($sql);
	
	$stmt = $database->query($sql);
	
?>
	<table id="tbl_bestemming">
		<tr><th>Code</th><th>Naam</th><th>Country</th><th>Inactive</th><th>Actie</th></tr>
<?php
	
	while ($record = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		print_bestemming($record);
	}
?>
	<form method="POST">
	<tr>
		<td><input type="text" required customValidity name="bcode" /></td>
		<td><input type="text" required name="bname"  /></td>
		<td><input type="text" required name="bcountry"  /></td>
		<td></td>
		<td><button name="add" type="submit">Toevoegen</button></td>
	</tr>
	</form>
	</table>
<?php
}

function print_bestemming($record)
{
	$code = $record["bestemmingscode"];
	$name = $record["naam"];
	$country = $record["land"];
	$deleted = $record["deleted"];
?>
	<form method="POST">
	<tr>
		<td><input type="hidden" name="bcode" value="<?=$code?>" /><?=$code?></td>
		<td><input type="text" required name="bname" value="<?=$name?>" /></td>
		<td><input type="text" required name="bcountry" value="<?=$country?>" /></td>
		<td><input type="checkbox" name="deleted" <?=($deleted=='1')?"checked":""?> /></td>
		<td>
			<button name="update" type="submit">Wijzigen</button>
			<button name="delete" type="submit">Verwijderen</button>
		</td>
	</tr>
	</form>
<?php
}

function add_bestemming($code, $name, $country)
{
	global $database;

	$sql = "INSERT INTO bestemming (bestemmingscode, naam, land) VALUES (:code, :name, :country)";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	
	
	$data = [
		"code" => $code,
		"name" => $name,
		"country" => $country
	];
	
	try {
		
		if ($stmt->execute($data))
		{
			echo "Bestemming added.";
		}
		else
		{
			debug_warning("Bestemming was not added.");
		}
	} 
	catch (Exception $ex)
	{
		if ($ex->getCode()==23000)
		{
			debug_warning("Bestemming niet toegevoegd, omdat een andere bestemming dezelfde code gebruikt: '$code'");
		}
		else 
		{
		//print_r($ex);
		
			debug_error("Failed to add bestemming because ", $ex);
		}
	}
	
}

function update_bestemming($code, $name, $country, $deleted)
{
	global $database;

	$sql = 
		"UPDATE bestemming ".
		"SET naam=:field1, ".
		"    land=:field2, ".
		"    deleted=:field3 ".
		"WHERE bestemmingscode=:id";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	
	
	$data = [
		"id" => $code,
		"field1" => $name,
		"field2" => $country,
		"field3" => $deleted
	];
	
	if ($stmt->execute($data))
	{
		echo "Bestemming updated.";
	}
	else
	{
		debug_warning("Bestemming was not updated.");
	}	
}
?>