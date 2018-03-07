<?php

\Vendi\Shared\template_router::get_instance()->get_header();

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
        <div  class="grey-bottom-border" >
            <h1> Purchase Credits </h1>
            <p> Credits are used to purchase ad space in the weekly Rotary flier. Each credit costs $<?php echo $payment->get_price() ?> and can be used to purchase one slot in a weekly flier. Each subsequent week costs an additional credit. Select the number of credits that you wish to purchase below. </p>
        </div>
        <form method="post" action="<?php echo \Vendi\Shared\template_router::get_instance()->create_url('purchase-credits-confirm'); ?>">
          <input type="number" name="quantity" value="1" min="1" max="10">
          <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
          <input type="submit" name="" class="steps-button" value="Purchase Credits">
        </form>
    </div>
</div>
<?php

\Vendi\Shared\template_router::get_instance()->get_footer();
