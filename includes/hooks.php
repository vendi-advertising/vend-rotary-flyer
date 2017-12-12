<?php

//this is the ajax action for updating status on posts from the front end
add_action(
            'wp_ajax_my_action',
            function()
            {
                global $wpdb; // this is how you get access to the database

                $status = $_POST['status'];
                $id = $_POST['id'];
                $result = Vendi\RotaryFlyer\post_modifier::set_post_status($id, $status);
                echo $result;
                exit; // this is required to terminate immediately and return a proper response
            }
        );

//this is the ajax action for creating a new post out of a placeholder
add_action(
            'wp_ajax_convert_placeholder',
            function()
            {
                global $wpdb; // this is how you get access to the database

                $id = $_POST['id'];
                $date = $_POST['date'];
                $result = Vendi\RotaryFlyer\post_modifier::create_post_from_placeholder_id($id, $date);

                echo $result;

                exit;
            }
        );

add_action(
            'vendi/rotary-flyer/footer',
            function()
            {
                ?>
    <!-- begin footer -->
    <div id="footer">
        <div class="footer-region">
            <div class="pdf-footer">Share an announcement with your Rotary community. Weekly space available at: rotarycluboflacrosse.org </div>
            <div> Design and web Development by Vendi Advertising </div>
        </div>
    </div>
    <?php wp_footer(); ?>
    <!-- end footer -->
                <?php
            }
);

add_action(
            'vendi/rotary-flyer/body-header',
            function()
            {
                ?>
    <!-- begin header -->
    <div id="header" role="banner">
        <div class="header-region">
            <div class="header-wrapper">
                <h1> Rotary Club of La Crosse </h1>
                <h2> NEWS WHEEL </h2>
                <img class="logo" src="<?php echo VENDI_ROTARY_WP_PLUGIN_DIR_URL; ?>images/Rotary_Gear.png" alt="Rotary News Wheel Logo" />
            </div>
            <div class="lower-ribbon">
                <div class="header-wrapper">
                    <p> Share an announcement with your Rotary community </p>
                </div>
            </div>
        </div>
    </div>
    <!-- end header -->
                <?php
            }
);
