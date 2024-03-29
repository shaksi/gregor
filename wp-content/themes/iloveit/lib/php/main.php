<?php
	function ilove__autoload( $class_name ){
        if( substr( $class_name , 0 , 6 ) == 'widget'){
            $class_name = str_replace( 'widget_' , '' ,  $class_name );
            if( is_file( get_template_directory() . '/lib/php/widget/' . $class_name . '.php' ) ){
                include get_template_directory() . '/lib/php/widget/' . $class_name . '.php';

            }
        }
		if( is_file( get_template_directory() . '/lib/php/' . $class_name . '.class.php' ) ){
			include_once get_template_directory() . '/lib/php/' . $class_name . '.class.php';
            if( is_file( get_template_directory() . '/lib/php/' . $class_name . '.register.php' ) ){
				include_once get_template_directory() . '/lib/php/' . $class_name . '.register.php';
			}
		}
	}
    
	spl_autoload_register ("ilove__autoload");
	
	include_once get_template_directory() . '/lib/php/audio-player.php';
    
	/*for som reason this must be included manually*/
	include_once get_template_directory() . '/lib/php/simple_modal_login.class.php';
  
    
	/* check if item is usable folder or file */
    function is_item( $item ){
        if( $item != '.' && $item != '..' && $item != '.svn' ){
            return true;
        }else{
            return false;
        }
    }
    
    
    
    function get_item_label( $item ){
        $item = basename( $item );
        $item = str_replace( '-' , ' ' , $item );
        return $item;
    }

    function get_item_slug( $item ){
        $item = basename( $item );
        $item = str_replace( '-', '_' , str_replace( ' ', '__' , $item ) );
        return $item;
    }

    function get_subitem_slug( $item , $subitem ){
        $item = get_item_slug( $item );
        $subitem = get_item_slug( $subitem );
        $subitem = $item . FN_DELIM . $subitem;
        return $subitem;
    }

    function get_items( $slug ){
        $items = explode( FN_DELIM , $slug );
        $result = array();
        if( is_array( $items ) ){
            foreach( $items as $item ){
                $result[] = str_replace( '_', '-' , str_replace( '__', ' ' , $item ) );
            }
        }else{
            $result = str_replace( '_', '-' , str_replace( '__', ' ' , $item ) );
        }

        return $result;
    }

    function get_item( $slug ){
        $item = str_replace( '_', '-' , str_replace( '__', ' ' , $slug ) );
        return $item;
    }

    function get_path( $slug ){
        $item = str_replace( '_', '-' , str_replace( '__', ' ' , str_replace( FN_DELIM, '/' , $slug ) ) );
        return $item;
    }

    function get__categories( $nr = -1 ){
        $categories = get_categories();

        $result = array();
        foreach($categories as $key => $category){
            if( $key == $nr ){
                break;
            }
            if( $nr > 0 ){
                $result[ $category -> term_id ] = $category -> term_id;
            }else{
                $result[ $category -> term_id ] = $category -> cat_name;
            }
        }

        return $result;
    }

    function get__pages( $first_label = 'Select item' ){
        $pages = get_pages();
        $result = array();
        if( is_array( $first_label ) ){
            $result = $first_label;
        }else{
            if( strlen( $first_label ) ){
                $result[] = $first_label;
            }
        }
        foreach($pages as $page){
            $result[ $page -> ID ] = $page -> post_title;
        }

        return $result;
    }

    function get__posts( $args = array() , $first_label = 'Select item' ){
        $posts = get_posts( $args );
        $result = array();
        
        if( is_array( $first_label ) ){
            $result = $first_label;
        }else{
            if( strlen( $first_label ) ){
                $result[] = $first_label;
            }
        }
        if( is_array( $posts ) && !empty( $posts ) ){
            foreach( $posts as $post ){
                $result[ $post -> ID  ] = $post -> post_title;
            }
        }

        return $result;
    }

    function menu( $id ,  $args = array() ){

        $menu = new menu( $args );

        $vargs = array(
            'menu'            => '',
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => isset( $args['class'] ) ? $args['class'] : '',
            'menu_id'         => '',
            'echo'            => false,
            'fallback_cb'     => '',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'depth'           => 0,
            'walker'          => $menu,
            'theme_location'  => $id ,
        );

        $result = wp_nav_menu( $vargs );

        if(!$result){
            if( $id == 'megusta' && options::logic( 'menu' , 'megusta' ) ){
                $home_url   = home_url();

                $hot        = array( 'fp_type' => "hot" );
                $hot_url    = add_query_arg( $hot , $home_url );

                $news       = array( 'fp_type' => "news" );
                $news_url    = add_query_arg( $news , $home_url );

                if( options::get_value( 'front_page' , 'type' ) == 'new_hot_posts' || options::get_value( 'front_page' , 'type' ) == 'page'  ){
                    $home = true;
                }else{
                    $home = false;
                }
				$mclasses = '';
                if( isset( $_GET['fp_type'] ) ){
                    if( $_GET['fp_type'] == 'news' ){
                        $nclasses = 'active';
                    }else{
                        $nclasses = "";
                    }

                    if( $_GET['fp_type'] == 'hot' ){
                        $hclasses = 'active';
                    }else{
                        $hclasses = "";
                    }
                    
                    if( $_GET['fp_type'] == 'like' ){
                        $hclasses = ' ';
                        $nclasses = ' ';
                    }

                    if( strlen( $hclasses . $nclasses ) == 0 ){
                        if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
                            $hclasses = 'active';
                        }else{
                            if( options::get_value( 'front_page' , 'type' ) == 'new_posts' ){
                                $nclasses = 'active';
                            }else{
                                
                                $mclasses = 'active';
                            }
                        }
                    }

                }else{
                    if( is_front_page () ){
                        if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
                            $hclasses = 'active';
                        }else{
                            $hclasses = '';
                        }

                        if( options::get_value( 'front_page' , 'type' ) == 'new_posts' ){
                            $nclasses = 'active';
                        }else{
                            $nclasses = "";
                        }

                        if( options::get_value( 'front_page' , 'type' ) == 'new_hot_posts' || options::get_value( 'front_page' , 'type' ) == 'page'  ){
                            $mclasses = 'active';
                        }else{
                            $mclasses = "";
                        }
                    }else{
                        $nclasses = "";
                        $hclasses = "";
                    }
                }
        ?>
                <ul <?php echo $menu -> menu_id .' '.$menu -> classes; ?>  >
                    <?php
                        if( $home ){
                            ?><li class="menu-item home <?php echo $mclasses; ?>" ><a href="<?php echo home_url(); ?>" ><?php echo options::get_value( 'menu' , 'home' ); ?><span><?php echo options::get_value( 'menu' , 'home_' ); ?></span></a></li><?php
                        }
                    ?>
                    <?php
                        if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
                            if( options::logic( 'general' , 'enb_likes' ) ){

                    ?>
                                <li class="menu-item hot <?php echo $hclasses; ?>" ><a href="<?php echo $hot_url; ?>" ><?php echo options::get_value( 'menu' , 'featured' ); ?><span><?php echo options::get_value( 'menu' , 'featured_' ); ?></span></a></li>
                    <?php
                            }
                    ?>
                            <li class="menu-item new <?php echo $nclasses; ?>"><a href="<?php echo $news_url; ?>" ><?php echo options::get_value( 'menu' , 'fresh' ); ?><span><?php echo options::get_value( 'menu' , 'fresh_' ); ?></span></a></li>
                    <?php
                        }else{
                            if( options::get_value( 'front_page' , 'type' ) == 'new_posts' ){
                    ?>
                                <li class="menu-item new <?php echo $nclasses; ?>"><a href="<?php echo $news_url; ?>" ><?php echo options::get_value( 'menu' , 'fresh' ); ?><span><?php echo options::get_value( 'menu' , 'fresh_' ); ?></span></a></li>
                                <?php
                                    if( options::logic( 'general' , 'enb_likes' ) ){
                                        ?><li class="menu-item hot <?php echo $hclasses; ?>" ><a href="<?php echo $hot_url; ?>" ><?php echo options::get_value( 'menu' , 'featured' ); ?><span><?php echo options::get_value( 'menu' , 'featured_' ); ?></span></a></li><?php
                                    }
                                ?>
                    <?php
                            }else{
                                if( options::logic( 'general' , 'enb_likes' ) ){
                    ?>

                                    <li class="menu-item hot <?php echo $hclasses; ?>" ><a href="<?php echo $hot_url; ?>" ><?php echo options::get_value( 'menu' , 'featured' ); ?><span><?php echo options::get_value( 'menu' , 'featured_' ); ?></span></a></li>
                    <?php
                                }
                    ?>
                                <li class="menu-item new <?php echo $nclasses; ?>"><a href="<?php echo $news_url; ?>" ><?php echo options::get_value( 'menu' , 'fresh' ); ?><span><?php echo options::get_value( 'menu' , 'fresh_' ); ?></span></a></li>
                    <?php
                            }
                        }
                    ?>
                    <li class="menu-item random"><a href="javascript:act.go_random();" ><?php echo options::get_value( 'menu' , 'random' ); ?><span><?php echo options::get_value( 'menu' , 'random_' ); ?></span></a></li>
                    <?php
                        //$registration = get_option( 'users_can_register' );
                        /*
                        if( options::logic( 'general' , 'user_login' ) ){
                            if( is_user_logged_in() ){
                    ?>
                                <li class="menu-item login"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'logout' , 'cosmotheme' ); ?></a></li>
                    <?php
                            }else{
                    ?>
                                <li class="menu-item login"><a href="<?php echo wp_login_url(); ?>" class="simplemodal-login simplemodal-none"><?php _e( 'login' , 'cosmotheme' ); ?></a></li>
                    <?php
                            }
                        } */
                    ?>
                </ul>
        <?php
            }else{
                if( $id == 'footer_menu' ){
                    $result = $menu -> get_terms_menu();
                }
            }
        }

        if( $menu -> need_more &&  $id != 'megusta' ){
                $result .="</li></ul>".$menu -> aftersubm ;
        }
        
        return $result;
    }

    function get_meta_records_( $post_id , $args ){
        $meta = meta::get_meta( $post_id , $args[ 1 ]  );
        $struct = meta::new_structure( resources::$box[ $args[0] ][ $args[1 ] ]['struct'] );
        $result = '';
        if( !empty( $meta ) ){
            $result .= '<h3 class="hndlell"><span>' . resources::$box[ $args[0] ][ $args[1 ] ]['records-title'] . '</span></h3>';
            foreach( $meta as $index => $m ){
                $img = '';

                if( isset( resources::$box[ $args[0] ][ $args[1 ] ]['res_type'] ) ){
                    switch( resources::$box[ $args[0] ][ $args[1 ] ]['res_type'] ){
                        case 'user' : {
							if(get_the_author_meta( 'first_name' , $m['idrecord'] ) != '' || get_the_author_meta( 'last_name' , $m['idrecord'] ) != ''){
								$title      = get_the_author_meta( 'first_name' , $m['idrecord'] ) . ' ' . get_the_author_meta( 'last_name' , $m['idrecord'] ) . ' (' . get_the_author_meta( 'nickname' , $m['idrecord'] ) . ') ';
							}else{
								$title      = get_the_author_meta( 'nickname' , $m['idrecord'] ) ;
							}
                            $status     = get_the_author_meta( 'user_status' , $m['idrecord'] ) ;

                            break;
                        }
                    }
                }else{
                    $post = get_post( $m['idrecord'] );
                    $title  = $post -> post_title;
                    if( isset( $post -> post_excerpt ) ){
                        $excerpt = strip_tags( $post -> post_excerpt );
                    }
                }
                
                if( !empty( $title ) ){
                    $result .= '<div class="side-meta-box-multiple-records">';
                    if( isset( $post ) && has_post_thumbnail( $post -> ID ) ){
                        $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , array( 50 , 50 ) );
                        $img = '<div class="icon"><img src="' . $src[0] . '" width="50" height="50"/></div>';
                    }
                    if( isset( $post ) ){
                        $result .= '<strong><a href="post.php?post=' . $post -> ID . '&action=edit">' . $title . '</a></strong>';
                    }else{
                        $result .= '<strong>' . $title . '</strong>';
                    }
                    
                    if( isset( $excerpt ) ){
                        $result .= '<p>'.$img.'<i>' . mb_substr( $excerpt , 0 , 140 ) . '</i></p>';
                    }
                    if( isset( $post ) ){
                        $result .= meta::get_actions( $struct , $args[0] , $args[1 ] , $post_id , null , $index , '#' . $args[0] . $args[1 ] . $index , ' - <b>'. __('Status: ','cosmotheme') . '</b>' . $post -> post_status ) ;
                    }else{
                        $result .= meta::get_actions( $struct , $args[0] , $args[1 ] , $post_id , null , $index , '#' . $args[0] . $args[1 ] . $index , '' ) ;
                    }
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                }
            }
        }
        return $result;
    }

    function page(){
        if( (int)get_query_var('paged') > (int)get_query_var('page') ){
            $result = (int)get_query_var('paged');
        }else{

            if( (int)get_query_var('page') == 0 ){
                $result = 1;
            }else{
                $result = (int)get_query_var('page');
            }
        }

        return $result;
    }

    function de_remove_wpautop($content) {
		$content = do_shortcode( shortcode_unautop( $content ) );
		$content = preg_replace('#^<\/p>|^<br \/>|^<br>|<p>$#', '', $content);
		return $content;
	}

    function de_post_gallery( $output, $attr) {
	    global $post, $wp_locale;

	    static $instance = 0;
	    $instance++;

	    if ( isset( $attr['orderby'] ) ) {
	        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
	        if ( !$attr['orderby'] )
	            unset( $attr['orderby'] );
	    }
		
		if ( isset( $attr['columns'] ) ) {
	        switch (intval($attr['columns'])) {
		    	case 1:
		    		$size='tmedium';
		    		$box_width = '620px';
		    	break;
		    	case 2:
		    		$size='tmedium_gallery';
		    		$box_width = '460px';
		    	break;
		    	case 4:
		    		$size='tgallery';
		    		$box_width = '220px';
		    	break;
		    	default:
		    		$size='tgrid';
		    		$box_width = '300px';
		    	break;
		    }
	    }else{
	    	$size='tgrid';
	    	$box_width = '300px';
	    }	
  
	    extract(shortcode_atts(array(
	        'order'      => 'ASC',
	        'orderby'    => 'menu_order ID',
	        'id'         => $post->ID,
	        'itemtag'    => 'dl',
	        'icontag'    => 'dt',
	        'captiontag' => 'dd',
	        'columns'    => 3,
	        'size'       => $size,
	        'include'    => '',
	        'exclude'    => ''
	    ), $attr));

	    $id = intval($id);
	    if ( 'RAND' == $order )
	        $orderby = 'none';

	    if ( !empty($include) ) {
	        $include = preg_replace( '/[^0-9,]+/', '', $include );
	        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

	        $attachments = array();
	        foreach ( $_attachments as $key => $val ) {
	            $attachments[$val->ID] = $_attachments[$key];
	        }
	    } elseif ( !empty($exclude) ) {
	        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
	        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	    } else {
	        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	    }

	    if ( empty($attachments) )
	        return '';

	    if ( is_feed() ) {
	        $output = "\n";
	        foreach ( $attachments as $att_id => $attachment )
	            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
	        return $output;
	    }

	    $itemtag = tag_escape($itemtag);
	    $captiontag = tag_escape($captiontag);
	    $columns = intval($columns);
	    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	    $float = is_rtl() ? 'right' : 'left';

	    $selector = "gallery-{$instance}";

	    $output = apply_filters('gallery_style', "
	        <style type='text/css'>
	            #{$selector} {
	                margin: auto;
	            }
	            #{$selector} .gallery-item {
	                float: {$float};
	                margin-top: 10px;
	                text-align: center;
	                         }
	            #{$selector} img {
	                /*border: 2px solid #cfcfcf;*/
	            }
	            #{$selector} .gallery-caption {
	                margin-left: 0;
	            }
	        </style>
	        <!-- see gallery_shortcode() in wp-includes/media.php -->
	        <div id='$selector' class='gallery galleryid-{$id} colls-{$columns}'>");

	    $i = 0;
		$rand_id = mt_rand(1,1000);
	    foreach ( $attachments as $id => $attachment ) {
			$image_attributes = wp_get_attachment_image_src( $id,'large' );

			$link = isset($attr['link']) && 'file' == $attr['link'] ? '<a href="'.$image_attributes[0].'"  rel="prettyPhoto[pp_'.$rand_id.']"><span class="mosaic-overlay"></span>'.wp_get_attachment_image($id, $size, false).'</a>' : '<a href="'.$image_attributes[0].'"  rel="prettyPhoto[pp_'.$rand_id.']"><span class="mosaic-overlay"></span>'.wp_get_attachment_image($id, $size, false).'</a>';
	        $output .= "<{$itemtag} class='gallery-item'>";
	        $output .= "
	            <{$icontag} class='gallery-icon'>
	                $link
	            </{$icontag}>";
	        if ( $captiontag && trim($attachment->post_title) ) {
	            $output .= "
	                <{$captiontag} class='gallery-caption'>
	                " .wp_get_attachment_link($id, $size, true, false, wptexturize($attachment->post_title))  . "
	                </{$captiontag}>";
	        }
	        $output .= "</{$itemtag}>";
	        /*if ( $columns > 0 && ++$i % $columns == 0 )
	            $output .= '<br style="clear: both" />';*/
	    }

	    $output .= "</div>\n";

	    return $output;
	}
    
    function clear_meta( $post_id ){

        $resources = array( 'conference' => array( 'sponsor' , 'presentation' , 'exhibitor' )  , 'presentation' => array( 'speaker' )  );
        foreach( $resources as $res => $boxes ){
            $posts = get_posts( array( 'post_type' => $res ));
            foreach( $posts as $post ){
                foreach( $boxes as $box ){
                    $box_meta = meta::get_meta( $post -> ID , $box );
                    foreach( $box_meta as $index => $meta ){
                        if( $meta['idrecord'] == $post_id ){
                            meta::delete( $res , $box ,  $post -> ID , '' , $index );
                        }
                    }
                }
            }
        }
    }

	function dimox_breadcrumbs() {

	  $delimiter = '';
	  $home = __('Home','cosmotheme'); // text for the 'Home' link

	  $before = '<li>'; // tag before the current crumb
	  $after = '</li>'; // tag after the current crumb

	  if (  !is_front_page() || is_paged() ) {

	    /*echo '<div id="crumbs">';*/

	    global $post;
	    $homeLink = home_url();
	    echo '<li><a href="' . $homeLink . '">' . $home . '</a> </li>' . $delimiter . ' ';

	    if ( is_category() ) {
	      global $wp_query;
	      $cat_obj = $wp_query->get_queried_object();
	      $thisCat = $cat_obj->term_id;
	      $thisCat = get_category($thisCat);
	      $parentCat = get_category($thisCat->parent);
	      if ($thisCat->parent != 0) echo($before .get_category_parents($parentCat, TRUE, ' ' . '</li><li>' . ' '). $after);
	      echo $before . __('Archive by category','cosmotheme').' "' . single_cat_title('', false) . '"' . $after;

	    } elseif ( is_day() ) {
	      echo $before.'<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> '. $after . $delimiter . ' ';
	      echo $before.'<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> '. $after . $delimiter . ' ';
	      echo $before . get_the_time('d') . $after;

	    } elseif ( is_month() ) {
	      echo $before . '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> '. $after . $delimiter . ' ';
	      echo $before . get_the_time('F') . $after;

	    } elseif ( is_year() ) {
	      echo $before . get_the_time('Y') . $after;

	    } elseif ( is_single() && !is_attachment() ) {
	      if ( get_post_type() != 'post' ) {

	        $post_type = get_post_type_object(get_post_type());
	        $slug = $post_type->rewrite;
	        echo $before . '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> '. $after . $delimiter . ' ';
	        echo $before . get_the_title() . $after;
	      } else {
	        $cat = get_the_category(); $cat = $cat[0];
	        echo $before . get_category_parents($cat, TRUE, ' ' . '</li><li>' . ' ') . $after;
	        echo $before . get_the_title() . $after;
	      }

	    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
	      $post_type = get_post_type_object(get_post_type()); 
          if($post_type){
            echo $before . $post_type->labels->singular_name . $after;
          }  

	    } elseif ( is_attachment() ) {
	      $parent = get_post($post->post_parent);
	      /*$cat = get_the_category($parent->ID); $cat = $cat[0];*/
	      /*echo $before . get_category_parents($cat, TRUE, ' ' . $delimiter . ' ') . $after;*/
	      echo $before . '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> '. $after . $delimiter . ' ';
	      echo $before . get_the_title() . $after;

	    } elseif ( is_page() && !$post->post_parent ) {
	      echo $before . get_the_title() . $after;

	    } elseif ( is_page() && $post->post_parent ) {
	      $parent_id  = $post->post_parent;
	      $breadcrumbs = array();
	      while ($parent_id) {
	        $page = get_page($parent_id);
	        $breadcrumbs[] = $before .'<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>'.$after ;
	        $parent_id  = $page->post_parent;
	      }
	      $breadcrumbs = array_reverse($breadcrumbs);
	      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
	      echo $before . get_the_title() . $after;

	    } elseif ( is_search() ) {
	      echo $before . __('Search results for','cosmotheme').' "' . get_search_query() . '"' . $after;

	    } elseif ( is_tag() ) {
	      echo $before . __('Posts tagged','cosmotheme').' "' . single_tag_title('', false) . '"' . $after;

	    } elseif ( is_author() ) {
	       global $author;
	      $userdata = get_userdata($author);
	      echo $before . __('Articles posted by ','cosmotheme') . $userdata->display_name . $after;

	    } elseif ( is_404() ) {
	      echo $before . __('Error 404','cosmotheme') . $after;
	    }

	    if ( get_query_var('paged') ) {
	      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
	      echo __('Page','cosmotheme') . ' ' . get_query_var('paged');
	      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
	    }

	  	if(is_home()){
			echo $before . __('Blog','cosmotheme'). $after;
		}

	    /*echo '</div>';*/

	  }
	} /* end dimox_breadcrumbs()*/

    function remove_post_custom_fields() {
        remove_meta_box( 'postcustom' , 'post' , 'normal' );
    }
	
	function get_bg_image(){
            $pattern = explode( '.' , options::get_value( 'styling' , 'background' ) ) ; 
            if( isset( $pattern[ count( $pattern ) - 1 ] ) && $pattern[ count( $pattern ) - 1 ] == 'none'  || get_background_image() != '' ){
                $background_img = '';
            }else{
                $background_img_url = str_replace( 's.pattern.' , 'pattern.' , options::get_value( 'styling' , 'background' ) );
                if(strpos($background_img_url,'day') || strpos($background_img_url,'night')) { 
                    $background_img_url = str_replace( '.png' , '.jpg' , $background_img_url );  
                }
                $pieces = explode("/", $background_img_url);
                $background_img = $pieces[count($pieces) -1 ]; 	
            }
            
			/*if cookies are set we overite the settings*/ 
			if( isset($_COOKIE[ZIP_NAME."_bg_image"]) ){  
				$background_img = 'pattern.'.trim($_COOKIE[ZIP_NAME."_bg_image"].'.png');  
			}
			
			return $background_img;
	}
	
	function get_content_bg_color(){

            $background_color = options::get_value( 'styling' , 'background_color' );
            
			/*if cookies are set we ovewrite the settings*/
			if(isset($_COOKIE[ZIP_NAME."_content_bg_color"])){ 
				$background_color = trim($_COOKIE[ZIP_NAME."_content_bg_color"]); 
			}
			
			return $background_color;
	}
	
	function get_footer_bg_color(){
		if(isset($_COOKIE[ZIP_NAME."_footer_bg_color"])){ 
			$footer_background_color = trim($_COOKIE[ZIP_NAME."_footer_bg_color"]);
		}else{
			$footer_background_color = options::get_value( 'styling' , 'footer_bg_color' );
		}
		
		return $footer_background_color;
	}

    function get_slide_resources(){

        $type_res = isset( $_POST['res_type'] ) ? trim( $_POST['res_type'] ) : exit;
        $field_id   = isset( $_POST['field_id'] ) ?  $_POST['field_id'] : exit;


        if(  $type_res == 'none' ){
            exit;
        }

        if(  $type_res == 'program' ){
            $data  = get__posts( array( 'post_type' =>  'conference' ,'numberposts' => -1) );

        }else{
            $data = get__posts( array( 'post_type' =>  $type_res,'numberposts' => -1 ) , false );
        }

        $result     = '';

        if( count( $data ) ){
            $result .= '<select id="' . $field_id . '" name="box[resources][]">';

            foreach( $data as $id => $value ){
                $result .= '<option value="' . $id . '">' . $value . '</option>';

            }

            $result .= '</select>';
        }else{

        }

        echo $result;
        exit;
    }

    function get_slide_resources_label(){
        $type_res = isset( $_POST['res_type'] ) ? trim( $_POST['res_type'] ) : exit;
		if(isset(resources::$box['slideshow']['box']['content']['resources']['multiple_label'][ $type_res ])){
			echo resources::$box['slideshow']['box']['content']['resources']['multiple_label'][ $type_res ];
		}
        exit;
    }

	function cosmo_avatar( $user_info, $size, $default = DEFAULT_AVATAR ) {
		
		$avatar = '';
        if( is_numeric( $user_info ) ){
            if( get_user_meta( $user_info , 'custom_avatar' , true ) == -1 ){
                $avatar = '<img src="' . $default . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
            }else{
                if(  get_user_meta( $user_info , 'custom_avatar' , true ) > 0 ){
                    $cusom_avatar = wp_get_attachment_image_src( get_user_meta( $user_info , 'custom_avatar' , true ) , array( $size , $size ) );
                    $avatar = '<img src="' . $cusom_avatar[0] . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
                }else{
                    $avatar = get_avatar( $user_info , $size , $default = $default );
                }
            }
            
        }else{
            if( is_object( $user_info ) ){
                if( isset( $user_info -> user_id ) && is_numeric( $user_info -> user_id ) && $user_info -> user_id > 0 ){
                    if( get_user_meta( $user_info -> user_id , 'custom_avatar' , true ) == -1 ){
                        $avatar = '<img src="' . $default . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
                    }else{
                        if( get_user_meta( $user_info -> user_id , 'custom_avatar' , true ) > 0 ){
                            $cusom_avatar = wp_get_attachment_image_src( get_user_meta( $user_info -> user_id , 'custom_avatar' , true ) , array( $size , $size ) );
                            $avatar = '<img src="' . $cusom_avatar[0] . '" height="' . $size . '" width="' . $size . '" alt="" class="photo avatar" />';
                        }else{
                            $avatar = get_avatar( $user_info , $size , $default = $default );
                        }
                    }
                }else{
                    $avatar = get_avatar( $user_info , $size , $default = $default );
                }
            }else{
                $avatar = get_avatar( $user_info , $size , $default = $default );
            }
        }
		
        return $avatar;
	}
?>