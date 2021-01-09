$(document).ready(function() {

    // Выключение отображения flash-сообщений
    window.setTimeout(function () {
        $(".flash").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 5000);

    // Информация об авторе
    $("a.author-info").on('click', function (e) {
        e.preventDefault();
        $("#author-modal").modal('show');
    });

    // Модальное окно города
    $("#cityModal").modal('show');

    // City choosing modal events
    $("#city-confirm").on('click', function () {
        const cityName = $(".modal-body>span").text();
        document.location.href = '/comment?city_name=' + cityName;
    });

    $("#city-another").on('click', function () {
        $(".city-choosing").removeClass("d-none").addClass("d-block");
    });

    // flash-message elements creating
    function createErrorFlash(message, flashType) {
        let alertElement = document.createElement('div');
        alertElement.className = 'alert alert-' + flashType + ' alert-dismissible fade show flash';
        alertElement.setAttribute('role', 'alert');

        let alertButton = document.createElement('button');
        alertButton.setAttribute('type', 'button');
        alertButton.className = 'close';
        alertButton.dataset.dismiss = 'alert';
        alertButton.setAttribute('aria-label', 'Close');

        let alertCloseSpan = document.createElement('span');
        alertCloseSpan.setAttribute('aria-hidden', 'true');
        alertCloseSpan.innerHTML = '&times;';

        alertButton.appendChild(alertCloseSpan);
        alertElement.innerText = message;
        alertElement.appendChild(alertButton);

        return alertElement;
    }

    //const errorFlash = createErrorFlash('Это ошибка!', 'danger');
    //const successFlash = createErrorFlash('Это успешное сообщение!', 'success');

    //$('.container-main').prepend(errorFlash);
    //$('.container').prepend(successFlash);

    // dadata - подсказка города
    var token = '06437c5f9078834928053139b09331cd4c2a17d8';
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
            locations: {city_type_full: "город"}
        },
        formatResult: formatResult,
        formatSelected: formatSelected,
        onSelect: function (suggestion) {
        }
    });
    // end dadata

    $('.city-multiple').suggestions({
        token: token,
        type: "ADDRESS",
        hint: false,
        bounds: "city",
        constraints: {
            locations: {city_type_full: "город"}
        },
        formatResult: formatResult,
        formatSelected: formatSelected,
        onSelect: function (suggestion) {
            cityName = suggestion.data.city;
            let deleteFlag = false;

            // Удаление существующего города из списка и скрытого селекта
            $("#city-select option").each(function () {
                if (cityName == $(this).val()) {
                    $(this).remove();
                    deleteFlag = true;
                }
            });
            if (deleteFlag) {
                $("#city-shell li").each(function () {
                    if (cityName == $(this).text().slice(1)) {
                        $(this).remove();
                    }
                });
            } else {
                // Добавление города в список выбранных
                let cityShell = document.getElementById('city-shell');
                let newLi = document.createElement('li');
                newLi.className = 'city-item__choice';
                let newSpan = document.createElement('span');
                newSpan.className = 'city-item__remove';
                newSpan.innerText = "×";
                newLi.appendChild(newSpan);
                newLi.append(cityName);
                cityShell.append(newLi);

                // Добавление города в скрытый select
                let citySelect = document.getElementById('city-select');
                let newOption = document.createElement('option');
                newOption.value = cityName;
                newOption.innerText = cityName;
                newOption.selected = true;
                citySelect.appendChild(newOption);
            }
            // Очистка поля ввода города
            let citiesData = document.getElementById('cities-data');
            citiesData.value = '';
        }
    });

    $('#city-shell').on('click', '.city-item__remove', function () {
        let liCityItem = $(this).parent();
        $(this).remove();
        let itemCityName = liCityItem.text();
        liCityItem.remove();
        $("#city-select option[value=" + itemCityName + "]").remove();
    });

    /*
     * Удаление отзыва
     *
     */

    $('body').on('click', '#delete-comment', function (e) {
        e.preventDefault();
        let comment_id = $(this).data("id");
        let token = $('input[name="_token"]').attr('value');
        if (confirm("Точно удалить отзыв?")) {
            $.ajax({
                type: 'DELETE',
                headers: {'X-CSRF-Token': token},
                dataType: 'json',
                url: "/comment/" + comment_id,
                data: {
                    "id": comment_id,
                    "_token": token
                },
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function (data) {
                    console.log(data);
                    document.location.href = '/';
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(JSON.stringify(jqXHR));
                    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    console.warn(jqXHR.responseText);
                    $('#loader').hide();
                },
                timeout: 8000
            });
        }
    });

    /*
     * Создание отзыва
     *
     */

    /* Модальное окно создания отзыва */
    $("#create-comment").on('click', function (e) {
        e.preventDefault();
        let token = $('input[name="_token"]').attr('value');

        $.ajax({
            type: 'GET',
            headers: {'X-CSRF-Token': token},
            dataType: 'json',
            url: "/comment/create",
            data: {
                "_token": token
            },
            beforeSend: function() {
                $('#loader').show();
            },
            success: function (data) {
                console.log(data);
                if(data.success == 'true') {
                    $('#comment-modal-dialog').html(data.html);
                } else {
                    $('#comment-modal-dialog').html('ERROR!!!');
                }
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                console.warn(jqXHR.responseText);
                $('#loader').hide();
            },
            timeout: 8000
        });
        $("#comment-modal-lg").modal('show');
    });

    /* Ajax-отправка формы создания отзыва */
    $('body').on('click', '#new-comment-create', function (e) {
        e.preventDefault();





        let token = $('input[name="_token"]').attr('value');
        $.ajax({
            type: 'POST',
            headers: {'X-CSRF-Token': token},
            dataType: 'json',
            url: "/comment/",
            data: {

                "_token": token
            },
            beforeSend: function() {
                $('#loader').show();
            },
            success: function (data) {
                console.log(data);
                document.location.href = '/';
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                console.warn(jqXHR.responseText);
                $('#loader').hide();
            },
            timeout: 8000
        });
    });

    /*
     * Редактирование отзыва
     *
     */

    /* Модальное окно редактирования отзыва */
    $("#edit-comment").on('click', function (e) {
        e.preventDefault();
        let token = $('input[name="_token"]').attr('value');
        //let comment_id = $(this).data("id");
        let url = $(this).attr('data-attr');
        console.log(url);
        $.ajax({
            type: 'GET',
            headers: {'X-CSRF-Token': token},
            dataType: 'json',
            url: url,
            data: {
                "_token": token
            },
            beforeSend: function() {
                $('#loader').show();
            },
            success: function (data) {
                console.log(data);
                if(data.success == 'true') {
                    $('#comment-modal-dialog').html(data.html);
                } else {
                    $('#comment-modal-dialog').html('ERROR!!!');
                }
            },
            complete: function() {
                $('#loader').hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                console.warn(jqXHR.responseText);
                $('#loader').hide();
            },
            timeout: 8000
        });
        $("#comment-modal-lg").modal('show');
    });


});




/*
        // display a modal (medium modal)
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

    /*
             document.getElementById('submit').onclick = function() {
              var selected = [];
              for (var option of document.getElementById('pets').options) {
                if (option.selected) {
                  selected.push(option.value);
                }
              }
              alert(selected);
            }

            document.getElementById('submit').onclick = function() {
              var select = document.getElementById('pets');
              var selected = [...select.options]
                                .filter(option => option.selected)
                                .map(option => option.value);
              alert(selected);
            }

            document.getElementById('submit').onclick = function() {
              var select = document.getElementById('pets');
              var selected = [...select.selectedOptions]
                                .map(option => option.value);
              alert(selected);
            }
     */



/*
$(document).ready(function(){

	$('.deleting').on('click', function() {
		var clickedElement = $(this);
		$.ajax({
			dataType: 'json',
			url: 'ajax.php?action=del&item_id=' + $(this).attr("attr-id"),
	7		success: function( cont ) {
				if ( cont == 'deleted' ) {
					clickedElement.parent().parent().hide("fast", function(){});
				}
				else{
					alert( cont );
				}
			}
		});
	});



		});
	});
});

     */

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
