<?php
require_once "database.php";
require_once "debug.php";

function create_registration($name, $lastname, $zipcode, $number, $phone, $username, $password)
{
	global $database;

	$sql = 
		"INSERT INTO passagier (roepnaam, achternaam, huisnummer, postcode, telefoon, username, password) ".
		"VALUES (?,?,?,?,?,?,?)";
	debug_log($sql);
	
	$stmt = $database->prepare($sql);	

	try {		
		if ($stmt->execute([$name, $lastname, $number, $zipcode, $phone, $username, $password]))
		{
			echo "Registratie toegevoegd!";
			header("Location: index.php");
		}
		else
		{
			debug_warning("Kon registratie niet toevoegen.");
		}	
	}
	catch (Exception $ex)
	{
		debug_error("Failed to add registration because ", $ex);
	}	
}

function update_registration($code, $name, $phone)
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