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
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>

<script type="text/javascript">
    (function($) {

    if (typeof acf !== 'undefined') {


        var dateObjectArray = Array();

            //look through all posts and count the number of adds on each date.
            $.getJSON(`/wp-json/wp/v2/vendi-rotary-flyer`, function () {

            })
                .done(function (data){
                    console.log('data', data);
                    $.each(data, function(key, value) {
                        var run_dates = value['acf']['run_dates'];
                        $.each(run_dates, function(key1, run_date){
                            console.log(run_dates[key1]['run_date']);
                            if(dateObjectArray.hasOwnProperty(run_date['run_date'])){
                                dateObjectArray[run_date['run_date']]['count']++;
                            }
                            else{
                                dateObjectArray[run_date['run_date']] = {
                                    count: 1
                                }
                            }
                        });
                    });
                    console.log(dateObjectArray);
                    return dateObjectArray;
                })
                .fail(function (){
                    console.log('fail :(');
                })


            console.log(dateObjectArray, '1');
            acf.add_filter('date_picker_args', function( args, $field ){
                args['beforeShowDay'] =  onlyThursday;
                args['showOtherMonths'] =  true;
                args['selectOtherMonths'] =  true;
                return args;
            });

            function arrayObjectIndexOf(myArray, searchTerm, property) {
                for(var i = 0, len = myArray.length; i < len; i++) {
                    if (myArray[i][property] === searchTerm) return i;
                }
                return -1;
            }

            //pad with zeros
            function str_pad(n) {
                return String("00" + n).slice(-2);
            }

            function onlyThursday(date){
              var todays_date = new Date()
              var todays_day = todays_date.getDay();
              todays_date = todays_date.setHours(0,0,0,0);
              var at_least_seven_days_out = (date - todays_date) > (7*24*60*60*1000);
              var day = date.getDay();
              var return_statement;
              var alreadyPicked = Array();
              var date_compare_string = str_pad((date.getMonth()+1))+'/'+str_pad(date.getDate())+'/'+date.getFullYear();
              var max_ad_count = 9;

              $('.hasDatepicker').each(function(){
                if($(this).context.value !== ''){
                    var dateString = new Date($(this).context.value);
                    dateString = (dateString.getMonth()+1)+'/'+ str_pad(dateString.getDate())+'/'+ dateString.getFullYear();
                    alreadyPicked.push(dateString);
                    console.log(alreadyPicked);

                }
              });

              if((day == 4 && date >= todays_date && alreadyPicked.indexOf(date_compare_string) == -1) && at_least_seven_days_out){
                    console.log(dateObjectArray, '119');
                    console.log(dateObjectArray.hasOwnProperty(date_compare_string), date_compare_string, '120');
                    if(dateObjectArray.hasOwnProperty(date_compare_string) && dateObjectArray[date_compare_string]['count'] >= max_ad_count){
                        console.log(dateObjectArray[date_compare_string]['count'], '125');

                        return_statement = [false, '','There are already '+ max_ad_count +' ad slots filled for this day.'];
                    }

                    else{
                        return_statement = [true, ''];
                    }


              }
              else if(alreadyPicked.indexOf(date_compare_string) > -1){
                console.log('This date has already been selected');
                return_statement = [false, 'already-chosen', 'This date has already been selected.'];
              }
              else if(date <= todays_date){
                return_statement = [false, '', 'This date is in the past.'];
              }
              else if(!at_least_seven_days_out){
                return_statement = [false, '', 'You must register for a date at least 7 days in advance.'];
              }
              else{
                return_statement = [false, '', 'Ads do not run on this day.'];
              }
              return return_statement;
            }

            //disable repeater sorting on front end form
            acf.add_action('load', function( $el ){
                console.log(acf.fields.repeate);
                $.extend( acf.fields.repeater, {
                    _mouseenter: function( e ){
                        if( $( this.$tbody.closest('.acf-field-repeater') ).hasClass('disable-sorting') ){
                                return;
                            }
                    }
                });
            });

        }
    })(jQuery);
</script>
<style>
    td.ui-datepicker-unselectable.ui-state-disabled span{
        background-color: red !important;
        color: white !important;
    }

    td.ui-datepicker-unselectable.ui-state-disabled.already-chosen span{
        background-color: blue !important;
        color: white !important;
    }


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
                 wp_enqueue_script("jquery");
                 //Load each JS file that starts with three digits followed by a dash in numerical order
                 /*foreach( glob( VENDI_ROTARY_FLYER_DIR . '/js/[0-9][0-9][0-9]-*.js' ) as $t )
                 {
                     wp_enqueue_script(
                                         basename( $t, '.js' ) . '-p-style',
                                         plugins_url($plugin = VENDI_ROTARY_PLUGIN_NAME ) . '/js/' . basename( $t ),
                                         null,
                                         filemtime( VENDI_ROTARY_FLYER_DIR . '/js/' . basename( $t ) ),
                                         true
                                     );
                 }*/
             }
         );

function vendi_rotary_register_plugin_css( $file, $media = 'all' )
{
    wp_enqueue_style(
                        basename( $file, '.css' ),
                        plugins_url( '/css/' . $file, VENDI_ROTARY_FLYER_FILE ),
                        null,
                        filemtime( VENDI_ROTARY_FLYER_DIR . '/css/' . $file ),
                        $media
                    );
}

function vendi_rotary_register_outside_css($name, $file, $media = 'all')
{
    wp_enqueue_style(
                          $name,
                          $file,
                          false,
                          null,
                          $media
                      );
}

function vendi_rotary_register_plugin_js( $file, $dependencies = false, $footer = true )
{

    wp_enqueue_script(
                        basename( $file, '.js' ),
                        plugins_url( '/js/' . $file, VENDI_ROTARY_FLYER_FILE ),
                        $dependencies,
                        filemtime( VENDI_ROTARY_FLYER_DIR . '/js/' . $file ),
                        $footer
                    );
}

function vendi_rotary_register_ajax_js( $file, $dependencies = false, $footer = true )
{
    //wp_register_script( basename( $file, '.js' ), plugins_url( '/js/' . $file, VENDI_ROTARY_FLYER_FILE ), $dependencies );

    wp_enqueue_script(
                        basename( $file, '.js' ),
                        plugins_url( '/js/' . $file, VENDI_ROTARY_FLYER_FILE ),
                        $dependencies,
                        filemtime( VENDI_ROTARY_FLYER_DIR . '/js/' . $file ),
                        $footer
                    );

    wp_localize_script( basename( $file, '.js' ), 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
}



