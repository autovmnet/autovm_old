jQuery(function() {

    jQuery('.test3').click(function() {

        if (!jQuery('#server-ip').val()) {
           alert('please enter your server ip address');
           return false;
        }

        jQuery.getJSON(baseUrl + 'admin/server/test?ip=' + jQuery('#server-ip').val(), function(data) {

            if (data.ok) {

                jQuery('#server-license').val(data.secret);

            }

        });

    });

});