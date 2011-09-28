<?php
/*
data insertion class
*/

if(!class_exists('wpdb_data_insertion')) : 
	class wpdb_data_insertion{
		function userdata($dm,$dt,$hidden=''){
			//$admin_email = get_option();			
				
			$term = trim(strtolower($dt['category']));						
			$data = array(
				'post_type' =>'psychologist',
				'post_title' => $dt['name'],
				'post_content' => $dt['description'],
				'post_status' => 'draft',			
				'post_date' => date("Y-m-d H:i:s",time()),
				'post_date_gmt' =>date("Y-m-d H:i:s",time()),		
				'ping_status' =>'open',	
			);			
			if($hidden == ''){
				$p_id = wp_insert_post( $data,false);
				if($p_id){
					
					$text_array = get_option('settings_api');
					$texts = $text_array['emails'];					
					
					$th = $text_array['confmail'].' '.' Your ID Number : '.$this->randomstring().$p_id.' ';
					
					$this->emailsending($dm['email'],'ID Number',$th);					
					
					if(count($texts)>0){
						$adminmessage = 'A new '.$dm['stupid'].' has just registered to your site '.'Please click the link to edit,delete or publish the content. ';
						$ur = get_option('home').'/wp-admin/post.php?post='.$p_id.'&action=edit';
						$adminmessage .= ' '.$ur;
						foreach($texts as $text){
							$this->emailsending($text,'New Psychologist',$adminmessage);
						}
					}
				}
			}
			else{				
				
				$hidden = (int)$hidden;
				$newdata = wp_parse_args(array('ID'=>$hidden),$data);
				$p_id = wp_insert_post($newdata,false);
				if($p_id){
					
					$text_array = get_option('settings_api');
					$texts = $text_array['emails'];
					
					$th = $text_array['updatemail'].' Your New ID Number : '.$this->randomstring().$p_id.' ';				
					
					$this->emailsending($dm['email'],'ID Number Updated',$th);					
					
					if(count($texts)>0){
						$adminmessage = 'A new '.$dm['stupid'].' has just changed his information'.''.'Please click the link to edit,delete or publish the content '.' ';
						$url = get_option('home').'/wp-admin/post.php?post='.$p_id.'&action=edit';
						$adminmessage .= $url;
						foreach($texts as $text){
							$this->emailsending($text,'Psychologist Modification',$adminmessage);
						}
					}
				}				
			}
			
			if($p_id){
				$this->saving_postmeta($dm,$p_id,'yes');
				$this->confirmation($p_id);
				/*
				global $wpdb;
				
				$term_id = $wpdb->get_var("SELECT `term_id` FROM $wpdb->terms WHERE `slug`='$term'");
				
				if($term_id){										
					$a = $wpdb->insert( $wpdb->term_relationships,array('object_id'=>$p_id,'term_taxonomy_id'=>$term_id), array('%d','%d'));					
				}
											
				if($a){
					$this->confirmation(1);
				}
				else{
					$this->confirmation(2);
				}	*/						
			}			
			
		}
		
		//confirmatin message;
		function confirmation($id){
			$cookie = $this->randomstring().$id.$this->randomstring();
			//cookie setting
			setcookie('uspsycholoigst',$cookie,time()+120);
			$options = get_option('settings_api');
			$registaionpage = ($options['informationform']);			
			$url = $registaionpage.'/?update=updated&message=okay&id='.$id.'okay';			
			header("Location:$url");
			exit;
		}
						
		
		//admin data manipulation while saving drafts
		function admin_data($dt,$dm,$post_id){
			if(is_array($dt)){ 			
				$terms = array();			
				foreach($dt as $val){			
					foreach($val as $value){
						$terms[]=$value;
					}				
				}			
				if(count($terms)!=0){					
					wp_set_post_tags($post_id,$terms);
					$this->saving_postmeta($dm,$post_id);
				}
				else {
					$this->saving_postmeta($dm,$post_id);					
				}
			}			
		}
		
		//savig data in database
		function saving_postmeta($d,$post_id,$email=''){	
			global $wpdb;
			$table = $wpdb->prefix.'psychologist';
			$id = $wpdb->get_var("SELECT `id` FROM $table WHERE `post_id`=$post_id");
			if($id){
				$wpdb->update($table,array('email'=>$d['email']),array('id'=>$id),array('%s'),array('%d'));
			}
			else{
				$wpdb->insert($table,array('post_id'=>$post_id,'email'=>$d['email']),array('%d','%s'));
			}		
			update_post_meta($post_id,'_doctorsdata',$d);
						
		}	
		
		//ajax data or random string generator
		
		function randomstring(){
			$alphpbet = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','p','q','r','s','t','u','v','w','x','y','z');
			$min = 0;
			$max = count($alphpbet)-1;
			$string = '';
			for($i=0;$i<5;$i++){
				$ran = rand($min,$max);
				$string .= $alphpbet[$ran];
			}
			
			return $string;
		}
			
		
			//ajax data verification
		function ajax(){
			$message = null;			
			$email = trim($_REQUEST['email']);
			//$nonce = $_REQUEST['nonce'];			
			
				if(is_email($email)){
					global $wpdb;
					$table = $wpdb->prefix.'psychologist';
					$email = strtolower($email);
					$post_id = $wpdb->get_var("SELECT `post_id` FROM $table WHERE `email`='$email'");			
					 
					if($post_id){
						$text_array = get_option('settings_api');
						$regaddress = $text_array['informationform'];
						$content = 'Please Click the link '.$regaddress.'/?pid='.$this->randomstring().$post_id.'&mail='.urlencode($email);
						//$content = 'Tracing Number : '.$this->randomstring().$post_id.' '.'Please do not reply to this mail';
						$subject = 'ID Number Recovery';
						
						$m = $this->emailsending($email,$subject,$content);
						if($m){
							$message = 'A mail has been sent! please check your mail';
						}
						else{
							$message = 'Mail cannot be sent to your email.Please check your email address and try again';
						}
					}
					else{
						$message = 'Sorry, Your Email is not Registered!';
					}
									
					
				}
				else{
					$message = 'Ooops Invalid Email!';
				}
			
			
			echo $message;
			exit;
		}
		
		function existingmailcheck(){
			$message = null;			
			$email = trim($_REQUEST['email']);
			$hidden = preg_replace('/[^0-9]/','',$_REQUEST['hidden']);
			$hidden = (int)$hidden;
			global $wpdb;
			$table = $wpdb->prefix.'psychologist';
			$email = strtolower($email);
			$post_id = $wpdb->get_var("SELECT `post_id` FROM $table WHERE `email`='$email'");
			if($post_id != $hidden){
				if($post_id){
					echo 'y';
					
				}
				else{
					echo 'n';
					
				}
			}
			else{
				echo 'e';
			}
			exit;
		}
		
		//email sending
		
		function emailsending($to='',$sub='',$mes=''){
			$text_array = get_option('settings_api');
			$text = $text_array['to'];
			$headers = 'From:'.$text.' '.' '."\r\n" .
			'Reply-To: '.$text.' '.' '. "\r\n";
			
			$m = mail($to,$sub,$mes,$headers);
			if($m){
				return true;
			}
			else{
				return false;
			}
		}
	}
	
	$wpdb_doc_data = new wpdb_data_insertion();
	//add_action('psychologist_verification');
	add_action( 'wp_ajax_nopriv_psychologist_verification',array($wpdb_doc_data,'ajax'));
	add_action( 'wp_ajax_psychologist_verification',array($wpdb_doc_data,'ajax'));
	add_action( 'wp_ajax_email_verification',array($wpdb_doc_data,'existingmailcheck'));
	add_action( 'wp_ajax_nopriv_email_verification',array($wpdb_doc_data,'existingmailcheck'));
	
endif;
?>
