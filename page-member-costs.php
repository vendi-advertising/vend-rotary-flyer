<?php
/**
 * Template Name: Member Costs
 *
 * This page allows the admin to view how much members owe for a given date range.
 */
require 'rotary-functions.php';

get_header();
vendi_rotary_register_ajax_js( '300-rotary-member-costs.js', array('jquery-ui-datepicker') );
wpse_enqueue_datepicker();
vendi_rotary_register_outside_css('rotary-date-picker-css',  plugins_url( '/css-assets/datepicker.css', VENDI_ROTARY_FLYER_FILE ));
$args = array(
                'post_type' => 'vendi-rotary-flyer',
                'post_status' => 'publish',
                'posts_per_page'   => -1

);

$query = new WP_Query($args);
$user_cost_arr = array();
while ( $query->have_posts()) : $query->the_post();
    $author = get_the_author();
    if(array_key_exists ($author , $user_cost_arr )){
        $user_cost_arr[$author]++;
    }
    else{
        $user_cost_arr[$author] = 1;
    }
endwhile;


?>
<div id="main-content">
    <div class="main-content-region">
        <div class="main-messaging">
            <h1> Member Balances </h1>
            <!--<p> Check how much each member owes by entering a date range below. </p>-->
            <p> Run a report on the number of ads created by each member during a given date range. </p>
        </div>
        <div class="member-costs-controls">
            <label for="rotary-from-date">From</label>
            <input id="rotary-from-date" type="text">

            <label for="rotary-to-date">To</label>
            <input id="rotary-to-date" type="text">
            <input id="rotary-query-owed" class="steps-button" type="submit" value="Check Balances">
            <a class="steps-button" href="<?php echo VENDI_ROTARY_ADMIN_DASHBOARD; ?>"> Return to Dashboard </a>
            <a class="steps-button" href="<?php echo wp_logout_url(); ?>"> Log Out </a>

            <hr>
        </div>
        <div id="rotary-owed-results">

        </div>
    </div>
</div>

<style>
@media print {
    #header, .member-costs-controls, .main-messaging, #footer{
        display: none;
    }

    .response-row{

    }

    .response-row + .response-row{
        border-top: 1px solid #eee;
    }

    .response-col{
        display: inline-block;
        box-sizing: border-box;
        padding: 5px;
    }

    .response-col-name{
        width: 55%;
    }

    .response-col-ads{
        width: 30%;
    }

    .response-col-price{
        width: 15%;
    }

    .header-row{
        background-color: #0d4c94;
        color: white;
    }


}
</style>

<?php

get_footer(); ?>
