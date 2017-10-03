
(function() {
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
})();
