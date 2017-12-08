<?php
/*
Plugin Name: Vendi Rotary Flyer
Version: 0.1
*/

define( 'VENDI_ROTARY_FLYER_FILE', __FILE__ );
define( 'VENDI_ROTARY_FLYER_DIR', dirname( __FILE__ ) );
define( 'VENDI_ROTARY_PLUGIN_NAME', 'vendi-rotary-flyer');
define( 'VENDI_ROTARY_USER_DASHBOARD', '/rotary-user-dashboard/');
define( 'VENDI_ROTARY_PDF_CREATION', '/test-page/');
define( 'VENDI_ROTARY_ADMIN_DASHBOARD', '/admin-view/');
define( 'VENDI_ROTARY_PDF_GENERATION_PAGE', '/pdf-builder/' );
define( 'VENDI_ROTARY_CREDITS_PURCHASE_PAGE', '/purchase-credits/' );
define( 'VENDI_ROTARY_CREDITS_CONFIRMATION_PAGE', '/credit-purchase-confirmation/' );
require_once VENDI_ROTARY_FLYER_DIR . '/includes/autoload.php';


//this is the ajax action for updating status on posts from the front end
function my_action() {
    global $wpdb; // this is how you get access to the database

    //$data_back = json_decode(file_get_contents('php://input'));
    //$id = $data_back['id'];
    //$status = $data_back['status'];

    $status = $_POST['status'];
    $id = $_POST['id'];
    $result = Vendi\RotaryFlyer\post_modifier::set_post_status($id, $status);
    echo $result;

    wp_die(); // this is required to terminate immediately and return a proper response
}
add_action( 'wp_ajax_my_action', 'my_action' );

//this is the ajax action for creating a new post out of a placeholder
function convert_placeholder() {
    global $wpdb; // this is how you get access to the database

    $id = $_POST['id'];
    $date = $_POST['date'];
    $result = Vendi\RotaryFlyer\post_modifier::create_post_from_placeholder_id($id, $date);

    echo $result;

    wp_die();

}
add_action('wp_ajax_convert_placeholder', 'convert_placeholder');



Vendi\RotaryFlyer\content_types::init();
Vendi\RotaryFlyer\user_types::init();
Vendi\RotaryFlyer\page_templater::init();
new Vendi\RotaryFlyer\login;
