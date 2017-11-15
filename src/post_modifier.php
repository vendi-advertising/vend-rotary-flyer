<?php

namespace Vendi\RotaryFlyer;

class post_modifier {
    /**
     * A reference to an instance of this class.
     */
    private static $_instance;

    public static function get_instance()
    {
        if( ! self::$_instance )
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function set_post_status($id, $status){
        $my_post = array(
            'ID'            => $id,
            'post_status'   => $status
        );

        $result = wp_update_post( $my_post );
        if($result == 0){
            return json_encode(array('status' => 'failure'));
        }
        return json_encode(array('status' => get_post($result)->post_status));
    }

}
