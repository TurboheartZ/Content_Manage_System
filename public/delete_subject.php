<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>

<?php
	$current_subject = find_subject_by_id($_GET["subject"]);
	if(!$current_subject) {
		//Subject ID is missing or invalid
		//If we cannot find the subject, redirect to previous page
		//Means we do not need to involve the layout from header
		redirect_to("manage_content.php");
	}
	
	$page_set = find_pages_for_subject($current_subject["id"],false);
	if(mysqli_num_rows($page_set)>0){
		// Must delete child pages before delete the subject
		$_SESSION["message"] = "Can't delete a subject with pages.";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");		
	}	
	
	$id = $current_subject["id"];
	$query = "DELETE FROM subjects ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1;";
	// Do the query
	$result = mysqli_query($dbconn, $query);
	if($result&&mysqli_affected_rows($dbconn)==1) {	
		// Delete success
		$_SESSION["message"] = "Subject deleted.";
		redirect_to("manage_content.php");		
	} else{
		// Delete failure
		$_SESSION["message"] = "Subject deletion failed.";
		redirect_to("manage_content.php?subject={$id}");
	}
?>