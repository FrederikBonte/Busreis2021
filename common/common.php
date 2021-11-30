<?php
// This function builds a combobox for selecting anything.
function printSelect($selectName, $query, $message = null, $selectedId = -1)
{
	// Enable the use of the $database variable.
	global $database;

	// Print the start of the select tag and the "no selection yet" option.
	echo "<select name=\"$selectName\">";
	if ($message) 
	{
		// Select this initial value, but the user may not select it.
		echo "<option value=\"-1\" disabled selected>$message</option>";
	}
	
	// Try to get all authors from the database...
	try {
		// Prepare a query "statement"
		$stmt = $database->query($query);
		// Activate the query...
		$stmt->execute();
  
		// Loop through all the records and
		// store each record in the $row variable.
		while ($row=$stmt->fetch(PDO::FETCH_NUM)) 
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
  
	} catch (Exception $ex) {
		echo "Failed to read authors from the database : ".$ex->getMessage();
	}
	// Print the closing tag for the select.
	echo "</select>";
}
?>