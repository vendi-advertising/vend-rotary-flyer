<?php

function my_save_post( $post_id ) {
    // bail early if not a vendi-rotary-flyer post
    //

    if( get_post_type($post_id) !== 'vendi-rotary-flyer' ) {

        return;

    }


    // bail early if editing in admin
    if( is_admin() ) {

        return;

    }


    // vars
    $post = get_post( $post_id );
    $title = get_field('organization', $post_id);

    $url_re = '/thank-you/?postid=' . $post_id;

    $my_post = array();
    $my_post['ID'] = $post_id;
    $my_post['post_title'] = $title;

    wp_update_post( $my_post );

    wp_safe_redirect( $url_re ); exit;

}

add_action('acf/save_post', 'my_save_post', 11);


function my_acf_load_value( $value, $post_id, $field )
{
    // run the_content filter on all textarea values
    $value = apply_filters('the_content',$value);

    return $value;
}


function my_acf_input_admin_footer() {

?>

<script type="text/javascript">
    (function($) {

        acf.add_filter('date_picker_args', function( args, $field ){
            args['beforeShowDay'] =  onlyThursday;
            args['showOtherMonths'] =  true;
            args['selectOtherMonths'] =  true;
            return args;
        });



        function onlyThursday(date){
          var todays_date = new Date().setHours(0,0,0,0);
          var day = date.getDay();
          var return_statement;
          if((day > 3 && day < 5 && date >= todays_date)){
            return_statement = [true, ''];
          }
          else{
            return_statement = [false, '', 'This date is not available'];
          }
          return return_statement;
        };


    })(jQuery);
</script>
<style>
    td.ui-datepicker-unselectable.ui-state-disabled span{
        background-color: red !important;
        color: white !important;
    }

    .acf-inline-block
</style>



<?php

}

add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');


//Load CSS and JavaScript
 add_action(
             'wp_enqueue_scripts',
             function()
             {
                 //Kill of Clef which wants to load on every page.
                 //So far this has been safe because Clef special-cases the login page anyway
                 wp_dequeue_style( 'wpclef-main' );

                 /*****************
                  *  Parent Theme *
                  *****************/
                 //Load each CSS file that starts with three digits followed by a dash in numerical order
                 foreach( glob( VENDI_ROTARY_FLYER_DIR . '/css/[0-9][0-9][0-9]-*.css' ) as $t )
                 {
                     wp_enqueue_style(
                                         basename( $t, '.css' ) . '-p-style',
                                         plugins_url($plugin = VENDI_ROTARY_PLUGIN_NAME ) . '/css/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/css/' . basename( $t ) ),
                                         'screen'
                                     );
                 }

                 //Load each JS file that starts with three digits followed by a dash in numerical order
                 foreach( glob( VENDI_ROTARY_FLYER_DIR . '/js/[0-9][0-9][0-9]-*.js' ) as $t )
                 {
                     wp_enqueue_script(
                                         basename( $t, '.js' ) . '-p-style',
                                         plugins_url($plugin = VENDI_ROTARY_PLUGIN_NAME ) . '/js/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/js/' . basename( $t ) ),
                                         true
                                     );
                 }
             }
         );
