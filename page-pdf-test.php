<?php
/**
 * Template Name: Thank you
 *
 */
?>
<?php
require 'rotary-functions.php';

get_header();

//Vendi\RotaryFlyer\pdf_generator::generate_from_url();
?>

<?php Vendi\RotaryFlyer\pdf_generator::generate(); ?>

<?php

//echo '<link rel="stylesheet" id="pdf-css" href="' . VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css" type="text/css" media="all">';

?>

<?php get_footer(); ?>
