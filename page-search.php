<?php
get_header();
while(have_posts()) {
	the_post();
	page_banner();
	?>
        <div class="generic-content">
            <form class="search-form" method="get" action="<?php echo esc_url(site_url('/')); ?>">
                <div class="search-form-row">
                    <label for="s" class="headline headline--medium"> Perform a new search:</label>
                    <input type="search" name="s" class="s">
                    <input class="search-submit" type="submit" value="Search">
                </div>
            </form>
        </div>
	<?php
}
get_footer();
?>