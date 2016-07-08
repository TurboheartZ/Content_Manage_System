<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php $layout_context ="admin"; ?>
<?php include("../includes/layouts/header.php") ?>
<?php find_selected_page(); ?>

<div id="main" >
  <div id="navigation">
	<h2>Navigation</h2>
		<a href = "admin.php">&laquo; Main Menu</a><br/>
		<?php echo navigation($current_subject, $current_page); ?>
		<br/>
		<a href = "new_subject.php">+ Add a subject</a>
  </div>
  <div id="page">
	<?php echo message();	?>
	<?php 
		if($current_subject) { ?> 
		<h2>Manage Subject</h2>
		Menu Name : <?php echo $current_subject["menu_name"]; ?><br/>
		Position : <?php echo $current_subject["position"]; ?><br/>
		Visible : <?php echo $current_subject["visible"]==1?"Yes":"No"; ?><br/>
		<a href = "edit_subject.php?subject=<?php echo htmlentities($current_subject["id"]); ?>">Edit Subject</a>
		<br/><br/><hr/>
		<?php 
			$page_set = find_pages_for_subject($current_subject["id"],false);
			if(mysqli_num_rows($page_set)>0){
				//This subject contains pages, display them ?>
				<h3>Pages in this subject:</h3>
				<?php echo page_navigation($page_set); ?>
			<?php }else{
				//Means this subject does not contain any pages
				//Do not need to display blank manage page section
			} 
		?>
		&#43; <a href = "new_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Add a new page to this subject</a>
	<?php } 
		else if($current_page) { ?>
		<h2>Manage Page</h2>
		Menu Name : <?php echo htmlentities($current_page["menu_name"]); ?><br/>
		Position : <?php echo $current_page["position"]; ?><br/>
		Visible : <?php echo $current_page["visible"]==1?"Yes":"No"; ?><br/>
		Content : <br/>
		<div class = "view-content">
			<?php echo htmlentities($current_page["content"])?>
		</div><br/>
		<a href = "edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>">Edit Page</a>
	<?php } 
		else { ?>
		<h2>Please select a subject or a page.</h2>
	<?php } ?>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>