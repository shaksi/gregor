<?php get_header(); ?>

<?php if( have_posts() ) while ( have_posts() ) : the_post(); ?>

<?php $layout = (themify_get('layout') == "sidebar-none" || themify_get('layout') == "sidebar1" || themify_get('layout') == "sidebar1 sidebar-left" || themify_get('layout') == "sidebar2") ? themify_get('layout') : themify_get('setting-default_page_post_layout'); /* set default layout */ if($layout == ''): $layout = 'sidebar1'; endif; ?>

<?php 

/////////////////////////////////////////////
// Set Default Image Sizes 							
/////////////////////////////////////////////

$content_width = 978;
$sidebar1_content_width = 670;

// Default single image size
$single_image_width = 978;

?>

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix <?php echo $layout; ?>">

	<!-- content -->
	<div id="content" class="list-post">
		<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix $class"); ?>>
			<div class="post-inner">
			
			<?php global $hide_date, $hide_meta, $hide_image, $hide_title, $unlink_title, $unlink_image; ?>
			<?php $hide_title = (themify_get('hide_post_title') != "default" && themify_check('hide_post_title')) ? themify_get('hide_post_title') : themify_get('setting-default_page_post_title'); ?>
			<?php $unlink_title = (themify_get('unlink_post_title') != "default" && themify_check('unlink_post_title')) ? themify_get('unlink_post_title') : themify_get('setting-default_page_unlink_post_title'); ?>
			<?php $hide_date = (themify_get('hide_post_date') != "default" && themify_check('hide_post_date')) ? themify_get('hide_post_date') : themify_get('setting-default_page_post_date'); ?>
			<?php $hide_meta = (themify_get('hide_post_meta') != "default" && themify_check('hide_post_meta')) ? themify_get('hide_post_meta') : themify_get('setting-default_page_post_meta'); ?>
			<?php $hide_image = (themify_get('hide_post_image') != "default" && themify_check('hide_post_image')) ? themify_get('hide_post_image') : themify_get('setting-default_page_post_image'); ?>
			<?php $unlink_image = (themify_get('unlink_post_image') != "default" && themify_check('unlink_post_image')) ? themify_get('unlink_post_image') : themify_get('setting-default_page_unlink_post_image'); ?>
			<?php $post_image_width = themify_get('image_width'); ?>
			<?php $post_image_height = themify_get('image_height'); ?>
			
			<?php
			
			///////////////////////////////////////////
			// Setting image width, height
			///////////////////////////////////////////
			
			global $width, $height;
			if($post_image_height == "" && $post_image_width == ""):
			
				$width = $single_image_width;
				$height = $single_image_height;
				
				if($layout == "sidebar1" || $layout == "sidebar1 sidebar-left"):
				
					$ratio = $width / $content_width;
					$aspect = $height / $width;
					$width = round($ratio * $sidebar1_content_width);
					if($height != '' && $height != 0):
						$height = round($width * $aspect);
					endif;
								
				endif;
				
			else:
			
				$width = $post_image_width;
				$height = $post_image_height;
				
			endif;
			?>
	
			<?php get_template_part( 'includes/loop' , 'single'); ?>
			
	
			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			<?php // Author Box 
				
				if(themify_check('setting-post_author_box')): ?>

					<div class="shortcode clearfix author-box">
					
						<p class="author-avatar">
							<?php echo get_avatar( get_the_author_meta('user_email'), $avatar_size, '' ); ?>
						</p>
	
							<div class="author-bio">
							
								<h4 class="author-name">
									
									<?php // Check for user url exists or not 
										if(get_the_author_meta('user_url')){ ?>
											<a href="<?php echo get_the_author_meta('user_url'); ?>"><?php echo get_the_author_meta('first_name').' '.get_the_author_meta('last_name'); ?></a>
									<?php } else { ?>
											<?php echo get_the_author_meta('first_name').' '.get_the_author_meta('last_name'); ?>
										<?php } ?>
								</h4>
									<?php echo get_the_author_meta('description'); ?>
										<?php // Check for user url exists or not
											 if(get_the_author_meta('user_url')){ ?>
												<p class="author-link">
													<a href="<?php echo get_the_author_meta('user_url'); ?>">&rarr; <?php echo get_the_author_meta('user_firstname').' '.get_the_author_meta('user_lastname'); ?> </a>
												</p>
										<?php } ?>
							</div> <!-- / author-bio -->
							
					</div>	<!-- / author-box -->		
			
			<?php endif; // End of themify_check('setting-post_author_box')) ?>

		<?php get_template_part( 'includes/post-nav'); ?>

		<?php if(!themify_check('setting-comments_posts')): ?>
			<?php comments_template(); ?>
		<?php endif; ?>
			</div>
			<!-- /.post-inner -->
		</article>
		<!-- /.post -->
	</div>
	<!-- /content -->

<?php endwhile; ?>

<?php 
/////////////////////////////////////////////
// Sidebar							
/////////////////////////////////////////////
?>

<?php if ($layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>