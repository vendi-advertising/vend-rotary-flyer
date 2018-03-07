<?php

$user = wp_get_current_user();
$is_admin = isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles );

$export_file_id = \Vendi\Shared\utils::get_get_value('id');
$abs_file_path = VENDI_ROTARY_FLYER_DIR . '/pdfs/' . $export_file_id . '.pdf';

$error_message = null;
if(!$is_admin){
    $error_message = 'Please login to download the flier';
}elseif(!is_readable($abs_file_path)){
    $error_message = 'The requested PDF could not be found, please generate a new one.';
}else{
    //Standard header stuff
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=RotaryFlier.pdf' );
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize( $abs_file_path ) );

    //Kill off anything that was previously streamed, just in case.
    ob_clean();
    //Push the headers out so that the filesize can be seen by the client
    flush();
    //Push the file
    readfile( $abs_file_path );

    //Stop PHP
    exit;
}

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_header();

?>

<div id="main-content">
    <div class="main-content-region">
        <h1>Error</h1>
        <p><?php echo $error_message; ?></p>
    </div>
</div>

<?php

\Vendi\Shared\template_router::get_instance( 'RotaryFlyer' )->get_footer();
