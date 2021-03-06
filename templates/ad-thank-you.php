<?php

\Vendi\Shared\template_router::get_instance()->get_header();

if(isset($_GET['postid'])){
    $post_id = $_GET['postid'];
}else{
    $post_id = '';
}

?>

<div id="main-content">
    <div class="main-content-region">
        <div class="main-messaging">
            <h1>Your finalized Rotary announcement </h1>
            <p>The announcement will display as it looks here:</p>
        </div>
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
            $html_string = '';
            $html_string .= '<div class="finalization-region">';

            if($rotary_layout == 'Stand-alone Image'){
                $html_string .=  '<div class="rotary-output standaloneimage white-bg ">';
                $html_string .=  '<div class="rotary-output-wrapper  white-bg '. $alt_bg .'">';
                $html_string .=      '<div class="rotary-image-container">';
                if($rotary_image){
                    $html_string .=          '<img class="rotary-image-output" ';
                    $html_string .=                 ' src="' . $rotary_image_src . '" ';
                    $html_string .=                 ' srcset="' . esc_attr( $rotary_image_srcset ) . '" ';
                    $html_string .=                 ' sizes="' . esc_attr( $rotary_image_sizes ) .'" ';
                    $html_string .=                 ' alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
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
            }
            else{
                $html_string .=  '<div class="rotary-output headerbodytextimage  white-bg '. $alt_bg .'">';
                $html_string .=  '<div class="rotary-output-wrapper  white-bg '. $alt_bg .'">';
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
                    $html_string .=                 'src="' . $rotary_image_src . '"';
                    $html_string .=                 'srcset="' . esc_attr( $rotary_image_srcset ) . '"';
                    if($image_information){
                        $html_string .=                 'height="'. esc_attr( $image_information[0]->height ) .'"';
                        $html_string .=                 'width="'. esc_attr( $image_information[0]->width ) .'"';
                    }
                    $html_string .=                 'alt="rotary-image" />';
                    $html_string .=      '</div>';
                }
                $html_string .=  '</div>';
            }
            $html_string .= '</div>';

        echo $html_string;

        ?>


    </div>
    <div class="acf-form-submit">
        <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance()->create_url('add-edit-ad', ['post_id' => $post_id]);  ?>"> Edit Ad Post </a>
        <a class="steps-button" href="<?php echo \Vendi\Shared\template_router::get_instance()->create_url('submit-ad', ['post_id' => $post_id]);  ?>"> Submit Final Ad</a>

        </div>
</div>
</div>

<?php
\Vendi\Shared\template_router::get_instance()->get_footer();
