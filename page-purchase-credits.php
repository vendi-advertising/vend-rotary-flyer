<?php
/**
 * Template Name: Purchase Credits
 *
 */
?>
<?php
require 'rotary-functions.php';


get_header();

$payment = new Vendi\RotaryFlyer\payment(get_current_user_id());

/*
Things needed:
1. a form with a drop down of how many credits to buy
2. Copy that lists the price, and explains what the user is buying
3. the form to $_POST the token amount to the page-credits-confirmation page

 */

$transaction_id = $payment->generate_transaction_id();

?>
<div id="main-content">
    <div class="main-content-region">
        <form method="post" action="<?php echo VENDI_ROTARY_CREDITS_CONFIRMATION_PAGE; ?>">
          <input type="number" name="quantity" min="1" max="10">
          <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
          <input type="submit" name="" value="Purchase Credits">
        </form>
    </div>
</div>
<?php
get_footer();
