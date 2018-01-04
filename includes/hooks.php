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

//this is the ajax action for getting all the costs of the members for a given date range
add_action(
            'wp_ajax_query_costs',
            function()
            {
                global $wpdb; // this is how you get access to the database

                $from_date = $_POST['from_date'];
                $to_date = $_POST['to_date'];
                $from_parts = explode('/',$from_date);
                $to_parts = explode('/',$to_date);
                $args = array(
                'post_type' => 'vendi-rotary-flyer',
                'post_status' => 'publish',
                'posts_per_page'   => -1,
                'date_query' =>
                        array(
                            'after'     => array('year'=>$from_parts[2],
                                                 'month'=>$from_parts[0],
                                                 'day'=>$from_parts[1]
                                                ),
                            'before'    => array('year'=>$to_parts[2],
                                                 'month'=>$to_parts[0],
                                                 'day'=>$to_parts[1]
                                                ),
                            'inclusive' => true,
                        )

                );

                $query = new WP_Query($args);
                $user_cost_arr = array();
                while ( $query->have_posts()) : $query->the_post();
                    $author = get_the_author();
                    if(array_key_exists ($author , $user_cost_arr )){
                        $user_cost_arr[$author]['ads_created']++;
                        $user_cost_arr[$author]['amount_owed'] = $user_cost_arr[$author]['ads_created']*10;

                    }
                    else{
                        $user_cost_arr[$author]['ads_created'] = 1;
                        $user_cost_arr[$author]['amount_owed'] = $user_cost_arr[$author]['ads_created']*10;
                    }
                endwhile;

                echo json_encode($user_cost_arr);

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
                <div class="header-text">
                  <h1> Rotary Club of La Crosse </h1>
                  <h2> NEWS WHEEL </h2>
                </div>
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
                if ($text == 'Log In'){
                    $text = 'Login';
                }
                if ($text == 'Lost your password?'){
                    $text = 'Click here if you are registering for the first time or having trouble logging in. ';
                }
                return $text;
                }
            );
}

/*add_filter( 'login_errors', function($message){
    return 'test';
});*/

add_filter( 'login_message', function($message){
    return '<p class="message" > Use your email address on file with Rotary. If you’ve forgotten that address, please contact rotarylax@charter.net. </p>';
});

add_filter( 'show_admin_bar' , 'handle_admin_bar');

function handle_admin_bar($content) {
     // 'manage_options' is a capability assigned only to administrators
     // here, the check for the admin dashboard is not necessary

     if (!current_user_can('manage_options')) {
        return false;
     }
     else{
        return true;
     }
}
