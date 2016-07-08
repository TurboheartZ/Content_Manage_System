<?php
	//Use constant value for db login infos
	define("DB_SERVER","localhost");
	define("DB_USER","root");
	define("DB_PASS","123123");
	define("DB_NAME","cms");

	//1. Create a database connection
	global $dbconn;
	$dbconn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	
	// Test if connection occurred.
	if(mysqli_connect_errno()){
		die("Database connection failed: ".mysqli_connect_error()." (".mysqli_connect_errno().")");
	}
	else{
		//echo "Connection success!<br/><br/>";
	}
?>