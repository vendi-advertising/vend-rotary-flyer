<?php
\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();
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

                if(!is_user_logged_in()){

                ?>
                    <a class="steps-button" href="<?php echo wp_login_url(); ?>">
                        Login
                    </a>

                <?php
                }
                else{
                    $user = wp_get_current_user();
                    //Send Rotary Users to the PDF creation screen
                    if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                        ?>
                        <a class="steps-button" href="<?php echo VENDI_ROTARY_PDF_CREATION; ?>">
                            Create An Ad
                        </a>
                        <a class="steps-button" href="<?php echo wp_logout_url(); ?>">
                            Log Out
                        </a>
                        <?php
                    }
                    else{
                        ?>
                        <a class="steps-button" href="<?php echo VENDI_ROTARY_ADMIN_DASHBOARD; ?>">
                            Manage Fliers
                        </a>
                        <a class="steps-button" href="<?php echo wp_logout_url(); ?>">
                            Log Out
                        </a>
                        <?php
                    }
                }
                ?>
            </div><!--
            --><div class="front-page-column">
                <div class="flyer-image-container">
                    <a href="<?php echo get_stylesheet_directory_uri(); ?>/images/Rotary-Ads-and-Announcements-newest.png">
                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/Rotary-Ads-and-Announcements-newest-thumb.png" alt="rotary flier screenshot"/>
                    </a>
                </div>
            </div>
        </div>
    </div><!-- #main-content -->

<?php
\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
