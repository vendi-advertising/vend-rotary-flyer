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
        <p> Thank you! Your posting has been submitted and your account will be billed. </p>

        <?php
        $user = wp_get_current_user();

        if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
            {
                $dashboard = VENDI_ROTARY_ADMIN_DASHBOARD;
            }
            else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                $dashboard = VENDI_ROTARY_USER_DASHBOARD;
            }

        ?>

        <a href="<?php echo $dashboard; ?>"> Return to Dashboard </a>
    </div>
</div>
<?php get_footer(); ?>
