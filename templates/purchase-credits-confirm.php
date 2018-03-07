<?php

\Vendi\Shared\template_router::get_instance()->get_header();
$payment = new Vendi\RotaryFlyer\payment(get_current_user_id());

?>
<div id="main-content">
    <div class="main-content-region">
<?php

if( isset($_POST['quantity']) && isset($_POST['transaction_id']) )
{
    $transaction_id = esc_attr($_POST['transaction_id']);
    $in_db = $payment->check_db_for_transaction_id($transaction_id);


    ?>
    <div class="grey-bottom-border">
        <h1> Thank you! </h1>
        <?php
        if($in_db )
        {
            $quantity = esc_attr($_POST['quantity']);
            $payment->purchase_tokens($quantity);
            $payment->payment_records($quantity, $transaction_id);
            echo '<p>Credits have already been applied to your account.</p>';
        }
        else
        {

        }   echo '<p>Credits have been added to your account and the administrator has been notified of new charges.</p>';
        ?>

    </div>
    <a class="steps-button" href="<?php echo \Vendi\RotaryFlyer\CurrentUser::get_dashboard_url(); ?>"> Return to Dashboard </a>

    <?php

}
else{

}
?>

    </div>
</div>
<?php

\Vendi\Shared\template_router::get_instance()->get_footer();
