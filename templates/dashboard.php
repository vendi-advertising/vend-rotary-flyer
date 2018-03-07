<?php
// require 'rotary-functions.php';

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();

$this_user = get_current_user_id();
$user_info = wp_get_current_user();
$first_name = $user_info->user_firstname;
$payment = new Vendi\RotaryFlyer\payment($this_user);
//Vendi\RotaryFlyer\pdf_generator::generate_from_url();
?>
<div id="main-content">
    <div class="main-content-region">
        <div class="user-dash-region">

            <h1> User Dashboard </h1>

        </div>
        <div class="user-dash-region user-payment-info-region">
            <div class="payment-info-block">
                <h2> Current amount due </h2>
                <?php //add in a jquery ui tool tip that explains this ?>
                <p> $<?php echo floatval($payment->get_current_balance()); ?> </p>
            </div>
            <div class="payment-info-block">
                <h2> Current rotary ad credits </h2>
                <p> <?php echo floatval($payment->get_available_tokens()); ?> </p>
            </div>
        </div>
        <?php
            $args = array(
                'author'      => $this_user,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'vendi-rotary-flyer',
                'post_status' => array('draft')
            );
            $loop = new \WP_Query( $args );
        ?>
        <div class="user-dash-region ads-in-progress-region">
            <h2> Your unpublished ads </h2>
            <div class="ads-wrapper">
                <ol>
                <?php
                    $htmlstring = '';
                    while ( $loop->have_posts())
                    {
                        $loop->the_post();
                        $post_id       = trim(get_the_ID());
                        $post_title    = get_the_title();
                        $htmlstring .= '<li> ';
                        $htmlstring .= get_the_title( $id );
                        $htmlstring .= ' <a class="steps-button" href=" /test-page/?post_id='. $id . '">';
                        $htmlstring .= ' &#9998;edit';
                        $htmlstring .= '</a>';
                        $htmlstring .= ' </li>';
                    }

                    echo $htmlstring;

                ?>
                </ol>
            </div>
        </div>
        <div class="user-dash-region user-decision-region">
            <h2> Actions </h2>
                <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('add-edit-ad'); ?>"> Create an ad </a>
                <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance('RotaryFlyer')->create_url('purchase-credits'); ?>"> Purchase credits </a>
        </div>
    </div>

</div>

<?php
\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
