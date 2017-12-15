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
            <div class="pdf-footer">Share an announcement with your Rotary community. Weekly space available at: Rotarynewswheel.org </div>
            <div> Web app by <a href="https://www.vendiadvertising.com" >Vendi </a></div>
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

//Load CSS and JavaScript
 add_action(
             'wp_enqueue_scripts',
             function()
             {

                 /*****************
                  *  Parent Theme *
                  *****************/
                 //Load each CSS file that starts with three digits followed by a dash in numerical order
                 foreach( glob( VENDI_ROTARY_FLYER_DIR . '/css/[0-9][0-9][0-9]-*.css' ) as $t )
                 {
                     wp_enqueue_style(
                                         basename( $t, '.css' ) . '-p-style',
                                         untrailingslashit( VENDI_ROTARY_WP_PLUGIN_DIR_URL ) . '/css/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/css/' . basename( $t ) ),
                                         'screen'
                                     );
                 }
                 wp_enqueue_script("jquery");
                 //Load each JS file that starts with three digits followed by a dash in numerical order
                 /*foreach( glob( VENDI_ROTARY_FLYER_DIR . '/js/[0-9][0-9][0-9]-*.js' ) as $t )
                 {
                     wp_enqueue_script(
                                         basename( $t, '.js' ) . '-p-style',
                                         untrailingslashit( VENDI_ROTARY_WP_PLUGIN_DIR_URL ) . '/js/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/js/' . basename( $t ) ),
                                         true
                                     );
                 }*/
             }
         );


add_action(
            'login_enqueue_scripts',
            function()
             {
                ?>
                <style type="text/css">
                    #login h1 a, .login h1 a {
                        background-image: url(<?php echo VENDI_ROTARY_WP_PLUGIN_DIR_URL; ?>/images/Rotary_ads_login_page_image_transparent.png);
                        height:169px;
                        width:240px;
                        background-size: 240px 169px ;
                        background-repeat: no-repeat;
                        padding-bottom: 30px;
                    }
                    .login #nav{
                        padding: 0 !important;
                        text-align: center;
                    }
                </style>
                <?php
            }
        );

add_filter(
            'login_headerurl',
            function()
            {
                return home_url();
            }
        );

add_filter(
            'login_headertitle',
            function()
            {
                return 'Rotary News Wheel';
            }
        );

function remove_lostpassword_text ( $text ) {
         if ($text == 'Lost your password?'){
            $text = '';
        }
                return $text;
         }

if ( $GLOBALS['pagenow'] === 'wp-login.php' ) {
    add_filter( 'gettext',
                function($text)
                {
                if ($text == 'Lost your password?'){
                    $text = 'Lost your password or signing in for the first time?';
                }
                if ($text == 'Log In'){
                    $text = 'Login';
                }
                return $text;
                }
            );
}
