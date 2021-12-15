<?php
include "templates/head.txt";
require "common/database.php";

session_start();

if (array_key_exists("user_id", $_SESSION))
{
	// Already logged in...
	header("Location: index.php");
	exit();
}

if (array_key_exists("login", $_REQUEST)) 
{
	// Attempt to login
	echo "<pre>";
	print_r($_REQUEST);
	echo "</pre>";
	checkLogin($_REQUEST["username"], $_REQUEST["password"]);
	if (array_key_exists("user_id", $_SESSION))
	{
		header("Location: index.php");
		exit();
	}
}

?>
	<h1>Inloggen</h1>
	<form action="login.php">
		Username: <input type="text" name="username" /><br />
		Password: <input type="password" name="password" />
		<input type="submit" name="login" value="Inloggen" />
	</form>
	<h2><a href="register.php">Ik heb nog geen account</a></h2>
</body>
</html>
<?php
function checkLogin($username, $password)
{
	global $database;
	
	$sql = 
	"SELECT * ".
	"FROM passagier ".
	"WHERE username=:field1 AND password = MD5(concat(:field2, salt))";
	
	echo "<!-- $sql -->\n\r";
	// SELECT passagier_nummer as id FROM passagier WHERE username='frederik' AND password = MD5(concat('geheimpje', salt))
	
	// Prepare a query...
	$stmt = $database->prepare($sql);
	// Additional database
	$data = [
		"field1" => $username,
		"field2" => $password
	];
	
	// Activate the query...
	$stmt->execute($data);
	// Retrieve one record.
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	// Return the found users id.
	if ($row) 
	{
		$_SESSION["user_id"] = $row["passagier_nummer"];
		$_SESSION["name"] = $row["roepnaam"]." ".$row["achternaam"];
		
	}
	else
	{
		echo "<p class=\"error\">Onbekende login gegevens</p>";
		return null;
	}
}
?>