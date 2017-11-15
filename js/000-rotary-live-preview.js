
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
        var rotary_image_cancel = jQuery('.acf-field-59d3d73f30577').find('[data-name="image"]').next('.acf-soh-target').find('[data-name="remove"]');
        var preview_image = document.getElementById('rotary-image-preview');
        var preview_wrapper = jQuery('.rotary-preview-wrapper');
        console.log('36',rotary_image_cancel);

        //load image in preview when image selected
        jQuery(rotary_image).on('load', function(){
            var source = jQuery(this).attr('src');
            console.log(source);
            var width = jQuery(rotary_image).width();
            var height = jQuery(rotary_image).height();
            console.log(width, height);
            jQuery(preview_image).attr('src', source);
            jQuery(preview_wrapper).addClass('white-bg');


        });

        function sendImageInfo(event, ui){
            console.log(ui);
            var json = [{
                "height":Math.round(ui['size']['height']),
                "width":Math.round(ui['size']['width'])
            }];
            jQuery('#acf-field_59f74ba0f3181').text(JSON.stringify(json));
        }

        function getImageInfo(selector, attribute){
            var json = jQuery(selector).text();
            if(json){
                json = jQuery.parseJSON(json);
                return ((json[0][attribute]) ? json[0][attribute] : false)
            }
        }

        jQuery(preview_image).on('load',function(){
            if(jQuery('.rotary-preview').hasClass('headerbodytextimage')){
                jQuery(preview_image).resizable({
                    disabled: false,
                    handles: "n, e, s, w, se",
                    aspectRatio: true,
                    containment: "parent",
                    stop: sendImageInfo
                });
            }
        });

        jQuery(rotary_image_cancel).on('click', function(){

            if(jQuery(preview_image).is(':data(ui-resizable)')){
                jQuery(preview_image).resizable('destroy');
            }
            jQuery(preview_image).css('height','auto');
            jQuery(preview_image).css('width','auto');
            jQuery(preview_image).attr('src', '');
            jQuery(preview_wrapper).removeClass('white-bg');

        });

        var current_radio = jQuery('[name="acf[field_59d553bebeefd]"]:checked').val();
        current_radio = current_radio.replace(/[^a-zA-Z ]/g, "").split(' ');
        current_radio = current_radio.join('').toLowerCase();
        jQuery('.rotary-preview').addClass(current_radio);

        //class change event in order to generate correct preview
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

            if(current_radio == 'standaloneimage'){
                jQuery(preview_image).resizable('destroy');
                jQuery(preview_image).css('height','auto');
                jQuery(preview_image).css('width','auto');
            }
            else if(current_radio == 'headerbodytextimage'){
                jQuery(preview_image).resizable({
                    disabled: false,
                    handles: "n, e, s, w, se",
                    aspectRatio: true,
                    containment: "parent",
                    stop: sendImageInfo
                });
            }
          console.log(current_radio);
          console.log(e);
        });

        if(rotary_image.attr('src')){
            jQuery(preview_image).attr('src', rotary_image.attr('src'));
            var width = getImageInfo(jQuery('#acf-field_59f74ba0f3181'), 'width');
            var height = getImageInfo(jQuery('#acf-field_59f74ba0f3181'), 'height');
            if(width){
                jQuery(preview_image).width(width);
            }
            if(height){
                jQuery(preview_image).height(height);
            }
            jQuery(preview_wrapper).addClass('white-bg');

        }

    });
})();
