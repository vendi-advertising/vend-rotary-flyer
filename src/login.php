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
                        if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles ) )
                        {
                            wp_redirect( VENDI_ROTARY_USER_DASHBOARD);
                            return $redirect_to;
                        }

                    }
                );



    add_filter('login_redirect', array($this, 'admin_default_page'), 10, 3);

    }
    function admin_default_page($redirect_to, $request, $user){
            if( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) )
            {
                return VENDI_ROTARY_ADMIN_DASHBOARD;
            }
            else if(isset( $user->roles ) && is_array( $user->roles ) && in_array( 'Rotary User', $user->roles )){
                return VENDI_ROTARY_USER_DASHBOARD;
            }
            else{
                return $redirect_to;
            }
        }
}
