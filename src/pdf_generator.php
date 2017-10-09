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
        $snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css');

        $args = array( 'post_type' => 'vendi-rotary-flyer');
        $loop = new \WP_Query( $args );

        $html_string = '<div id="main-output"><div class="main-output-region">';

        $post_count = 0;

        while ( $loop->have_posts() && $post_count <= 9 ) : $loop->the_post();

            $rotary_layout = get_field('rotary_layout');
            $rotary_header = get_field('rotary_header');
            $rotary_body = get_field('rotary_body');
            $rotary_image = get_field('rotary_image');
            //Get the default image SRC
            $rotary_image_src = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );

            //Get additional srcsets
            $rotary_image_srcset = wp_get_attachment_image_srcset( $rotary_image[ 'ID' ], 'home-featured-service' );

            //Get the sizes attribute
            $rotary_image_sizes  = wp_get_attachment_image_sizes(  $rotary_image[ 'ID' ], 'home-featured-service' );

            $rotary_image_server_path = get_attached_file($rotary_image[ 'ID' ]);


            if($rotary_layout == 'Stand-alone Image'){
                $html_string .=  '<div class="rotary-output standaloneimage">';
                $html_string .=      '<div class="rotary-image-container">';
                $html_string .=          '<img class="rotary-image-output" ';
                $html_string .=                 'src="' . $rotary_image_server_path . '"';
                $html_string .=                 'srcset="' . esc_attr( $rotary_image_srcset ) . '"';
                $html_string .=                 'sizes="' . esc_attr( $rotary_image_sizes ) .'"';
                $html_string .=                 'alt="rotary-image" />';
                $html_string .=      '</div>';
                $html_string .=  '</div>';
            }
            elseif($rotary_layout == 'Header, Body Text'){
                $html_string .=  '<div class="rotary-output headerbodytext">';
                $html_string .=      '<div class="rotary-text">';
                $html_string .=          '<h2 class="rotary-header-output">' . $rotary_header . '</h2>';
                $html_string .=          '<div class="rotary-body-output">';
                $html_string .=             $rotary_body;
                $html_string .=          '</div>';
                $html_string .=      '</div>';
                $html_string .=  '</div>';
            }
            else{
                $html_string .=  '<div class="rotary-output headerbodytextimage">';
                $html_string .=      '<div class="rotary-text">';
                $html_string .=          '<h2 class="rotary-header-output">' . $rotary_header . '</h2>';
                $html_string .=          '<div class="rotary-body-output">';
                $html_string .=             $rotary_body;
                $html_string .=          '</div>';
                $html_string .=      '</div>';
                $html_string .=      '<div class="rotary-image-container">';
                $html_string .=          '<img class="rotary-image-output" ';
                $html_string .=                 'src="' . $rotary_image_server_path . '"';
                $html_string .=                 'srcset="' . esc_attr( $rotary_image_srcset ) . '"';
                $html_string .=                 'sizes="' . esc_attr( $rotary_image_sizes ) .'"';
                $html_string .=                 'alt="rotary-image" />';
                $html_string .=      '</div>';
                $html_string .=  '</div>';
            }
            $post_count++;
        endwhile;

        $html_string .= '    </div></div>';

        echo $html_string;
        $snappy->generateFromHtml($html_string, VENDI_ROTARY_FLYER_DIR . '/pdfs/bill-1234.pdf');

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
