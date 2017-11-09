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
    $htmlstring .= '<h1><a href="/pdf-generate/?date=' . str_replace("/", "_", $date) . '"> Flyer for ' . $date . ' </a></h1>';
    $htmlstring .= '<p> Contains ' . count($id_arr) . ' entries out of a maximum of 9 </p>';
    $htmlstring .= '<ol>';
    foreach ($id_arr as $id) {
        $htmlstring .= '<li> ';
        $htmlstring .= '<a href=" /test-page/?post_id='. $id . '">';
        $htmlstring .= get_the_title( $id );
        $htmlstring .= '</a>';
        $htmlstring .= ' </li>';
    }
    $htmlstring .= '</ol>';
}

echo $htmlstring;

?>

</div>
</div>

<?php

//echo '<link rel="stylesheet" id="pdf-css" href="' . VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css" type="text/css" media="all">';

?>

<?php get_footer(); ?>
