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
            <ul class="min-list link-list" id="my-notes">
                <?php
                    $user_notes = new WP_Query(array(
                            'post_type' => 'note',
                            'post_per_page' => -1,
                            'author' => get_current_user_id()
                    ));
                    
                    while($user_notes->have_posts()) {
                        $user_notes->the_post(); ?>
                            <li>
                                <label>
                                    <input class="note-title-field" value="<?php echo esc_attr(get_the_title()); ?>">
                                </label>
                                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit note: </span>
                                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete note: </span>
                                <label>
                                    <textarea class="note-body-field"><?php echo esc_attr(esc_attr(get_the_content())); ?></textarea>
                                </label>
                            </li>    
                   <?php }
                ?>
            </ul>
        </div>
        <?php
    }
    get_footer();

?>