<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/functions.php") ?>

<?php
	// Version 1 : Simple log out
	$_SESSION["admin_id"] = null;
	$_SESSION["username"] = null;
	redirect_to("login.php");
?>