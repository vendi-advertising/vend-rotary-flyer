<?php
/*
Plugin Name: Vendi Rotary Flyer
Version: 0.1
*/

define( 'VENDI_ROTARY_FLYER_FILE', __FILE__ );
define( 'VENDI_ROTARY_FLYER_DIR', dirname( __FILE__ ) );

require_once VENDI_ROTARY_FLYER_DIR . '/includes/autoload.php';

Vendi\RotaryFlyer\content_types::init();
Vendi\RotaryFlyer\page_templater::init();
