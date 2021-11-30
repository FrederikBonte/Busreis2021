<?php
include "templates/head.txt";
include "templates/login_check.txt";
?>
	<h1>Welcome <?=$_SESSION["name"]?>!</h1>
	<a href="logout.php">Logout</a>
</body>
</html>