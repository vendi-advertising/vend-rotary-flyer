<?php
namespace Vendi\RotaryFlyer;
class login
{
    public function __construct()
    {
        //Redirect logins for non-admins to the dragon boat dashboard
        add_filter(
                    'login_redirect',
                    function( $redirect_to, $request, $user )
                    {
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
                );
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
                            wp_redirect( '/admin-view' );
                            return $redirect_to;
                        }
                        if( isset( $user->roles ) && is_array( $user->roles ) && ! in_array( 'administrator', $user->roles ) )
                        {
                            wp_redirect( '/test-page' );
                            exit;
                        }

                    }
                );

    }
}
