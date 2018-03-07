<!DOCTYPE html>
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php
$user = wp_get_current_user();
if(!is_front_page() && 0 == $user->ID )
{
     auth_redirect();
}
else{
    // if admin page, then redirect user away
    if(is_page(array('admin-view','pdf-assembler','pdf-builder','member-costs'))) {
        if(!current_user_can( 'delete_users' )) {
            wp_safe_redirect( VENDI_ROTARY_PDF_CREATION);
        }
    }
}
wp_head();

?>
</head>
<body <?php body_class(); ?>>

<?php

do_action( 'vendi/rotary-flyer/body-header' );
