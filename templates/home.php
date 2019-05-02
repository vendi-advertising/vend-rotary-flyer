<?php
\Vendi\Shared\template_router::get_instance()->get_header();
?>

    <div id="main-content">
        <div class="main-content-region">
            <div class="front-page-column">
                <div class="front-page-copy-region">
                    <h1> Welcome to Rotary News Wheel </h1>
                    <p>
                        Share an announcement or ad with your Rotary community.
                        Ads are displayed during Thursday Rotary meetings and on
                        our website. Posting an ad is as easy as posting to
                        Facebook or Twitter.
                    </p>
                    <ol>
                        <li> Log in </li>
                        <li> Select a date or dates for the ad to run </li>
                        <li> Create your ad
                            <ul>
                                <li> Headline and text </li>
                                <li> Headline, text and image/logo</li>
                                <li> Image file as the entire ad (2.25" x 2.25")</li>
                            </ul>
                        </li>
                        <li> Preview and approve</li>
                    </ol>
                    <p>
                        Your Rotary account will be charged $10 per ad post. You
                        may place 1 ad per week, up to 9 ads total. First come,
                        first served; 9 ads run each week.
                    </p>
                </div>
                <?php

                $links = [];

                if(!\Vendi\RotaryFlyer\CurrentUser::is_user_logged_in()){
                    $links['Login'] = wp_login_url();
                }else{
                    if(\Vendi\RotaryFlyer\CurrentUser::is_user_admin()){
                        $links['Manage Fliers'] = \Vendi\RotaryFlyer\CurrentUser::get_dashboard_url();
                    }else{
                        $links['Create An Ad'] = \Vendi\Shared\template_router::get_instance()->create_url('add-edit-ad');
                    }

                    //Always add the logout link for anyone that's logged in
                    $links['Log Out'] = wp_logout_url();
                }

                foreach($links as $text => $link){
                    echo sprintf(
                                    '<a class="steps-button" href="%1$s">%2$s</a> ',
                                    esc_url( $link ),
                                    esc_html( $text )
                                );
                }

                ?>
            </div><!--
            --><div class="front-page-column">
                <div class="flyer-image-container">
                    <a href="<?php echo VENDI_ROTARY_WP_PLUGIN_DIR_URL; ?>/media/images/Rotary-Ads-and-Announcements-newest.png">
                        <img src="<?php echo VENDI_ROTARY_WP_PLUGIN_DIR_URL; ?>/media/images/Rotary-Ads-and-Announcements-newest-thumb.png" alt="Rotary flier screenshot"/>
                    </a>
                </div>
            </div>
        </div>
    </div><!-- #main-content -->

<?php
\Vendi\Shared\template_router::get_instance()->get_footer();
