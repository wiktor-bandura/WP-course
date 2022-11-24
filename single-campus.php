<?php
get_header();

while(have_posts()) {
	the_post();
    page_banner();
    ?>


	<div class="container container--narrow page-section">

		<div class="metabox metabox--position-up metabox--with-home-link">
			<p>
				<a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a>
				<span class="metabox__main">
                        <?php the_title(); ?>
                    </span>
			</p>
		</div>

		<div class="generic-content"><?php the_content(); ?></div>

        <div class="acf-map">
			<?php $map_location = get_field('map_location'); ?>
                <div data-lat="<?php echo $map_location['lat']; ?>" data-lng="<?php echo $map_location['lng']; ?>" class="marker">
                    <h3><?php the_title(); ?></h3>
					<?php echo $map_location['address']; ?>
                </div>
        </div>

        <?php

	$related_program = new WP_Query([
		'post_type' => 'program',
		'orderby' => 'title',
		'order' => 'ASC',
		'meta_query' => [
			array(
				'key' => 'related_campus',
				'compare' => 'LIKE',
				'value' => '"'.get_the_ID().'"',
			)
		],
	]);
	if($related_program->have_posts()) { ?>
        <hr class="section-break">
        <h2 class="headline headline--medium">Programs on this campus: </h2>

		<?php
        echo ' <ul class="link-list min-list">';
		while($related_program->have_posts()) {
			$related_program->the_post();
			?>
            <li><a href="<?php echo get_permalink() ?>"><?php echo get_the_title(); ?> </a></li>
		<?php }
        echo '</ul>';
		wp_reset_postdata();
    } ?>
	</div>

<?php }
    get_footer();
?>