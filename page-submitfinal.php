<?php
/**
 * Template Name: Submit Final
 *
 */
get_header();
$post_id = $_GET['post_id'];

$update_post = array(
    'ID' => $post_id,
    'post_status' => 'pending'
);

wp_update_post( $update_post );

?>
<div id="main-content">
    <div class="main-content-region">
        <p> Thank you! Your posting has been submitted and your account will be billed </p>
    </div>
</div>
<?php get_footer(); ?>
