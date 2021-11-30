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
		<td></td>
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

// This function builds a combobox for selecting anything.
function print_select_chauffeur($selectedId = -1, $selectName = "driver")
{
	printSelect(
		$selectName, 
		"SELECT code, naam FROM chauffeur ORDER BY naam",
		"Kies een chauffeur",
		$selectedId);
}

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
		debug_error("Failed to add chauffeur because ", $ex);
	}
}
?>