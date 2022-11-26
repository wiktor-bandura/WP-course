<?php

	// LIKE ROUTE

	function page_register_like() {
		register_rest_route('university', 'managelike', array(
			'methods' => 'POST',
			'callback' => ,
		));

		register_rest_route('university', 'managelike', array(
			'methods' => 'DELETE',
			'callback' => ,
		));
	}

	function create_like() {

	}

	function delete_like() {
		
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
				array_push($programs_meta_query, array(
					'key' => 'related_programs',
					'compare' => 'LIKE',
					'value' => '"'.$item['id'].'"'
				));
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