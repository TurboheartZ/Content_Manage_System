<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>

<?php require_once("../includes/validation_functions.php") ?>
<?php find_selected_page(); ?>
<?php
	if(!$current_page) {
		//Page ID is missing or invalid
		//If we cannot find the page, redirect to previous page
		//Means we do not need to involve the layout from header
		redirect_to("manage_content.php");
	}
	$subject_id = $current_page["subject_id"];
?>

<?php 
	//Process the form
	if(isset($_POST["submit"])) {			
		//Validations
		$required_fields = array("menu_name", "position", "visible");
		validate_presences($required_fields);
		
		$fields_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($fields_with_max_lengths);
		
		if(empty($errors)) {
			//Perform Update
			// If submitted, then process the form
			$id = $current_page["id"];
			$menu_name = mysql_prep($_POST["menu_name"]);	
			$position =  (int) $_POST["position"];
			$visible = (int) $_POST["visible"];
			$content = mysql_prep($_POST["content"]);
			
			// Build the insert query
			$query = "UPDATE pages SET ";
			$query .= "menu_name = '{$menu_name}', ";
			$query .= "position = {$position}, ";
			$query .= "visible = {$visible}, ";
			$query .= "content = '{$content}' ";
			$query .= "WHERE id = {$id} ";
			$query .= "LIMIT 1;";
			
			// Do the query
			$result = mysqli_query($dbconn, $query);
			if($result&&mysqli_affected_rows($dbconn)>=0) {
				// Edit success
				$_SESSION["message"] = "Page updated";
				$addr = "manage_content.php?subject=".$subject_id;
				redirect_to($addr);
			}
			else{
				$message = "Page update failed";
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
	<h2>Navigation</h2>
		<?php echo navigation($current_subject, $current_page); ?>
  </div>
  <div id="page">
		<?php if(!empty($message)) {
			echo "<div class = \"message\">".htmlentities($message)."</div>";
		}	?>
		<?php	echo form_errors($errors)?>
		<h2>Edit Page : <?php echo htmlentities($current_page["menu_name"]); ?></h2>
		<form action = "edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method = "post">
			<p>
				Menu Name : <input type = "text" name = "menu_name" value = "<?php echo htmlentities($current_page["menu_name"]); ?>" />
			</p>
			<p>
				Position : 
				<select name = "position">
					<?php 
					$page_set = find_pages_for_subject($subject_id,false);//find_all_pages($subject_id);
					$row_num = mysqli_num_rows($page_set);
					for($count = 1; $count<=$row_num; $count++){
						echo "<option value = \"{$count}\"";
						if($current_page["position"] == $count){
							echo " selected ";
						}
						echo ">{$count}</option>";
					} ?>
				</select>
			</p>
			<p>
				Visible : 
				<input type = "radio" name = "visible" value = "0" <?php 
					if ($current_page["visible"]==0) {echo "checked";}
				?>/> No 
				&nbsp;
				<input type = "radio" name = "visible" value = "1" <?php 
					if ($current_page["visible"]==1) {echo "checked";}
				?>/> Yes
			</p>
			<p>
				Content : <br/>
				<textarea class =  "inputcontent"  name = "content" value = "<?php echo htmlentities($current_page["content"]); ?>"><?php echo htmlentities($current_page["content"]); ?></textarea>
			</p>
			<input type = "submit" name = "submit" value = "Edit Page"/>
		</form>
		<br/>
		<a href = "manage_content.php?subject=<?php echo urlencode($subject_id);?>">Cancel</a>
		&nbsp;
		&nbsp;
		<a href = "delete_page.php?page=<?php echo urlencode($current_page["id"]);?>"
		 onclick="return confirm('Are you sure?');">Delete</a>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>