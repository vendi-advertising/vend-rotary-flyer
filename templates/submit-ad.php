<?php

\Vendi\Shared\template_router::get_instance()->get_header();


$post_id = $_GET['post_id'];

$post_status = get_post_status( $post_id );

$this_user = get_current_user_id();

$payment = new Vendi\RotaryFlyer\payment($this_user);
$required = floatval(count(get_field('field_59ef4bccd0939', $post_id)));
$required = $required*($payment->get_price());
$current = floatval($payment->get_current_balance());
$difference = $current-$required;

$links = [];
$message = '';

if($post_status != 'publish' && $post_id != ''){

    $update_post = [
                        'ID' => $post_id,
                        'post_status' => 'publish'
                    ];

    wp_update_post( $update_post );
    $payment->deduct_from_owed_balance($required, $post_id);

    $links['Create More Ads'] = \Vendi\Shared\template_router::get_instance()->create_url('add-edit-ad');
    if( \Vendi\RotaryFlyer\CurrentUser::is_user_admin() ){
        $links['Return to Dashboard'] = \Vendi\RotaryFlyer\CurrentUser::get_dashboard_url();
    }
    $links['Log Out'] = wp_logout_url();
    $message = 'Your ad has been submitted. You will be billed with your next invoice for the number of ads purchased.';

}
else if($post_status == 'pending'){
    $links['Return to Dashboard'] = \Vendi\RotaryFlyer\CurrentUser::get_dashboard_url();
    $message = 'Your ad has been submitted. You will be billed with your next invoice for the number of ads purchased.';
}
else{
    $update_post = [
                        'ID' => $post_id,
                        'post_status' => 'publish'
                    ];

    wp_update_post( $update_post );

    $links['Create More Ads'] = \Vendi\Shared\template_router::get_instance()->create_url('add-edit-ad');
    if( \Vendi\RotaryFlyer\CurrentUser::is_user_admin() ){
        $links['Return to Dashboard'] = \Vendi\RotaryFlyer\CurrentUser::get_dashboard_url();
    }
    $links['Log Out'] = wp_logout_url();
    $message = 'Your ad has been updated.';
}
?>


<div id="main-content">
    <div class="main-content-region">
        <div class="main-messaging">
            <h1> Thank you! </h1>
            <p><?php echo esc_html($message); ?></p>
            <?php
                foreach($links as $text => $link){
                    echo sprintf(
                                    '<a class="steps-button" href="%1$s">%2$s</a> ',
                                    esc_url( $link ),
                                    esc_html( $text )
                                );
                }
            ?>
        </div>
    </div>
</div>

<?php
\Vendi\Shared\template_router::get_instance()->get_footer();
