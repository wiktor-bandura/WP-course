<?php

    if(!is_user_logged_in()) {
        wp_redirect(esc_url(site_url('/')));
        exit;
    }
    get_header();

    while(have_posts()) {
        the_post();
        page_banner();
        ?>
        <div class="container container--narrow page-section">
            <div class="create-note">
                <h2 class="headline headline--medium">Create new Note</h2>
                <input class="new-note-title" type="text" placeholder="Title">
                <textarea class="new-note-body" placeholder="Your Note here"></textarea>
                <span class="submit-note">Create Note</span>
                <span class="note-limit-message">Note limit reached: delete an existing note to make room for a new one.</span>
            </div>
            <ul class="min-list link-list" id="my-notes">
                <?php
                    $user_notes = new WP_Query(array(
                            'post_type' => 'note',
                            'post_per_page' => -1,
                            'author' => get_current_user_id()
                    ));
                    
                    while($user_notes->have_posts()) {
                        $user_notes->the_post(); ?>
                            <li data-id="<?php the_ID(); ?>">
                                <label>
                                    <input readonly class="note-title-field" value="<?php echo esc_attr(get_the_title()); ?>">
                                </label>
                                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit note: </span>
                                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete note: </span>
                                <label>
                                    <textarea readonly class="note-body-field"><?php echo esc_attr(wp_strip_all_tags(get_the_content(), false)); ?></textarea>
                                </label>
                                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save note: </span>
                            </li>    
                   <?php }
                ?>
            </ul>
        </div>
        <?php
    }
    get_footer();

?>