<?php

namespace Vendi\RotaryFlyer;

use \Vendi\Shared\template_router;

final class CurrentUser
{
    public static function get_current_user()
    {
        return wp_get_current_user();
    }

    public static function get_dashboard_url()
    {
        $user = self::get_current_user();

        //If they aren't logged in, send them to the default page
        if( !self::is_user_logged_in() ){
            return template_router::get_instance()->create_url();
        }

        //If they're an admin, send to admin dasboard
        if( self::is_user_admin()){
            return template_router::get_instance()->create_url('admin-dashboard');
        }

        //Everyone else
        return template_router::get_instance()->create_url('dashboard');
    }

    public static function is_user_logged_in() : bool
    {
        $user = self::get_current_user();

        return 0 !== $user->ID;
    }

    public static function is_user_admin() : bool
    {
        if(!self::is_user_logged_in()){
            return false;
        }

        $user = self::get_current_user();

        //If they're an admin, send to admin dasboard
        return isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles );
    }
}
