<?php

	//Function to do the redirection
	function redirect_to($new_location) {
		header("Location: ".$new_location);
		exit;
	}
	
	//Function to prepare the input value for mysql
	function mysql_prep($string) {
		global $dbconn;
		$escaped_string = mysqli_real_escape_string($dbconn, $string);
		return $escaped_string;
	}

	//Function for testing if there is a query error
	function confirm_query ($result_set){
		if(!$result_set){
			die("Database query failed!");
		}
	}

	function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
		  $output .= "<div class=\"error\">";
		  $output .= "Please fix the following errors:";
		  $output .= "<ul>";
		  foreach ($errors as $key => $error) {
			$output .= "<li>";
			$output .=htmlentities($error);
			$output .="</li>";
		  }
		  $output .= "</ul>";
		  $output .= "</div>";
		}
		return $output;
	}	
	
	//Function for selecting all the rows in subjects table
	function find_all_subjects ($public = true) {
		global $dbconn;
		$query = "SELECT * FROM subjects ";
		if($public){
			$query .="WHERE visible = 1 ";
		}
		$query .= "ORDER BY position;";
		$subject_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($subject_set);
		return $subject_set;
	}
	
	//Function for selecting all the rows in pages table
	function find_all_pages($subject_id) {
		//2. Perform database_page table query
		global $dbconn;
		$query = "SELECT * FROM pages WHERE visible = 1 AND subject_id = {$subject_id} ORDER BY position;";
		$page_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($page_set);		
		return $page_set;
	}
	
	function find_default_page_for_subject($subject_id) {
		$page_set = find_pages_for_subject($subject_id,true);
		if($first_page = mysqli_fetch_assoc($page_set)) {
			return $first_page;
		}
		else{
			return null;
		}
	}
	
	//Function for finding the page we are going to by GET arguments
	function find_selected_page() {
		global $current_subject;
		global $current_page;
		if(isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"]);
			$current_page = null;
		}
		elseif(isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"]);
			$current_subject = null;
		}
		else{
			$current_page = null;
			$current_subject = null;
		}		
	}
		
	function find_subject_by_id($subject_id) {
		global $dbconn;
		$safe_subject_id = mysqli_real_escape_string($dbconn, $subject_id); //Make it safe
		$query = "SELECT * FROM subjects ";
		$query .="WHERE id = {$safe_subject_id} ";
		$query .= "LIMIT 1;";
		$subject_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($subject_set);
		if($subject =  mysqli_fetch_assoc($subject_set)) {
			return $subject;
		}
		else{
			return null;
		}
	}
	
	function find_page_by_id($page_id) {
		global $dbconn;
		$safe_page_id = mysqli_real_escape_string($dbconn, $page_id); //Make it safe
		$query = "SELECT * FROM pages ";
		$query .="WHERE id = {$safe_page_id} ";
		$query .= "LIMIT 1;";
		$page_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($page_set);
		if($page =  mysqli_fetch_assoc($page_set)) {
			return $page;
		}
		else{
			return null;
		}
	}	

	function find_pages_for_subject($subject_id, $public = true) {
		global $dbconn;
		
		$safe_subject_id = mysqli_real_escape_string($dbconn, $subject_id);
		
		$query  = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE subject_id = {$safe_subject_id} ";
		if($public){
			$query .= "AND visible = 1  ";
		}		
		$query .= "ORDER BY position ASC";
		$page_set = mysqli_query($dbconn, $query);
		confirm_query($page_set);
		return $page_set;
	}	
	
	function find_all_admins () {
		global $dbconn;
		global $admin_set;
		$query = "SELECT * FROM admins ";
		$query .= "ORDER BY id;";
		$admin_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($admin_set);
		return $admin_set;		
	}
	
	function find_admin_by_id($admin_id) {
		global $dbconn;
		$safe_admin_id = mysqli_real_escape_string($dbconn, $admin_id); //Make it safe
		$query = "SELECT * FROM admins ";
		$query .="WHERE id = {$safe_admin_id} ";
		$query .= "LIMIT 1;";
		$admin_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($admin_set);
		if($admin =  mysqli_fetch_assoc($admin_set)) {
			return $admin;
		}
		else{
			return null;
		}
	}	
	
	function find_admin_by_username($username) {
		global $dbconn;
		$safe_admin_username = mysqli_real_escape_string($dbconn, $username); //Make it safe
		$query = "SELECT * FROM admins ";
		$query .="WHERE username = '{$safe_admin_username}' ";
		$query .= "LIMIT 1;";
		$admin_set = mysqli_query($dbconn, $query); //Resource of database rows
		confirm_query($admin_set);
		if($admin =  mysqli_fetch_assoc($admin_set)) {
			return $admin;
		}
		else{
			return null;
		}
	}	

	//Navigation takes 2 arguments
	// - the current selected subject array
	// - the current selected page array
	function navigation($sel_subject_array, $sel_page_array) {		
		$output = "<ul class=\"subjects\">";
		$subject_set  = find_all_subjects(false);				
			// 3. Use returned data (if any)
			//mysqli_fetch_row returns integers as keys-->Fast
			//mysqli_fetch_assoc returns column names as keys-->Convenient, Slower
			while($subject = mysqli_fetch_assoc($subject_set)){
				$output .= "<li ";
				if($sel_subject_array && $sel_subject_array["id"]==$subject["id"]) { 
					$output .= "class = \"selected\" ";
				}
				$output .=">";
				$output .= "<a href = \"manage_content.php?subject=";
				$output .= urlencode($subject["id"]); 
				$output .="\">";
					$output .= htmlentities($subject["menu_name"]); 
				$output .="</a>";
				//if($sel_subject_id==$subject["id"]) {				
					$page_set = find_pages_for_subject($subject["id"],false);					
					$output	.= "<ul class=\"pages\">";
						while($page = mysqli_fetch_assoc($page_set)) {
								$output .= "<li ";
								if($sel_page_array && $sel_page_array["id"]==$page["id"]) { 
									$output .= "class = \"selected\" ";
								}
								$output .=">";
								$output .="<a href = \"manage_content.php?page=";
								$output .= urlencode($page["id"]); 
								$output .="\">"; 
									$output .= htmlentities($page["menu_name"]); 
								$output .="</a>";
								$output .="</li>";
						}
					// 4. Release returned data
					mysqli_free_result($page_set);			
					$output .= "</ul>";	
				//}	
				$output .= "</li>";
			}		
			// 4. Release returned data
		mysqli_free_result($subject_set);					
		$output .= "</ul>";	
		return $output;	
	}	

	function public_navigation($sel_subject_array, $sel_page_array) {		
		$output = "<ul class=\"subjects\">";
		$subject_set  = find_all_subjects(true);				
			// 3. Use returned data (if any)
			//mysqli_fetch_row returns integers as keys-->Fast
			//mysqli_fetch_assoc returns column names as keys-->Convenient, Slower
			while($subject = mysqli_fetch_assoc($subject_set)){
				$output .= "<li ";
				if($sel_subject_array && $sel_subject_array["id"]==$subject["id"]) { 
					$output .= "class = \"selected\" ";
				}
				$output .=">";
				$output .= "<a href = \"index.php?subject=";
				$output .= urlencode($subject["id"]); 
				$output .="\">";
				$output .= htmlentities($subject["menu_name"]); 
				$output .="</a>";
				if($sel_subject_array["id"]==$subject["id"] || $sel_page_array["subject_id"]==$subject["id"]) {				
					$page_set = find_all_pages($subject["id"]);					
					$output	.= "<ul class=\"pages\">";
						while($page = mysqli_fetch_assoc($page_set)) {
								$output .= "<li ";
								if($sel_page_array && $sel_page_array["id"]==$page["id"]) { 
									$output .= "class = \"selected\" ";
								}
								$output .=">";
								$output .="<a href = \"index.php?page=";
								$output .= urlencode($page["id"]); 
								$output .="\">"; 
								$output .= htmlentities($page["menu_name"]); 
								$output .="</a>";
								$output .="</li>";
						}
					// 4. Release returned data
					mysqli_free_result($page_set);			
					$output .= "</ul>";	
				}	
				$output .= "</li>";
			}		
			// 4. Release returned data
		mysqli_free_result($subject_set);					
		$output .= "</ul>";	
		return $output;	
	}	
	
	function page_navigation($page_set) {
		if(!$page_set){
			return null;
		}
		$output = "<ul class = \"pages\">";
		while($page = mysqli_fetch_assoc($page_set)) {
			$output .= "<li>";
			$output .= "<a href = \"manage_content.php?page=".urlencode($page["id"])."\">".htmlentities($page["menu_name"])."</a>";
			$output .= "</li>";
		}
		$output .= "</ul>";
		return $output;
	}
	
	function password_encrypt($password) {
		$hash_format = "$2y$10$";
		$salt_length = 22;
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format.$salt;
		$hash = crypt($password,$format_and_salt);
		return $hash;
	}
	
	function generate_salt($length) {
		$unique_random_string = md5(uniqid(mt_rand(), true));
		$base64_string = base64_encode($unique_random_string);
		$modified_base64_string = str_replace('+','.',$base64_string);
		$salt = substr($modified_base64_string,0,$length);
		return $salt;
	}
	
	function password_check($password, $existing_hash) {
		$hash = crypt($password, $existing_hash);
		if($hash==$existing_hash){
			return true;
		}
		else{
			return false;
		}
	}
	
	function attempt_login($username, $password) {
		$admin = find_admin_by_username($username);
		if($admin){
			//found admin, check password
			if(password_check($password, $admin["hashed_password"])){
				//Matches
				return $admin;
			}
			else{
				//Not match
				return false;
			}
		}
		else{
			return false;
		}
	}

	function confirm_logged_in() {
		if(!logged_in()) {
			redirect_to("login.php");
		} 		
	}	
	
	function logged_in() {
		return isset($_SESSION["admin_id"]);
	}	
	
?>