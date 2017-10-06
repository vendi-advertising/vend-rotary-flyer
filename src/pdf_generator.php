<?php

namespace Vendi\RotaryFlyer;

use Knp\Snappy\Pdf;

class pdf_generator {

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

    //In the end, this function should generate html using get_the_field() from ACF and throw the html into one long String. It can
    //include the CSS file using $snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/000-vendi-rotary-main.css');
    //We should then be able to form a web view using another function
    public static function generate(){

        $snappy = new Pdf();
        $snappy->setBinary(VENDI_ROTARY_FLYER_DIR . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/000-vendi-rotary-main.css');
        $snappy->generateFromHtml('<h1 class="test-class">Bill</h1><p>You owe me money, dude.</p>', VENDI_ROTARY_FLYER_DIR . '/pdfs/bill-123.pdf');

    }

    public static function generate_from_url(){
        $snappy = new Pdf();
        $snappy->setBinary(VENDI_ROTARY_FLYER_DIR . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $pageUrl = $snappy->generate('https://rotary-pdfs.helix.vendiadvertising.com/test-page/', '/pdfs/test-output.pdf', array(), $overwrite = true);

    }

    public static function render_in_page(){
        /*$snappy = new Pdf();
        $snappy->setBinary(VENDI_ROTARY_FLYER_DIR . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $pageUrl = $this->generate('https://rotary-pdfs.helix.vendiadvertising.com/test-page/', '/pdfs/test-output.pdf', $overwrite = true);*/

    }

    public static function init(){
        self::get_instance();
    }
}
