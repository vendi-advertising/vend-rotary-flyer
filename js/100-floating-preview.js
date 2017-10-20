(function() {

jQuery( document ).ready(function() {

    var preview                = jQuery('#preview-region');
    var preview_height         = preview.height();
    var preview_initial_top    = preview.offset().top;
    var preview_initial_left   = preview.offset().left - 20;
    var preview_top            = preview_initial_top;
    var footer_top              = jQuery('#footer').offset().top;
    var scrollTop               = jQuery(window).scrollTop();
    var distance                = preview_top - scrollTop;
    var bottom_limit            = jQuery('#footer').offset().top;
    var wpadminbar_height       = jQuery('#wpadminbar').height();
    var topPadding              = 0 + wpadminbar_height;
    var offset                  = jQuery('#preview-region').offset();
    var width_change_delay;

    const mq                    = window.matchMedia('(min-width: 901px)');
    mq.addListener(WidthChange);
    WidthChange(mq);

    jQuery(document).scroll(function(e) {
        scrollTop = jQuery(window).scrollTop();
        console.log(mq.matches, preview_top);
        if (mq.matches) {

            if(scrollTop >= preview_top - topPadding){
                if(scrollTop + preview_height + 55 >= bottom_limit){
                    //do nothing - this will stop the preview until we scroll back up
                }
                else{
                    jQuery(preview).stop().animate({
                        marginTop: jQuery(window).scrollTop() - offset.top + topPadding
                    });
                }

            }
            else{
                jQuery(preview).stop().animate({
                    marginTop: 0
                });
            }
        }
        else{
            console.log('test');
            jQuery(preview).css('top', 0);
            jQuery(preview).css('margin-top', 0);
        }
    });

    function WidthChange(mq) {
      if (mq.matches) {

        if(scrollTop >= preview_top - topPadding){
            jQuery(preview).stop().animate({
                marginTop: jQuery(window).scrollTop() - offset.top + topPadding
            });
        }
        else{
            jQuery(preview).stop().animate({
                marginTop: 0
            });
        }


      }

    }

});



})();
