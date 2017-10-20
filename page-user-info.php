<?php
/**
 * Template Name: User Info
 *
 */

require 'rotary-functions.php';

acf_form_head();
get_header();
?>

<div id="main-content">
    <div class="main-content-region">
        <?php while ( have_posts() ) : the_post(); ?>
            <?php

            acf_form(array(
                'field_groups'  => array( 'group_59e7b425beec4' ),
                'submit_value'  => 'Proceed to posting creation',
                'return' => home_url('test-page'),
                'html_submit_button'    => '<input type="submit" class="steps-button acf-button button button-primary button-large" value="%s" />'

            ));

            ?>
        <?php endwhile; ?>
    </div>

</div>

<?php

get_footer();
