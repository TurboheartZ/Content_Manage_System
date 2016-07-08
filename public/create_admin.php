<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_functions.php") ?>

<?php 
	if(isset($_POST["submit"])) {		
		// If submitted, then process the form
		$user_name = mysql_prep($_POST["user_name"]);	
		$password =  password_encrypt($_POST["password"]);		
		//Validations
		$required_fields = array("user_name", "password");
		validate_presences($required_fields);
		
		$fields_with_max_lengths = array("user_name" => 30);
		validate_max_lengths($fields_with_max_lengths);
		
		if(!empty($errors)) {
			$_SESSION["errors"] = $errors;
			redirect_to("new_admin.php");
		}
		
		// Build the insert query
		$query = "INSERT INTO admins (";
		$query .= " username, hashed_password ";
		$query .= " ) VALUES (";
		$query .= " '{$user_name}', '{$password}' ";
		$query .= " );";
		
		// Do the query
		$result = mysqli_query($dbconn, $query);
		if($result) {
			// Insert success
			$_SESSION["message"] = "Admin created";
			redirect_to("manage_admins.php");
		}
		else {
			// Failure
			$_SESSION["message"] = "Admin creation failed";
			redirect_to("new_admin.php");
		}
		
	}
	else{
		// This is probably a GET request
		redirect_to("new_admin.php");
	}
?>

<?php require_once("../includes/db_connection_close.php") ?>