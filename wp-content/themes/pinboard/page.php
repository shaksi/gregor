<?php get_header(); ?>
		
<?php if (is_home() && !is_paged()) get_template_part( 'includes/welcome-message'); ?>

<?php 

$content_width = 978;
$sidebar1_content_width = 670;

// Grid4
$grid4_width = 280;

// Grid3
$grid3_width = 306;

// Grid2
$grid2_width = 474;

// List Post
$list_post_width = 978;

?>

<?php $layout = themify_get('setting-default_layout'); if($layout == ''): $layout = 'sidebar-none'; endif;  /* get default page layout setting for 404 */ ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<?php global $post_query_category, $post_layout, $page_title, $hide_image, $hide_title, $unlink_title, $unlink_image, $hide_meta, $hide_date, $display_content; ?>
	<?php $post_query_category = themify_get('query_category'); ?>
	<?php $layout = (themify_get('page_layout') != "default" && themify_check('page_layout')) ? themify_get('page_layout') : themify_get('setting-default_page_layout'); /* set default layout */ if($layout == ''): $layout = 'sidebar-none'; endif; ?>
	<?php $post_layout = (themify_get('layout') != "default" && themify_check('layout')) ? themify_get('layout') : themify_get('setting-default_post_layout'); /* set default post layout */ if($post_layout == ''): $post_layout = 'grid4'; endif; ?>
	<?php $page_title = (themify_get('hide_page_title') != "default" && themify_check('hide_page_title')) ? themify_get('hide_page_title') : themify_get('setting-hide_page_title'); ?>
	<?php $hide_title = themify_get('hide_title'); ?>
	<?php $unlink_title = themify_get('unlink_title'); ?>
	<?php $hide_image = themify_get('hide_image'); ?>
     <?php $unlink_image = themify_get('unlink_image'); ?>
	<?php $hide_meta = themify_get('hide_meta'); ?>
	<?php $hide_date = themify_get('hide_date'); ?>
	<?php $display_content = themify_get('display_content') ?>
	<?php $post_image_width = themify_get('image_width'); ?>
	<?php $post_image_height = themify_get('image_height'); ?>
	<?php $page_navigation = themify_get('hide_navigation'); ?>
	<?php $posts_per_page = themify_get('posts_per_page'); ?>

<?php endwhile; endif; ?>

<!-- layout-container -->
<div id="layout" class="pagewidth clearfix <?php echo $layout; ?>">	

	<!-- content -->
	<div id="content" class="clearfix">
	
		<?php 
		/////////////////////////////////////////////
		// 404							
		/////////////////////////////////////////////
		?>
		<?php if(is_404()): ?>
			<h1 class="page-title"><?php _e('404','themify'); ?></h1>	
			<p><?php _e( 'Page not found.', 'themify' ); ?></p>	
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// PAGE							
		/////////////////////////////////////////////
		?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
			<!-- page-title -->
			<?php if($page_title != "yes"): ?> 
				<h1 class="page-title"><?php the_title(); ?></h1>
			<?php endif; ?>	
			<!-- /page-title -->

			<div class="page-content">
				<?php the_content(); ?>
			</div>
			<!-- /.post-content -->
			
			<?php wp_link_pages(array('before' => '<p><strong>'.__('Pages:','themify').'</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			
			<?php edit_post_link(__('Edit','themify'), '[', ']'); ?>
			
			<!-- comments -->
			<?php if(!themify_check('setting-comments_pages') && $post_query_category == ""): ?>
				<?php comments_template(); ?>
			<?php endif; ?>
			<!-- /comments -->
		
		<?php endwhile; endif; ?>
		
		<?php 
		/////////////////////////////////////////////
		// Query Category							
		/////////////////////////////////////////////
		?>

		<?php 
		///////////////////////////////////////////
		// Setting image width, height
		///////////////////////////////////////////
		
		global $width, $height;
		
		if($post_image_height == "" && $post_image_width == ""):
		
			if($post_layout == 'grid4'):
			
				$width = $grid4_width;
				$height = $grid4_height;
			
			elseif($post_layout == 'grid3'):
			
				$width = $grid3_width;
				$height = $grid3_height;
			
			elseif($post_layout == 'grid2'):
			
				$width = $grid2_width;
				$height = $grid2_height;
				
			elseif($post_layout == 'list-large-image'):
			
				$width = $list_large_image_width;
				$height = $list_large_image_height;
			
			elseif($post_layout == 'list-thumb-image'):
			
				$width = $list_thumb_image_width;
				$height = $list_thumb_image_height;
		
			elseif($post_layout == 'grid2-thumb'):
			
				$width = $grid2_thumb_width;
				$height = $grid2_thumb_height;
				
			elseif($post_layout == 'list-post'):
			
				$width = $list_post_width;
				$height = $list_post_height;
			
			else:
						
				$width = $list_post_width;
				$height = $list_post_height;
				
			endif;
			
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

		if(get_query_var('paged')):
			$paged = get_query_var('paged');
		elseif(get_query_var('page')):
			$paged = get_query_var('page');
		else:
			$paged = 1;
		endif;
		
		if($post_query_category != ""): ?>
		
			<?php if(themify_get('section_categories') != 'yes'): ?>
			
				<?php $wp_query->query('cat='.$post_query_category.'&posts_per_page='.$posts_per_page.'&paged='.$paged); ?>
				
					<?php if(have_posts()): ?>
						
						<!-- loops-wrapper -->
						<div class="loops-wrapper <?php echo $post_layout; ?> infinite-scrolling AutoWidthElement">

							<?php while(have_posts()) : the_post(); ?>
								<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix $class"); ?>>
									<div class="post-inner">
										<?php get_template_part('includes/loop', 'query'); ?>
									</div>
									<!-- /.post-inner -->
								</article>
								<!-- /.post -->
							<?php endwhile; ?>
												
						</div>
						<!-- /loops-wrapper -->

						<?php if ($page_navigation != "yes"): ?>
							<?php get_template_part( 'includes/pagination'); ?>
						<?php endif; ?>
								
					<?php else : ?>	
					
					<?php endif; ?>

			<?php else: ?>
				
				<?php $categories = explode(",",str_replace(" ","",$post_query_category)); ?>
				
				<?php foreach($categories as $category): ?>
				
				<?php $cats = get_categories(array('include'=>$category, 'orderby' => 'id')); ?>
				
				<?php foreach($cats as $cat): ?>
					
				<?php
 $wp_query->query('cat='.$cat->cat_ID.'&posts_per_page='.$posts_per_page.'&paged='.$paged);	?>
			
					<?php if(have_posts()): ?>
						
						<!-- category-section -->
						<div class="category-section clearfix <?php echo $cat->cat_name; ?>-category">

							<h3 class="category-section-title"><?php echo $cat->cat_name; ?></h3>

							<!-- loops-wrapper -->
							<div class="loops-wrapper <?php echo $post_layout; ?> infinite-scrolling AutoWidthElement">
								<?php while(have_posts()) : the_post(); ?>
									
									<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix $class"); ?>>
										<div class="post-inner">
											<?php get_template_part('includes/loop', 'query'); ?>
										</div>
										<!-- /.post-inner -->
									</article>
									<!-- /.post -->
							
								<?php endwhile; ?>
							</div>
							<!-- /loops-wrapper -->

							<?php if ($page_navigation != "yes"): ?>
								<?php get_template_part( 'includes/pagination'); ?>
							<?php endif; ?>

						</div>
						<!-- /category-section -->
								
					<?php else : ?>	
					
					<?php endif; ?>
				
				<?php endforeach; ?>
				
				<?php endforeach; ?>
			
			<?php endif; ?>
			
		<?php endif; ?>
		<?php wp_reset_query(); ?>
	</div>
	<!-- /content -->

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	?>

	<?php if ($layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /layout-container -->
	
<?php get_footer(); ?>
