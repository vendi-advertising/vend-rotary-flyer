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
?>

<?php

if(isset($_GET['date'])){
    $date = $_GET['date'];
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date();
    $date = str_replace( "_", "/", $date);
    Vendi\RotaryFlyer\pdf_generator::generate_for_date($date_organized_posts[$date], $date);

}
else{
    Vendi\RotaryFlyer\pdf_generator::generate();
}


?>

<?php

//echo '<link rel="stylesheet" id="pdf-css" href="' . VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css" type="text/css" media="all">';

?>

<?php get_footer(); ?>
