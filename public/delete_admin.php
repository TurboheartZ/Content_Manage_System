<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>

<?php
	$current_admin = find_subject_by_id($_GET["admin"]);
	if(!$current_admin) {
		redirect_to("manage_admins.php");
	}		
	
	$id = $current_admin["id"];
	$query = "DELETE FROM admins ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1;";
	// Do the query
	$result = mysqli_query($dbconn, $query);
	if($result&&mysqli_affected_rows($dbconn)==1) {	
		// Delete success
		$_SESSION["message"] = "Admin deleted.";
		redirect_to("manage_admins.php");		
	} else{
		// Delete failure
		$_SESSION["message"] = "Admin deletion failed.";
		redirect_to("manage_admins.php");
	}
?>