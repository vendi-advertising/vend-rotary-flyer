<?php
/**
 * Template Name: Thank you
 *
 */
?>
<?php
require 'rotary-functions.php';
$pdf_output = true;
get_header();
//Vendi\RotaryFlyer\pdf_generator::generate_from_url();
vendi_rotary_register_ajax_js( '200-slider-enable.js', array('jquery'));
vendi_rotary_register_plugin_js( '250-place-holder-modal.js');

$wp_scripts = wp_scripts();
vendi_rotary_register_outside_css( 'jqueryui-smoothness-theme','https://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css')
?>

<?php

include 'placeholder-modal.php';

if(isset($_GET['date'])){
    $date = $_GET['date'];
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
    $date = str_replace( "_", "/", $date);
    Vendi\RotaryFlyer\pdf_generator::generate_preview_for_date($date_organized_posts[$date], $date);

}
else{
    Vendi\RotaryFlyer\pdf_generator::generate();
}


?>

<?php

//echo '<link rel="stylesheet" id="pdf-css" href="' . VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css" type="text/css" media="all">';

?>

<?php get_footer();
