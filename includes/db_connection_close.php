
<?php
	//5. Close database connection
	global $dbconn;
	if(isset($dbconn)){
		mysqli_close($dbconn);
	}