<?php
/**
 * Template Name: Purchase Credits
 *
 */
?>
<?php
require 'rotary-functions.php';


get_header();

$payment = new Vendi\RotaryFlyer\payment(get_current_user_id());

?>
<?php
get_footer();
