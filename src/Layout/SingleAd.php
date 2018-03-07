<?php

namespace Vendi\RotaryFlyer\Layout;

class SingleAd
{
    public $outer_classes = [];

    public $inner_classes = [];

    private $_post;

    public function __construct($post_or_id)
    {
        $this->_post = get_post($post_or_id);
    }

    public function with_outer_classes(array $classes = []) : self
    {
        $this->outer_classes = $classes;
        return $this;
    }

    public function with_inner_classes(array $classes = []) : self
    {
        $this->inner_classes = $classes;
        return $this;
    }

    public function get_toggle_html() : string
    {
        $post_id = $this->_post->ID;

        $edit_url = \Vendi\Shared\template_router::get_instance()->create_url('add-edit-ad', ['post_id' => $post_id]);

        $classes = ['toggle'];
        if('publish' === $this->_post->post_status)
        {
            $classes[] = 'active';
            $text = 'Approved';
        }else{
            $text = 'Unapproved';
        }

        return sprintf(
                        '
                            <div class="approve-container">
                                <div data-name="%1$s" class="%2$s">
                                    <h1> Administrative Options </h1>
                                    <div class="toggle-header"><label> Approval Status: </label></div>
                                    <div class="toggle-switch"></div>
                                    <div class="toggle-label toggle-label-on">%3$s</div>
                                </div>
                                <div class="edit-post-container">
                                    <a href="%4$s"> &#9998;Edit post </a>
                                </div>
                            </div>
                        ',
                        $post_id,
                        implode(' ', $classes),
                        esc_html( $text ),
                        esc_url( $edit_url )
                    );
    }

    private function _setup_shell()
    {
        $layout = get_field('rotary_layout', $this->_post);

        switch($layout){
            case 'Stand-alone Image':
                $this
                    ->with_outer_classes( ['rotary-output', 'standaloneimage', 'white-bg'] )
                    ->with_inner_classes( ['rotary-output-wrapper', 'white-bg'] )
                ;
                break;

            case 'Header, Body Text':
                $this
                    ->with_outer_classes( ['rotary-output', 'headerbodytext'] )
                    ->with_inner_classes( ['rotary-output-wrapper'] )
                ;
                break;

            default:
                $this
                    ->with_outer_classes( ['rotary-output', 'headerbodytextimage', 'white-bg'] )
                    ->with_inner_classes( ['rotary-output-wrapper', 'white-bg'] )
                ;
                break;
        }
    }

    private function _get_inner_part_image_with_wrapper(bool $pdf_wrapper) : string
    {
        $rotary_image = get_field('rotary_image', $this->_post);
        $rotary_image_server_path = $pdf_wrapper ? get_attached_file($rotary_image[ 'ID' ]) : wp_get_attachment_image_url( $rotary_image[ 'ID' ], 'home-featured-service' );

        $image_information = json_decode(get_field('image_information', $this->_post));

        $html_string = '';
        $html_string .=      '<div class="rotary-image-container">';
        $html_string .=          '<img class="rotary-image-output" ';
        $html_string .=                 ' src="' . $rotary_image_server_path . '" ';
        if($image_information){
            $html_string .= 'style="width: '. esc_attr( $image_information[0]->width ) .'px !important; height: '. esc_attr( $image_information[0]->height ) .'px !important;"';
        }
        $html_string .=                 ' alt="rotary-image" />';
        $html_string .=      '</div>';

        return $html_string;
    }

    private function _get_inner_part(bool $pdf_wrapper) : string
    {
        $layout = get_field('rotary_layout', $this->_post);

        $rotary_header = get_field('rotary_header', $this->_post);
        $rotary_body = get_field('rotary_body', $this->_post);

        $html_string = '';

        if('Stand-alone Image' === $layout){
            $html_string .= $this->_get_inner_part_image_with_wrapper($pdf_wrapper);
        }else{
            $html_string .=      '<div class="rotary-text">';
            $html_string .=          '<h2 class="rotary-header-output">' . $rotary_header . '</h2>';
            $html_string .=          '<div class="rotary-body-output">';
            $html_string .=             $rotary_body;
            $html_string .=          '</div>';
            $html_string .=      '</div>';

            if(get_field('rotary_image', $this->_post)){
                $html_string .= $this->_get_inner_part_image_with_wrapper($pdf_wrapper);
            }
        }

        return $html_string;
    }

    public function get_html(bool $pdf_wrapper) : string
    {
        $this->_setup_shell();

        $buf = [];

        $buf[] = sprintf( '<div class="%1$s">', implode(' ', $this->outer_classes) );
        $buf[] = sprintf( '<div class="%1$s">', implode(' ', $this->inner_classes) );
        $buf[] = $this->get_toggle_html($pdf_wrapper);
        $buf[] = $this->_get_inner_part($pdf_wrapper);

        $buf[] = '</div>';
        $buf[] = '</div>';


        return implode("\n", $buf);
    }

}
