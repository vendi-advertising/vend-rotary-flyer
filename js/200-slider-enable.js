(function() {
jQuery( document ).ready(function() {
    jQuery('.slider-toggle').each(function(){

    });
    jQuery('.toggle').on('click', function(event){
        event.preventDefault();
        var status = 'publish';
        var toggle = jQuery(this);
        var label = jQuery(toggle).children('.toggle-label');
        if(jQuery(this).hasClass('active')){
            status = 'pending';
        }
        var $_post_id = jQuery(this).attr("data-name");
        var the_data = JSON.stringify({'status': status, 'id': $_post_id});
        var rest_url =  '/plugins/vendi-rotary-flyer/status-change.php';

        var data = {
            'action': 'my_action',
            'status': status,
            'id': $_post_id
        };
        jQuery('html, body').addClass('wait');

        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(myAjax.ajaxurl, data, function(response) {
            response = JSON.parse(response);
            console.log(response['status']);
            if(response['status'] == 'publish'){
                jQuery(toggle).addClass('active');
                jQuery(label).text('Approved');
            }
            else if(response['status'] == 'pending'){
                jQuery(toggle).removeClass('active');
                jQuery(label).text('Unapproved');
            }
            jQuery('html, body').removeClass('wait');
        });



      });

});

})();
