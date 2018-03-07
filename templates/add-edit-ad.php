<?php

$role = get_role('Rotary User');
$role->add_cap('upload_files');
// require 'rotary-functions.php';
wp_enqueue_style('common');
wp_enqueue_media();
acf_form_head();


\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();

//vendi_rotary_register_plugin_js( '100-floating-preview.js' );
vendi_rotary_register_plugin_js( '000-rotary-live-preview.js' );
vendi_rotary_register_plugin_js( '100-floating-preview.js' );
vendi_rotary_register_plugin_js( '150-rotary-acf-custom-validation.js', array( 'acf-input' ));

if(isset($_GET['post_id'])){
    $post_id = $_GET['post_id'];
    $header_text = 'Edit this ad';
}
else{
    $post_id = 'new_post';
    $header_text = 'Create an ad';
}
//test comment

?>
<?php
            $user = wp_get_current_user();

            $admin_button = '';
            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                {

                    $admin_button = sprintf( '<a class="steps-button" href="%1$s"> Admin Dashboard </a>', \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('admin-dashboard') );
                }
?>
<div id="main-content">
    <div class="main-content-region">
        <h1> <?php echo $header_text; ?> </h1>
        <?php /* The loop */

        ?>
        <?php while ( have_posts() ) : the_post(); ?>

            <div class="card">
                <div class="card-half acf-half">
                <?php
                acf_form(array(
                    'post_id'       => $post_id,
                    'field_groups'  => array( 'group_59d3d71e1ef3e' ),
                    'submit_value'  => 'Finalize Ad Post',
                    'new_post' => array(
                        'post_type' => 'vendi-rotary-flyer',
                        'post_status'   => 'draft',
                        'post_title' => '',

                    ),
                    'uploader' => 'wp',
                    'return' => \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('ad-thank-you'),
                    'html_submit_button'    => '<input type="submit" class="acf-button button button-primary button-large" value="%s" /> ' . $admin_button
                ));
                ?>
                </div>
                <div id="preview-region" class="card-half rotary-half">
                    <h1> Preview </h1>
                    <div class="rotary-preview headerbodytextimage">
                        <div class="rotary-preview-wrapper">

                            <div class="rotary-text">
                                <h2 id="rotary-header-preview"> </h2>
                                <div id="rotary-body-preview">

                                </div>
                            </div>
                                <img id="rotary-image-preview" data-name="image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/transparent-placeholder.png" >
                        </div>
                    </div>
                </div>
            </div>
            <?php
            ?>
        <?php endwhile; ?>
    </div>
</div>


<?php
\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
