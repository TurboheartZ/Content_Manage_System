<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php $layout_context ="admin"; ?>
<?php include("../includes/layouts/header.php") ?>

<div id="main" >
  <div id="navigation">
  </div>
  <div id="page">
		<?php echo message();	?>
		<?php $errors = errors();	echo form_errors($errors); ?>
		<h2>Create Admin</h2>
		<form action = "create_admin.php" method = "post">
			<p>
				User Name : <input type = "text" name = "user_name" value = "" />
			</p>
			<p>
				Password : <input type = "text" name = "password" value = "" />
			</p>
			<input type = "submit" name = "submit" value = "Create Admin"/>
		</form>
		<br/>
		<a href = "manage_admins.php">Cancel</a><br/>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>