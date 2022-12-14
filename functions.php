<?php
    require get_theme_file_path('/includes/routes.php');

    function page_custom_rest() {
        register_rest_field('post', 'authorName', array(
            'get_callback' => function() { return get_the_author(); }
        ));
    }

    add_action('rest_api_init', 'page_custom_rest');

	function page_banner($args = array(
            'title' => NULL,
            'subtitle' => NULL,
            'photo' => NULL,
    )) {
		error_reporting(E_ERROR | E_PARSE);
        if(!isset($args['title'])) {
	        $args['title'] = get_the_title();
        }

        if(!isset($args['subtitle'])) {
	        $args['subtitle'] = get_field('page_baner_subtitle');
        }

        if(!isset($args['photo'])) {
	        $args['photo'] = get_field( 'page_baner_background_image' )['sizes']['page-banner'];

	        if(!isset($args['photo'])) {
		        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
	        }
        }

	?>
		<div class="page-banner">
			<div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>)"></div>
			<div class="page-banner__content container container--narrow">
				<h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
				<div class="page-banner__intro">
					<p><?php echo $args['subtitle']; ?></p>
				</div>
			</div>
		</div>
	<?php
	}

	function load_resources() {
		wp_enqueue_script('google-map', 'https://maps.googleapis.com/maps/api/js?key=#', array('jquery'), '1.0', true);
		wp_enqueue_script('website-main-js', get_theme_file_uri('/build/index.js'), array('jquery'), '1.2', true);
		wp_enqueue_style('font-google', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
		wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_enqueue_style('website_main_styles', get_theme_file_uri('/build/style-index.css'));
		wp_enqueue_style('website_extra_styles', get_theme_file_uri('/build/index.css'));

        wp_localize_script('website-main-js','themeData', array(
                'root_url' => get_site_url(),
                'nonce' => wp_create_nonce('wp_rest'),
        ));
	}

	add_action('wp_enqueue_scripts', 'load_resources');

	function load_features() {
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		add_image_size('landscape', 400, 260, true);
		add_image_size('portrait', 380, 650, true);
		add_image_size('page-banner', 1500, 350, true);
	}

	add_action('after_setup_theme', 'load_features');

	function page_adjust_queries($query){

		if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
			$query->set('post_per_page', -1);
		}

		if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
			$query->set('orderby', 'title');
			$query->set('order', 'ASC');
			$query->set('post_per_page', -1);
		}

		$today = date('Ymd');
		if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
			$query->set('meta_key', 'event_date');
			$query->set('orderby', 'meta_value_num');
			$query->set('order', 'ASC');
			$query->set('meta_query', array(
				'key' => 'event_date',
				'compare' => '>=',
				'value' => $today,
			));
		}
	}

	add_action('pre_get_posts', 'page_adjust_queries');

    function page_map_key($api) {
        $api['key'] = '#';
        return $api;
    }

    add_filter('acf/fields/google_map/api', 'page_map_key');

    // Redirect subscribers out of admin panel

    function redirect_subs_to_frontend() {
        $current_user = wp_get_current_user();

        if(count($current_user->roles) == 1 AND $current_user->roles[0] == 'subscriber') {
            wp_redirect(site_url('/'));
            exit;
        }
    }

    add_action('admin_init', 'redirect_subs_to_frontend');

    function no_subs_adminbar() {
        $current_user = wp_get_current_user();

        if(count($current_user->roles) == 1 AND $current_user->roles[0] == 'subscriber') {
            show_admin_bar(false);
        }
    }

    add_action('wp_loaded', 'no_subs_adminbar');

    function custom_headerurl() {
        return esc_url(site_url('/'));
    }

    add_filter('login_headerurl', 'custom_headerurl');

    add_action('login_enqueue_scripts', 'custom_login_css');

    function custom_login_css() {
	    wp_enqueue_style('font-google', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	    wp_enqueue_style('website_main_styles', get_theme_file_uri('/build/style-index.css'));
	    wp_enqueue_style('website_extra_styles', get_theme_file_uri('/build/index.css'));
    }

    function custom_headertitle() {
        return get_bloginfo('name');
    }

    add_filter('login_headertitle', 'custom_headertitle');

// Force notes to be private

//    function force_private($data, $postarr) {
//
//        if($data['post-type'] == 'note') {
//            if(count_user_posts(get_current_user_id(), 'note') > 4 AND $postarr['ID']) {
//                die('You have reached your note limit.');
//            }
//        }
//
//        if($data['post_type'] == 'note') {
//            $data['post_content'] = sanitize_textarea_field($data['post_content']);
//	        $data['post_title'] = sanitize_text_field($data['post_title']);
//        }
//
//        if($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
//	        $data['post_status'] = 'private';
//        }
//
//        return $data;
//    }
//
//    add_filter('wp_insert_post_data', 'force_private');