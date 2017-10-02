<?php
add_filter('acf/pre_save_post' , 'my_pre_save_post' );

function my_pre_save_post( $post_id ) {

    // bail early if not a new post
    if( $post_id !== 'new' ) {
        return $post_id;

    }


    // vars
    $title = $_POST['fields']['field_59cd6a7881b5d'];

    // Create a new post
    $post = array(
        'post_status'   => 'draft',
        'post_type'     => 'vendi-rotary-flyer',
        'post_title'    => $title,
    );

    // insert the post
    $post_id = wp_insert_post( $post );

    // return the new ID
    return $post_id;

}

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


    // get custom fields (field group exists for content_form)
    $header = get_field('flyer_entry_header', $post_id);
    $content = get_field('flyer_entry_content', $post_id);
    $image = get_field('flyer_entry_image', $post_id);

    //dump($header, $content, $image);
    // email data
    //$to = 'contact@website.com';
    //$headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n";
    //$subject = $post->post_title;
    //$body = $post->post_content;


    // send email
    //wp_mail($to, $subject, $body, $headers );

}
