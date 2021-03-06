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
                    $post_id = get_the_ID();
                    if(!get_field('is_placeholder', $post_id) == 'True'){


                        $count = count(get_field('run_dates', $post_id));
                        if(array_key_exists ($author , $user_cost_arr )){
                            $user_cost_arr[$author]['ads_created'] = $user_cost_arr[$author]['ads_created']+$count;
                            $user_cost_arr[$author]['amount_owed'] = $user_cost_arr[$author]['ads_created']*10;
                        }
                        else{
                            $user_cost_arr[$author]['ads_created'] = $count;
                            $user_cost_arr[$author]['amount_owed'] = $user_cost_arr[$author]['ads_created']*10;
                        }
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
                return \Vendi\Shared\template_router::get_instance()->create_url();
            }
        );

add_filter(
            'login_headertitle',
            function()
            {
                return 'Rotary News Wheel';
            }
        );

if ( $GLOBALS['pagenow'] === 'wp-login.php' )
{
    add_filter(
                'gettext',
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

add_filter(
            'login_message',
            function($message)
            {
                return '<p class="message" > Use your email address on file with Rotary. If you’ve forgotten that address, please contact rotarylax@charter.net. </p>';
            }
        );

add_filter(
            'show_admin_bar',
            function($content)
            {

                // 'manage_options' is a capability assigned only to administrators
                // here, the check for the admin dashboard is not necessary
                return current_user_can('manage_options');
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

                if( is_dir( VENDI_ROTARY_FLYER_DIR . '/media/css/' ) )
                {
                    //Load each CSS file that starts with three digits followed by a dash in numerical order
                    foreach( glob( VENDI_ROTARY_FLYER_DIR . '/media/css/[0-9][0-9][0-9]-*.css' ) as $t )
                    {
                        wp_enqueue_style(
                                            basename( $t, '.css' ) . '-p-style',
                                            VENDI_ROTARY_WP_PLUGIN_DIR_URL . 'media/css/' . basename( $t ),
                                            null,
                                            filemtime( VENDI_ROTARY_FLYER_DIR . '/media/css/' . basename( $t ) ),
                                            'screen'
                                        );
                    }
                }

                if( is_dir( VENDI_ROTARY_FLYER_DIR . '/media/js/' ) )
                {
                    //Load each JS file that starts with three digits followed by a dash in numerical order
                    foreach( glob( VENDI_ROTARY_FLYER_DIR . '/media/js/[0-9][0-9][0-9]-*.js' ) as $t )
                    {
                        wp_enqueue_script(
                                            basename( $t, '.js' ) . '-p-style',
                                            VENDI_ROTARY_WP_PLUGIN_DIR_URL . 'media/js/' . basename( $t ),
                                            null,
                                            filemtime( VENDI_ROTARY_FLYER_DIR . '/media/js/' . basename( $t ) ),
                                            true
                                        );
                    }
                }
            }
        );


add_filter(
            'wp_handle_upload_prefilter',
            function( $file )
            {

                //Sanity check and bail early
                if(!$file || ! is_array($file))
                {
                    return $file;
                }

                if(! array_key_exists('type', $file))
                {
                    return $file;
                }

                //We only want to process images
                if(false === strpos($file[ 'type' ], 'image/') )
                {
                    return $file;
                }

                //Block out CMYK (or more specifically, 4-channel) images
                $image_info   = getimagesize( $file['tmp_name'] );
                if( 4 === $image_info['channels'] )
                {
                    $file['error'] = 'This image is CMYK, please convert it to RGB and upload again.';
                }
                return $file;
            }
        );

add_action(
            'admin_menu',
            function()
            {
                remove_menu_page( 'index.php' );                                        //Dashboard
                remove_menu_page( 'edit.php' );                                         //Posts
                remove_menu_page( 'edit.php?post_type=page' );                          //Pages
                remove_menu_page( 'edit-comments.php' );                                //Comments
                remove_menu_page( 'themes.php' );                                       //Appearance
                remove_menu_page( 'plugins.php' );                                      //Plugins
                remove_menu_page( 'users.php' );                                        //Users
                remove_menu_page( 'tools.php' );                                        //Tools
                remove_submenu_page( 'options-general.php', 'options-media.php' );      //Settings, Media
                remove_submenu_page( 'options-general.php', 'options-discussion.php' ); //Settings, Discussion
                remove_submenu_page( 'options-general.php', 'options-reading.php' );    //Settings, Reading
                remove_submenu_page( 'options-general.php', 'options-writing.php' );    //Settings, Writing
            }
        );

add_action(
            'admin_bar_menu',
            function($wp_admin_bar )
            {
                $wp_admin_bar->remove_menu('customize');
                $wp_admin_bar->remove_menu('new-content');
                $wp_admin_bar->remove_menu('comments');
                $wp_admin_bar->remove_menu('wp-logo');
                $wp_admin_bar->remove_menu('dashboard');
                $wp_admin_bar->remove_menu('themes');
                $wp_admin_bar->remove_menu('view-site');
                $wp_admin_bar->remove_menu('edit-profile');
                $wp_admin_bar->remove_menu('search');
                $wp_admin_bar->remove_menu('user-info');
            },
            100
        );
