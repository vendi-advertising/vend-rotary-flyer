<?php
/**
 * Template Name: Admin View
 *
 */
?>
<?php
require 'rotary-functions.php';
get_header();

//Vendi\RotaryFlyer\pdf_generator::generate_from_url();
?>
<div id="main-content">
    <div class="main-content-region">
<?php $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
$htmlstring = '';
foreach($date_organized_posts as $date => $id_arr){

    //replace slashes so that it can be used as a get parameter - will need to convert back on the next page
    $htmlstring .= '<div class="admin-view-box">';
    $htmlstring .= '<div class="grey-bottom-border"><h1>Flyer for ' . $date . '</h1><a class="steps-button" href="'. VENDI_ROTARY_PDF_ASSEMBLER_PAGE .'?date=' . str_replace("/", "_", $date) . '"> Prepare flyer </a>';
    $htmlstring .= '<p class="admin-view-box-description"> Contains ' . count($id_arr) . ' entries out of a maximum of 9: </p></div>';
    $htmlstring .= '<ol class="admin-list">';
    foreach ($id_arr as $id) {
        $htmlstring .= '<li > ';
        $htmlstring .= '<p>' . get_the_title( $id ) . '</p>';
        $htmlstring .= ' <a class="" href="'. VENDI_ROTARY_PDF_CREATION .'?post_id='. $id . '">';
        $htmlstring .= ' &#9998;Edit post';
        $htmlstring .= '</a>';
        $htmlstring .= ' </li>';
    }
    $htmlstring .= '</ol>';
    $htmlstring .= '</div>';
}

echo $htmlstring;

?>

</div>
</div>

<?php

//echo '<link rel="stylesheet" id="pdf-css" href="' . VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css" type="text/css" media="all">';

?>

<?php get_footer(); ?>
