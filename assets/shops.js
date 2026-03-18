/**
 * WordPress Plugin Shops - Frontend JavaScript
 */

(function($) {
    'use strict';

    const translations = window.wpsTranslations || {};

    $(document).ready(function() {
        
        // Floor filter
        $('#wps-floor-filter').on('change', function() {
            const selectedFloor = $(this).val();
            
            $('.wps-shop-card').each(function() {
                const shopFloor = $(this).data('floor');
                
                if (selectedFloor === '' || shopFloor === selectedFloor) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Search functionality
        $('#wps-search').on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            
            $('.wps-shop-card').each(function() {
                const shopName = $(this).data('name').toLowerCase();
                const shopDescription = $(this).find('.wps-shop-description').text().toLowerCase();
                
                if (shopName.includes(searchTerm) || shopDescription.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
        
        // Floor plan modal
        $('.wps-view-plan').on('click', function(e) {
            e.preventDefault();
            const planUrl = $(this).data('plan');
            
            if (planUrl) {
                showPlanModal(planUrl);
            }
        });
        
        $('.wps-modal-close, .wps-modal').on('click', function(e) {
            if (e.target === this) {
                closePlanModal();
            }
        });
        
        // WhatsApp link tracking
        $('.wps-whatsapp-link').on('click', function() {
            const shopName = $(this).closest('.wps-shop-card').data('name');
            console.log('WhatsApp clicked for:', shopName);
            // You can add analytics tracking here
        });
        
        // Email link tracking
        $('.wps-email-link').on('click', function() {
            const shopName = $(this).closest('.wps-shop-card').data('name');
            console.log('Email clicked for:', shopName);
            // You can add analytics tracking here
        });
    });
    
    function showPlanModal(planUrl) {
        const modal = $('<div class="wps-modal active">' +
            '<div class="wps-modal-content">' +
            '<button class="wps-modal-close">&times;</button>' +
            '<img src="' + planUrl + '" alt="' + (translations.floorPlanAlt || 'Floor Plan') + '" class="wps-modal-image">' +
            '</div>' +
            '</div>');
        
        $('body').append(modal);
        
        modal.find('.wps-modal-close, .wps-modal').on('click', function(e) {
            if (e.target === this) {
                closePlanModal();
            }
        });
        
        // Close on ESC key
        $(document).on('keyup.wpsmodal', function(e) {
            if (e.keyCode === 27) {
                closePlanModal();
            }
        });
    }
    
    function closePlanModal() {
        $('.wps-modal').remove();
        $(document).off('keyup.wpsmodal');
    }
    
})(jQuery);
