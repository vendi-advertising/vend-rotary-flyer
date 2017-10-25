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

        function onlyThursday(date){
          var todays_date = new Date().setHours(0,0,0,0);
          var day = date.getDay();
          var return_statement;
          var alreadyPicked = Array();
          var date_compare_string = (date.getMonth()+1)+'/'+date.getDate()+'/'+date.getFullYear();
          var max_ad_count = 8;

          $('.hasDatepicker').each(function(){

            if($(this).context.value !== ''){
                var dateString = new Date($(this).context.value);
                dateString = (dateString.getMonth()+1)+'/'+ dateString.getDate()+'/'+ dateString.getFullYear();
                alreadyPicked.push(dateString);
            }
          });

          if((day > 3 && day < 5 && date >= todays_date && alreadyPicked.indexOf(date_compare_string) == -1)){
                console.log(dateObjectArray, '119');
                if(dateObjectArray.hasOwnProperty(date_compare_string) && dateObjectArray[date_compare_string]['count'] >= max_ad_count){
                    return_statement = [false, '','This slot is filled'];
                }
                else{
                    return_statement = [true, ''];
                }


          }
          else if(alreadyPicked.indexOf(date_compare_string) > -1){
            return_statement = [false, 'already-chosen', 'This date has already been selected'];
          }
          else{
            return_statement = [false, '', 'Ads do not run on this day'];
          }
          return return_statement;
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
