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
            $title = $_POST['fields']['field_59d3d72a30576'];
            ?>

            <div class="card">
                <div class="card-half">
                <?php
                acf_form(array(
                    'post_id'       => 'new_post',
                    'field_groups'  => array( 'group_59d3d71e1ef3e' ),
                    'submit_value'  => 'Create a new Rotary Flyer Entry',
                    'new_post' => array(
                        'post_type' => 'vendi-rotary-flyer',
                        'post_status'   => 'publish',
                        'post_title' => $title

                    ),
                    'return' => home_url('thank-you')
                ));
                ?>
                </div>
                <div class="card-half">
                    <h1> Preview: </h1>
                    <div class="rotary-text">
                        <h2 id="rotary-header-preview"> </h2>
                        <div id="rotary-body-preview">

                        </div>
                    </div>
                    <div class="rotary-image-container">
                        <img id="rotary-image-preview" data-name="image" src="" alt="">
                    </div>
                </div>
            </div>
            <?php
            ?>
        <?php endwhile; ?>
    </div>
</div>


<?php get_footer(); ?>
