<?php
/*
Plugin Name: Vendi Rotary Flyer
Version: 0.1
*/

define( 'VENDI_ROTARY_FLYER_FILE', __FILE__ );
define( 'VENDI_ROTARY_FLYER_DIR', dirname( __FILE__ ) );
define( 'VENDI_ROTARY_WP_PLUGIN_DIR_URL', plugin_dir_url(VENDI_ROTARY_FLYER_FILE));
define( 'VENDI_ROTARY_PLUGIN_NAME', 'vendi-rotary-flyer');
define( 'VENDI_ROTARY_USER_DASHBOARD', '/rotary-user-dashboard/');
define( 'VENDI_ROTARY_PDF_CREATION', '/ad-creation/');
define( 'VENDI_ROTARY_ADMIN_DASHBOARD', '/admin-view/');
define( 'VENDI_ROTARY_PDF_ASSEMBLER_PAGE', '/pdf-assembler/' );
define( 'VENDI_ROTARY_PDF_GENERATION_PAGE', '/pdf-builder/');
define( 'VENDI_ROTARY_CREDITS_PURCHASE_PAGE', '/purchase-credits/' );
define( 'VENDI_ROTARY_CREDITS_CONFIRMATION_PAGE', '/credit-purchase-confirmation/' );

//Auto-load code
require_once VENDI_ROTARY_FLYER_DIR . '/includes/autoload.php';

//Hooks such as add_filter() and add_action()
require_once VENDI_ROTARY_FLYER_DIR . '/includes/hooks.php';

require_once VENDI_ROTARY_FLYER_DIR . '/includes/init.php';
