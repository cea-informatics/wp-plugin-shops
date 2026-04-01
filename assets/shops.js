/**
 * WordPress Plugin Shops - Frontend JavaScript
 */

(function($) {
    'use strict';

    const translations = window.wpsTranslations || {};

    $(document).ready(function() {        
        const applyFilters = () => {
            const searchTerm = $('#wps-search').val().toLowerCase();
            const selectedFloor = $('#wps-floor-filter').val();
            filterCards(searchTerm, selectedFloor);
        };

        $('#wps-apply-filters').on('click', applyFilters);

        $('#wps-search').on('keypress', function(event) {
            if (event.which === 13) {
                event.preventDefault();
                applyFilters();
            }
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

    function filterCards(searchTerm, floorFilter) {
        const floor = (floorFilter ?? '').toString().trim().toLowerCase();
        $('.wps-shop-card').each(function() {
            const shopName = ($(this).data('name') || '').toString().toLowerCase();
            const shopNumber = ($(this).data('number') || '').toString().toLowerCase();
            const shopFloor = ($(this).data('floor') ?? '').toString().trim().toLowerCase();

            const matchesSearch = !searchTerm || shopName.includes(searchTerm) || shopNumber.includes(searchTerm);
            const matchesFloor = !floor || shopFloor === floor;

            if (matchesSearch && matchesFloor) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
})(jQuery);
