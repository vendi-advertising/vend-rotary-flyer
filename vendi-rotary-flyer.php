<?php
/*
Plugin Name: Vendi Rotary Flyer
Version: 0.1
*/

define( 'VENDI_ROTARY_FLYER_FILE', __FILE__ );
define( 'VENDI_ROTARY_FLYER_DIR', dirname( VENDI_ROTARY_FLYER_FILE ) );

//Additional constants
require_once VENDI_ROTARY_FLYER_DIR . '/includes/constants.php';

//Auto-load code
require_once VENDI_ROTARY_FLYER_DIR . '/includes/autoload.php';

//Hooks such as add_filter() and add_action()
require_once VENDI_ROTARY_FLYER_DIR . '/includes/hooks.php';

//Any misc code
require_once VENDI_ROTARY_FLYER_DIR . '/includes/init.php';
