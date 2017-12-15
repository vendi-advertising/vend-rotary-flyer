<?php
/**
 * Template Name: Thank you
 *
 */

require 'rotary-functions.php';
$pdf_render_mode = isset($_POST['pdf_date']);

// if(!$pdf_render_mode){
    get_header();
// }


if($pdf_render_mode){
    $date = $_POST['pdf_date'];
    $date = str_replace( "_", "/", $date);
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
    $date = str_replace( "_", "/", $date);
    $result = Vendi\RotaryFlyer\pdf_generator::generate_for_date($date_organized_posts[$date], $date, true);
    //file_put_contents(VENDI_ROTARY_FLYER_DIR . '/text-html2.html',$result['html'] );
    // echo $result['html'];
    // die;
    ?>

<div class="main-content-region">
    <div  class="grey-bottom-border" >
        <h1> PDF generated </h1>
        <p> The Rotary Flyer PDF for <?php echo $date; ?> has been generated. Preview and print the PDF using the button below or return to the dashboard. </p>
    </div>
    <a href="<?php echo $result['link']; ?>" class="steps-button"> Flyer PDF </a>            <a class="steps-button" href="<?php echo VENDI_ROTARY_ADMIN_DASHBOARD; ?>"> Return to Dashboard </a>

</div>
    <?php
}
/*if(isset($_POST['date'])){
    $date = $_POST['date'];
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
    $date = str_replace( "_", "/", $date);
    Vendi\RotaryFlyer\pdf_generator::generate_placeholders($date_organized_posts[$date], $date);
    Vendi\RotaryFlyer\pdf_generator::generate_preview_for_date($date_organized_posts[$date], $date);
}*/


// if(!$pdf_render_mode){
    get_footer();
// }
