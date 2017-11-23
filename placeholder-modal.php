<?php


?>
<!-- The Modal -->
<div id="placeholderModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="modal-preview-region">
    <h1> Select a placeholder </h1>
    <?php
        $args = array(
            'post_type' => 'Rotary Placeholder',
            'post_status' => array('publish')
        );

        $loop = new \WP_Query( $args );

        while ( $loop->have_posts()) : $loop->the_post();

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

            $rotary_image_server_path = wp_get_attachment_image_url(    $rotary_image[ 'ID' ], 'home-featured-service' );


            if($rotary_layout == 'Stand-alone Image'){
                ?>
                    <div class="modal-preview-output rotary-output standaloneimage">

                        <div class="rotary-output-wrapper <?php echo $alt_bg;?>">
                            <div class="approve-container">
                                <h1> select </h1>
                            </div>
                            <div class="rotary-image-container">
                                <img class="rotary-image-output" src="<?php echo $rotary_image_server_path;?>" alt="rotary-image">
                            </div>

                        </div>
                    </div>
                <?php
            }
            elseif($rotary_layout == 'Header, Body Text'){
                ?>
                    <div class="modal-preview-output rotary-output headerbodytext">
                        <div class="rotary-output-wrapper">
                            <div class="approve-container">
                                <h1> select </h1>
                            </div>
                            <div class="rotary-text">
                                <h2 class="rotary-header-output"> <?php echo $rotary_header; ?> </h2>
                                <div class="rotary-body-output">
                                    <?php echo $rotary_body; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php
            }
            else{
                ?>
                    <div class="modal-preview-output rotary-output headerbodytextimage <?php echo $alt_bg; ?> ">
                        <div class="rotary-output-wrapper <?php echo $alt_bg;?>">
                            <div class="approve-container">
                                <h1> select </h1>
                            </div>
                            <div class="rotary-text">
                                <h2 class="rotary-header-output"> <?php echo $rotary_header; ?> </h2>
                                <div class="rotary-body-output">
                                    <?php echo $rotary_body; ?>
                                </div>
                            </div>
                            <div class="rotary-image-container">
                                <img class="rotary-image-output" src="<?php echo $rotary_image_server_path;?>" alt="rotary-image">
                            </div>

                        </div>
                    </div>
                <?php
            }

        endwhile;
    ?>
    </div>
  </div>

</div>
