<?php
	// LIKE ROUTE

	function page_register_like() {
		register_rest_route('university', 'managelike', array(
			'methods' => 'POST',
			'callback' => 'create_like',
		));

		register_rest_route('university', 'managelike', array(
			'methods' => 'DELETE',
			'callback' => 'delete_like',
		));
	}

	function create_like($data) {

		if(is_user_logged_in()) {
			$professor = sanitize_text_field($data['professorId']);

			$exist_query = new WP_Query(array(
				'author' => get_current_user_id(),
				'post_type' => 'like',
				'meta_query' => array(
					array(
						'key' => 'liked_professor_id',
						'compare' => '=',
						'value' => $professor
					)
				)
			));

			if($exist_query->found_posts == 0 AND get_post_type($professor) == 'professor') {
				return wp_insert_post(array(
					'post_type' => 'like',
					'post_status' => 'publish',
					'title' => 'Example like',
					'meta_input' => array(
						'liked_professor_id' => $professor,
					)
				));
			} else {
				die('Invalid professor ID');
			}
		} else {
			die('Only logged in users can create a like.');
		}
	}

	function delete_like($data) {
		$like_ID = sanitize_text_field($data['like']);
		if(get_current_user_id() == get_post_field('post_author', $like_ID) AND get_post_type($like_ID) == 'like') {
			wp_delete_post($like_ID, true);
		} else {
			die("You don't have permission to delete that!");
		}
	}

	add_action('rest_api_init', 'page_register_like');

	// SEARCH ROUTES

	function page_register_search() {
		register_rest_route('university','search', array(
			'methods' => WP_REST_Server::READABLE,
			'callback' => 'page_search_results',
		));
	}

/**
 * @throws Exception
 */
function page_search_results($data) {

		$main_query = new WP_Query(array(
			'post_type' => array('post', 'page', 'professor', 'program', 'campus', 'event'),
			's' => sanitize_text_field( $data['term'] ),
		));
		$results = array(
			'generalInfo' => array(),
			'professors' => array(),
			'programs' => array(),
			'events' => array(),
			'campuses' => array(),
		);

		while($main_query->have_posts()) {
			$main_query->the_post();

			if(get_post_type() == 'post' or get_post_type() == 'page') {
				$results['generalInfo'][] = array(
					'title' => get_the_title(),
					'link'  => get_the_permalink(),
					'postType' => get_post_type(),
					'authorName' => get_the_author(),
				);
			}

			if(get_post_type() == 'professor') {
				$results['professors'][] = array(
					'title' => get_the_title(),
					'link'  => get_the_permalink(),
					'image' => get_the_post_thumbnail_url(0, 'landscape')
				);
			}

			if(get_post_type() == 'program') {

				$related_campuses = get_field('related_campus');

				if($related_campuses) {
					foreach($related_campuses as $campus) {
						$results['campuses'][] = array(
							'title' => get_the_title($campus),
							'link' => get_the_permalink($campus),
						);
					}
				}


				$results['programs'][] = array(
					'title' => get_the_title(),
					'link'  => get_the_permalink(),
					'id' => get_the_id(),
				);
			}

			if(get_post_type() == 'campus') {
				$results['campuses'][] = array(
					'title' => get_the_title(),
					'link'  => get_the_permalink(),
				);
			}

			if(get_post_type() == 'event') {

				$eventDate = new DateTime(get_field('event_date'));

				$results['events'][] = array(
					'title' => get_the_title(),
					'link'  => get_the_permalink(),
					'month' => $eventDate->format('M'),
					'day' => $eventDate->format('d'),
				);
			}
		}

		if($results['programs']) {
			$programs_meta_query = array('relation', 'OR');

			foreach ($results['programs'] as $item) {
				$programs_meta_query[] = array(
					'key'     => 'related_programs',
					'compare' => 'LIKE',
					'value'   => '"' . $item['id'] . '"'
				);
			}

			$program_relationship_query = new WP_Query(array(
				'post_type' => array('professor', 'event'),
				'meta_query' => $programs_meta_query,
			));

			while($program_relationship_query->have_posts()) {
				$program_relationship_query->the_post();

				if(get_post_type() == 'professor') {
					$results['professors'][] = array(
						'title' => get_the_title(),
						'link'  => get_the_permalink(),
						'image' => get_the_post_thumbnail_url(0, 'landscape'),
					);
				}

				if(get_post_type() == 'event') {

					$eventDate = new DateTime(get_field('event_date'));

					$results['events'][] = array(
						'title' => get_the_title(),
						'link'  => get_the_permalink(),
						'month' => $eventDate->format('M'),
						'day' => $eventDate->format('d'),
					);
				}
			}
		}

		return $results;
	}

	add_action('rest_api_init', 'page_register_search');