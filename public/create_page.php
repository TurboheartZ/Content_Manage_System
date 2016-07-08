<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_functions.php") ?>

<?php 
	if(isset($_POST["submit"])) {		
		// If submitted, then process the form
		$menu_name = mysql_prep($_POST["menu_name"]);	
		$position =  (int) $_POST["position"];
		$visible = (int) $_POST["visible"];
		$content = mysql_prep($_POST["content"]);
		$subject_id = (int) $_POST["subject_id"];
		
		//Validations
		$required_fields = array("menu_name", "position", "visible", "content");
		validate_presences($required_fields);
		
		$fields_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($fields_with_max_lengths);
		
		if(!empty($errors)) {
			$_SESSION["errors"] = $errors;
			$addr = "new_page.php?subject=";
			$addr .= $subject_id;			
			redirect_to($addr);
		}
		
		// Build the insert query
		$query = "INSERT INTO pages (";
		$query .= " menu_name, position, visible, content, subject_id ";
		$query .= " ) VALUES (";
		$query .= " '{$menu_name}', {$position}, {$visible}, '{$content}', {$subject_id}  ";
		$query .= " );";
		
		// Do the query
		$result = mysqli_query($dbconn, $query);
		if($result) {
			// Insert success
			$_SESSION["message"] = "Page created";
			$addr = "manage_content.php?subject=";
			$addr .= $subject_id;
			redirect_to($addr);
		}
		else {
			// Failure
			$_SESSION["message"] = "Page creation failed";
			redirect_to("new_page.php");
		}
		
	}
	else{
		// This is probably a GET request
		redirect_to("new_page.php");
	}
?>

<?php require_once("../includes/db_connection_close.php") ?>