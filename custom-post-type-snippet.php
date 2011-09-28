<?php
/*
 *custom posty type admin control
 * */
 
 if(!class_exists('psychologist_post_type')) :
	class psychologist_post_type{
		// chnage the columns for edit psychologistcategory screen
		function change_columns($cols){	
			
			$new = array();
						
			$new['cb'] = $cols['cb'];
			$new['title'] = $cols['title'];
			$new['author'] = $cols['author'];
			$new['category'] = __( 'Category', 'trans' );
			$new['email'] = __( '<a href="#" >Email<a>', 'trans' );			
			$new['tags'] = $cols['tags'];
			$new['date'] = $cols['date'];		  
		  
		  return $new;
		}
		
		//providing custom columns to the psychologist
		function custom_columns( $column, $post_id ){			
								
			$metadata = get_post_meta($post_id,'_doctorsdata',true);
			$terms = wp_get_object_terms($post_id,'psy-neupsy');			
						
			$email = $metadata['email'];			
			
			switch ( $column ) {
				case "category":				 
				  echo (strlen($terms[0]->name)>3)? '<a href="#">' . $terms[0]->name. '</a>' : $metadata['stupid'];
				  break;
				case "email":				  
				  echo '<a class ="individualemail" id="psy-'.$post_id.'" href="#">' . $email. '</a><br/><span class="customtextarea" id="textarea-'.$post_id.'" >Subject<br/><input type="text" id="ind-'.$post_id.'"  /><br/>Content<br/><textarea id="text-'.$post_id.'" cols="13" rows="8"></textarea><br/><input type="button" value="send" class="send" id="send-'.$post_id.'" /></span>';
				  //echo $email;
				  break;			
		  }
		}
		
		//making the column sortalbe
		function sortable_columns($a){
			$new = array();			
			$new = $a;
			$new['category'] = 'category';					  
		  
		  return $new;
		 
		}
		
		// Filter the request to just give posts for the given taxonomy, if applicable.
		function taxonomy_filter_restrict_manage_posts() {
			global $typenow ;
			if($typenow == 'psychologist') : 
			
			// If you only want this to work for your specific post type,
			// check for that $type here and then return.
			// This function, if unmodified, will add the dropdown for each
			// post type / taxonomy combination.

				$post_types = get_post_types( array( '_builtin' => false ) );

				if ( in_array( $typenow, $post_types ) ) {
					$filters = get_object_taxonomies( $typenow );

					foreach ( $filters as $tax_slug ) {
						if ($tax_slug == 'post_tag') continue;
						$tax_obj = get_taxonomy($tax_slug);
						wp_dropdown_categories( array(
							'show_option_all' => __('Show All Category' ),
							'taxonomy' 	  => $tax_slug,
							'name' 		  => $tax_obj->name,
							'orderby' 	  => 'name',
							'selected' 	  => $_GET[$tax_slug],
							'hierarchical' 	  => $tax_obj->hierarchical,
							'show_count' 	  => false,
							'hide_empty' 	  => true
						) );
					}
				}
				
			endif;
		}
		
		function taxonomy_filter_post_type_request( $query ) {
		  global $pagenow;$typenow;
			if($typenow == 'psychologist') :
			  if ( 'edit.php' == $pagenow ) {
				$filters = get_object_taxonomies( $typenow );
				foreach ( $filters as $tax_slug ) {
				  $var = &$query->query_vars[$tax_slug];
				  if ( isset( $var ) ) {
					$term = get_term_by( 'id', $var, $tax_slug );
					$var = $term->slug;
				  }
				}
			  }
			  
			  endif;
		}
		
		function ajax(){
			$p_id = preg_replace('/[^0-9]/','', $_REQUEST['pid']);
			$text = $_REQUEST['text'];
			$subject = $_REQUEST['subject'];
			$p_id = (int)$p_id;
			//echo $subject;
			//echo $text;
			//echo $p_id;
			//exit;
			if($p_id == ''){
				
				global $wpdb;
				$table = $wpdb->prefix.'psychologist';
				$emails = $wpdb->get_col("SELECT `email` FROM $table");
				$email = '';
				foreach($emails as $value){
					$email .= $value.',';
				}
				$e = trim($email,',');
				if($this->emailsending($e,$text,$subject,'t')){
					$message = "Mail has been sent to all the psychologists";
				}
				else{
					$message = "Server Can not send mail to the users";
				}				
			}
			else{
				
				$metadata = get_post_meta($p_id,'_doctorsdata',true);
				$email = $metadata['email'];			
				
				if(strlen($email)>4){
					if($this->emailsending($email,$text,$subject,'f')){
						$message = 'Mail has been sent';
					}
					else{
						$message = 'Server can not send the mail to that address';
					}				
				}
				else{
					$message = 'invalid address';
				}				
				
			}
			
			echo $message;
			exit;
			
		}
		
		//mailsending
		function emailsending($email,$content,$subject='Important Notice',$t){
			
			$text_array = get_option('settings_api');
			$text = $text_array['to'];
			
			$headers = 'From:'.$text.' '.' '."\r\n" .
			'Reply-To: '.$text.' '.' '. "\r\n";
			if($t == 't'){
				$headers .= 'Bcc:'.$text."\r\n";
				//$headers .= 'Cc:'.$text."\r\n";
			}
			
			$m = mail($email,$subject,$content,$headers);
			if($m){
				return true;
			}
			else{
				return false;
			}
		}

			
	}
	
	$psychologistcategory = new psychologist_post_type();
	
	add_filter("manage_psychologist_posts_columns",array($psychologistcategory,"change_columns"));
	add_action("manage_pages_custom_column",array($psychologistcategory,"custom_columns"),10,2);	
	add_filter("manage_edit-psychologist_sortable_columns",array($psychologistcategory,"sortable_columns"));
	
	//add_action( 'restrict_manage_posts',array($psychologistcategory,'taxonomy_filter_restrict_manage_posts'));
	//add_filter( 'parse_query',array($psychologistcategory,'taxonomy_filter_post_type_request'));
	
	add_action( 'wp_ajax_nopriv_psychologist_email',array($psychologistcategory,'ajax'));
	add_action( 'wp_ajax_psychologist_email',array($psychologistcategory,'ajax'));
	
	
 endif;
?>
