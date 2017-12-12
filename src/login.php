<?php
namespace Vendi\RotaryFlyer;
class login
{
    public function __construct()
    {
        //Redirect logins for non-admins to the dragon boat dashboard
        /*add_filter(
                    'login_redirect',
                    function( $redirect_to, $request, $user )
                    {
                        dump($redirect_to);
                        if( ! $user instanceOf \WP_User )
                        {
                            return $redirect_to;
                        }

                        if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                        {
                            return $redirect_to;
                        }
                    },
                    10,
                    3
                );*/
        //Redirect any non-admin users that attempt to access the WP backend back to the dragon boat dashboard
        add_action(
                    'admin_init',
                    function()
                    {
                        $user = wp_get_current_user();
                        if( ! $user instanceOf \WP_User )
                        {
                            wp_die( 'Invalid user account found.' );
                        }

                        //Allow AJAX requests to just pass through
                        if( defined( 'DOING_AJAX' ) && DOING_AJAX )
                        {
                            return;
                        }

                        //If the user is one of our custom roles then kick them to the dashboard
                        if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles ) )
                        {
                            wp_safe_redirect( VENDI_ROTARY_USER_DASHBOARD);
                            exit;
                        }

                    }
                );



        add_filter(
                    'login_redirect',
                    function($redirect_to, $request, $user)
                    {
                        //Send WP admins to the custom admin dashboard
                        if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
                        {
                            return VENDI_ROTARY_ADMIN_DASHBOARD;
                        }

                        //Send Rotary Users to the PDF creation screen
                        if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                            return VENDI_ROTARY_PDF_CREATION;
                        }

                        //Pass through for everyone else
                        return $redirect_to;
                    },
                    10,
                    3
                );

    }
}
