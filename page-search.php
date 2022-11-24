<?php
get_header();
while(have_posts()) {
	the_post();
	page_banner();
	?>
	<div class="container container--narrow page-section">

	</div>
	<?php
}
get_footer();
?>