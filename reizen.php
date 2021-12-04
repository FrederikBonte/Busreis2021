<?php
include "templates/head.txt";
include "templates/login_check.txt";
require_once "common/bestemmingen.php";
require_once "common/chauffeurs.php";
require_once "common/reizen.php";
require_once "common/debug.php";
?>
	<h1>Reizen</h1>
<?php

	debug_dump($_REQUEST);

	if (array_key_exists("add", $_REQUEST))
	{
		$driver = $_REQUEST["driver"];
		$destination = $_REQUEST["destination"];
		$date = $_REQUEST["date"];
		add_reis($driver, $destination, $date);
	}
	else if (array_key_exists("update", $_REQUEST))
	{
		$number = $_REQUEST["number"];
		$driver = $_REQUEST["driver"];
		$destination = $_REQUEST["destination"];
		$date = $_REQUEST["date"];
		update_reis($number, $driver, $destination, $date);
	}

	print_reizen();
?>
</body>
</html>