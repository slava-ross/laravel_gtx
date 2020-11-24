$(document).ready(function() {
    $("#cityModalBox").modal('show');

    window.setTimeout(function() {
        $(".flash").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 5000);

    $('.select2-city-multiple').select2();

    var token = "06437c5f9078834928053139b09331cd4c2a17d8";

    var defaultFormatResult = $.Suggestions.prototype.formatResult;

    function formatResult(value, currentValue, suggestion, options) {
        var newValue = suggestion.data.city;
        suggestion.value = newValue;
        return defaultFormatResult.call(this, newValue, currentValue, suggestion, options);
    }

    function formatSelected(suggestion) {
        return suggestion.data.city;
    }

    $("#city").suggestions({
        token: token,
        type: "ADDRESS",
        hint: false,
        bounds: "city",
        constraints: {
            locations: { city_type_full: "город" }
        },
        formatResult: formatResult,
        formatSelected: formatSelected,
        onSelect: function(suggestion) {
            console.log(suggestion);
        }
    });
});
