<?php get_header(); ?>

<?php if (is_home() && !is_paged()) get_template_part( 'includes/welcome-message'); ?>

<?php 
/////////////////////////////////////////////
// Setup Default Variables	 							
/////////////////////////////////////////////
?>

<?php global $post_layout, $hide_title, $hide_image, $hide_title, $unlink_title, $unlink_image, $hide_meta, $hide_date, $display_content; ?>
<?php $layout = themify_get('setting-default_layout'); /* set default layout */ if($layout == '' ) : $layout = 'sidebar-none'; endif; ?>
<?php $post_layout = themify_get('setting-default_post_layout'); /* set default post layout */ if($post_layout == ''): $post_layout = 'grid4'; endif; ?>
<?php $page_title = themify_get('setting-hide_page_title'); ?>
<?php $hide_title = themify_get('setting-default_post_title'); ?>
<?php $unlink_title = themify_get('setting-default_unlink_post_title'); ?>
<?php $hide_image = themify_get('setting-default_post_image'); ?>
<?php $unlink_image = themify_get('setting-default_unlink_post_image'); ?>
<?php $hide_meta = themify_get('setting-default_post_meta'); ?>
<?php $hide_date = themify_get('setting-default_post_date'); ?>

<?php $display_content = themify_get('setting-default_layout_display'); ?>
<?php $post_image_width = themify_get('image_width'); ?>
<?php $post_image_height = themify_get('image_height'); ?>
<?php $page_navigation = themify_get('hide_navigation'); ?>
<?php $posts_per_pages = themify_get('posts_per_page'); ?>
	
<?php 

/////////////////////////////////////////////
// Set Default Image Sizes 							
/////////////////////////////////////////////

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

		
///////////////////////////////////////////
// Setting image width, height
///////////////////////////////////////////

global $width, $height;

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
?>
		
<!-- layout -->
<div id="layout" class="pagewidth clearfix <?php echo $layout; ?>">

	<!-- content -->
	<div id="content" class="clearfix">
		
		<?php 
		/////////////////////////////////////////////
		// Author Page	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_author()) : ?>
			<?php
			$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
			$author_url = $curauth->user_url;
			?>
			<div class="author-bio clearfix">
				<p class="author-avatar"><?php echo get_avatar( $curauth->user_email, $size = '48' ); ?></p>
				<h2 class="author-name"><?php _e('About','themify'); ?> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?></h2>
				<?php if($author_url != ''): ?><p class="author-url"><a href="<?php echo $author_url; ?>"><?php echo $author_url; ?></a></p><?php endif; //author url ?>
				<div class="author-description">
					<?php echo $curauth->user_description; ?>
				</div>
				<!-- /.author-description -->
			</div>
			<!-- /.author bio -->
			
			<h2 class="author-posts-by"><?php _e('Posts by','themify'); ?> <?php echo $curauth->first_name; ?> <?php echo $curauth->last_name; ?>:</h2>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Search Title	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_search()): ?>
			<h1 class="page-title"><?php _e('Search Results for:','themify'); ?> <em><?php echo get_search_query(); ?></em></h1>
		<?php endif; ?>
	
		<?php 
		/////////////////////////////////////////////
		// Category Title	 							
		/////////////////////////////////////////////
		?>
		<?php if(is_category() || is_tag() || is_tax() ): ?>
			<h1 class="page-title"><?php single_cat_title(); ?></h1>
			<?php
				$category_description = category_description();
				if ( !empty( $category_description ) ){
					echo '<div class="category-description">' . $category_description . '</div>';
				}
			?>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Default query categories	 							
		/////////////////////////////////////////////
		?>
		<?php $query_cats = themify_get('setting-default_query_cats'); ?>
		<?php if(($query_cats !="") && !is_search()): ?>
			<?php query_posts($query_string . '&cat='.$query_cats); ?>
		<?php endif; ?>

		<?php 
		/////////////////////////////////////////////
		// Loop	 							
		/////////////////////////////////////////////
		?>
		<?php if (have_posts()) : ?>
		
			<!-- loops-wrapper -->
			<div class="loops-wrapper <?php echo $post_layout; ?> infinite-scrolling AutoWidthElement">

				<?php while (have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class("post clearfix $class"); ?>>
						<div class="post-inner">
						<?php if(is_search()): ?>
							<?php get_template_part( 'includes/loop' , 'search'); ?>
						<?php else: ?>
							<?php get_template_part( 'includes/loop' , 'index'); ?>
						<?php endif; ?>
						</div>
						<!-- /.post-inner -->
					</article>
					<!-- /.post -->
				<?php endwhile; ?>
							
			</div>
			<!-- /loops-wrapper -->
			<?php get_template_part( 'includes/pagination'); ?>
		
		<?php 
		/////////////////////////////////////////////
		// Error - No Page Found	 							
		/////////////////////////////////////////////
		?>
	
		<?php else : ?>
	
			<p><?php _e( 'Sorry, nothing found.', 'themify' ); ?></p>
	
		<?php endif; ?>
	
	</div>
	<!-- /#content -->

	<?php 
	/////////////////////////////////////////////
	// Sidebar							
	/////////////////////////////////////////////
	?>
	
	<?php if ($layout != "sidebar-none"): get_sidebar(); endif; ?>

</div>
<!-- /#layout -->

<?php get_footer(); ?>