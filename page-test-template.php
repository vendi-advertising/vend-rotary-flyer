<?php
/**
 * Template Name: test
 *
 */
?>
<?php
require 'rotary-functions.php';

acf_form_head();

get_header();


?>

<div id="main-content">
    <div class="main-content-region">
        <?php /* The loop */ ?>
        <?php while ( have_posts() ) : the_post(); ?>

            <?php

            acf_form(array(
                'post_id'       => 'new',
                'field_groups'  => array( 10 ),
                'submit_value'  => 'Create a new Rotary Flyer Entry',
                'return' => '/thank-you'
            ));

            ?>
        <?php endwhile; ?>
    </div>
</div>


<?php get_footer(); ?>
