$(document).ready(function() {

    // Выключение отображения flash-сообщений
    window.setTimeout(function() {
        $(".flash").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 5000);

    // Информация об авторе
    $("a.author-info").on('click', function(e) {
        e.preventDefault();
        $("#authorModal").modal('show');

        //const cityName = $(".modal-body>span").text();
        //document.location.href='/comment?city_name=' + cityName;


    });
    /*
     $(document).on('click', '#mediumButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#mediumModal').modal("show");
                    $('#mediumBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
        });

     */

    // Модальное окно города
    $("#cityModal").modal('show');

    // City choosing modal events
    $("#city-confirm").on('click', function() {
        const cityName = $(".modal-body>span").text();
        document.location.href='/comment?city_name=' + cityName;
    });

    $("#city-another").on('click', function() {
        $(".city-choosing").removeClass("d-none").addClass("d-block");
        //$(".city-choosing");
    });

    // dadata - подсказка города
    var token = '06437c5f9078834928053139b09331cd4c2a17d8';
    //alert(foo_token);
    var defaultFormatResult = $.Suggestions.prototype.formatResult;

    function formatResult(value, currentValue, suggestion, options) {
        var newValue = suggestion.data.city;
        suggestion.value = newValue;
        return defaultFormatResult.call(this, newValue, currentValue, suggestion, options);
    }

    function formatSelected(suggestion) {
        return suggestion.data.city;
    }

    $(".city").suggestions({
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
            //console.log(suggestion);
        }
    });

    // end dadata

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

    */

});
