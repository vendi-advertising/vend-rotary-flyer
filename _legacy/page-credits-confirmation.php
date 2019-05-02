<?php
/**
 * Template Name: Credits Confirmation
 *
 */
?>
<?php
require 'rotary-functions.php';
get_header();
$payment = new Vendi\RotaryFlyer\payment(get_current_user_id());

?>
<div id="main-content">
    <div class="main-content-region">
<?php

if( isset($_POST['quantity']) && isset($_POST['transaction_id']) )
{
    $transaction_id = esc_attr($_POST['transaction_id']);
    $quantity = esc_attr($_POST['quantity']);
    $in_db = $payment->check_db_for_transaction_id($transaction_id);
    if($in_db ){

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
                <p> Credits have already been applied to your account. </p>
            </div>
            <a class="steps-button" href="<?php echo $dashboard; ?>"> Return to Dashboard </a>

        <?php


    }
    else{

        $user = wp_get_current_user();

        if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
        {
            $dashboard = VENDI_ROTARY_ADMIN_DASHBOARD;
        }
        else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
            $dashboard = VENDI_ROTARY_USER_DASHBOARD;
        }

        $payment->purchase_tokens($quantity);
        $payment->payment_records($quantity, $transaction_id);

        ?>
            <div class="grey-bottom-border">
                <h1> Thank you! </h1>
                <p> Credtis have been added to your account and the administrator has been notified of new charges. </p>
            </div>
            <a class="steps-button" href="<?php echo $dashboard; ?>"> Return to Dashboard </a>

        <?php
    }

}
else{

}
?>

    </div>
</div>
<?php

?>
<?php
get_footer();
