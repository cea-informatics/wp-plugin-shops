(function($){
    'use strict';

    $(document).ready(function(){
        if (typeof IMask === 'undefined') {
            console.warn('IMask library is not loaded. Phone input masking will not work.');
            return;
        }

        // Initialize IMask for the phone field if available.
        document.querySelectorAll('[data-imask]').forEach(function(el) {
            try {
                IMask(el, {
                    mask: el.getAttribute('data-imask'),
                });
            } catch (e) {
                // fail silently
                console.error('IMask init error for', el, e);
            }
        });
    });
})(jQuery);
