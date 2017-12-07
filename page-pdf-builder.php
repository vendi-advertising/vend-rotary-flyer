<?php
/**
 * Template Name: Thank you
 *
 */
?>
<?php
require 'rotary-functions.php';
get_header();
dump($_POST);
dump(array_filter(json_decode ($_POST["placeholder_id_json"])));
/*if(isset($_POST['date'])){
    $date = $_POST['date'];
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
    $date = str_replace( "_", "/", $date);
    Vendi\RotaryFlyer\pdf_generator::generate_placeholders($date_organized_posts[$date], $date);
    Vendi\RotaryFlyer\pdf_generator::generate_preview_for_date($date_organized_posts[$date], $date);
}*/

?>
<?php
get_footer();
