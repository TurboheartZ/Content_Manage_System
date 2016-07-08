<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php $layout_context ="admin"; ?>
<?php include("../includes/layouts/header.php") ?>
<?php find_selected_page(); ?>

<?php 
	if(!isset($_GET["subject"])) {
		//Means not enter this page from a subject page
		//Return to manage_content.php
		redirect_to("manage_content.php");
	}
?>

<div id="main" >
  <div id="navigation">
	<h2>Navigation</h2>
		<a href = "manage_content.php">&laquo; Manage Content </a><br/>
		<?php echo navigation($current_subject, $current_page); ?>
  </div>
  <div id="page">
		<?php echo message();	?>
		<?php $errors = errors();	echo form_errors($errors); ?>
		<h2>Create Page</h2>
		<form action = "create_page.php" method = "post">
			<input type = "hidden" name = "subject_id" value = "<?php echo $_GET["subject"];?>"/>
			<p>
				Menu Name : <input type = "text" name = "menu_name" value = "" />
			</p>
			<p>
				Position : 
				<select name = "position">
					<?php 
					$subject_id = urlencode($_GET["subject"]);
					$page_set = find_pages_for_subject($subject_id,false);// find_all_pages($subject_id);
					$row_num = mysqli_num_rows($page_set);
					for($count = 1; $count<=$row_num+1; $count++){
						echo "<option value = \"{$count}\">{$count}</option>";
					} ?>
				</select>
			</p>
			<p>
				Visible : 
				<input type = "radio" name = "visible" value = "0"/> No 
				&nbsp;
				<input type = "radio" name = "visible" value = "1"/> Yes
			</p>
			<p>
				Content : <br/>
				<textarea class =  "inputcontent"  name = "content" ></textarea>
			</p>
			<input type = "submit" name = "submit" value = "Create Page"/>
		</form>
		<br/>
		<a href = "manage_content.php?subject=<?php echo $subject_id; ?>">Cancel</a><br/>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>