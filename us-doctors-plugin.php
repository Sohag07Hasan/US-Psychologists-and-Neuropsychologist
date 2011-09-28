<?php
/*
 * plugin name: US Doctors' locator plugin
 * author: Md.Mahibul Hasan
 * author uri: http://sohag07hasan.elance.com
 * plugin uri: http://hasan-sohag.blogspot.com
 * description: You can find the different specialised doctors from different states of USA.You can also find their information including educational background,hospital,contact information(email,phone,website)..etc
 *  
 * 
 */ 
 
 //staring of main class
if(!class_exists('usa_doctors_information')) :
	class usa_doctors_information{			
		//states
		var $states = array('Alabama','Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West','Virginia','Wisconsin','Wyoming');
		//professions
		var $categories = array('Psychologist','Neuropsychologist');
		//var $categories = array('Psychologist','Neuropsychologist');
		var $subprof = array('Pediatric', ' Adults', ' Older Adult');
		//language
		var $languages = array('English','Spanish','French','German','Hebrew','Chinese','Japanese','Portuguese','Russian','Arabic','Hindi/Urdu','Bengali','Malay-Indonesian');
		
		function subprofing(){
			$sub = '';
			foreach($this->subprof as $value){
				$sub .= "<input name='doctor[subcat][]' type='checkbox' value='$value' />$value";
			}
			return $sub;
		}
		
		//language pack
		function languagepack($packs,$c){
			$name = ($c)?'docmeta[lang][]':'doctor[lang][]';
			if(is_array($packs)){
				$sub = '';
				foreach($this->languages as $value){
					$value = trim($value);
					$sub .= "<input name='$name' type='checkbox' value='$value'".$this->checking($packs,strtolower($value))." />$value".'&nbsp &nbsp';
				}
				return $sub;
			}
		}
		
		//for admin
		function sub_profing($check){
			if(is_array($check)){				
				$sub = '';				
				foreach($this->subprof as $key=>$value){					
					$value = trim($value);				
					$keying = $key+1;
					$sub .= "<input name='docmeta[subcat$keying]' type='checkbox' value='$value'".$this->checking($check,strtolower($value))." />$value";
				}
				return $sub;
			}
			
		}
		
		//for users
		
		function sub_profinggg($check){
			if(is_array($check)){				
				$sub = '';				
				foreach($this->subprof as $key=>$value){					
					$value = trim($value);				
					$keying = $key+1;
					$sub .= "<input name='doctor[subcat][]' type='checkbox' value='$value'".$this->checking($check,strtolower($value))." />$value".'&nbsp &nbsp';
				}
				return $sub;
			}
			
		}
		
		function checking($check,$value){
			
			foreach($check as $c){
				if(strtolower($c)==$value){
					$a = "checked='checked'";
					return $a;
				}
				else{
					$a='';
				}
			}
			return $a;
		}
		
		function creating_opt($sel=''){
			$new = '';
			$sel = strtolower($sel);
			foreach($this->states as $state){
				$new .= "<option value='$state'".selected($sel,strtolower($state))." >$state</option>";
			}
			return $new;
		}
		
		function creating_category($cat=''){
			$new = '';
			$cat = strtolower($cat);
			foreach($this->categories as $c){
				$new .= "<option value='$c'".selected($cat,strtolower($c))." >$c</option>";
			}
			return $new;
		}
		
		//taxonomy inserting
		function pluginSetuptaxonomy(){
			//creating psychologists table
			global $wpdb;
			$table = $wpdb->prefix.'psychologist';
			$sql = "CREATE TABLE IF NOT EXISTS `$table`(
				`id` bigint unsigned NOT NULL AUTO_INCREMENT,	
				`post_id` bigint unsigned NOT NULL,			
				`email` varchar(150) NOT NULL,				
				PRIMARY KEY(id),
				UNIQUE(email)
				)";
			//loading the dbDelta function manually
			require_once(ABSPATH.'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
									
			//term insertion			
			foreach($this->categories as $category){
				global $wpdb;
				$category = trim($category);
				$slug = strtolower(str_replace(' ','-',$category));
								
				$termid = $wpdb->get_var("SELECT `term_id` FROM $wpdb->terms WHERE `slug`='$slug'");
				if($termid){
					$wpdb->update($wpdb->term_taxonomy,array('taxonomy'=>'psy-neupsy'),array('term_taxonomy_id'=>$termid),array('%s'),array('%d'));
				}
				else{
					$wpdb->insert_id = null;
					$wpdb->insert( $wpdb->terms,array('name'=>$category,'slug'=>$slug), array('%s','%s'));
					if($wpdb->insert_id) :
						$wpdb->insert( $wpdb->term_taxonomy,array('term_id'=>$wpdb->insert_id,'taxonomy'=>'psy-neupsy'), array('%d','%s'));
					endif;
					$wpdb->insert_id = null;
				}				
								
			}
			 
		}	

	
		
		//javascript
		function javascript_adition(){
				
				wp_enqueue_script('jquery');
				wp_enqueue_script('us_doctors',plugins_url('/',__FILE__).'js/doctors.js',array('jquery'));
				wp_enqueue_script('us_doctors-admin',plugins_url('/',__FILE__).'js/admindoc.js',array('jquery'));
				$nonce = wp_create_nonce('wp-psychologists');
				wp_localize_script( 'us_doctors','PsyAjax', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce' => $nonce	
					
				));					
			
		}
		// css addition
		function css_adition(){							
				wp_register_style('us_doctors-css',plugins_url('/',__FILE__).'css/style.css');
				wp_enqueue_style('us_doctors-css');		
				wp_enqueue_style('us_doctors-css');		
		}
		
		
		//meta box
		function advanced_boxex(){		
					
			add_meta_box('doctor-location',__('Practice Locations'),array($this,'locations'),'psychologist','normal','high');
			add_meta_box('doctor-contact',__('Conatact Information'),array($this,'email'),'psychologist','normal','high');
			add_meta_box('doctor-qualification',__('Educational Background'),array($this,'background'),'psychologist','normal','high');
			add_meta_box('doctor-degrees',__('Degrees/Practice Focus(comma separated)'),array($this,'degree'),'psychologist','normal','high');
			add_meta_box('doctor-langulages',__('Languages Spoken Fluently'),array($this,'lang'),'psychologist','normal','high');
			add_meta_box('doctor-stupid',__('Psycholoigst/Neuropsychologist'),array($this,'stupid'),'psychologist','normal','high');
		}		
		
		
		function stupid(){
			global $post;			
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
			
			$stupid = $metadata['stupid'];	
		?>
		
		Please check (if not checked) the correct psycologist/neuropsychologist(only for sinup) at the right side of your admin panel
		<h2><input name ="docmeta[stupid]" type="text" readonly="readonly" value="<?php echo $stupid; ?>" /></h2>
		
		<?php 
		}
		function email(){
			global $post;
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
				$email = ($metadata['email'])?$metadata['email']:'';
				$web = ($metadata['website'])?$metadata['website']:'';
				$phone = ($metadata['telephone'])?$metadata['telephone']:'';
				$email_hide = ($metadata['email_hide'])?$metadata['email_hide']:'';
			?>
			Email
			<input id="doc-email" type="text"  value="<?php echo $email; ?>" name="docmeta[email]" />
			<br/>
			<input type="hidden" name="docmeta[email_hide]" value ="<?php echo $email_hide; ?>" />
			Website
			<input id="doc-website" type="text"  value="<?php echo $web; ?>" name="docmeta[website]" />
			<br/>
			Phone Number
			<input id="doc-phone" type="text"  value="<?php echo $phone; ?>" name="docmeta[phone]" />
			
			
		<?php	
		}
		
		function background(){
			global $post;
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
			$internship = ($metadata['internship'])?$metadata['internship']:'';			
			$trainig = ($metadata['training'])?$metadata['training']:'';			
			$fellow = ($metadata['fellowship'])?$metadata['fellowship']:'';			
			
		?>
			Internship<input id="doc-internship" type="text"  value="<?php echo $internship; ?>" name="docmeta[internship]" />
			<br/>
			Doctoral Training<input id="doc-training" type="text"  value="<?php echo $trainig; ?>" name="docmeta[training]" />
			<br/>
			Fellowship <input id="doc-fellowship" type="text"  value="<?php echo $fellow; ?>" name="docmeta[fellowship]" />
			
		
		<?php	
		}
		function degree(){
			global $post;
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
			if(!is_array($metadata)){
				$metadata = array();
				$metadata['degree']='';
				//$metadata['practice']='';
				$metadata['subcat']=array('a','b','c');
			}
			$degree = ($metadata['degree'])?$metadata['degree']:'';
			//$practice = ($metadata['practice'])?$metadata['practice']:'';
			$subcat = ($metadata['subcat'])?$metadata['subcat']:array('a','b','c');
		?>			
			Degrees (MBBS,FCPS...)<br/> <input id="doc-degreee" type="text"  value="<?php echo $degree ; ?>" name="docmeta[degree]" />	
			<br/><br/>			
			
			Speciality area<br/> <?php echo $this->sub_profing($subcat); ?>		
		
		<?php	
		}
		
		
		function lang(){
			global $post;
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
			if(!is_array($metadata)){
				$metadata = array();
				$metadata['lang'] = array('a','b');
			}
			$langs = $metadata['lang'];
			echo $this->languagepack($langs,true);
		}
		
		function hospital(){
			global $post;
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
			if(!is_array($metadata)){
				$metadata = array();
				$metadata['hospitals'] = array('','','','');
			}
			for($i=0;$i<3;$i++){
				$hospital = ($metadata['hospitals'][$i])?$metadata['hospitals'][$i]:'';
		?>			
				
			hospital(practice location<?php echo $i+1; ?>) <textarea name="docmeta[hospital][]" cols="20" rows="2" ><?php echo $hospital; ?></textarea>
			<br/>			
		
		<?php	
			}
		}
		
		function locations(){
			global $post;
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
			if(!is_array($metadata)){
				$metadata = array();
				$a = array('','','');
				$metadata['states'] = $a;
				$metadata['cities'] = $a;
				$metadata['zips'] = $a;
			}			
				
			for($i=0;$i<2;$i++){
				$j = $i+1;
				$state = ($metadata['states'][$i])?$metadata['states'][$i]:'';
				$city = ($metadata['cities'][$i])?$metadata['cities'][$i]:'' ;
				$zip = ($metadata['zips'][$i])?$metadata['zips'][$i]:'' ;
		?>			
			State<?php echo $j; ?> <select name="docmeta[state][]">
				<?php echo $this->creating_opt($state); ?>								
			</select>
			<br/>
			City<?php echo $j; ?> <input type="text" id="<?php echo 'city'.$i ;?>" value="<?php echo $city ?>" name="docmeta[city][]" />
			<br/>
			Zip<?php echo $j; ?> <input type="text"  value="<?php echo $zip; ?>" name="docmeta[zip][]" />		
			<br/>
			
		<?php	
			} //end for loop
		}	
		
		
			
		
		function javascript_adition_ewo(){
			wp_enqueue_script('jquery');
			wp_enqueue_script('us_doctors_posts',plugins_url('/',__FILE__).'js/doc.js',array('jquery'));
		}
		
		//custom post types
		function custom_post_creation(){

				$args = array(
				'public' => true,
				//'query_var' => 'psychologist_',
				'query_var' => true,
				'rewrite' => array('with_front'=>false,'slug'=>'psychologist-neuropsychologist','pages'=>true),
				
				'supports' => array('title','editor','author','thumbnail','custom-fields'),
				'has_archive' => true,
				'hierarchical' => true,
				
				'labels' => array(
					'name' => 'Psychologists',
					'singular_name' => 'psychologist',
					'add_new' => 'Add New',
					'add_new_item' => 'Add New Psychologist/Neuropsychologist',
					'edit_item' => 'Edit Psychologists/Neuropsychologists',
					'new_item' => 'New Psychologist',
					'view_item' => 'View pshchologist/Neuropsychologists',
					'search_items' => 'Search Psychologists & Neuropsychologist',
					'not_found' => 'No Psychologists or Neuropsychologists Found',
					'not_found_in_trash' => 'No Psychologists or Neuropsychologists Found In Trash'
				),				
				
				'taxonomies' => array( 'post_tag','psy-neupsy')

			);			
			register_post_type('psychologist',$args);			
		}
		
		function custom_texanomy_creation(){
			$labels = array(
				'name' => 'Psychologist/Neuropsychologist', 
				'search_items' =>  __( 'Search psy-neupsys' ),
				'all_items' => __( 'All psy-neupsys' ),
				'parent_item_colon' => __( 'Parent psy-neupsy:' ),
				'edit_item' => __( 'Edit psy-neupsy' ), 
				'update_item' => __( 'Update psy-neupsy' ),
				'add_new_item' => __( 'Add New psy-neupsy' ),
				'new_item_name' => __( 'New psy-neupsy Name' ),
				'menu_name' => __( 'psy-neupsy' ),				
			  );
			 register_taxonomy('psy-neupsy',array('psychologist'), array(
				'hierarchical' => true,
				'labels' => $labels,
				'public' => true,
				'show_ui' => true,
				'query_var' => true,
				//'rewrite' => array( 'slug' => 'psy-neupsy','with_front' =>true,),
				'rewrite' => true,				
				
			  ));
		  }
		  
		  //function to hide the email field manipulation
		 function hide_the_email(){
			
		}

			
	}
	
	

	
	$us_doctors = new usa_doctors_information();	
	
	register_activation_hook( __FILE__, array($us_doctors,'pluginSetuptaxonomy'),9);
	//register_deactivation_hook( __FILE__,array($us_doctors,'pluginDeactivate'));
	add_action('wp_print_scripts',array($us_doctors,'javascript_adition'));
	add_action('wp_print_styles',array($us_doctors,'css_adition'),100);	
	add_action('admin_enqueue_scripts',array($us_doctors,'css_adition'),100);	
	add_action('add_meta_boxes',array($us_doctors,'advanced_boxex'));	
	
	add_action('admin_print_scripts-post.php',array($us_doctors,'javascript_adition_ewo'));
	add_action('admin_print_scripts-post-new.php',array($us_doctors,'javascript_adition_ewo'));	
	
	add_action('init',array($us_doctors,'custom_post_creation'));
	add_action('init',array($us_doctors,'custom_texanomy_creation'));
endif;
//data insertion calass
include('wpdb-data-insertion.php');

//form-data manipulation class
include('form-validation.php');


//adding search form class
include('plugins-settings.php');
//custom
include('custom-post-type-snippet.php');

?>
