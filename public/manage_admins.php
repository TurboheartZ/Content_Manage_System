<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php $layout_context ="admin"; ?>
<?php include("../includes/layouts/header.php") ?>

<div id="main" >
  <div id="navigation">
	<h2>Navigation</h2>
		<a href = "admin.php">&laquo; Main Menu</a><br/>
  </div>
  <div id="page">
	<?php echo message();	?>
	<h2>Manage Admins</h2>
	<table class = "admin_table">
	<tr>
		<th>Username</th>
		<th>Action</th>
	</tr>
	<?php if($admin_set = find_all_admins()) {
		while($admin = mysqli_fetch_assoc($admin_set)) { ?>
		<tr>
			<td><?php echo $admin["username"]; ?></td>
			<td><a href = "edit_admin.php?admin=<?php echo $admin["id"];?>">Edit</a>&nbsp;
			<a href = "delete_admin.php?admin=<?php echo $admin["id"];?>" onclick="return confirm('Are you sure?');">Delete</a></td>
		</tr>
		<?php }
	}?>
	</table>
	<br/>
	<a href = "new_admin.php">Add new admin</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>