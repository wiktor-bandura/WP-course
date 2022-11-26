<?php
    get_header();
    while(have_posts()) {
        the_post();
        page_banner();
?>
    <div class="container container--narrow page-section">
        <?php
            $parentPage = wp_get_post_parent_id(get_the_ID());

            if($parentPage) { ?>
                <div class="metabox metabox--position-up metabox--with-home-link">
                    <p>
                        <a class="metabox__blog-home-link" href="<?php echo get_permalink($parentPage); ?>"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;Back to <?php echo get_the_title($parentPage); ?></a> <span class="metabox__main"><?php the_title(); ?></span>
                    </p>
                </div>
            <?php }

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
            <?php
                the_content();
            ?>
        </div>
    </div>

    <div class="page-section page-section--beige">
        <div class="container container--narrow generic-content">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia voluptates vero vel temporibus aliquid possimus, facere accusamus modi. Fugit saepe et autem, laboriosam earum reprehenderit illum odit nobis, consectetur dicta. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos molestiae, tempora alias atque vero officiis sit commodi ipsa vitae impedit odio repellendus doloremque quibusdam quo, ea veniam, ad quod sed.</p>

            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia voluptates vero vel temporibus aliquid possimus, facere accusamus modi. Fugit saepe et autem, laboriosam earum reprehenderit illum odit nobis, consectetur dicta. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos molestiae, tempora alias atque vero officiis sit commodi ipsa vitae impedit odio repellendus doloremque quibusdam quo, ea veniam, ad quod sed.</p>
        </div>
    </div>

    <div class="page-section page-section--white">
        <div class="container container--narrow">
            <h2 class="headline headline--medium">Biology Professors:</h2>

            <ul class="professor-cards">
                <li class="professor-card__list-item">
                    <a href="#" class="professor-card">
                        <img class="professor-card__image" src="<?php echo get_theme_file_uri('/images/barksalot.jpg'); ?>" />
                        <span class="professor-card__name">Dr. Barksalot</span>
                    </a>
                </li>
                <li class="professor-card__list-item">
                    <a href="#" class="professor-card">
                        <img class="professor-card__image" src="<?php echo get_theme_file_uri('/images/meowsalot.jpg'); ?>" />
                        <span class="professor-card__name">Dr. Meowsalot</span>
                    </a>
                </li>
            </ul>
            <hr class="section-break" />

            <div class="row group generic-content">
                <div class="one-third">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia voluptates vero vel temporibus aliquid possimus, facere accusamus modi. Fugit saepe et autem, laboriosam earum reprehenderit illum odit nobis, consectetur dicta. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos molestiae, tempora alias atque vero officiis sit commodi ipsa vitae impedit odio repellendus doloremque quibusdam quo, ea veniam, ad quod sed.</p>
                </div>

                <div class="one-third">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia voluptates vero vel temporibus aliquid possimus, facere accusamus modi. Fugit saepe et autem, laboriosam earum reprehenderit illum odit nobis, consectetur dicta. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos molestiae, tempora alias atque vero officiis sit commodi ipsa vitae impedit odio repellendus doloremque quibusdam quo, ea veniam, ad quod sed.</p>
                </div>

                <div class="one-third">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia voluptates vero vel temporibus aliquid possimus, facere accusamus modi. Fugit saepe et autem, laboriosam earum reprehenderit illum odit nobis, consectetur dicta. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quos molestiae, tempora alias atque vero officiis sit commodi ipsa vitae impedit odio repellendus doloremque quibusdam quo, ea veniam, ad quod sed.</p>
                </div>
            </div>
        </div>
    </div>
<?php
    }
    get_footer();
?>