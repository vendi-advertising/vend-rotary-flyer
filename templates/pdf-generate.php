<?php
/**
 * Template Name: Thank you
 *
 */


$pdf_render_mode = isset($_POST['pdf_date']);

// if(!$pdf_render_mode){
    \Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();
// }


if($pdf_render_mode){
    $date = $_POST['pdf_date'];
    $date = str_replace( "_", "/", $date);
    $date_organized_posts = Vendi\RotaryFlyer\pdf_generator::get_entries_sorted_by_date(true);
    $date = str_replace( "_", "/", $date);

    if(array_key_exists($date, $date_organized_posts)){
        $result = Vendi\RotaryFlyer\pdf_generator::generate_for_date($date_organized_posts[$date], $date, true);
        ?>
        <div class="main-content-region">
            <div  class="grey-bottom-border" >
                <h1> PDF generated </h1>
                <p> The Rotary Flyer PDF for <?php echo $date; ?> has been generated. Preview and print the PDF using the button below or return to the dashboard. </p>
            </div>
            <a href="<?php echo $result['link']; ?>" class="steps-button"> Flier PDF </a>            <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('dashboard'); ?>"> Return to Dashboard </a>

        </div>
        <?php
    }
    else{
        ?>
        <div class="main-content-region">
            <div  class="grey-bottom-border" >
                <h1> PDF could not be generated </h1>
                <p> There were no ads found for this date. </p>
            </div>
            <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('dashboard'); ?>"> Return to Dashboard </a>

        </div>
        <?php
    }
}


// if(!$pdf_render_mode){
    \Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
// }
