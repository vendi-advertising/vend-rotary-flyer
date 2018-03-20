(function() {

    jQuery( document ).ready(function() {
        jQuery('#rotary-from-date').datepicker({
            "showButtonPanel": true,
            "changeMonth": true,
            "changeYear": true

        });
        jQuery('#rotary-to-date').datepicker({
            "showButtonPanel": true,
            "changeMonth": true,
            "changeYear": true
        });
        jQuery('#ui-datepicker-div').wrap('<div class="acf-ui-datepicker"/>');
        var ad_cost = 10;
        jQuery('#rotary-query-owed').click(function(){
                var from_date = jQuery('#rotary-from-date').val();
                var to_date   = jQuery('#rotary-to-date').val();
                var data      = {
                                    'action': 'query_costs',
                                    'from_date': from_date,
                                    'to_date': to_date
                                }
                jQuery('html, body').addClass('wait');
                jQuery('#rotary-owed-results').html('<h2> Loading results... </h2>');
                if(from_date == '' || to_date == '' ){
                    jQuery('#rotary-owed-results').html('<h2> Please fill out all fields in the form </h2>');
                    jQuery('html, body').removeClass('wait');
                }
                else{
                    jQuery.post(myAjax.ajaxurl, data, function(response) {
                        response = JSON.parse(response);
                        // console.log(response);
                        jQuery('#rotary-owed-results').html('');
                        var html_string = '';
                        if(!Array.isArray(response)){
                            html_string = '<h2> Charges for ads created between '+from_date+' and '+to_date+' </h2>';
                            html_string += '<div class="response-row header-row">'+
                                        '<div class="response-col response-col-name"> User Name </div>'+
                                        '<div class="response-col response-col-ads"> Number of Ads Created</div>'+
                                        '<div class="response-col response-col-price"> Amount Owed </div>'+
                                        '</div>';
                            for (var property in response) {
                                if (response.hasOwnProperty(property)) {
                                    html_string += '<div class="response-row">';
                                    html_string += '<div class="response-col response-col-name">'+ property +'</div>';
                                    html_string += '<div class="response-col response-col-ads">'+ response[property]['ads_created'] +'</div>';
                                    html_string += '<div class="response-col response-col-price">$'+ response[property]['amount_owed'] +'</div>';
                                    html_string += '</div>';
                                }
                            }
                            html_string += '<hr>';
                        }
                        else{
                            html_string = '<h2> No ads found between '+from_date+' and '+to_date+' </h2>';
                        }
                            jQuery('#rotary-owed-results').html(html_string);

                        jQuery('html, body').removeClass('wait');

                    });
                }

        });


    });

})();
