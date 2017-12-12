<?php
/**
 * Template Name: Submit Final
 *
 */
require 'rotary-functions.php';

get_header();

?>
<div id="main-content">
        <div class="main-content-region">
<?php

$post_id = $_GET['post_id'];

$post_status = get_post_status( $post_id );

$this_user = get_current_user_id();

$payment = new Vendi\RotaryFlyer\payment($this_user);
//get post and check how many days it will run, use this to find the credits needed
$required = count(get_field('field_59ef4bccd0939', $post_id));
//get the amount of credits that the user has
$available = intval($payment->get_available_tokens());
//compare user credits to required
$difference = $available-$required;
//if difference is greater than zero, then we move post into "pending" and deduct credits from user's account
if($difference >= 0 && $post_status != 'pending' && $post_id != ''){

    $update_post = array(
        'ID' => $post_id,
        'post_status' => 'pending'
    );

    wp_update_post( $update_post );
    $payment->deduct_from_owed_balance($required);
    ?>
        <div class="main-messaging">
            <h1> Thank you! </h1>
            <p> Your ad has been submitted. You will be billed with your next invoice for the number of ads purchased. </p>
        </div>
            <?php
            $user = wp_get_current_user();

            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                {
                    $dashboard = VENDI_ROTARY_ADMIN_DASHBOARD;
                }
                else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                    $dashboard = VENDI_ROTARY_USER_DASHBOARD;
                }

            ?>

            <a class="steps-button" href="<?php echo VENDI_ROTARY_PDF_CREATION; ?>"> Create More Ads </a>
            <a class="steps-button" href="<?php echo wp_logout_url(); ?>"> Log Out </a>
<?php
}
else if($post_status == 'pending'){

    $user = wp_get_current_user();

    if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
    {
        $dashboard = VENDI_ROTARY_ADMIN_DASHBOARD;
    }
    else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
        $dashboard = VENDI_ROTARY_USER_DASHBOARD;
    }
    ?>
    <div class="grey-bottom-border">
        <h1> Thank you! </h1>
        <p> Your post has already been submitted, and will be approved by an administrator. </p>
    </div>

        <a class="steps-button" href="<?php echo $dashboard; ?>"> Return to Dashboard </a>
     <?php
}
else{
    ?>
    <div class="grey-bottom-border">
        <h1> Not enough credits </h1>
        <p> You do not have enough credits to submit a posting at the moment. If you wish, you may purchase credits.</p>
    </div>

    <a class="steps-button" href="<?php echo VENDI_ROTARY_CREDITS_PURCHASE_PAGE; ?>"> Purchase credits </a>
    <?php
}

?>
    </div>
</div>
<?php

get_footer(); ?>
