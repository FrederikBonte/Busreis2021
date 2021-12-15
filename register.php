<?php
include "templates/head.txt";
include "common/register.php";
// @TODO: Check same passwords with javascript!

if (array_key_exists("register", $_REQUEST))
{
	$name = $_REQUEST["rnaam"];
	$lastname = $_REQUEST["anaam"];
	$zipcode = $_REQUEST["postcode"];
	$number = $_REQUEST["hnummer"];
	$phone = $_REQUEST["telefoon"];
	$username = $_REQUEST["username"];
	$password = $_REQUEST["password1"];
	create_registration($name, $lastname, $zipcode, $number, $phone, $username, $password);
}
include "templates/register.txt";
?>
</body>
</html>