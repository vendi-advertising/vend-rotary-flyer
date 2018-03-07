<?php

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();

?>
<div id="main-content">
        <div class="main-content-region">
<?php

$post_id = $_GET['post_id'];

$post_status = get_post_status( $post_id );

$this_user = get_current_user_id();

$payment = new Vendi\RotaryFlyer\payment($this_user);
$required = floatval(count(get_field('field_59ef4bccd0939', $post_id)));
$required = $required*($payment->get_price());
$current = floatval($payment->get_current_balance());
$difference = $current-$required;
if($post_status != 'publish' && $post_id != ''){

    $update_post = array(
        'ID' => $post_id,
        'post_status' => 'publish'
    );

    wp_update_post( $update_post );
    $payment->deduct_from_owed_balance($required, $post_id);
    ?>
        <div class="main-messaging">
            <h1> Thank you! </h1>
            <p> Your ad has been submitted. You will be billed with your next invoice for the number of ads purchased. </p>
        </div>
            <?php
            $user = wp_get_current_user();

            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                {
                    $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('admin-dashboard');
                }
                else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                    $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('dashboard');
                }

            ?>

            <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('add-edit-ad'); ?>"> Create More Ads </a>
            <?php

            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                {

             ?>
            <a class="steps-button" href="<?php echo $dashboard; ?>"> Return to Dashboard </a>

            <?php
                }

            ?>
            <a class="steps-button" href="<?php echo wp_logout_url(); ?>"> Log Out </a>
<?php
}
else if($post_status == 'pending'){

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
        <p> Your ad has been submitted. You will be billed with your next invoice for the number of ads purchased. </p>
    </div>

        <a class="steps-button" href="<?php echo $dashboard; ?>"> Return to Dashboard </a>
     <?php
}
else{
$update_post = array(
    'ID' => $post_id,
    'post_status' => 'publish'
);

wp_update_post( $update_post );


    ?>


<div class="main-messaging">
            <h1> Thank you! </h1>
            <p> Your ad has been updated. </p>
        </div>
            <?php
            $user = wp_get_current_user();

            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                {
                    $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('admin-dashboard');
                }
                else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                    $dashboard = \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('dashboard');
                }

            ?>

            <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('add-edit-ad'); ?>"> Create More Ads </a>

            <?php

            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                {

             ?>
            <a class="steps-button" href="<?php echo $dashboard; ?>"> Return to Dashboard </a>

            <?php
                }

            ?>

            <a class="steps-button" href="<?php echo wp_logout_url(); ?>"> Log Out </a>


<?php
}

?>
    </div>
</div>

<?php
\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
