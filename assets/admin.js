(function($){
    'use strict';

    $(document).ready(function(){
        // Media uploader buttons are already wired inline in the PHP view; keep compatibility.
        // Initialize IMask for the phone field if available.
        var phoneEl = document.getElementById('phone');
        if (phoneEl && typeof IMask !== 'undefined') {
            try {
                IMask(phoneEl, {
                    mask: '+41 00 000 00 00'
                });
            } catch (e) {
                // fail silently
                console.error('IMask init error', e);
            }
        }
    });
})(jQuery);
