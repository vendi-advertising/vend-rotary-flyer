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
        echo 'Credits already purchased';
    }
    else{
        echo 'inserting';
        $payment->purchase_tokens($quantity);
        $payment->payment_records($quantity, $transaction_id);
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
