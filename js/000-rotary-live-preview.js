
(function() {
    jQuery( document ).ready(function() {
        var rotary_header = document.getElementById('acf-field_59d3d72a30576');

        rotary_header = rotary_header.onkeyup = function(){
            document.getElementById('rotary-header-preview').innerHTML = this.value;
        }

        var rotary_header = document.getElementById('acf-field_59d3d76730578');

        rotary_header = rotary_header.onkeyup = function(){
            document.getElementById('rotary-body-preview').innerHTML = this.value;
        }

        var rotary_image = jQuery('.acf-field-59d3d73f30577').find('[data-name="image"]');
        var rotary_image_cancel = jQuery('.acf-field-59d3d73f30577').find('[data-name="image"]').next('.acf-actions').find('.-cancel');

        console.log(rotary_image_cancel);

        var preview_image = document.getElementById('rotary-image-preview');
        jQuery(rotary_image).on('load', function(){

            var source = jQuery(this).attr('src');
            console.log(source);
            jQuery(preview_image).attr('src', source);
        });

        jQuery(rotary_image_cancel).on('click', function(){


            jQuery(preview_image).attr('src', '');
        });

        var current_radio = '';
        jQuery('[data-key="field_59d553bebeefd"] input[type="radio"]').on('change', function(e) {
            //change the class on the preview so that we can assign different layouts
            console.log(e.target.value);
            if(current_radio == ''){
                //remove all special characters and split into array via spaces
                current_radio = e.target.value.replace(/[^a-zA-Z ]/g, "").split(' ');

                //join into camelcase format
                current_radio = current_radio.join('').toLowerCase();
                jQuery('.rotary-preview').addClass(current_radio);

            }
            else{
                jQuery('.rotary-preview').removeClass(current_radio);
                //remove all special characters and split into array via spaces
                current_radio = e.target.value.replace(/[^a-zA-Z ]/g, "").split(' ');

                //join into camelcase format
                current_radio = current_radio.join('').toLowerCase();
                jQuery('.rotary-preview').addClass(current_radio);
            }
          console.log(current_radio);
          console.log(e);
        });
    });
})();
