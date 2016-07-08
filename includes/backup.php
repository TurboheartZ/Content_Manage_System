		<ul class="subjects">
		<?php $subject_set  = find_all_subjects(); ?>				
		<?php
			// 3. Use returned data (if any)
			//mysqli_fetch_row returns integers as keys-->Fast
			//mysqli_fetch_assoc returns column names as keys-->Convenient, Slower
			while($subject = mysqli_fetch_assoc($subject_set)){
				?>
				<li <?php if($selected_subject_id==$subject["id"]) { echo "class = 'selected'";}?>>
					<a href = "manage_content.php?subject=<?php echo urlencode($subject["id"]);?>"><?php echo $subject["menu_name"]; ?></a>
					<?php $page_set = find_all_pages($subject["id"]); ?>						
						<ul class="pages">
							<?php
								while($page = mysqli_fetch_assoc($page_set)) {
									?>
										<li>
											<a href = "manage_content.php?page=<?php echo urlencode($page["id"]);?>"><?php echo $page["menu_name"]; ?></a>
										</li>
								<?php
								}
							?>
							<?php
								// 4. Release returned data
								mysqli_free_result($page_set);
							?>						
						</ul>					
				</li>
				<?php
			}		
		?>	
		<?php
			// 4. Release returned data
			mysqli_free_result($subject_set);
		?>					
		</ul>