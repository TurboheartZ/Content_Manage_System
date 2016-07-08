<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_functions.php") ?>
<?php $layout_context ="admin"; ?>
<?php include("../includes/layouts/header.php") ?>

<?php 
$user_name = "";
if(isset($_POST["submit"])) {
	//Validations
	$required_fields = array("user_name", "password");
	validate_presences($required_fields);	

	if(empty($errors)) {
		//Attempt Login
		$user_name = $_POST["user_name"];
		$password = $_POST["password"];
		$found_admin = attempt_login($user_name, $password);
		
		if($found_admin) {
			//Success
			//Mark user as logged in
			$_SESSION["admin_id"] = $found_admin["id"];
			$_SESSION["username"] = $found_admin["username"];
			redirect_to("admin.php");
		}
		else{
			//Failure
			$_SESSION["message"] = "Username/Password Incorrect";
		}		
	}	
	
}?>

<div id="main" >
  <div id="navigation">
  </div>
  <div id="page">
		<?php echo message(); ?>
 		<?php if(!empty($message)) {
			echo "<div class = \"message\">".htmlentities($message)."</div>";
		}	?>
		<?php	echo form_errors($errors)?> 
		<h2>Login</h2>
		<form action = "login.php" method = "post">
			<p>
				User Name : <input type = "text" name = "user_name" value = "<?php echo htmlentities($user_name);?>" />
			</p>
			<p>
				Password : <input type = "text" name = "password" value = "" />
			</p>
			<input type = "submit" name = "submit" value = "Submit"/>
		</form>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>