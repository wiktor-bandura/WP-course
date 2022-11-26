<?php
get_header();

while(have_posts()) {
	the_post();
    page_banner(); ?>


    <div class="container container--narrow page-section">

        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail('portrait'); ?>
                </div>
                <div class="two-thirds">
                    <?php
                        $like_count = new WP_Query(array(
                                'post_type' => 'like',
                                'meta_query' => array(
                                        array(
                                            'key' => 'liked_professor_id',
                                            'compare' => '=',
                                            'value' => get_the_ID(),
                                        )
                                )
                        ));

                        $exist_status = 'no';

                        $exist_query = new WP_Query(array(
                            'author' => get_current_user_id(),
                            'post_type' => 'like',
                            'meta_query' => array(
                                array(
                                    'key' => 'liked_professor_id',
                                    'compare' => '=',
                                    'value' => get_the_ID(),
                                )
                            )
                        ));

                        if($exist_query->found_posts) {
                            $exist_status = 'yes';
                        }

                    ?>

                    <span class="like-box" data-exists="<?php echo $exist_status; ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count">
                            <?php echo $like_count->found_posts; ?>
                        </span>
                    </span>
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
        <hr class="section-break">
        <h2 class="headline headline--medium">This professor teaches: </h2>
        <ul class="link-list min-list">
			<?php
			$related_programs = get_field('related_programs');
			if($related_programs) {
				foreach ($related_programs as $program) { ?> <li><a href="<?php echo get_permalink($program) ?>"><?php echo get_the_title($program); ?> </a></li>
				<?php }} ?>
        </ul>
    </div>

	<?php
}
get_footer();
?>