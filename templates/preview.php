<?php

$pdf_output = true;

\Vendi\Shared\template_router::get_instance()->get_header();

vendi_rotary_register_ajax_js( '200-slider-enable.js', array('jquery'));
vendi_rotary_register_plugin_js( '250-place-holder-modal.js');

$wp_scripts = wp_scripts();
vendi_rotary_register_outside_css( 'jqueryui-smoothness-theme','https://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css')
?>

<?php

include VENDI_ROTARY_FLYER_DIR . '/placeholder-modal.php';
if(isset($_GET['date'])){
    $date = $_GET['date'];
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
    $date = str_replace( "_", "/", $date);

    if(array_key_exists ( $date, $date_organized_posts )){
    	echo Vendi\RotaryFlyer\pdf_generator::generate_preview_for_date($date_organized_posts[$date], $date);
	}
	else{
		echo Vendi\RotaryFlyer\pdf_generator::generate_preview_for_date(array(), $date);
	}
}
else{
    Vendi\RotaryFlyer\pdf_generator::generate();
}


\Vendi\Shared\template_router::get_instance()->get_footer();
