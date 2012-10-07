<?php

/*
To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder. 
They will be added to the theme automatically.
*/

/* 	Enqueue Stylesheets and Scripts
/***************************************************************************/
add_action('wp_enqueue_scripts', 'themify_theme_enqueue_scripts');
function themify_theme_enqueue_scripts(){
	///////////////////
	//Enqueue scripts
	///////////////////

	//prettyPhoto script
	wp_enqueue_script( 'pretty-photo', get_template_directory_uri() . '/js/jquery.prettyPhoto.js', array('jquery'), false, true );

	//Slider
	wp_enqueue_script( 'jquery-slider', get_template_directory_uri() . '/js/jquery.slider.js', array('jquery'), false, true );	

	//Photoswipe script
	wp_enqueue_script( 'klass', get_template_directory_uri() . '/js/klass.min.js', array('jquery'), false, true );
	wp_enqueue_script( 'photoswipe', get_template_directory_uri() . '/js/jquery.photoswipe.js', array('jquery'), false, true );
	
	//Themify internal script
	wp_enqueue_script( 'theme-script',	get_template_directory_uri() . '/js/themify.script.js', array('jquery'), false, true );
	
	//WordPress thread comment reply script
	if ( is_single() || is_page() ) wp_enqueue_script( 'comment-reply' );
	
	///////////////////
	//Enqueue styles
	///////////////////
	
	//Themify base stylesheet
	wp_enqueue_style( 'themify-styles', get_bloginfo('stylesheet_url'));

	//Themify Media Queries stylesheet
	wp_enqueue_style( 'themify-media-queries', get_template_directory_uri() . '/media-queries.css');
	
	//Custom stylesheet
	if(is_file(TEMPLATEPATH . "/custom_style.css"))
		wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/custom_style.css');
	
	//PrettyPhoto stylesheet
	wp_enqueue_style( 'pretty-photo', get_template_directory_uri() . '/prettyPhoto.css');

	//Photoswipe stylesheet
	wp_enqueue_style( 'photoswipe', get_template_directory_uri() . '/photoswipe.css');
	
	//Google Web Fonts
	wp_enqueue_style( 'google-fonts', 'http://fonts.googleapis.com/css?family=Oswald');
	
}

/**
 * Add JavaScript files if IE version is lower than 9
 * @package themify
 */
function themify_ie_enhancements(){
	echo '
<!-- media-queries.js -->
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->

<!-- html5.js -->
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
';
}
add_action( 'wp_head', 'themify_ie_enhancements' );

/**
 * Add viewport tag for responsive layouts
 * @package themify
 */
function themify_viewport_tag(){
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
}
add_action( 'wp_head', 'themify_viewport_tag' );

// enqueue jquery 1.6 because photoswipe.js is not compatible with jquery 1.7
function themify_enqueue_jquery_script() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js');
    wp_enqueue_script( 'jquery' );
}    
add_action('wp_enqueue_scripts', 'themify_enqueue_jquery_script');

/* 	Custom Write Panels
/***************************************************************************/

	///////////////////////////////////////
	// Setup Write Panel Options
	///////////////////////////////////////
	
	// Post Meta Box Options
	$post_meta_box_options = array(
		// Layout
		array(
			  "name" 		=> "layout",	
			  "title" 		=> __('Sidebar Option', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "layout",			
			  "meta"		=> array(
			  						array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true),

									 array("value" => "sidebar1", 	"img" => "images/layout-icons/sidebar1.png"),
									 array("value" => "sidebar1 sidebar-left", 	"img" => "images/layout-icons/sidebar1-left.png"),
									 array("value" => "sidebar-none",	 	"img" => "images/layout-icons/sidebar-none.png")
									 )			
			),
		// Post Image
		array(
			  "name" 		=> "post_image",
			  "title" 		=> __('Post Image', 'themify'),
			  "description" => __('Post image used in the loop', 'themify'),
			  "type" 		=> "image",
			  "meta"		=> array()
			),
	   	// Feature Image
		array(
			  "name" 		=> "feature_image",
			  "title" 		=> __('Feature Image', 'themify'),
			  "description" => "Feature image used in feature post widget or slider", 
			  "type" 		=> "image",
			  "meta"		=> array()	
			),
		// Hide Post Title
		array(
			  "name" 		=> "hide_post_title",	
			  "title" 		=> __('Hide Post Title', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Unlink Post Title
		array(
			  "name" 		=> "unlink_post_title",	
			  "title" 		=> __('Unlink Post Title', 'themify'), 	
			  "description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Hide Post Meta
		array(
			  "name" 		=> "hide_post_meta",	
			  "title" 		=> __('Hide Post Meta', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Hide Post Date
		array(
			  "name" 		=> "hide_post_date",	
			  "title" 		=> __('Hide Post Date', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Hide Post Image
		array(
			  "name" 		=> "hide_post_image",	
			  "title" 		=> __('Hide Post Image', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Unlink Post Image
		array(
			  "name" 		=> "unlink_post_image",	
			  "title" 		=> __('Unlink Post Image', 'themify'), 	
			  "description" => __('Unlink post image (it will display the post image without link)', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Image Width
		array(
			  "name" 		=> "image_width",	
			  "title" 		=> __('Image Width', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// Image Height
		array(
			  "name" 		=> "image_height",	
			  "title" 		=> __('Image Height', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// External Link
		array(
			  "name" 		=> "external_link",	
			  "title" 		=> __('External Link', 'themify'), 	
			  "description" => __('Link post image to external URL', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array()			
			),
		// Lightbox Link
		array(
			  "name" 		=> "lightbox_link",	
			  "title" 		=> __('Lightbox Link', 'themify'), 	
			  "description" => __('Link post image to lightbox image, video or external iframe', 'themify'), 				
			  "type" 		=> "textbox",			
			  "meta"		=> array()			
			)
	);


	// Page Meta Box Options
	$page_meta_box_options = array(
	  	// Page Layout
		array(
			  "name" 		=> "page_layout",
			  "title"		=> __('Sidebar Option', 'themify'),
			  "description"	=> "",
			  "type"		=> "layout",
			  "meta"		=> array(
			  						array("value" => "default", "img" => "images/layout-icons/default.png", "selected" => true),
									
									 array("value" => "sidebar1", 	"img" => "images/layout-icons/sidebar1.png"),
									 array("value" => "sidebar1 sidebar-left", 	"img" => "images/layout-icons/sidebar1-left.png"),
									 array("value" => "sidebar-none",	 	"img" => "images/layout-icons/sidebar-none.png")
									 )
			),
		// Hide page title
		array(
			  "name" 		=> "hide_page_title",
			  "title"		=> __('Hide Page Title', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )	
			),
	   // Query Category
		array(
			  "name" 		=> "query_category",
			  "title"		=> __('Query Category', 'themify'),
			  "description"	=> __('Select a category or enter multiple category IDs (eg. 2,5,6). Enter 0 to display all category.', 'themify'),
			  "type"		=> "query_category",
			  "meta"		=> array()
			),
		// Section Categories
		array(
			  "name" 		=> "section_categories",	
			  "title" 		=> __('Section Categories', 'themify'), 	
			  "description" => __('Display multiple query categories separately', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Post Layout
		array(
			  "name" 		=> "layout",
			  "title"		=> __('Query Post Layout', 'themify'),
			  "description"	=> "",
			  "type"		=> "layout",
			  "meta"		=> array(
									 array("value" => "list-post", "img" => "images/layout-icons/list-post.png", "selected" => true),
									 array("value" => "grid4", "img" => "images/layout-icons/grid4.png"),
									 array("value" => "grid3", "img" => "images/layout-icons/grid3.png"),
									 array("value" => "grid2", "img" => "images/layout-icons/grid2.png"),
									 array("value" => "list-large-image", "img" => "images/layout-icons/list-large-image.png"),
									 array("value" => "list-thumb-image", "img" => "images/layout-icons/list-thumb-image.png"),
									 array("value" => "grid2-thumb", "img" => "images/layout-icons/grid2-thumb.png")
									 )
			),
		// Posts Per Page
		array(
			  "name" 		=> "posts_per_page",
			  "title"		=> __('Posts per page', 'themify'),
			  "description"	=> "",
			  "type"		=> "textbox",
			  "meta"		=> array("size" => "small")
			),
		
		// Display Content
		array(
			  "name" 		=> "display_content",
			  "title"		=> __('Display Content', 'themify'),
			  "description"	=> "",
			  "type"		=> "dropdown",
			  "meta"		=> array(
									 array('name' => __('Full Content', 'themify'),"value"=>"content","selected"=>true),
			  						 array('name' => __('Excerpt', 'themify'),"value"=>"excerpt"),
									 array('name' => __('None', 'themify'),"value"=>"none")
									 )
			),
		// Hide Title
		array(
			  "name" 		=> "hide_title",
			  "title"		=> __('Hide Post Title', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )
			),
		// Unlink Post Title
		array(
			  "name" 		=> "unlink_title",	
			  "title" 		=> __('Unlink Post Title', 'themify'), 	
			  "description" => __('Unlink post title (it will display the post title without link)', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Hide Post Date
		array(
			  "name" 		=> "hide_date",
			  "title"		=> __('Hide Post Date', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )
			),
		// Hide Post Meta
		array(
			  "name" 		=> "hide_meta",
			  "title"		=> __('Hide Post Meta', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )
			),
		// Hide Post Image
		array(
			  "name" 		=> "hide_image",	
			  "title" 		=> __('Hide Post Image', 'themify'), 	
			  "description" => "", 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Unlink Post Image
		array(
			  "name" 		=> "unlink_image",	
			  "title" 		=> __('Unlink Post Image', 'themify'), 	
			  "description" => __('Unlink post image (it will display the post image without link)', 'themify'), 				
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )			
			),
		// Page Navigation Visibility
		array(
			  "name" 		=> "hide_navigation",
			  "title"		=> __('Hide Page Navigation', 'themify'),
			  "description"	=> "",
			  "type" 		=> "dropdown",			
			  "meta"		=> array(
			  						array("value" => "default", "name" => "", "selected" => true),

									 array("value" => "yes", 'name' => __('Yes', 'themify')),
									 array("value" => "no",	'name' => __('No', 'themify'))
									 )
			),
		// Image Width
		array(
			  "name" 		=> "image_width",	
			  "title" 		=> __('Image Width', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			),
		// Image Height
		array(
			  "name" 		=> "image_height",	
			  "title" 		=> __('Image Height', 'themify'), 
			  "description" => "", 				
			  "type" 		=> "textbox",			
			  "meta"		=> array("size"=>"small")			
			)
		
	);
		
	///////////////////////////////////////
	// Build Write Panels
	///////////////////////////////////////
	themify_build_write_panels(array(
		array(
			 "name"		=> __('Post Options', 'themify'),			// Name displayed in box
			 "options"	=> $post_meta_box_options, 	// Field options
			 "pages"	=> "post"					// Pages to show write panel
			 ),
		array(
			 "name"		=> __('Page Options', 'themify'),	
			 "options"	=> $page_meta_box_options, 		
			 "pages"	=> "page"
			 )
  		)
	);
	
	
	
	
/* 	Custom Functions
/***************************************************************************/	

	///////////////////////////////////////
	// Enable WordPress feature image
	///////////////////////////////////////
	add_theme_support( 'post-thumbnails');

	///////////////////////////////////////
	// Add wmode transparent and post-video container for responsive purpose
	///////////////////////////////////////	
	function themify_add_video_wmode_transparent($html, $url, $attr) {
		
		$html = '<p class="post-video">' . $html . '</p>';
		if (strpos($html, "<embed src=" ) !== false) {
			$html = str_replace('</param><embed', '</param><param name="wmode" value="transparent"></param><embed wmode="transparent" ', $html);
			return $html;
		}
		else {
			if(strpos($html, "wmode=transparent") == false){
				if(strpos($html, "?fs=" ) !== false){
					$search = array('?fs=1', '?fs=0');
					$replace = array('?fs=1&wmode=transparent', '?fs=0&wmode=transparent');
					$html = str_replace($search, $replace, $html);
					return $html;
				}
				else{
					$youtube_embed_code = $html;
					$patterns[] = '/youtube.com\/embed\/([a-zA-Z0-9._-]+)/';
					$replacements[] = 'youtube.com/embed/$1?wmode=transparent';
					return preg_replace($patterns, $replacements, $html);
				}
			}
			else{
				return $html;
			}
		}
	}
	add_filter('embed_oembed_html', 'themify_add_video_wmode_transparent');
	
	///////////////////////////////////////
	// Register Custom Menu Function
	///////////////////////////////////////
	function register_custom_nav() {
		if (function_exists('register_nav_menus')) {
			register_nav_menus( array(
				'main-nav' => __( 'Main Navigation', 'themify' ),
				'footer-nav' => __( 'Footer Navigation', 'themify' ),
			) );
		}
	}
	
	// Register Custom Menu Function - Action
	add_action('init', 'register_custom_nav');
	
	///////////////////////////////////////
	// Default Main Nav Function
	///////////////////////////////////////
	function default_main_nav() {
		echo '<ul id="main-nav" class="main-nav clearfix">';
		wp_list_pages('title_li=');
		echo '</ul>';
	}

	///////////////////////////////////////
	// Register Widgets
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'name' => 'Sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="widgettitle">',
			'after_title' => '</h4>',
		));
		register_sidebar(array(
			'name' => 'Social_Widget',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<strong>',
			'after_title' => '</strong>',
		));
	}

	///////////////////////////////////////
	// Footer Widgets
	///////////////////////////////////////
	if ( function_exists('register_sidebar') ) {
		$data = get_data();
		$columns = array('footerwidget-4col' 			=> 4,
						'footerwidget-3col'			=> 3,
						'footerwidget-2col' 		=> 2,
						'footerwidget-1col' 		=> 1,
						'none'			 		=> 0, );
		$option = ($data['setting-footer_widgets'] == "" || !isset($data['setting-footer_widgets'])) ?  "footerwidget-3col" : $data['setting-footer_widgets'];
		for($x=1;$x<=$columns[$option];$x++){
			register_sidebar(array(
				'name' => 'Footer_Widget_'.$x,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h4 class="widgettitle">',
				'after_title' => '</h4>',
			));			
		}
	}

	///////////////////////////////////////
	// Custom Theme Comment
	///////////////////////////////////////
	function custom_theme_comment($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment; 
	   ?>

<li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
	<p class="comment-author"> <?php echo get_avatar($comment,$size='48'); ?> <?php printf('<cite>%s</cite>', get_comment_author_link()) ?><br />
		<small class="comment-time"><strong>
		<?php comment_date('M d, Y'); ?>
		</strong> @
		<?php comment_time('H:i:s'); ?>
		<?php edit_comment_link( __('Edit', 'themify'),' [',']') ?>
		</small> </p>
	<div class="commententry">
		<?php if ($comment->comment_approved == '0') : ?>
		<p><em>
			<?php _e('Your comment is awaiting moderation.', 'themify') ?>
			</em></p>
		<?php endif; ?>
		<?php comment_text() ?>
	</div>
	<p class="reply">
		<?php comment_reply_link(array_merge( $args, array('add_below' => 'comment', 'depth' => $depth, 'reply_text' => __( 'Reply', 'themify' ), 'max_depth' => $args['max_depth']))) ?>
	</p>
	<?php
	}

?>
