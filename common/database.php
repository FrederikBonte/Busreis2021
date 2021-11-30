<?php
require_once "common/debug.php";
// Connect to mysql on the localhost:3307, use the database "bibliotheek".
$databaseConnectionString = "mysql:host=localhost;port=3306;dbname=busreis";
// Use this username.
$username = "root";
// And this password.
$password = "usbw";

// try to connect to the database.
try {
	// Create a PDO object using the connection string with the correct username and password.
	$database = new PDO($databaseConnectionString, $username, $password);
	// Throw exceptions with error info when stuff fails.
	$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	debug_log("Connected to the database.");
} 
catch (Exception $ex) 
{
	// Let the user know what we were doing and also provide the actual exception message.
	debug_error("Failed to connect to the database : ", $ex);
}
?>