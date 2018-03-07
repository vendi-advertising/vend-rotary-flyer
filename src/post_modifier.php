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

    public static function create_post_from_placeholder_id($id, $date){

        //get placeholder post and ACF data to start cloning process
        $title              = get_the_title($id);
        $ad_description     = get_field('field_5a1717eb2230', $id);
        $ad_type            = get_field('field_5a1717eb22432', $id);
        $header             = get_field('field_5a1717eb224c1', $id);
        $body               = get_field('field_5a1717eb22550', $id);
        $image              = get_field('field_5a1717eb225de', $id);
        $image_information  = get_field('field_5a1717eb2266c', $id);
        $date_arr           = array(array("field_59e7bc2b9e5bb" => $date));

        //post args
        $args = array(  
            'post_type' => 'vendi-rotary-flyer',
            'post_title' => 'placeholder',
            'post_content' => '',
            'post_status'   => 'publish'
        );

        //create new rotaryflyer post and capture the returned ID
        $new_id = wp_insert_post($args);
        //update ACF fields using newly generated post id
        if($new_id !== 0){
            update_field('field_59d553bebeefd', $ad_type, $new_id);
            update_field('field_59d3d72a30576', $header, $new_id);
            update_field('field_59d3d76730578', $body, $new_id);
            update_field('field_59d3d73f30577', (int)$image['ID'], $new_id);
            update_field('field_59f74ba0f3181', $image_information, $new_id);
            update_field('field_59ef4bccd0939', $date_arr, $new_id);
            update_field('is_placeholder', 1 ,$new_id);
        }

        $json = array(  'rotary_layout' => $ad_type,
                        'rotary_header' => $header,
                        'rotary_body' => $body,
                        'rotary_image' => $image,
                        'image_information' => $image_information,
                        'run_dates' => $date_arr,
                        'id'        => $new_id,
                        'blah'      => (int)get_post_meta($id, "rotary_image")[0],
                        'blah2'      => $ad_type
                        );

        //return the fields to our javascript so that we can update the UI
        return json_encode($json);
    }

}
