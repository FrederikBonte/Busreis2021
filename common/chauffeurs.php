<?php
require_once "database.php";
require_once "debug.php";

function print_chauffeurs()
{
	global $database;
	
	$sql = "SELECT * FROM chauffeur";
	debug_log($sql);
	
	$stmt = $database->query($sql);
	
?>
	<table id="tbl_chauffeurs">
		<tr><th>Code</th><th>Naam</th><th>Telefoon</th><th>Actie</th></tr>
<?php
	
	while ($record = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		print_chauffeur($record);
	}
?>
	<form method="POST">
	<tr>
		<td><input type="text" required customValidity name="dcode" /></td>
		<td><input type="text" required name="dname"  /></td>
		<td><input type="text" required name="dphone"  /></td>
		<td><button name="add" type="submit">Toevoegen</button></td>
	</tr>
	</form>
	</table>
<?php
}

function print_chauffeur($record)
{
	//debug_dump($record);
	
	$code = $record["code"];
	$name = $record["naam"];
	$phone = $record["telefoonnummer"];
?>
	<form method="POST">
	<tr>
		<td><input type="hidden" name="dcode" value="<?=$code?>" /><?=$code?></td>
		<td><input type="text" required name="dname" value="<?=$name?>" /></td>
		<td><input type="text" required name="dphone" value="<?=$phone?>" /></td>
		<td><button name="update" type="submit">Wijzigen</button></td>
	</tr>
	</form>
<?php
}

/*
// This function builds a combobox for selecting anything.
function print_select_chauffeur($selectedId = -1, $selectName = "driver")
{
	printSelect(
		$selectName, 
		"SELECT code, naam FROM chauffeur ORDER BY naam",
		"Kies een chauffeur",
		$selectedId);
}
*/

function add_chauffeur($code, $name, $phone)
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

function update_chauffeur($code, $name, $phone)
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
		debug_error("Failed to update chauffeur because ", $ex);
	}
}

class DriverSelector 
{
	private $records;
	
	function __construct()
	{
		global $database;

		$sql = "SELECT code, concat(naam, \"( \", telefoonnummer, \" )\") as naam FROM chauffeur ORDER by naam";
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
			echo "Failed to read driver records from the database : ".$ex->getMessage();
		}
	}
	
	function print_select_driver($selectedId = -1, $selectName = "driver")
	{
		//debug_dump($this->records);
?>
		<select name="<?=$selectName?>">
			<option value="-1" disabled selected>Selecteer chauffeur</option>
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
?>