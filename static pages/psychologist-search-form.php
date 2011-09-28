<?php /* Template Name: psychologists' search-form */ ?>
<?php get_header(); ?>
	<div id="content" class="col-full">
		<div id="main" class="fullwidth">
					<div class="post">
						<?php edit_post_link('edit','<span class="the_edit_link">','</span>') ?>
						<h2 class="title">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
								<?php the_title(); ?>
							</a>
						</h2>
						<div id="seach_id" class="wrapping">
	<form method="post" action="">
	<table class="form-tabletable">
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr valign="top"><th scope="row"><h3>Find Your Location</h3></th>		
			
			<td>&nbsp; &nbsp; State*<br/>
					&nbsp; &nbsp;<select name="find[state]"><option value='Alabama' >Alabama</option><option value='Alaska' >Alaska</option><option value='Arizona' >Arizona</option><option value='Arkansas' >Arkansas</option><option value='California' >California</option><option value='Colorado' >Colorado</option><option value='Connecticut' >Connecticut</option><option value='Delaware' >Delaware</option><option value='Florida' >Florida</option><option value='Georgia' >Georgia</option><option value='Hawaii' >Hawaii</option><option value='Idaho' >Idaho</option><option value='Illinois' >Illinois</option><option value='Indiana' >Indiana</option><option value='Iowa' >Iowa</option><option value='Kansas' >Kansas</option><option value='Kentucky' >Kentucky</option><option value='Louisiana' >Louisiana</option><option value='Maine' >Maine</option><option value='Maryland' >Maryland</option><option value='Massachusetts' >Massachusetts</option><option value='Michigan' >Michigan</option><option value='Minnesota' >Minnesota</option><option value='Mississippi' >Mississippi</option><option value='Missouri' >Missouri</option><option value='Montana' >Montana</option><option value='Nebraska' >Nebraska</option><option value='Nevada' >Nevada</option><option value='New Hampshire' >New Hampshire</option><option value='New Jersey' >New Jersey</option><option value='New Mexico' >New Mexico</option><option value='New York' >New York</option><option value='North Carolina' >North Carolina</option><option value='North Dakota' >North Dakota</option><option value='Ohio' >Ohio</option><option value='Oklahoma' >Oklahoma</option><option value='Oregon' >Oregon</option><option value='Pennsylvania' >Pennsylvania</option><option value='Rhode Island' >Rhode Island</option><option value='South Carolina' >South Carolina</option><option value='South Dakota' >South Dakota</option><option value='Tennessee' >Tennessee</option><option value='Texas' >Texas</option><option value='Utah' >Utah</option><option value='Vermont' >Vermont</option><option value='Virginia' >Virginia</option><option value='Washington' >Washington</option><option value='West' >West</option><option value='Virginia' >Virginia</option><option value='Wisconsin' >Wisconsin</option><option value='Wyoming' >Wyoming</option></select>
			</td>
			<td>&nbsp; &nbsp; City <br/> <input id="city" name="find[city]" type="text" value="" /></td>
			<td>&nbsp; &nbsp; Zip Code (first 5 digits)<br/> <input id="zip" name="find[zip]" type="text" value="" /></td>							
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		
		<tr valign="top"><th scope="row"><h3>Select Your Criteria</h3></th>
		<td>&nbsp; &nbsp; Category<br/>&nbsp;&nbsp; <select name="find[category]">							
				<option value="">Pick One</option>
				<option value="Psychologist">Psychologist</option>
				<option value="Neuropsychologist">Neuropsychologist</option>
			</select></td>
			<td>&nbsp; &nbsp; Sub Category<br/>
				&nbsp; &nbsp; <select name="find[subcat]">
					<option value="all">all</option>
					<option value="Pediatric">Pediatric</option>
					<option value="Adults">Adults</option>
					<option value="Older Adult">Older Adult</option>
					
				</select>
			</td>											
		</tr>
	
	</table>
	<input id="find-doctor" type="submit" value="Find" name="find_professionals" />					
	<input type="reset" value="Reset"  />
	<br/>
	<h3>* Required</h3>					
	</form>
</div>
					</div>
				
		</div>
	</div>
<?php get_footer(); ?>
