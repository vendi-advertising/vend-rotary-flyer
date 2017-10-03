<?php

add_action('acf/save_post', 'my_save_post');

function my_save_post( $post_id ) {
    // bail early if not a contact_form post
    if( get_post_type($post_id) !== 'vendi-rotary-flyer' ) {

        return;

    }


    // bail early if editing in admin
    if( is_admin() ) {

        return;

    }


    // vars
    $post = get_post( $post_id );


    //dump($header, $content, $image);
    // email data
    //$to = 'contact@website.com';
    //$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
    //$subject = $post->post_title;
    //$body = $post->post_content;


    // send email
    //wp_mail($to, $subject, $body, $headers );

}

//Load CSS and JavaScript
 add_action(
             'wp_enqueue_scripts',
             function()
             {
                 //Kill of Clef which wants to load on every page.
                 //So far this has been safe because Clef special-cases the login page anyway
                 wp_dequeue_style( 'wpclef-main' );

                 /*****************
                  *  Parent Theme *
                  *****************/
                 //Load each CSS file that starts with three digits followed by a dash in numerical order
                 foreach( glob( VENDI_ROTARY_FLYER_DIR . '/css/[0-9][0-9][0-9]-*.css' ) as $t )
                 {
                     wp_enqueue_style(
                                         basename( $t, '.css' ) . '-p-style',
                                         plugins_url($plugin = VENDI_ROTARY_PLUGIN_NAME ) . '/css/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/css/' . basename( $t ) ),
                                         'screen'
                                     );
                 }

                 //Load each JS file that starts with three digits followed by a dash in numerical order
                 foreach( glob( VENDI_ROTARY_FLYER_DIR . '/js/[0-9][0-9][0-9]-*.js' ) as $t )
                 {
                     wp_enqueue_script(
                                         basename( $t, '.js' ) . '-p-style',
                                         plugins_url($plugin = VENDI_ROTARY_PLUGIN_NAME ) . '/js/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/js/' . basename( $t ) ),
                                         true
                                     );
                 }
             }
         );
