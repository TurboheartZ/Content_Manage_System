<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>

<?php
	$current_page =  find_page_by_id($_GET["page"]);
	if(!$current_page) {
		//Page ID is missing or invalid
		//If we cannot find the page, redirect to previous page
		//Means we do not need to involve the layout from header
		redirect_to("manage_content.php");
	}
		
	$id = $current_page["id"];
	$query = "DELETE FROM pages ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1;";
	// Do the query
	$result = mysqli_query($dbconn, $query);
	if($result&&mysqli_affected_rows($dbconn)==1) {	
		// Delete success
		$_SESSION["message"] = "Page deleted.";
		$addr = "manage_content.php?subject=".$current_page["subject_id"];
		redirect_to($addr);			
	} else{
		// Delete failure
		$_SESSION["message"] = "Page deletion failed.";
		redirect_to("manage_content.php?subject={$current_page["subject_id"]}");
	}
?>