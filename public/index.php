<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php $layout_context ="public"; ?>
<?php include("../includes/layouts/header.php") ?>
<?php find_selected_page(); ?>

<div id="main" >
  <div id="navigation">
	<h2>Navigation</h2>
		<?php echo public_navigation($current_subject, $current_page); ?>
  </div>
  <div id="page">
	<?php echo message();	?>
	<?php 
		if($current_subject) { ?> 
		<h2><?php echo htmlentities($current_subject["menu_name"]); ?></h2>
		Menu Name : <?php echo htmlentities($current_subject["menu_name"]); ?><br/>
	<?php } 
		else if($current_page) { ?>
		<h2><?php echo htmlentities($current_page["menu_name"]); ?></h2>
			<?php echo nl2br(htmlentities($current_page["content"]));?>
	<?php } 
		else { ?>
		<p>Welcome!</p>
	<?php } ?>
  </div>
</div>

<?php include("../includes/layouts/footer.php") ?>
<?php require_once("../includes/db_connection_close.php") ?>
