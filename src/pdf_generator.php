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

    public static function get_entries_sorted_by_date(){
        $post_array = array();
        $args = array(
            'numberposts' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'vendi-rotary-flyer',
            'post_status' => array('publish')
        );
        $post_array = get_posts($args);
        $post_dates = array();
        foreach($post_array as $post){
            $run_dates = get_field('run_dates', $post->ID);
            foreach($run_dates as $run_date){
                if(!array_key_exists ( $run_date['run_date'] , $post_dates )){
                    $post_dates[$run_date['run_date']] = array();
                }
                array_push( $post_dates[$run_date['run_date']], $post->ID);
            }
        }

        return $post_dates;
    }

    //In the end, this function should generate html using get_the_field() from ACF and throw the html into one long String. It can
    //include the CSS file using $snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/000-vendi-rotary-main.css');
    //We should then be able to form a web view using another function
    public static function generate(){

        $snappy = new Pdf();
        $snappy->setBinary(VENDI_ROTARY_FLYER_DIR . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css');
        $snappy->setOption('margin-bottom', 5);
        $snappy->setOption('margin-top', 5);
        $snappy->setOption('margin-left', 5);
        $snappy->setOption('margin-right', 5);

        //calculate the date of the immediate next Thursday
        $thursday = strtotime('next thursday');
        $thursday = getDate($thursday);
        $month = $thursday["month"];
        $year = $thursday["year"];
        $mday = $thursday["mday"];

        $args = array(
            'post_type' => 'vendi-rotary-flyer',
            'post_status' => array('publish')
        );

        $loop = new \WP_Query( $args );

        $html_string = '<div id="main-output">';
        $html_string .= '<div class="pdf-header">';
        $html_string .= '<img src="'. VENDI_ROTARY_FLYER_DIR .'/images/RotaryAds_top_graphic.jpg" alt"header-image" />';
        $html_string .= '</div>';
        $html_string .= '<div class="pdf-date"><p> Week of: ' . $month . ' ' . $mday . ', ' . $year . '</p></div>';
        $html_string .= '<div class="main-output-region">';



        $post_count = 0;

        while ( $loop->have_posts() && $post_count < 9 ) : $loop->the_post();

            $rotary_layout = get_field('rotary_layout');
            $rotary_header = get_field('rotary_header');
            $rotary_body = get_field('rotary_body');
            $rotary_image = get_field('rotary_image');
            //Get the default image SRC
            $rotary_image_src = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );

            $alt_bg = '';

            if(wp_check_filetype( $rotary_image_src)['ext'] != "png" && wp_check_filetype( $rotary_image_src)['ext'] != false){
                $alt_bg = ' white-bg ';
            }

            //Get additional srcsets
            $rotary_image_srcset = wp_get_attachment_image_srcset( $rotary_image[ 'ID' ], 'home-featured-service' );

            //Get the sizes attribute
            $rotary_image_sizes  = wp_get_attachment_image_sizes(  $rotary_image[ 'ID' ], 'home-featured-service' );

            $rotary_image_server_path = get_attached_file($rotary_image[ 'ID' ]);


            if($rotary_layout == 'Stand-alone Image'){
                $html_string .=  '<div class="rotary-output standaloneimage">';
                $html_string .=  '<div class="rotary-output-wrapper '. $alt_bg .'">';
                $html_string .=      '<div class="rotary-image-container">';
                if($rotary_image){
                    $html_string .=          '<img class="rotary-image-output" ';
                    $html_string .=                 ' src="' . $rotary_image_server_path . '" ';
                    $html_string .=                 ' srcset="' . esc_attr( $rotary_image_srcset ) . ' " ';
                    $html_string .=                 ' sizes="' . esc_attr( $rotary_image_sizes ) .'" ';
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            elseif($rotary_layout == 'Header, Body Text'){
                $html_string .=  '<div class="rotary-output headerbodytext">';
                $html_string .=  '<div class="rotary-output-wrapper">';
                $html_string .=      '<div class="rotary-text">';
                $html_string .=          '<h2 class="rotary-header-output">' . $rotary_header . '</h2>';
                $html_string .=          '<div class="rotary-body-output">';
                $html_string .=             $rotary_body;
                $html_string .=          '</div>';
                $html_string .=      '</div>';
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            else{
                $html_string .=  '<div class="rotary-output headerbodytextimage '. $alt_bg .'">';
                $html_string .=  '<div class="rotary-output-wrapper '. $alt_bg .'">';
                $html_string .=      '<div class="rotary-text">';
                $html_string .=          '<h2 class="rotary-header-output">' . $rotary_header . '</h2>';
                $html_string .=          '<div class="rotary-body-output">';
                $html_string .=             $rotary_body;
                $html_string .=          '</div>';
                $html_string .=      '</div>';
                if($rotary_image){

                    $image_information = json_decode(get_field('image_information', $post_id));

                    $html_string .=      '<div class="rotary-image-container">';
                    $html_string .=          '<img class="rotary-image-output" ';
                    $html_string .=                 ' src="' . $rotary_image_server_path . '" ';
                    $html_string .=                 ' srcset="' . esc_attr( $rotary_image_srcset ) . ' " ';

                    $html_string .=                 ' sizes="' . esc_attr( $rotary_image_sizes ) .'" ';

                    if($image_information){
                        $html_string .=                 ' height="'. esc_attr( $image_information[0]->height ) .'" ';
                        $html_string .=                 ' width="'. esc_attr( $image_information[0]->width ) .'" ';
                    }
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            $post_count++;
        endwhile;
        $html_string .= '    <div class="pdf-footer"> Email rotarylax@charter.net for help or questions. Form app by Vendi Advertising. </div>';
        $html_string .= '    </div></div>';

        echo $html_string;
        $snappy->generateFromHtml($html_string, VENDI_ROTARY_FLYER_DIR . '/pdfs/bill-1234.pdf', array(), $overwrite = true);

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
