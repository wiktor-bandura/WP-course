<?php
get_header();
while(have_posts()) {
	the_post();
	page_banner();
	?>
	<div class="container container--narrow page-section">
	<?php
		$parentPage = wp_get_post_parent_id(get_the_ID());

		$testArray = get_pages([
			'child-of' => get_the_ID()
		]);

		if($parentPage or $testArray) { ?>

			<div class="page-links">
				<h2 class="page-links__title"><a href="<?php echo get_permalink($parentPage); ?>"><?php echo get_the_title($parentPage); ?></a></h2>
				<ul class="min-list">
					<?php
					if($parentPage) {
						$findChildrenOf = $parentPage;
					} else {
						$findChildrenOf = get_the_ID();
					}

					wp_list_pages(array(
						'title_li' => NULL,
						'child_of' => $findChildrenOf,
					));

					?>
				</ul>
			</div>

		<?php } ?>

        <div class="generic-content">
            <form method="get" action="<?php echo esc_url(site_url('/')); ?>">
                <label>
                    <input type="search" name="s">
                </label>
                <input type="submit" value="Search">
            </form>
        </div>

	</div>
	<?php
}
get_footer();
?>