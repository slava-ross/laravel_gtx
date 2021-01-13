$(document).ready(function() {

    // Выключение отображения flash-сообщений
    /*
    window.setTimeout(function () {
        $(".flash").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 8000);
    */

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
    function createFlash(message, flashType) {
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

    //const errorFlash = createFlash('Это ошибка!', 'danger');
    //const successFlash = createFlash('Это успешное сообщение!', 'success');

    //$('.container-main').prepend(errorFlash);
    //$('.container').prepend(successFlash);

    /*
     * Прелоадер - AJAX-методы
     *
     */
    function loaderOn() {
        $('.loader-icon').removeClass('shrinking-cog').addClass('spinning-cog');
        $('#loader').show();
    }
    function loaderOff() {
        setTimeout(() => {
        $('.loader-icon').removeClass('spinning-cog').addClass('shrinking-cog');
        setTimeout(() => {
                $('#loader').hide();
            },300
        );
            },200
        );
    }

    /*
     * DADATA - подсказка города
     *
     */
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

    $('body').on('focus', '#cities-data', function () {
        $(this).suggestions = $('body').suggestions;
        $(this).suggestions({
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
        })
    });

    // end dadata

    $('body').on('click', '.city-item__remove', function () {
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
                beforeSend: loaderOn(),
                success: function (data) {
                    document.location.href = '/';
                },
                complete: loaderOff(),
                error: function (jqXHR, textStatus, errorThrown) {
                    loaderOff();
                    //console.log(JSON.stringify(jqXHR));
                    //console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    //console.warn(jqXHR.responseText);
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
            beforeSend: loaderOn(),
            success: function (data) {
                $('#comment-modal-dialog').html(data.html);
                $("#comment-modal-lg").modal('show');
            },
            complete: loaderOff(),
            error: function (jqXHR, textStatus, errorThrown) {
                loaderOff();
                let response = jqXHR.responseJSON;
                if (response.message === 'Unauthenticated.') {
                    document.location.href = '/login';
                } else {
                    alert(response.message);
                }
            },
            timeout: 8000
        });
    });

    // Сброс формы - функция?

    /* Ajax-отправка формы создания отзыва */

    $('body').on('submit', '#create-comment-form', function (e) {
        e.preventDefault();
        $('div.alert').remove();
        const token = $('input[name="_token"]').attr('value');
        const url = $(this).attr('data-attr');
        let formData = new FormData($(this)[0]);
        /*for (var pair of formData.entries()) {
            console.log(pair[0]+ ', ' + pair[1]);
        }
        alert();
        }*/
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': token
            },
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: loaderOn(),
            success: function (data) {
                $("#comment-modal-lg").modal('hide');
                let message = data.success;
                let successFlash = createFlash(message, 'success');
                $('.container-main').prepend(successFlash);
            },
            complete: loaderOff(),
            error: function (jqXHR, textStatus, errorThrown) {
                loaderOff();
                let response = jqXHR.responseJSON;
                console.log(response);
                response.errors.forEach(function(errorMessage, index, array) {
                    $('.modal-body').prepend(createFlash(errorMessage, 'danger'));
                });
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
        $.ajax({
            type: 'GET',
            headers: {
                'X-CSRF-Token': token
            },
            dataType: 'json',
            url: url,
            data: {
                "_token": token
            },
            beforeSend: loaderOn(),
            success: function (data) {
                if(data.success == 'true') {
                    $('#comment-modal-dialog').html(data.html);
                } else {
                    $('#comment-modal-dialog').html('ERROR!!!');
                }
                $("#comment-modal-lg").modal('show');
            },
            complete: loaderOff(),
            error: function (jqXHR, textStatus, errorThrown) {
                loaderOff();
                let response = jqXHR.responseJSON;
                if (response.message === 'Unauthenticated.') {
                    document.location.href = '/login';
                } else {
                    alert(response.message);
                }
            },
            timeout: 8000
        });
    });

    /* Ajax-отправка формы редактирования отзыва */

    $('body').on('submit', '#edit-comment-form', function (e) {
        e.preventDefault();
        $('div.alert').remove();
        const url = $(this).attr('data-attr');
        const token = $('input[name="_token"]').attr('value');
        let formData = new FormData($(this)[0]);
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-Token': token
            },
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            beforeSend: loaderOn(),
            success: function (data) {
                $("#comment-modal-lg").modal('hide');
                console.log(data);
                let message = data.success;
                let image = data.img;
                if (image === null) {
                    image = 'http://laravel_gtx/images/default.jpg';
                }
                if(message) {
                    let successFlash = createFlash(message, 'success');
                    $('.container-main').prepend(successFlash);
                    $('h2#title').text(data.title);
                    $('div.card-img').attr('style', 'background-image: url(' + image + ')');
                    $('div.card-descr').text(data.comment_text);
                    $('div.card-rating').text(data.rating);
                } else {
                    alert('Error data!!!');
                }
            },
            complete: loaderOff(),
            error: function (jqXHR, textStatus, errorThrown) {
                loaderOff();
                let response = jqXHR.responseJSON;
                console.log(response);
                response.errors.forEach(function(errorMessage, index, array) {
                    $('.modal-body').prepend(createFlash(errorMessage, 'danger'));
                });
            },
            timeout: 8000
        });
    });

    /*
     * Отображение/сокрытие диалога загрузки файла изображения к отзыву
     *
     */

    $('body').on('change', '#img-checkbox', function () {
        const imageInput = $('#img-input');
        if(this.checked){
            imageInput.addClass('d-none');
        }
        else {
            imageInput.removeClass('d-none');
        }
    });

});
