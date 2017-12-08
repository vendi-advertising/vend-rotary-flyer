<?php
/**
 * Template Name: Admin View
 *
 */
?>
<?php
require 'rotary-functions.php';
get_header();
$this_user = get_current_user_id();
$payment = new Vendi\RotaryFlyer\payment($this_user);

//Vendi\RotaryFlyer\pdf_generator::generate_from_url();
?>
<div id="main-content">
    <div class="main-content-region">
        <div class="user-payment-info-region">
            <div class="payment-info-block">
                <h2> Current amount due </h2>
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
                'numberposts' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'vendi-rotary-flyer',
                'post_status' => array('pending')
            );
            $loop = new \WP_Query( $args );
        ?>
        <div class="ads-in-progress-region">
            <h2> Your unpublished ads </h2>
            <div class="ads-wrapper">
                <ol>
                <?php
                    while ( $loop->have_posts()) : $loop->the_post();
                        $post_id       = trim(get_the_ID());
                        $post_title    = get_the_title();
                        $htmlstring .= '<li> ';
                        $htmlstring .= get_the_title( $id );
                        $htmlstring .= ' <a href=" /test-page/?post_id='. $id . '">';
                        $htmlstring .= ' &#9998;edit';
                        $htmlstring .= '</a>';
                        $htmlstring .= ' </li>';
                        echo $htmlstring;
                    endwhile;
                ?>
                </ol>
            </div>
        </div>
        <div class="user-decision-region">
            <div class="decision-block">
                <a href="<?php echo VENDI_ROTARY_PDF_CREATION; ?>"> Create an ad </a>
            </div>
            <div class="decision-block">
                <a href="<?php echo VENDI_ROTARY_CREDITS_PURCHASE_PAGE; ?>"> Purchase credits </a>
            </div>
        </div>
    </div>

</div>

<?php
get_footer();
