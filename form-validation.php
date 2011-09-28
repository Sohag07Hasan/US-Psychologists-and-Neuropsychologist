<?php
if(!class_exists('doctor_validation')) :
	class doctor_validation{
		//doctors data at sining up by doctors
		function doctor_data($data){
						
			$hidden = preg_replace('/[^0-9]/','',$data['hidden']);					
			$doctor = array();
			$error = array();
			$docpost = array();			
			
			//validating the user data
			$docpost['name'] = preg_replace('/[^a-zA-Z" "]/','',$data['firstname']).' '.preg_replace('/[^a-zA-Z" "]/','',$data['last']);
			if(strlen($docpost['name'])<4){
				$error['name'] = 1;
			}
			$docpost['category'] = trim($data['category']);
			$docpost['description'] = sanitize_text_field(strip_tags($data['description']));
			
			if(is_email($data['email'])){
				$doctor['email'] = strtolower(trim($data['email']));
				if(strlen($hidden)<1) :
					global $wpdb;
					$table = $wpdb->prefix.'psychologist';
					$email = strtolower($data['email']);
					$post_id = $wpdb->get_var("SELECT `post_id` FROM $table WHERE `email`='$email'");
					 
					if($post_id){
							$error['regemail'] = 1;
					}
				endif;
			}
			else{
				$error['email'] = 1;
			}
			$doctor['telephone'] = preg_replace('/[^0-9-]/','',$data['telephone']);	
			if(strlen($doctor['telephone']) <= 4){
				$error['telephone'] = 1;
			}		
			$doctor['website'] = strtolower(trim(strip_tags($data['website'])));
			
			if(isset($data['city'])){
				foreach($data['city'] as $key=>$city){
					if($city != ''){
						//$doctor["hospital$key"] = strip_tags($data['hospital'][$key]);
						//$doctor['hospitals'][] = strip_tags($data['hospital'][$key]);
						$doctor['cities'][] = preg_replace('/[^a-zA-Z" "-]/','',$city);
						$doctor['states'][] = trim(strip_tags($data['state'][$key]));
						$doctor['zips'][] = preg_replace('/[^0-9]/','',$data['zip'][$key]);					
					}								
				}
				
			}
			if(count($doctor['cities']) == 0){
				$error['city'] = 1;
			}
			if(count($doctor['zips']) == 0){
				$error['zip'] = 1;
			}
			
			
			if(isset($data['subcat'])){				
				foreach($data['subcat'] as $rt=>$value){
					$doctor['subcat'][] = trim($value);
				}			
			}
			$doctor['internship'] = sanitize_text_field(strip_tags($data['internship']));	
			$doctor['training'] = sanitize_text_field(strip_tags($data['doc-training']));	
			$doctor['fellowship'] = sanitize_text_field(strip_tags($data['fellow']));
			$doctor['degree'] = preg_replace('/[^a-zA-Z0-9.,]/','',$data['degree']);
			$doctor['stupid'] = $data['category'];
			$doctor['lang'] = $data['lang'];
			$doctor['email_hide'] = $data['email_hide'];
			
				
			if(count($error) == 0){				
				global $wpdb_doc_data;
				$wpdb_doc_data->userdata($doctor,$docpost,$hidden);
			}
			else{								
				$this->error_message($error);	
			}		
		
	}
	
	//function error message
		function error_message($errors){
			$options = get_option('settings_api');
			$registaionpage = ($options['informationform']);									
			$message = '';
			foreach($errors as $error=>$value){
				$message .= $error.'-';
			}
			$message = trim($message,'-');			
			$url = $registaionpage.'/?errormessage='.urlencode($message);
			header("Location:$url");
			exit;
			
		}
		
		//meta data by admin input
		function save_metaaa($post_id){	
				
			global $wpdb_doc_data;					
			$doctor = array();
			$d = array();
			if(isset($_REQUEST['docmeta'])){				
				$data = $_REQUEST['docmeta'];				
						
				$doctor['email'] = strtolower(trim($data['email']));
				$doctor['website'] = strtolower(trim(strip_tags($data['website'])));
				$doctor['telephone'] = preg_replace('/[^0-9]/','',$data['phone']);
				$doctor['degree'] = preg_replace('/[^a-zA-Z0-9," "]/','',$data['degree']);
				$doctor['internship'] = sanitize_text_field(strip_tags($data['internship']));	
				$doctor['training'] = sanitize_text_field(strip_tags($data['training']));	
				$doctor['fellowship'] = sanitize_text_field(strip_tags($data['fellowship']));
				$doctor['lang'] = $data['lang'];
				$doctor['email_hide'] = $data['email_hide'];
				
				//term data
				if(count($data['city']!=0)){
					foreach($data['city'] as $key=>$value){
						$value = trim($value);
						if($value!=''){
							$d['cities'][] = $value;
							$d['states'][] = trim($data['state'][$key]);
							$d['zip'][] = preg_replace('/[^0-9]/','',$data['zip'][$key]);
							//$d['hospital'][] = trim($data['hospital'][$key]);
						}						
					}
					$doctor['cities'] = $d['cities'];
					$doctor['states'] = $d['states'];
					$doctor['zips']= $d['zip'];
					//$doctor['hospitals'] = $d['hospital'];
					$doctor['stupid']=$data['stupid'];
					global $us_doctors;
					for($i=1;$i<=count($us_doctors->subprof);$i++){
						$doctor['subcat'][]=trim($data["subcat$i"]);
						if(isset($data["subcat$i"])){
							$d['extra'][] = trim($data["subcat$i"]);
						}
					}										
									
				}							
					if(count($d['cities']) == 0){
						$wpdb_doc_data->admin_data('',$doctor,$post_id);											
					}
					else{
						//unset($d['hospital']);						
						$wpdb_doc_data->admin_data($d,$doctor,$post_id);
					}	
				}
			}		
		
			function errormanipulation($ext=''){
				$ext = '';
			}
			
			function find_data($find){		
				//extracting the array keys as variables				
				extract($find,EXTR_SKIP);
				$state =strtolower(preg_replace('/[" "]/','-',trim($state)));
				$city = strtolower(preg_replace('/[" "]/','-',trim($city)));				
				$zip = preg_replace('/[^0-9]/','',$zip);
				$category = strtolower(trim($category));
				$subcat = strtolower(preg_replace('/[" "]/','-',trim($subcat)));
				$link = 'category='.$category.'&state='.$state.'&city='.$city.'&zip='.$zip.'&subcat='.$subcat;
								
				$options = get_option('settings_api');
				$mainpage = ($options['psychologistpage']);
				$url = $mainpage.'/?'.$link;
				
				header("Location:$url");
				exit;				
					
			}	
			
			
			//function deleteing the psychologist table
			function delete($p_id){							
				global $wpdb;
				$table = $wpdb->prefix.'psychologist';
				$wpdb->query("DELETE FROM $table WHERE `post_id`=$p_id");
			}
			
			
		
	}//enf of the class
	
	$doctordata = new doctor_validation();
	//user data 
	if($_REQUEST['us-doctor-sumbit']){	
		$doctordata->doctor_data($_REQUEST['doctor']);
	}
	
	//validating search form daata
	if($_REQUEST['find_professionals']){
		$doctordata->find_data($_REQUEST['find']);		
	}
	
	//admin data
	add_action('save_post',array($doctordata,'save_metaaa'));
	add_action('deleted_post',array($doctordata,'delete'),2);
	
	
	
endif;
?>
