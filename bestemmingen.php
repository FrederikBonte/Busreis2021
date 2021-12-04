<?php
include "templates/head.txt";
include "templates/login_check.txt";
include "common/bestemmingen.php";
require_once "common/debug.php";
?>
	<h1>Bestemmingen</h1>
<?php

	//debug_dump($_REQUEST);
	
	if (array_key_exists("add", $_REQUEST))
	{
		$bianca = $_REQUEST["bcode"];
		$name = $_REQUEST["bname"];
		$country = $_REQUEST["bcountry"];
		add_bestemming($bianca, $name, $country);
		
	}
	else if (array_key_exists("update", $_REQUEST))
	{
		$bianca = $_REQUEST["bcode"];
		$name = $_REQUEST["bname"];
		$country = $_REQUEST["bcountry"];
		$deleted = array_key_exists("deleted", $_REQUEST)?"1":"0";
		update_bestemming($bianca, $name, $country, $deleted);
	} 
	else if (array_key_exists("delete", $_REQUEST))
	{
?>
		<p class="error">NEVER DELETE ANYTHING YOU WEIRDO!!!</p>
<?php
	}

	print_bestemmingen();
?>
</body>
</html>