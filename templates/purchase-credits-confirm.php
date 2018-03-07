<?php

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();
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
            $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('admin-dashboard');
        }
        else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
            $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('dashboard');
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
            $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('admin-dashboard');
        }
        else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
            $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('dashboard');
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

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
