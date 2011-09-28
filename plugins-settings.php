<?php
/*settings's api 
 * plugin author: Md Mahibul Hasan
 * 
 * */
 
 if(!class_exists('settings_api_demo')) : 
	class settings_api_demo{
		
		var $confmessage = "Thank You For Your Information,ID Number has been sent to your email.If your provided information is okay,please do not hit the sumbit button agian";
		var $confmail ="Thank You for your information.Admin will Check your information and publish it.Once your information is checked by the admin,you can edit your information with this ID Number.";
		var $updatemail = "Thank You for updating your information.Admin will Check your updates and publish it.Once your information is checked by the admin,you can edit your information further using this ID Number";
		
		//creating an options page
		function optionsPage(){
			add_options_page('settings page for psycologists','Psychologists\' Settings','activate_plugins','settings-api',array($this,'optionsPageDetails'));
		}
		
		//creating options page in admin panel
		function optionsPageDetails(){
			//starin html form
		?>
			<div class="wrap">
				<?php screen_icon('options-general'); ?>
				<h2>Psychologists & Neuropsychologists</h2>
				<form action="options.php" method="post">
					<?php
						settings_fields('setting_api_demo');
						do_settings_sections('settings-api');
					?>
					<input type="submit" class="button-primary" value="submit" />
					<br/>
					<br/>
					<br/>
					<input type="button" value="Blast Email" class="button-primary" id="basetemailservice" >
					<br/><br/>
					<span id="blastclass" class="customtextarea"> Subject <br/><input id="blustsubject" type="text" /><br/>Content <br/><textarea cols="60" rows="6" id="balstmail"></textarea><br/><input type="button" value="send" id="sendmail" class="button-secondary" /></span> 
				</form>
			</div>
			
		<?php
			
		}
				
		//registering options
		function registerOption(){
			register_setting('setting_api_demo','settings_api',array($this,'data_validation'));
			//add_settings_section('first_section','',array($this,'first_settings_section'),'settings-api');
			add_settings_section('first_section','',array($this,'first_settings_section'),'settings-api');
			add_settings_field('first_input','Search page\'s url',array($this,'first_settings_field'),'settings-api','first_section');
			add_settings_field('second_input','Registration page\'s url',array($this,'second_settings_field'),'settings-api','first_section');
			add_settings_field('third_input','Psychologists\' home page\'s url',array($this,'third_settings_field'),'settings-api','first_section');
			add_settings_field('fourth_input','Admins\' Emails(comma separated)',array($this,'fourth_settings_field'),'settings-api','first_section');
			
			add_settings_field('fifth_input','Confirmation Message',array($this,'fifth_settings_field'),'settings-api','first_section');
			add_settings_field('sixth_input','Confirmation Email Message',array($this,'sixth_settings_field'),'settings-api','first_section');
			add_settings_field('seventh_input','Confiramtion Email Message,if the existing information is edited by a user',array($this,'seventh_settings_field'),'settings-api','first_section');
			add_settings_field('eighth_input','Sender Mail Address',array($this,'eighth_settings_field'),'settings-api','first_section');
			//add_settings_field('nineth_input','Blast Email Service',array($this,'nineth_settings_field'),'settings-api','first_section');
		}
		
		//first_settings_field for the first sections
		function first_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['searchform'];
			echo "<input style='width:362px' type='text' name='settings_api[searchform]' value='$text' />";
		}
		
		function second_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['informationform'];
			echo "<input style='width:362px' type='text' name='settings_api[informationform]' value='$text' />";
		}
		
		function third_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['psychologistpage'];
			echo "<input style='width:362px' type='text' name='settings_api[psychologistpage]' value='$text' />";
		}
		
		//first settins sections callback
		function first_settings_section(){
			//echo '<p>This is the first Section</p>';
		}
		
		function fourth_settings_field(){
			$text_array = get_option('settings_api');
			$texts = $text_array['emails'];
			$text = '';
			if($texts) :
				foreach($texts as $t){
					$text .= $t.',';
				}
			endif;
			$text = trim($text,',');
			
			//echo "<input type='text' name='settings_api[errormessage]' value='$text' />";
			echo "<textarea cols='40' rows='3' name='settings_api[emails]'>$text</textarea>";
		}
		
		function fifth_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['confmessage'];			
			
			//echo "<input type='text' name='settings_api[errormessage]' value='$text' />";
			echo "<textarea cols='40' rows='3' name='settings_api[confmessage]'>$text</textarea>";
		}
		
		function sixth_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['confmail'];			
			
			//echo "<input type='text' name='settings_api[errormessage]' value='$text' />";
			echo "<textarea cols='40' rows='3' name='settings_api[confmail]'>$text</textarea>";
		}
		
		function seventh_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['updatemail'];			
			
			//echo "<input type='text' name='settings_api[errormessage]' value='$text' />";
			echo "<textarea cols='40' rows='3' name='settings_api[updatemail]'>$text</textarea>";
		}
		
		function eighth_settings_field(){
			$text_array = get_option('settings_api');
			$text = $text_array['to'];			
			
			//echo "<input type='text' name='settings_api[errormessage]' value='$text' />";
			echo "<input style='width:362px' name='settings_api[to]' value='$text' />";
		}
			
		
		function checkingdata($a,$b){
			return (strlen($a)>10)? $a : $b;
		}
		//validating data
		function data_validation($data){			
			
			$valid = array();
			$valid['confmessage'] = $this->checkingdata(strip_tags($data['confmessage']),$this->confmessage);
			$valid['confmail'] = $this->checkingdata(strip_tags($data['confmail']),$this->confmail);
			$valid['updatemail'] = $this->checkingdata(strip_tags($data['updatemail']),$this->updatemail);
			
			$valid['searchform'] = trim(trim($data['searchform']),'/');
			$valid['informationform'] = trim(trim($data['informationform']),'/');
			$valid['psychologistpage'] = trim(trim($data['psychologistpage']),'/');			
			$valid['errormessage'] = strip_tags($data['errormessage']);
			//$valid['from'] = trim($data['from']);
			$valid['to'] = trim($data['to']);
			
			//sanitizing			
			$valid['searchform'] = 	($valid['searchform'])?$valid['searchform']:get_option('home');		
			$valid['informationform'] = ($valid['informationform'])?$valid['informationform']:get_option('home');		
			$valid['psychologistpage'] = ($valid['psychologistpage'])?$valid['psychologistpage']:get_option('home');
			if(strlen($data['emails'])>5){
				$emails = explode(',',$data['emails']);
				foreach($emails as $email){
					$email = trim($email);
					if(!is_email($email)){
						continue;
					}
					$valid['emails'][] = $email;
				}	
			}			
			return $valid;
		}
	}
	
	$settings_api = new settings_api_demo();
	add_action('admin_menu',array($settings_api,'optionsPage'));
	add_action('admin_init',array($settings_api,'registerOption'));
endif;

?>
