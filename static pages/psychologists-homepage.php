<?php
/*
Template Name: us neurologists and psychologists
*/

?>
<?php
	get_header();
	
?>

<?php
	function getting_data($slug){
		global $wpdb;
		return $wpdb->get_var("SELECT `name` FROM $wpdb->terms WHERE `slug`='$slug'");
	}
	//controlling loop
	if($_REQUEST['state']){	
				
		$category = preg_replace('/[^a-zA-Z]/','',trim($_REQUEST['category']));
		$city = strtolower(preg_replace('/[^A-Za-z0-9-]/','',trim($_REQUEST['city'])));
		$state = strtolower(preg_replace('/[^A-Za-z0-9-]/','',trim($_REQUEST['state'])));
		$zip = preg_replace('/[^0-9]/','',$_REQUEST['zip']);
		$subcat = $subcat = strtolower(preg_replace('/[^a-zA-Z-]/','',trim($_REQUEST['subcat'])));
		
		 $args = array(
			'post_type' => 'psychologist',
			'posts_per_page'=>-1,
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'tax_query' => array(
				'relation' => 'AND',
				
				array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => $state
				),				
								
			)
			
				
			);
			
			if(strlen($category)>4){
				$args['tax_query'][] = array(
					'taxonomy' => 'psy-neupsy',
					'field' => 'slug',
					'terms' => $category,					
				);
				$c = getting_data($category).'s';
			}
			else{
				$c = 'Psychologists & Neuropsychologists';
			}
			
			$city_r = '';
			$zip_r = '';
		
		if(strlen($city)>2){
			$args['tax_query'][] = array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => $city
				);
				$city_r = ' , '.getting_data($city);
		}
		if(strlen($zip)>2){
			$args['tax_query'][] = array(
					'taxonomy' => 'post_tag',
					'field' => 'slug',
					'terms' => $zip
				);
				$zip_r = ' & '.$zip;
		}
		
		if($subcat != 'all'){		
			$args['tax_query'][] = array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => $subcat
			);						
		}
				
			
		$message = '<div id="messageing" class="updateds">Search Results : '.$c.' '.'tagged with '.getting_data($state).$city_r.$zip_r.'</div>';
				
	}
	else{
		$args = array( 'post_type' => 'psychologist', 'posts_per_page' => 50,'post_status'=>'publish','orderby' => 'rand','order' => 'DESC');
		$message = '';
	}
	//starting loop
	
	$loop = new WP_Query($args);
	//var_dump($loop->posts);	
	//exit;	
	
 ?>
 
<div id="content" class="col-full">
	<div id="main" class="col-left">
	

<?php if ( ! $loop->have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

	<div class="pagination">
<?php if($loop->have_posts()) :
	echo $message;
	while ( $loop->have_posts() ) : $loop->the_post(); ?>
	
	<div class="newpost">
	
	<h2 class="entry-title"><a  class ="entry-doc" href="" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php echo strtoupper(get_the_title()); ?></a></h2>
	<hr/>
	
		<?php
			//meta data for psychologists and neuropsychologists
			$metadata = get_post_meta($post->ID,'_doctorsdata',true);
						
			$terms = wp_get_object_terms($post->ID,'psy-neupsy');			
			//var_dump($terms);
			$subcat = '';
			if(is_array($metadata['subcat'])) : 
				foreach($metadata['subcat'] as $value){
					if(strlen($value)<3) continue;
					$subcat .=  $value.',';
				}
				$subcat = trim($subcat,',');
			endif;
			$languages = '';
			if(is_array($metadata['lang'])){
				foreach($metadata['lang'] as $va){
					$languages .= $va.',';
				}
				$languages = trim($languages,',');
			}		
						
		?>
		
		<div class="degree-contact">
			<h4>Professional Information</h4>
			Category: <span class="italicstyle"><?php echo $terms[0]->name ;?></span><br/>
			Degrees: <span class="italicstyle"><?php echo $metadata['degree']; ?></span><br/>
			Internship: <span class="italicstyle"><?php echo $metadata['internship']; ?></span><br/>
			Post Doctoral Training : <span class="italicstyle"><?php echo $metadata['training']; ?></span><br/>
			Fellowship: <span class="italicstyle"><?php echo $metadata['fellowship']; ?></span><br/>
			Speciality Area : <span class="italicstyle"><?php echo $subcat; ?></span><br/>
			Languages: <span class="italicstyle"><?php echo $languages; ?></span><br/>
			<br/>
			<h4>Contact Information</h4>
			<?php if($metadata['email_hide'] !='yes') { ?>	
			Email: <span class="italicstyle"><?php echo $metadata['email']; ?></span><br/>	
			<?php } ?>
				
			Telephone : <span class="italicstyle"><?php echo $metadata['telephone']; ?><span><br/>
			Website: <span class="italicstyle"><?php echo $metadata['website']; ?></span><br/>			
			About me: <span class="italicstyle"><?php the_content(); ?></span><br/>
		</div><!-- end of contact-degree -->
		
		<div class="contact-information">
			<?php 
				$location = '';
				if(is_array($metadata['cities'])) : 
					foreach ($metadata['cities'] as $key=>$value){
						$num = $key + 1;
						$location .= '<h4>Location '.$num.'</h4>State: <span class="italicstyle">'.$metadata['states'][$key].'</span><br/> City: <span class="italicstyle">'.$value.'</span><br/>Zip: <span class="italicstyle">'.$metadata['zips'][$key].'</span><br/>';
					}
				endif;
				echo $location ;
			?>	
		</div><!-- contack meta -->
		
		<div style="clear:both"></div>
		
	</div> <!-- post-->
	<div style="clear:both"></div>
	
	
<?php endwhile; // End the loop. Whew. ?>
<?php endif; // This was the if statement that broke the loop into three parts based on categories. ?>

</div> <!-- pagination-->
<?php 
	
	wp_reset_query();
 ?>

	</div><!--#main -->

<?php get_sidebar(); ?>

</div><!--content -->

<?php get_footer(); ?>
