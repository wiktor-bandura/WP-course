<?php
	get_header();
	page_banner(array(
		'title' => 'Search Results',
		'subtitle' => 'You searched for &ldquo;'.esc_html(get_search_query(false)).'&rdquo;'
	));
?>
	<div class="container container--narrow page-section">

		<?php
		if(have_posts()) {
			while ( have_posts() ) {
				the_post();
				get_template_part( '/template-parts/content', get_post_type() );
			}
			echo paginate_links();
		} else {
            echo '<h2 class="headline headline--small">No result match that search.</h2>';
        }
		?>
	</div>

<?php get_footer(); ?>