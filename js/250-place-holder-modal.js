(function() {
    jQuery( document ).ready(function() {
        jQuery('[data-slot]').length;

        var placeholder_arr = [];
        var placeholder_id;
        var slot;
        var entry_id;

        jQuery('.place-holder-entry').each(function(){
            jQuery(this).click(function(){
                placeholder_id = jQuery(this);
                entry_id = jQuery(this).attr('id');
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
                /*var savedhtml = jQuery(placeholder_id).html();
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
                placeholder_arr[slot] = parseInt(jQuery(this).attr('data-id'));
                jQuery('#placeholder_id_json').val(JSON.stringify(placeholder_arr));*/

                //
                //new code
                //
                var placeholder_id = parseInt(jQuery(this).attr('data-id'));
                var date = jQuery('.pdf-date').attr('data-date');
                var data = {
                    'action': 'convert_placeholder',
                    'id': placeholder_id,
                    'date': date
                }
                jQuery('html, body').addClass('wait');
                console.log(placeholder_id);
                jQuery.post(myAjax.ajaxurl, data, function(response) {
                    response = JSON.parse(response);
                    console.log(response);

                    if(response['rotary_layout'] == 'Stand-alone Image'){
                        var html_ = [
                            '<div id="post-' + response["id"] + '" class="rotary-output-wrapper  white-bg "><div class="approve-container">',
                            '<div data-name="' + response["id"] + '" class="toggle active">',
                                  '<h1> '+ response["rotary_header"] +' </h1>',
                                  '<div class="toggle-header"><label> Approval Status: </label></div>',
                                  '<div class="toggle-switch"></div>',
                                  '<div class="toggle-label toggle-label-on">Approved</div>',
                                '</div>',
                                '<div class="edit-post-container">',
                                    '<a href="/test-page/?post_id=' + response["id"] + '"> &#9998;Edit post </a>',
                                '</div>',
                                '</div><div class="rotary-image-container"><img class="rotary-image-output" src="'+ response["rotary_image"]["url"] + '" alt="rotary-image"></div></div>'
                        ].join("");
                        console.log(entry_id);
                        jQuery('#'+entry_id).html(html_);
                        jQuery('#'+entry_id).removeClass();
                        jQuery('#'+entry_id).addClass('rotary-output standaloneimage');
                    }
                    else if(response['rotary_layout'] == 'headerbodytext'){
                        jQuery(placeholder_id).html(jQuery([
                            '',
                            '',
                            ''
                        ].join("\n")));
                    }
                    else if(response['rotary_layout'] == 'headerbodytextimage'){
                        jQuery(placeholder_id).html(jQuery([
                            '',
                            '',
                            ''
                        ].join("\n")));
                    }

                    jQuery('html, body').removeClass('wait');
                });

            });
        });


    });

})();
