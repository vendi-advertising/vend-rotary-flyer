(function() {

jQuery( document ).ready(function() {
    jQuery('.slider-toggle').each(function(){

    });
    jQuery('.toggle').on('click', function(event){
        event.preventDefault();
        var status = 'publish';
        if(jQuery(this).hasClass('active')){
            status = 'pending';
        }
        var $_post_id = jQuery(this).attr("data-name");
        var data = JSON.stringify({'status': status});
        console.log(data);
        var rest_url = '/wp-json/wp/v2/vendi-rotary-flyer/' + $_post_id + '';
        /*jQuery.ajax({
                url: rest_url
                data : JSON.stringify(data),
                contentType : 'application/json',
                type : 'POST'
            }).done(function(){

            })
            .fail(function(data){
                console.log(data);
            });*/

        jQuery(this).toggleClass('active');


      });
});

})();
