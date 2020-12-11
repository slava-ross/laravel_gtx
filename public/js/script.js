$(document).ready(function() {

    // Выключение отображения flash-сообщений
    window.setTimeout(function() {
        $(".flash").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 5000);

    // Модальное окно города
    $("#cityModalBox").modal('show');

    // City choosing modal events
    $("#city-confirm").on('click', function(e) {
        const cityName = $(".modal-body>span").text();
        document.location.href='/comment?city_name=' + cityName;
        e.preventDefault();
    });

    $("#city-another").click(function(){
        alert('Вам нужна не эта кнопка!');
    });



    /*        $.ajax({
        type: 'GET',
        dataType: 'json',
        url: '/ajax/' + cityName,
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
        success: function (content) {
            console.log(content);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(JSON.stringify(jqXHR));
            console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
            console.warn(jqXHR.responseText);
        }
    });
*/

     /*
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
    */

});
