(function() {
    jQuery( document ).ready(function() {
        jQuery('[data-slot]').length;

        var placeholder_arr = [];
        var placeholder_id;
        var slot;

        jQuery('.place-holder-entry').each(function(){
            jQuery(this).click(function(){
                placeholder_id = jQuery(this);
                slot = jQuery(this).attr('data-slot');
                jQuery('#placeholderModal').show();
            });
        });

        // Get the modal
        var modal = document.getElementById('placeholderModal');

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        jQuery('.modal-preview-output').each(function(){
            jQuery(this).click(function(){
                var savedhtml = jQuery(placeholder_id).html();
                jQuery(placeholder_id).html(jQuery(this).html());
                if(jQuery(this).hasClass('standaloneimage')){
                    jQuery(placeholder_id).addClass('standaloneimage');
                }
                else if(jQuery(this).hasClass('headerbodytextimage')){
                    jQuery(placeholder_id).addClass('headerbodytextimage');
                }
                else if(jQuery(this).hasClass('headerbodytext')){
                    jQuery(placeholder_id).addClass('headerbodytext');
                }
                jQuery(placeholder_id).removeClass('place-holder-entry');
                jQuery(placeholder_id).addClass('place-holder-filled');

                var admin_html = '<p> Change Placeholder </p>';

                jQuery(placeholder_id).find('.approve-container').html(admin_html);
                modal.style.display = "none";
                placeholder_arr[slot] = jQuery(this).attr('data-id');
                jQuery('#placeholder_id_json').val(JSON.stringify(placeholder_arr));
            });
        });


    });

})();
