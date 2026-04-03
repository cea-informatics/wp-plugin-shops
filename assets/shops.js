/**
 * WordPress Plugin Shops - Frontend JavaScript
 */

(function ($) {
  "use strict";

  $(document).ready(function () {
    const applyFilters = () => {
      const searchTerm = $("#wps-search").val().toLowerCase();
      const selectedFloor = $("#wps-floor-filter").val();
      filterCards(searchTerm, selectedFloor);
    };

    $("#wps-apply-filters").on("click", applyFilters);

    $("#wps-search").on("keypress", (event) => {
      if (event.which !== 13) return; // Enter

      event.preventDefault();
      applyFilters();
    });
  });

  const filterCards = (searchTerm, floorFilter) => {
    const search = (searchTerm ?? "").toString().trim().toLowerCase();
    const floor = (floorFilter ?? "").toString().trim().toLowerCase();

    const cards = $(".wps-shop-card");
    const matched = cards
      .filter(function () {
        const $card = $(this);
        const shopName = ($card.data("name") ?? "").toString().toLowerCase();
        const shopNumber = ($card.data("number") ?? "")
          .toString()
          .toLowerCase();

        return (
          !search || shopName.includes(search) || shopNumber.includes(search)
        );
      })
      .filter(function () {
        const $card = $(this);
        const shopFloor = ($card.data("floor") ?? "").toString().toLowerCase();

        return !floor || floor === shopFloor;
      });

    cards.hide();
    matched.show();

    matched.length > 0
      ? $("#wp-shops-container .wps-empty-state").hide()
      : $("#wp-shops-container .wps-empty-state").show();
  };
})(jQuery);
