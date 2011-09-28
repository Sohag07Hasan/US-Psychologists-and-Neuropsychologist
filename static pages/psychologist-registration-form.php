<?php
/*
* template name: Registration-form
* */
//globaling the plugin
global $us_doctors;
global $wpdb_doc_data;
global $wpdb;
$table = $wpdb->prefix.'psychologist';

$subcat = array('a','b','c');
$states = array('','','');
$cities= array('','','');
$zips = array('','','');
$hospitals = array('','','');

$hidden = '';
$disabled = '';
$degree = '';
$fname = '';
$lname = '';
$email = '';
$intership = '';
$postdoc = '';
$fellow = '';
$web = '';
$aboutme = '';
$phone = '';
$error = array();
$erms = '';
$post_id = '';
$category = '';
$erms = '';
$conf = '';
$languages = array('a','b');
$errao = '';

if($_REQUEST['pid']){
	$id = preg_replace('/[^1-9]/','',$_REQUEST['pid']);
	$id = (int)$id;
	$mail = strtolower(trim($_REQUEST['mail']));
	
	if(is_email($mail)){
		//$idcheck = $wpdb->get_var("SELECT `id` FROM $table WHERE `post_id`=$id");
		$met = get_post_meta($id,'_doctorsdata',true);
		
		$metaemail = $met['email'];
		
		
		//$checkmail = $wpdb->get_var("SELECT `email` FROM $table WHERE `post_id`=$id");
			
		if(strtolower($metaemail) != strtolower($mail)){
				$error['email'] = 3;
		}				
		
	}
	else{
		$error['email'] = 2;
	}	
	
	if(count($error)>0){		
		
		if($error['email'] == 3){
			$emailmessage = 'Sorry! Your Email and ID number do not match';
		}
		
		if($error['email'] == 2){
			$emailmessage = 'Sorry! This is not a valid Email.Please Check the email and try again';
		}
		$erms = '<div id="messageing" class="errorss"><p>'.'<br/>'.$emailmessage.'</p></div>';
		
	}	
	
	else{
		//populating the form table
		$postdata = get_post($id,ARRAY_A);
		$metadata = get_post_meta($id,'_doctorsdata',true);
		if(is_array($postdata)){
			$name = explode(' ',trim($postdata['post_title']));			
			$fname = $name[0];
			$lname = $name[1];
			$aboutme = $postdata['post_content'];
		}
		if(is_array($metadata)){
			$email = $metadata['email'];
			$phone = $metadata['telephone'];
			$web = $metadata['website'];
			$intership = $metadata['internship'];
			$postdoc = $metadata['training'];
			$fellow = $metadata['fellowship'];
			$degree = $metadata['degree'];
			$subcat = $metadata['subcat'];
			$category = $metadata['stupid'];
			$states = $metadata['states'];
			$cities = $metadata['cities'];
			$zips = $metadata['zips'];
			$languages = $metadata['lang'];
			$hidden = $wpdb_doc_data->randomstring().$id;
			$hi = $wpdb_doc_data->randomstring().$id;
			$email_hyde = ($metadata['email_hide'] == 'yes')? "checked='checked'" : '';			

		}
	}
}


?>

<?php 
	//confirmation message
	
	if($_REQUEST['update']){
		
		$cookie = $_COOKIE["uspsycholoigst"];
		
		$cookieid = preg_replace('/[^0-9]/','',$cookie);
		
		$cookieid = (int)$cookieid;
		$okay = preg_replace('/[^a-z]/','',$_REQUEST['message']);
		$id = preg_replace('/[^0-9]/','',$_REQUEST['id']);
		$id = (int)$id;
		if($okay=='okay'){
			if($id == $cookieid){
				
				$postdata = get_post($id,ARRAY_A);
				$metadata = get_post_meta($id,'_doctorsdata',true);
				if(is_array($postdata)){
					$name = explode(' ',trim($postdata['post_title']));;
					$fname = $name[0];
					$lname = $name[1];
					//$name = $postdata['post_title'];
					$aboutme = $postdata['post_content'];
				}
				if(is_array($metadata)){
					$email = $metadata['email'];
					$phone = $metadata['telephone'];
					$web = $metadata['website'];
					$intership = $metadata['internship'];
					$postdoc = $metadata['training'];
					$fellow = $metadata['fellowship'];
					$degree = $metadata['degree'];
					$subcat = $metadata['subcat'];
					$category = $metadata['stupid'];
					$states = $metadata['states'];
					$cities = $metadata['cities'];
					$zips = $metadata['zips'];
					$languages = $metadata['lang'];
					$hidden = $wpdb_doc_data->randomstring().$id;
						
					$email_hyde = ($metadata['email_hide'] == 'yes')? "checked='checked'" : '';	

				}
			}
		}
		
		$message = preg_replace('/[^a-z]/','',$_REQUEST['update']);
		if($message == 'updated'){
			$text_array = get_option('settings_api');
			$text = $text_array['confmessage'];	
			
			$conf = '<div id="messageing" class="updateds"><p>'.$text.'</p></div>';
		}						
				
	}
	
	if($_REQUEST['errormessage']){		
		$messages = preg_replace('/[^a-zA-z]/',',',$_REQUEST['errormessage']);
		$errors = explode(',',$messages);
		foreach($errors as $er){
			if($er == 'regemail'){
				$errao = '<div id="messageing" class="errorss"><p>Sorry! Your email has already been registered  .Please try with different email!</p></div>';
			} 
		}
		if(strlen($errao)<5){		
			$errao = '<div id="messageing" class="errorss"><p>Please fillup the form again with correct '.$messages.' !</p></div>';
		}
	}
	else{
		$errao = '';
	}
?>


<?php get_header(); ?>
<div id="content" class="col-full">
		<div id="main" class="fullwidth">
<div class="post">

<p class="alreadyr">Already registered? <a id="already" href="#"> Click here </a></p>



<?php edit_post_link('edit','<span class="the_edit_link">','</span>') ?>
<h2 class="title">
	<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
		<?php the_title(); ?>
	</a>
</h2>
<?php
	echo $errao;
	echo $conf;
	echo $erms;
	$sec = (strlen($hi)>3)?'readonly="readonly"':'';
?>

<div class="wrapping">

<div id="checkform" class="formhidden">
	<form method="post" action="">
		<p class="tracinghidden">Please Insert Your ID Number & email. <a class="forget" href="#"> Click here to get your ID number.</a></p>
		ID Number<input name="pid" type="text" id="tracingnum" value="" />		
		Email Address<input name="mail" type="text" id="emailchecking" value="" />		
		<input class="submitcheck" type="submit" value="find" />		
				
	</form>
</div>
	
	<form method="post" action="">
		<table class="form-tabletable">
			<tr valign="top"><th scope="row">NAME*</th>			
				<td>First Name <br/><input id="firstname" name="doctor[firstname]" type="text" value="<?php echo $fname; ?>" /></td>
				<td>Last Name<br/><input id="lastname" name="doctor[last]" type="text" value="<?php echo $lname; ?>" /></td>
			</tr>
			<tr valign="top"><th scope="row">Educational <br/> Background</th>			
				<td>Internship<br/><input name="doctor[internship]" type="text" value="<?php echo $intership; ?>" /></td>
				<td>Doctoral Training<br><input name="doctor[doc-training]" type="text" value="<?php echo $postdoc; ?>" /></td>				
				<td>Post Doctoral Fellowship<br/><input name="doctor[fellow]" type="text" value="<?php echo $fellow; ?>" /></td>
			</tr>
			<tr valign="top"><th scope="row">Degrees<br/>(comma separated)</th>			
				<td colspan="3">Degrees (Ph.D., Psy.D., M.A., M.S., M.D., D.O., etc.)<br/><input name="doctor[degree]" type="text" value="<?php echo $degree; ?>" /></td>
			</tr>
			<tr valign="top"><th scope="row">Professional Role</th>
				<td>Category<br/>
					<select name="doctor[category]">						
						<?php
							echo $us_doctors->creating_category($category);
						?>
						
					</select>
				</td>
				<td colspan="2">Speciality Area<br/>
				<?php echo $us_doctors->sub_profinggg($subcat); ?>	
				</td>				
			</tr>
			<?php 
				for($i=0;$i<2;$i++){
				$j = $i+1;				
				$star = ($j==1)?'*':'';
				$state = ($states[$i])?$states[$i]:'';
				$city = ($cities[$i])?$cities[$i]:'' ;
				$zip = ($zips[$i])?$zips[$i]:'' ;
				echo '<tr valign="top"><th scope="row">Practice Location '.$j.' '.$star.'</th>';
		?>	
			
			<td>State <?php echo $j.$star; ?><br/> <select name="doctor[state][]">
				<?php echo $us_doctors->creating_opt($state); ?>								
			</select></td>
			
			<td>City <?php echo $j.$star; ?><br/> <input type="text" id="<?php echo 'city'.$j ;?>" value="<?php echo $city ?>" name="doctor[city][]" />
			</td>
			<td>Zip <?php echo $j.$star.' (5 digits)'; ?><br/> <input type="text" id="<?php echo 'zip'.$j ;?>" value="<?php echo $zip; ?>" name="doctor[zip][]" />		
			</td>
			</tr>
			
		<?php	
			} //			
		?>	
		
			
			<tr valign="top"><th scope="row">Email Address*</th>			
				<td>Email*<br/><input id="email" name="doctor[email]" type="text" value="<?php echo $email; ?>" <?php echo $sec; ?> /></td>
				<td colspan="2">If checked, email is not publicly visible<br/>
				<input type="checkbox" value="yes" name="doctor[email_hide]" <?php echo $email_hyde; ?> /></td>
				
			</tr>
			<tr valign="top"><th scope="row">Contact Information</th>				
				
				<td>Website<br/><input name="doctor[website]" type="text" value="<?php echo $web; ?>" /></td>
				<td>Telephone* (no dashes xxxxxxxxxx)<br/><input id="telephone" name="doctor[telephone]" type="text" value="<?php echo $phone; ?>" /></td>							
			</tr>
			<tr valign="top"><th scope="row">Languages Spoken Fluently</th>			
				<td colspan="3"> <?php echo $us_doctors->languagepack($languages,false); ?> </td>							
			</tr>
			<tr valign="top"><th scope="row">About Yourself</th>				
				<td colspan="2">
					Maximum of 50 words<br/>
					<textarea cols="50" rows="4" id="abouturself" name="doctor[description]"><?php echo $aboutme; ?></textarea>
				</td>				
			</tr>						
			
		</table>
		<input id="hiddencheck" type="hidden" name="doctor[hidden]" value="<?php echo $hidden; ?>"/>
		<input id="doctor-form-submit" name="us-doctor-sumbit" type="submit" value="Submit"/>		
		<input type="reset" value="Reset"/>		
	</form>
</div>
</div>
</div></div>
<?php get_footer(); ?>

