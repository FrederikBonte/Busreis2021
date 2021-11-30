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
/*
	if (array_key_exists("add", $_REQUEST))
	{
		$bianca = $_REQUEST["dcode"];
		$name = $_REQUEST["dname"];
		$phone = $_REQUEST["dphone"];
		add_chauffeur($bianca, $name, $phone);
	}
	else if (array_key_exists("update", $_REQUEST))
	{
		$bianca = $_REQUEST["dcode"];
		$name = $_REQUEST["dname"];
		$phone = $_REQUEST["dphone"];
		update_chauffeur($bianca, $name, $phone);
	}
*/
	print_reizen();
?>
</body>
</html>