<?php
/**
 * Template Name: Thank you
 *
 */
?>
<?php



require 'rotary-functions.php';

get_header();

if(isset($_GET['postid'])){
    $post_id = $_GET['postid'];
}

?>

<div id="main-content">
    <div class="main-content-region">
        <h1> Your finalized post </h1>
        <?php

            $rotary_layout = get_field('rotary_layout', $post_id);
            $rotary_header = get_field('rotary_header', $post_id);
            $rotary_body = get_field('rotary_body', $post_id);
            $rotary_image = get_field('rotary_image', $post_id);
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
                    $html_string .=                 'src="' . $rotary_image_server_path . '"';
                    $html_string .=                 'srcset="' . esc_attr( $rotary_image_srcset ) . '"';
                    $html_string .=                 'sizes="' . esc_attr( $rotary_image_sizes ) .'"';
                    $html_string .=                 'alt="rotary-image" />';
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
                    $html_string .=      '<div class="rotary-image-container">';
                    $html_string .=          '<img class="rotary-image-output" ';
                    $html_string .=                 'src="' . $rotary_image_server_path . '"';
                    $html_string .=                 'srcset="' . esc_attr( $rotary_image_srcset ) . '"';
                    $html_string .=                 'sizes="' . esc_attr( $rotary_image_sizes ) .'"';
                    $html_string .=                 'alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
            }
            $post_count++;

        echo $html_string;

        ?>


    </div>
    <div>
            <a class="steps-button" href="<?php echo home_url('test-page?post_id='. $post_id . '');  ?>"> Edit Posting </a><a class="steps-button" href="<?php echo home_url('submit-final?post_id='. $post_id . '');  ?>"> Submit Final </a>
        </div>
</div>
</div>


<?php get_footer(); ?>
