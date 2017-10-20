
(function() {
    jQuery( document ).ready(function() {
        var rotary_header = document.getElementById('acf-field_59d3d72a30576');

        //set on page load
        if(rotary_header.value){
            document.getElementById('rotary-header-preview').innerHTML = rotary_header.value;
        }

        rotary_header = rotary_header.onkeyup = function(){
            document.getElementById('rotary-header-preview').innerHTML = this.value;
        }

        var rotary_body = document.getElementById('acf-field_59d3d76730578');

        //set on page load
        if(rotary_body.value){
            document.getElementById('rotary-body-preview').innerHTML = rotary_body.value;
        }

        rotary_body = rotary_body.onkeyup = function(e){
            console.log(e);
            if(e.keycode == 13){
                document.getElementById('rotary-body-preview').innerHTML = this.value+'<br/>';

            }
            else{
                document.getElementById('rotary-body-preview').innerHTML = this.value;
            }
        }

        var rotary_image = jQuery('.acf-field-59d3d73f30577').find('[data-name="image"]');
        var rotary_image_cancel = jQuery('.acf-field-59d3d73f30577').find('[data-name="image"]').next('.acf-actions').find('.-cancel');
        var preview_image = document.getElementById('rotary-image-preview');


        console.log(rotary_image_cancel);

        jQuery(rotary_image).on('load', function(){

            var source = jQuery(this).attr('src');
            console.log(source);
            jQuery(preview_image).attr('src', source);
        });

        jQuery(rotary_image_cancel).on('click', function(){


            jQuery(preview_image).attr('src', '');
        });

        var current_radio = jQuery('[name="acf[field_59d553bebeefd]"]:checked').val();
        current_radio = current_radio.replace(/[^a-zA-Z ]/g, "").split(' ');
        current_radio = current_radio.join('').toLowerCase();
        jQuery('.rotary-preview').addClass(current_radio);


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

        if(rotary_image.attr('src')){

            jQuery(preview_image).attr('src', rotary_image.attr('src'));
        }

    });
})();
