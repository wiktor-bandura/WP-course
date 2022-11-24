<?php
    get_header();
    page_banner(['title' => 'Events', 'subtitle' => 'See whats going on.']);
?>

	<div class="container container--narrow page-section">

		<?php
		while(have_posts()) {
			the_post();
            get_template_part('template-parts/content', 'event');
        }
		echo paginate_links();
		?>
	</div>

<?php get_footer(); ?><?php
