<?php
include "templates/head.txt";
include "templates/login_check.txt";
include "common/chauffeurs.php";
require_once "common/debug.php";
?>
	<h1>Chauffeurs</h1>
<?php

	// debug_dump($_REQUEST);

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

	print_chauffeurs();
?>
</body>
</html>