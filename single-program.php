<?php
get_header(); //

while(have_posts()) {
	the_post();
    page_banner(); ?>

	<div class="container container--narrow page-section">

		<div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All programs</a>
				<span class="metabox__main">
                        <?php the_title(); ?>
                    </span>
			</p>
		</div>

		<div class="generic-content"><?php the_content(); ?></div>
        <hr class="section-break">
        <h2 class="headline headline--medium">Upcoming <?php the_title() ?> events: </h2>

		<?php
		$today = date('Ymd');
		$homepage_events_query = new WP_Query([
			'post_type' => 'event',
			'meta_key' => 'event_date',
			'orderby' => 'meta_value_num',
			'order' => 'ASC',
			'meta_query' => [
				array(
					'key' => 'event_date',
					'compare' => '>=',
					'value' => $today,
				),
                array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"'.get_the_ID().'"',
                )
			],
		]);
        if($homepage_events_query) {
		while($homepage_events_query->have_posts()) {
			$homepage_events_query->the_post();
            get_template_part('template-parts/content', 'event');
			wp_reset_postdata();
        }}

		$related_professors = new WP_Query([
			'post_type' => 'professor',
			'orderby' => 'title',
			'order' => 'ASC',
			'meta_query' => [
                array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"'.get_the_ID().'"',
                )
			],
		]);
            if($related_professors->have_posts()) { ?>
                <hr class="section-break">
                <h2 class="headline headline--medium">Professors: </h2>
                <?php
                while($related_professors->have_posts()) {
                    $related_professors->the_post();
                ?>
                <li class="professor-card__list-item"><a class="professor-card" href="<?php the_permalink(); ?>">
                        <img src="<?php the_post_thumbnail_url('landscape'); ?>" alt="Professor thumbnail" class="professor-card__image">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                </a></li>
                <?php }
                wp_reset_postdata();
            }

	 ?>

        <?php
            $related_campus = get_field('related_campus');

            if($related_campus) {
                echo '<h2 class="headline headline--medium">'.get_the_title().' is available At These Campuses:</h2>
                      <ul class="link-list min-list">';
                foreach ($related_campus as $campus) {
                    ?>
                        <li><a href="<?php echo get_the_permalink($campus) ?>"><?php echo get_the_title($campus); ?></a></li>
                   <?php

                }
	            echo '</ul>';
            }
        ?>

	</div>

	<?php
}
get_footer();
?>