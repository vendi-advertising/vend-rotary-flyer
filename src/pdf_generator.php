<?php

namespace Vendi\RotaryFlyer;

use Knp\Snappy\Pdf;
use dawood\phpChrome\Chrome;

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

    public static function get_entries_sorted_by_date($publish){
        $post_array = array();
        if($publish){
            $args = array(
                'numberposts' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'vendi-rotary-flyer',
                'post_status' => array('pending', 'publish')
            );
        }
        else{
            $args = array(
                'numberposts' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
                'post_type' => 'vendi-rotary-flyer',
                'post_status' => array('publish')
            );
        }
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

    public static function generate_placeholders($id_arr, $date){
        $args = array(
            'post_type' => 'Rotary Placeholder',
            'post_status' => array('publish', 'pending'),
            'post__in'      => $id_arr
        );

        $loop = new \WP_Query( $args );

        //loop through placeholder posts and get the field values
        //
        //create new rotary-flyer post
        //
        //get new $post_ID
        //
        //use update_field($selector, $value, $post_ID) to insert field values
        //
        //use get_entries_sorted_by_date to get all posts
        //
        //create rotary flyer with generate_preview_for_date

        while ( $loop->have_posts()) : $loop->the_post();
            $post_id            =   trim(get_the_ID());
            $title              =   trim(get_the_title());
            $rotary_layout      =   get_field('rotary_layout');
            $rotary_header      =   get_field('rotary_header');
            $rotary_body        =   get_field('rotary_body');
            $rotary_image       =   get_field('rotary_image');
            $image_information  =   get_field('image_information', $post_id);
            $date_arr           =   array(array("run_date" => $date));
            //wp_insert_post();

            $new_id = wp_insert_post(array(
                'post_type' => 'vendi-rotary-flyer',
                'post_title' => $title,
                'post_content' => '',
                'post_status'   => 'publish',
            ));

            if($new_id !== 0){
                update_field('rotary_layout', $rotary_layout, $new_id);
                update_field('rotary_header', $rotary_header, $new_id);
                update_field('rotary_body', $rotary_body, $new_id);
                update_field('rotary_image', $rotary_image, $new_id);
                update_field('image_information', $image_information, $new_id);
                update_field('run_dates', $date_arr, $new_id);
            }


        endwhile;


    }

    public static function generate_preview_for_date($id_arr, $week, $pdf_wrapper=false){
        $args = array(
            'post_type' => 'vendi-rotary-flyer',
            'post_status' => array('publish', 'pending'),
            'post__in'      => $id_arr,
        );
        $loop = new \WP_Query( $args );
        $loop->posts = array_reverse($loop->posts);
        $html_string = '<div id="main-output">';
        if($pdf_wrapper){
            $html_string .= '<div class="pdf-header">';
            $html_string .= '<img src="'. VENDI_ROTARY_FLYER_DIR .'/images/RotaryAds_top_graphic.jpg" alt"header-image" />';
            $html_string .= '</div>';
        }
        $date_string_to_time = strtotime($week);
        $newDate = date("F j, Y", $date_string_to_time);
        $html_string .= '<div data-date="' . $week . '" class="pdf-date"><p> Week of: ' .  $newDate . '</p></div>';
        $html_string .= '<div class="main-output-region">';



        $post_count = 0;
        $post_limit = 9;

        while ( $loop->have_posts() && $post_count < $post_limit) : $loop->the_post();

            $post_id       = trim(get_the_ID());
            $post_status   = get_post_status( $post_id );

            if($post_status == 'publish'){
                $toggleString = '<div class="approve-container">

                    <div data-name="'. $post_id .'" class="toggle active">
                      <h1> Administrative Options </h1>
                      <div class="toggle-header"><label> Approval Status: </label></div>
                      <div class="toggle-switch"></div>
                      <div class="toggle-label toggle-label-on">Approved</div>
                    </div>
                    <div class="edit-post-container">
                        <a href="/ad-creation/?post_id='. $post_id .'"> &#9998;Edit post </a>
                    </div>
                    </div>';
            }
            else{
                $toggleString = '<div class="approve-container">

                    <div data-name="'. $post_id .'" class="toggle">
                      <h1> Administrative Options </h1>
                      <div class="toggle-header"><label> Approval Status: </label></div>
                      <div class="toggle-switch"></div>
                      <div class="toggle-label toggle-label-on">Unapproved</div>
                    </div>
                    <div class="edit-post-container">
                        <a href="/ad-creation/?post_id='. $post_id .'"> &#9998;Edit post </a>
                    </div>
                    </div>';
            }

            $post_date = str_replace( "/", "_", $week);
            $form = '<form class="generation-form" method="post" action="'. VENDI_ROTARY_PDF_GENERATION_PAGE .'">
                      <input type="hidden" type="text" id="pdf_date" name="pdf_date" value="'.$post_date.'">
                      <input class="center steps-button" type="submit" value="Generate PDF">
                    </form>';

            $rotary_layout = get_field('rotary_layout');
            $rotary_header = get_field('rotary_header');
            $rotary_body = get_field('rotary_body');
            $rotary_image = get_field('rotary_image');
            //Get the default image SRC
            $rotary_image_src = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );

            $alt_bg = '';
            /*if(wp_check_filetype( $rotary_image_src)['ext'] != "png" && wp_check_filetype( $rotary_image_src)['ext'] != false){
                $alt_bg = ' white-bg ';
            }*/


            //Get additional srcsets
            $rotary_image_srcset = wp_get_attachment_image_srcset( $rotary_image[ 'ID' ], 'home-featured-service' );
            //Get the sizes attribute
            $rotary_image_sizes  = wp_get_attachment_image_sizes(  $rotary_image[ 'ID' ], 'home-featured-service' );

            if($pdf_wrapper){
                $rotary_image_server_path = get_attached_file($rotary_image[ 'ID' ]);
            }
            else{
                $rotary_image_server_path = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );
            }

            if($rotary_layout == 'Stand-alone Image'){
                $html_string .=  '<div class="rotary-output standaloneimage white-bg ">';
                $html_string .=  '<div id="post-'. $post_id .'" class="rotary-output-wrapper white-bg '. $alt_bg .'">';
                $html_string .=  $toggleString;
                $html_string .=      '<div class="rotary-image-container">';
                if($rotary_image){
                    $html_string .=          '<img class="rotary-image-output" ';
                    $html_string .=                 ' src="' . $rotary_image_server_path . '" ';
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            elseif($rotary_layout == 'Header, Body Text'){
                $html_string .=  '<div class="rotary-output headerbodytext">';
                $html_string .=  '<div id="post-'. $post_id .'" class="rotary-output-wrapper">';
                $html_string .=  $toggleString;
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
                $html_string .=  '<div id="post-'. $post_id .'" class="rotary-output-wrapper white-bg '. $alt_bg .'">';
                $html_string .=  $toggleString;
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
                    if($image_information){
                        /*$html_string .=                 ' height="'. esc_attr( $image_information[0]->height ) .'" ';
                        $html_string .=                 ' width="'. esc_attr( $image_information[0]->width ) .'" ';*/
                        $html_string .= 'style="width: '. esc_attr( $image_information[0]->width ) .'px !important; height: '. esc_attr( $image_information[0]->height ) .'px !important;"';
                    }
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            $post_count++;
        endwhile;

        if($post_count < $post_limit ){
            while($post_count < $post_limit){
                $html_string .= '<div data-slot="'.($post_count-1).'" id="placeholder-'.$post_count.'" class="rotary-output place-holder-entry">';
                $html_string .= '<p> &#43; Insert Placeholder </p>';
                $html_string .= '</div>';
                $post_count++;
            }

        }
        $html_string .= $form;
        if($pdf_wrapper){
            $html_string .= '    <div class="pdf-footer"> Email rotarylax@charter.net for help or questions. Form app by Vendi Advertising. </div>';
        }
        $html_string .= '    </div></div>';

        return $html_string;
    }


    public static function generate_for_date($id_arr, $week, $pdf=false){
        $args = array(
            'post_type' => 'vendi-rotary-flyer',
            'post_status' => array('publish'),
            'post__in'      => $id_arr,
            'posts_per_page' => 9
        );
        $loop = new \WP_Query( $args );
        $loop->posts = array_reverse($loop->posts);
        $fonts = [
                    'Open Sans' => [
                                        'Open Sans Bold Italic' =>  [
                                                                        'weight' => 700,
                                                                        'style'  => 'italic',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensans/v15/PRmiXeptR36kaC0GEAetxp_TkvowlIOtbR7ePgFOpF4.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                                        'Open Sans Light' =>  [
                                                                        'weight' => 300,
                                                                        'style'  => 'normal',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensans/v15/DXI1ORHCpsQm3Vp6mXoaTYnF5uFdDttMLvmWuJdhhgs.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                                        'Open Sans Regular' =>  [
                                                                        'weight' => 400,
                                                                        'style'  => 'normal',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensans/v15/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                                        'Open Sans Bold' =>  [
                                                                        'weight' => 700,
                                                                        'style'  => 'normal',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensans/v15/k3k702ZOKiLJc3WVjuplzInF5uFdDttMLvmWuJdhhgs.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                    ],
                    'Open Sans Condensed' => [
                                        'Open Sans Condensed Light Italic' =>  [
                                                                        'weight' => 300,
                                                                        'style'  => 'italic',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensanscondensed/v12/jIXlqT1WKafUSwj6s9AzV6-Pg0ixc20mZJdRQiuQhCr3rGVtsTkPsbDajuO5ueQw.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                                        'Open Sans Condensed Light' =>  [
                                                                        'weight' => 300,
                                                                        'style'  => 'normal',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensanscondensed/v12/gk5FxslNkTTHtojXrkp-xD1GzwQ5qF9DNzkQQVRhJ4g.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                                        'Open Sans Condensed Bold' =>  [
                                                                        'weight' => 700,
                                                                        'style'  => 'normal',
                                                                        'url'    => 'https://fonts.gstatic.com/s/opensanscondensed/v12/gk5FxslNkTTHtojXrkp-xJhsE6jcpsD2oq89kgohWx0.ttf',
                                                                        'format' => 'truetype',
                                                                ],
                    ],
        ];

        $font_string = '';
        foreach( $fonts as $font_family => $items )
        {
            foreach( $items as $local => $options )
            {
                $font_string .= sprintf(
                                        '
                                            @font-face {
                                              font-family: \'%1$s\';
                                              font-style: %2$s;
                                              font-weight: %3$s;
                                              src: url(%4$s) format(\'%5$s\');
                                            }
                                        ',
                                        $font_family,
                                        $options[ 'style' ],
                                        $options[ 'weight' ],
                                        'data:application/octet-stream;base64,' . base64_encode( file_get_contents( $options[ 'url' ] ) ),
                                        $options[ 'format' ]
                );
            }
        }

        // dump( $font_string );
        // die;

        $html_string ='<!doctype HTML>
        <html>
        <head>
        <title>Rotary PDF</title>
        <style>' . $font_string . '</style>
        <style>' .
        // file_get_contents( 'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300i,700|Open+Sans:300,400,700,700i' ) .
        file_get_contents( VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css' ) .
        '</style>
        <style>
        body{
            margin: 4px 3px 4px 5px;
        }
        .main-output-region{
            font-size: 0;
        }
        .pdf-footer{
            font-size: 16px;
        }
        .rotary-output{
            margin: 14px 17px;
        }
        .rotary-output:nth-child(3n){
            margin-right: 0;
        }
        .pdf-sub-footer{
            font-size: 13px;
            text-align: center;
        }
        </style>
        <base href="'. get_site_url() .'/" />
        </head>
        <body>';

        $html_string .= '<div id="main-output">';
        $html_string .= '<div class="pdf-header">';
        $html_string .= '<img src="data:' . mime_content_type( VENDI_ROTARY_FLYER_DIR .'/images/RotaryAds_graphic_header.png' ) . ';base64,' . base64_encode( file_get_contents( VENDI_ROTARY_FLYER_DIR .'/images/RotaryAds_graphic_header.png' ) ) . '" alt="header-image" />';

        //$src = 'src="data: '.mime_content_type($tmpfname).';base64,'.$imageData . '" ';

        $date_string_to_time = strtotime($week);
        $newDate = date("F j, Y", $date_string_to_time);
        $html_string .= '</div>';
        $html_string .= '<div class="pdf-date"><p> WEEK OF: ' . $newDate . '</p></div>';
        $html_string .= '<div class="main-output-region">';


        $post_count = 0;

        while ( $loop->have_posts()) : $loop->the_post();

            $post_id       = trim(get_the_ID());
            $post_status   = get_post_status( $post_id );

            if($post_status == 'publish'){
                $toggleString = '';
            }
            else{
                $toggleString = '';
            }

            $rotary_layout = get_field('rotary_layout');
            $rotary_header = get_field('rotary_header');
//            $rotary_body = str_replace('–', '&ndash;', get_field('rotary_body'));
            $rotary_body = htmlentities( get_field('rotary_body'));

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

            // $binary = file_get_contents( get_attached_file($rotary_image[ 'ID' ]) );

            // if($pdf){
                // $rotary_image_server_path = get_attached_file($rotary_image[ 'ID' ]);

                $rotary_image_server_path = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );

                if( $rotary_image_server_path ){
                    // dump( $rotary_image_server_path );

                    $tmpfname = tempnam('/tmp', 'FOO');

                    file_put_contents( $tmpfname, file_get_contents( $rotary_image_server_path ) );

                    $imageData = base64_encode(file_get_contents($tmpfname));

                    $src = 'src="data: '.mime_content_type($tmpfname).';base64,'.$imageData . '" ';

                    unlink($tmpfname);
                }


            // }
            // else{
                // $rotary_image_server_path = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );
            // }
            //

            if($rotary_layout == 'Stand-alone Image'){
                $html_string .=  '<div class="rotary-output standaloneimage">';
                $html_string .=  '<div id="post-'. $post_id .'" class="rotary-output-wrapper '. $alt_bg .'">';
                $html_string .=  $toggleString;
                $html_string .=      '<div class="rotary-image-container">';
                if($rotary_image){
                    $html_string .=          '<img class="rotary-image-output" ';
                    $html_string .=                 $src;
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            elseif($rotary_layout == 'Header, Body Text'){
                $html_string .=  '<div class="rotary-output headerbodytext">';
                $html_string .=  '<div id="post-'. $post_id .'" class="rotary-output-wrapper">';
                $html_string .=  $toggleString;
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
                $html_string .=  '<div id="post-'. $post_id .'" class="rotary-output-wrapper '. $alt_bg .'">';
                $html_string .=  $toggleString;
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
                    $html_string .=                 $src;
                    if($image_information){
                        /*$html_string .=                 ' height="'. esc_attr( $image_information[0]->height ) .'" ';
                        $html_string .=                 ' width="'. esc_attr( $image_information[0]->width ) .'" ';*/
                        $html_string .= 'style="width: '. esc_attr( $image_information[0]->width ) .'px !important; height: '. esc_attr( $image_information[0]->height ) .'px !important;"';
                    }
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
                $html_string .=  '</div>';
            }
            $html_string .= "\n";
            $post_count++;
        endwhile;
        $html_string .= '    <div><div class="pdf-footer"> Share an announcement with your Rotary Community. Weekly space available at: rotarycluboflacrosse.org </div><div class="pdf-sub-footer"> Web app by Vendi </div></div>';
        $html_string .= '    </div></div></body></html>';

        // $html_string = str_replace( get_site_url(), 'file://' . dirname( dirname( dirname( VENDI_ROTARY_FLYER_DIR ) ) ), $html_string);

        // echo $html_string;
        // die;

        $snappy = new Pdf();
        $snappy->setBinary(VENDI_ROTARY_FLYER_DIR . '/vendor/bin/wkhtmltopdf-amd64');
        // $snappy->setOption('enable-javascript', true);
        // $snappy->setOption('javascript-delay', 13500);
        // $snappy->setOption('enable-smart-shrinking', true);
        // $snappy->setOption('no-stop-slow-scripts', true);
        //$snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/050-fonts.css');
        // $snappy->setOption('user-style-sheet', VENDI_ROTARY_FLYER_DIR . '/css/100-pdf-output.css');
        $snappy->setOption('page-size', 'Letter');
        $snappy->setOption('margin-bottom', 0);
        $snappy->setOption('margin-top', 0);
        $snappy->setOption('margin-left', 5);
        $snappy->setOption('margin-right', 3);
        $snappy->generateFromHtml($html_string, VENDI_ROTARY_FLYER_DIR . '/pdfs/bill-1234.pdf', array(), $overwrite = true);
        $return_arr = array(
            'html' => $html_string,
            'link' => plugins_url() . '/vendi-rotary-flyer/pdfs/bill-1234.pdf'
        );
        return $return_arr;


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
