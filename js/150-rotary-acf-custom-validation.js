
jQuery( document ).ready(function() {
    jQuery(document).on('acf/validate_field', function( e, field ){
            // vars
            $field = jQuery(field);

            // set validation to false on this field
            if( $field.find('input').val() == 'test' )
            {
                $field.data('validation', false);
            }

        });
});
