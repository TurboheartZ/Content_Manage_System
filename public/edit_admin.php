<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_functions.php") ?>
<?php if(isset($_GET["admin"])) {
		$current_admin = find_admin_by_id($_GET["admin"]);
	}
	else{
		$current_admin = null;
	}
?>
<?php
	if(!$current_admin) {
		redirect_to("manage_admins.php");
	}
?>

<?php 
	//Process the form
	if(isset($_POST["submit"])) {			
		//Validations
		$required_fields = array("user_name", "password");
		validate_presences($required_fields);
		
		$fields_with_max_lengths = array("user_name" => 30);
		validate_max_lengths($fields_with_max_lengths);
		
		if(empty($errors)) {
			//Perform Update
			// If submitted, then process the form
			$id = $current_admin["id"];
			$user_name = mysql_prep($_POST["user_name"]);	
			$password =  password_encrypt($_POST["password"]);
			
			// Build the insert query
			$query = "UPDATE admins SET ";
			$query .= "username = '{$user_name}', ";
			$query .= "hashed_password = '{$password}' ";
			$query .= "WHERE id = {$id} ";
			$query .= "LIMIT 1;";
			
			// Do the query
			$result = mysqli_query($dbconn, $query);
			if($result&&mysqli_affected_rows($dbconn)>=0) {
				// Edit success
				$_SESSION["message"] = "Admin updated";
				redirect_to("manage_admins.php");
			}
			else{
				$message = "Admin update failed";
			}			
		}
	}
	else{
		// This is probably a GET request
	} // End : if(isset($_POST["submit"])) 
?>

<?php $layout_context ="admin"; ?>
<?php include("../includes/layouts/header.php") ?>

<div id="main" >
  <div id="navigation">
  </div>
  <div id="page">
		<?php if(!empty($message)) {
			echo "<div class = \"message\">".htmlentities($message)."</div>";
		}	?>
		<?php	echo form_errors($errors)?>
		<h2>Edit Admin: <?php echo htmlentities($current_admin["username"]); ?></h2>
		<form action = "edit_admin.php?admin=<?php echo urlencode($current_admin["id"]); ?>" method = "post">
			<p>
				User Name : <input type = "text" name = "user_name" value = "<?php echo htmlentities($current_admin["username"]); ?>" />
			</p>
			<p>
				Password : <input type = "text" name = "password"  />
			</p>
			<input type = "submit" name = "submit" value = "Edit Admin"/>
		</form>
		<br/>
		<a href = "manage_admins.php">Cancel</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>