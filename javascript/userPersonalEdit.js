/**
 * **************************
 * jQuery-плагины для дизайна
 * **************************
 */

// ...

/**
 * ******************************
 * jQuery-плагины для функционала
 * ******************************
 */
/**
 * Defaultvalue
 * 
 * @version 1.4.2
 * @author Jan Jarfalk
 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
 * @param {Function} callback Callback function
 */
(function(c){c.fn.extend({defaultValue:function(e){if("placeholder"in document.createElement("input"))return!1;return this.each(function(){if(c(this).data("defaultValued"))return!1;var a=c(this),h=a.attr("placeholder"),f={input:a};a.data("defaultValued",!0);var d=function(){var b;if(a.context.nodeName.toLowerCase()=="input")b=c("<input />").attr({type:"text"});else if(a.context.nodeName.toLowerCase()=="textarea")b=c("<textarea />");else throw"DefaultValue only works with input and textareas";b.attr({value:h,
"class":a.attr("class")+" empty",size:a.attr("size"),style:a.attr("style"),tabindex:a.attr("tabindex"),rows:a.attr("rows"),cols:a.attr("cols"),name:"defaultvalue-clone-"+((1+Math.random())*65536|0).toString(16).substring(1)});b.focus(function(){b.hide();a.show();setTimeout(function(){a.focus()},1)});return b}();f.clone=d;d.insertAfter(a);var g=function(){a.val().length<=0?(d.show(),a.hide()):(d.hide(),a.show().trigger("click"))};a.bind("blur",g);g();e&&e(f)})}})})(jQuery);

/**
 * AJAX Upload ( http://valums.com/ajax-upload/ ) 
 * Copyright (c) Andrew Valums
 * Licensed under the MIT license 
 */
(function(){function d(a,b,c){if(a.addEventListener)a.addEventListener(b,c,!1);else if(a.attachEvent)a.attachEvent("on"+b,function(){c.call(a)});else throw Error("not supported or DOM not loaded");}function g(a,b){for(var c in b)b.hasOwnProperty(c)&&(a.style[c]=b[c])}function i(a,b){RegExp("\\b"+b+"\\b").test(a.className)||(a.className+=" "+b)}function f(a,b){a.className=a.className.replace(RegExp("\\b"+b+"\\b"),"")}function h(a){a.parentNode.removeChild(a)}var l=document.documentElement.getBoundingClientRect? function(a){var b=a.getBoundingClientRect(),c=a.ownerDocument,a=c.body,c=c.documentElement,j=c.clientTop||a.clientTop||0,d=c.clientLeft||a.clientLeft||0,e=1;a.getBoundingClientRect&&(e=a.getBoundingClientRect(),e=(e.right-e.left)/a.clientWidth);e>1&&(d=j=0);return{top:b.top/e+(window.pageYOffset||c&&c.scrollTop/e||a.scrollTop/e)-j,left:b.left/e+(window.pageXOffset||c&&c.scrollLeft/e||a.scrollLeft/e)-d}}:function(a){var b=0,c=0;do b+=a.offsetTop||0,c+=a.offsetLeft||0,a=a.offsetParent;while(a);return{left:c, top:b}},k=function(){var a=document.createElement("div");return function(b){a.innerHTML=b;return a.removeChild(a.firstChild)}}(),m=function(){var a=0;return function(){return"ValumsAjaxUpload"+a++}}();window.AjaxUpload=function(a,b){this._settings={action:"upload.php",name:"userfile",multiple:!1,data:{},autoSubmit:!0,responseType:!1,hoverClass:"hover",focusClass:"focus",disabledClass:"disabled",onChange:function(){},onSubmit:function(){},onComplete:function(){}};for(var c in b)b.hasOwnProperty(c)&& (this._settings[c]=b[c]);a.jquery?a=a[0]:typeof a=="string"&&(/^#.*/.test(a)&&(a=a.slice(1)),a=document.getElementById(a));if(!a||a.nodeType!==1)throw Error("Please make sure that you're passing a valid element");a.nodeName.toUpperCase()=="A"&&d(a,"click",function(a){if(a&&a.preventDefault)a.preventDefault();else if(window.event)window.event.returnValue=!1});this._button=a;this._input=null;this._disabled=!1;this.enable();this._rerouteClicks()};AjaxUpload.prototype={setData:function(a){this._settings.data= a},disable:function(){i(this._button,this._settings.disabledClass);this._disabled=!0;var a=this._button.nodeName.toUpperCase();(a=="INPUT"||a=="BUTTON")&&this._button.setAttribute("disabled","disabled");if(this._input&&this._input.parentNode)this._input.parentNode.style.visibility="hidden"},enable:function(){f(this._button,this._settings.disabledClass);this._button.removeAttribute("disabled");this._disabled=!1},_createInput:function(){var a=this,b=document.createElement("input");b.setAttribute("type", "file");b.setAttribute("name",this._settings.name);this._settings.multiple&&b.setAttribute("multiple","multiple");g(b,{position:"absolute",right:0,margin:0,padding:0,fontSize:"480px",fontFamily:"sans-serif",cursor:"pointer"});var c=document.createElement("div");g(c,{display:"block",position:"absolute",overflow:"hidden",margin:0,padding:0,opacity:0,direction:"ltr",zIndex:2147483583});if(c.style.opacity!=="0"){if(typeof c.filters=="undefined")throw Error("Opacity not supported by the browser");c.style.filter= "alpha(opacity=0)"}d(b,"change",function(){if(b&&b.value!==""){var c=b.value.replace(/.*(\/|\\)/,"");!1===a._settings.onChange.call(a,c,-1!==c.indexOf(".")?c.replace(/.*[.]/,""):"")?a._clearInput():a._settings.autoSubmit&&a.submit()}});d(b,"mouseover",function(){i(a._button,a._settings.hoverClass)});d(b,"mouseout",function(){f(a._button,a._settings.hoverClass);f(a._button,a._settings.focusClass);if(b.parentNode)b.parentNode.style.visibility="hidden"});d(b,"focus",function(){i(a._button,a._settings.focusClass)});d(b,"blur",function(){f(a._button,a._settings.focusClass)});c.appendChild(b);document.body.appendChild(c);this._input=b},_clearInput:function(){if(this._input)h(this._input.parentNode),this._input=null,this._createInput(),f(this._button,this._settings.hoverClass),f(this._button,this._settings.focusClass)},_rerouteClicks:function(){var a=this;d(a._button,"mouseover",function(){if(!a._disabled){a._input||a._createInput();var b=a._input.parentNode,c=a._button,d=l(c);g(b,{position:"absolute",left:d.left+ "px",top:d.top+"px",width:c.offsetWidth+"px",height:c.offsetHeight+"px"});b.style.visibility="visible"}})},_createIframe:function(){var a=m(),b=k('<iframe src="javascript:false;" name="'+a+'" />');b.setAttribute("id",a);b.style.display="none";document.body.appendChild(b);return b},_createForm:function(a){var b=this._settings,c=k('<form method="post" enctype="multipart/form-data"></form>');c.setAttribute("action",b.action);c.setAttribute("target",a.name);c.style.display="none";document.body.appendChild(c);for(var d in b.data)b.data.hasOwnProperty(d)&&(a=document.createElement("input"),a.setAttribute("type","hidden"),a.setAttribute("name",d),a.setAttribute("value",b.data[d]),c.appendChild(a));return c},_getResponse:function(a,b){var c=!1,f=this,g=this._settings;d(a,"load",function(){if(a.src=="javascript:'%3Chtml%3E%3C/html%3E';"||a.src=="javascript:'<html></html>';")c&&setTimeout(function(){h(a)},0);else{var e=a.contentDocument?a.contentDocument:window.frames[a.id].document;if(!(e.readyState&&e.readyState!= "complete")&&!(e.body&&e.body.innerHTML=="false")){var d;if(e.XMLDocument)d=e.XMLDocument;else if(e.body){if(d=e.body.innerHTML,g.responseType&&g.responseType.toLowerCase()=="json"){if(e.body.firstChild&&e.body.firstChild.nodeName.toUpperCase()=="PRE")e.normalize(),d=e.body.firstChild.firstChild.nodeValue;d=d?eval("("+d+")"):{}}}else d=e;g.onComplete.call(f,b,d);c=!0;a.src="javascript:'<html></html>';"}}})},submit:function(){var a=this._settings;if(this._input&&this._input.value!==""){var b=this._input.value.replace(/.*(\/|\\)/, "");if(!1===a.onSubmit.call(this,b,-1!==b.indexOf(".")?b.replace(/.*[.]/,""):""))this._clearInput();else{var a=this._createIframe(),c=this._createForm(a);h(this._input.parentNode);f(this._button,this._settings.hoverClass);f(this._button,this._settings.focusClass);c.appendChild(this._input);c.submit();h(c);h(this._input);this._input=null;this._getResponse(a,b);this._createInput()}}}}})();

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

var $messagesList = $('#messagesList'), // Список сообщений
    $messagesListMessage = $('#controlResponseMessage'), // Див для текста сообщения об аякс-запросе
    $messagesCount = $('#messagesCount'), // Див с кол-вом сообщений в списке
    $addVpForm = $('#addVpForm'),
    $mapAjaxLoading = $('#mapResponseLoading', $addVpForm),
    $locationMap = $('#locationMap', $addVpForm),
    $mapLoading = $('img#mapResponseLoading', $locationMap),
    $mapMessage = $('#locationMapMessage', $locationMap),
    $mapMessageText = $('#mapResponseText', $locationMap),
    $mapSaveLocation = $('#mapSaveLocation', $locationMap),
    $searchControl = $('#locationMapControl', $locationMap),
    $addressField = $('input#locationAddress', $searchControl),
    $addressStatus = $('#locationAddressStatus', $addVpForm),
    $regionIdField = $('input#locationRegionId', $addVpForm),
    map, // Объект карты для выбора координат
    marker, // Объект маркера на карте, указывающего координаты юзера
    geocoder; // Объект геолокатора

/**
 * Создание маркера, привязывающего сообщение к точке на карте.
 * Точка имеет координаты, адрес, регион и т.д.
 *
 * @param lat float Широта точки маркера.
 * @param lng float Долгота точки маркера.
 * @param doGeocoding bool Если передано true, будет выполнено геокодирование адреса по
 * указанным координатам. По умолчанию false.
 */
function createLocationMarker(lat, lng, doGeocoding)
{
    if( !lat || !lng )
        return;
    else {
        lat = parseFloat(lat);
        lng = parseFloat(lng);
    }

    doGeocoding = !!doGeocoding;

    if(marker) {
        marker.setMap(null);
        marker = '';
    }

    // Геолокация региона точки:
    $.getJSON(CONST_REGION_SERVICE_URL+'?lat='+lat+'&lng='+lng+'&callback=?')
     .success(function(resp){
//         console.log(resp);
         if(resp.length == 0)
             $regionIdField.val('-');
         else
             $regionIdField.val(resp[0].id);
//         alert($regionIdField.val());
     })
     .error(function(jqXHR, textStatus){
//         console.log('Error', textStatus);
         $regionIdField.val('-');
     });
    
    // Установка маркера:
    var markerLatLng = new google.maps.LatLng(lat, lng);
    marker = new google.maps.Marker({
        position: markerLatLng,
        icon: '/images/wh_icon.png',
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,
        title: 'Ваше местоположение'
    });

    google.maps.event.addListener(marker, 'dragend', function(){
        var newPosition = this.getPosition();

        $addressField.slideDown(200);
        $('#locationLatField', $addVpForm).val(newPosition.lat());
        $('#locationLngField', $addVpForm).val(newPosition.lng());
        
        $('#locationCoords', $mapMessage).html(newPosition.lat()+', '+newPosition.lng());

        if( !$addressField.data('filledByUser') )
            geocode(newPosition.lat().toFixed(2), newPosition.lng().toFixed(2));
    });

    map.panTo(markerLatLng);

    $addressField.slideDown(200);
    $('#locationLatField', $addVpForm).val(lat);
    $('#locationLngField', $addVpForm).val(lng);
    $('#locationError', $addVpForm).slideUp(250).html('');
    
    $('#locationCoords', $mapMessage).html(lat.toFixed(2)+', '+lng.toFixed(2));

    if(doGeocoding)
        geocode(markerLatLng.lat(), markerLatLng.lng());
}

/**
 * Прямая геолокация - получение адреса по координатам.
 *
 * @param lat float Широта точки.
 * @param lng float Долгота точки.
 */
function geocode(lat, lng)
{
    if( !lat || !lng )
        return;

    $mapAjaxLoading.show();

    // Геолокация OSM:
    //
    // Примечание: ответ возвращается в виде jsonp, при его получении
    // функция geocodeHandleResult будет вызвана автоматически:
//    $.ajax('http://nominatim.openstreetmap.org/reverse',
//           {type: 'get',
//            dataType: 'jsonp',
//            data: {format: 'json', json_callback: 'geocodeHandleResult',
//                   lat: parseFloat(lat), lon: parseFloat(lng)}});

    // Геолокация гугла:
    geocoder.geocode({'latLng': new google.maps.LatLng(parseFloat(lat), parseFloat(lng))},
                     function(results, status){
                         if(status == google.maps.GeocoderStatus.OK) {
                             geocodeHandleResult(results);
                         } else {
                             // @todo Здесь геолокация яндекса. Нужен API-ключ.
                             // Но пока для простоты вывести сообщение о ненайденном адресе:
                             $mapAjaxLoading.hide();
                             $mapMessageText.html(LANG_ADDR_NOT_FOUND);
//                             console.log('Failure:', status);
                         }
                     });
}

/**
 * Обработка результата геолокации (адреса по координатам) после выбора пользователем
 * своего местоположения на карте.
 * Если геолокационный запрос использует формат jsonp, функция вызывается автоматически.
 *
 * @param resp object JSON-объект с результатом геолокации.
 */
function geocodeHandleResult(resp)
{
    $mapAjaxLoading.hide();

    if( !resp || resp.length == 0 ) {
        $mapMessageText.html(LANG_ADDR_NOT_FOUND);
        return;
    }

    if(resp[0] && resp[0].formatted_address) { // Обрабатывается результат гугла
        resp = resp[0];
//        createLocationMarker(resp.geometry.location.lat(), resp.geometry.location.lng(), false);
        var addrFoundText = LANG_ADDR_FOUND+': '+resp.formatted_address;
        $mapMessageText.html(addrFoundText);
        $('#addressFound', $addressStatus).html(addrFoundText);
        $('#changeAddress', $addressStatus).show();
        $('#addressNotFound', $addressStatus).hide();
        $addressField.val(resp.formatted_address);
        $mapSaveLocation.show();
    }
//    else if() { // @todo Здесь обрабатывается результат яндекса. Нужен API-ключ.
//
//    }
    else if(resp.display_name && resp.display_name != 'undefined') { // Обрабатывается результат OSM
        // Почему-то иногда OSM-сервис Nominatim дописывает "США"
        // в строку адреса:
        resp = resp.display_name.replace(/, United States of America/i, '');
        var addrFoundText = LANG_ADDR_FOUND+': '+resp;
        $mapMessageText.html(addrFoundText);
        $('#addressFound', $addressStatus).html(addrFoundText);
        $('#changeAddress', $addressStatus).show();
        $('#addressNotFound', $addressStatus).hide();
        $addressField.val(resp);
        $mapSaveLocation.show();
    } else { // Вывести сообщение о ненайденном адресе:
        $mapMessageText.html(LANG_ADDR_NOT_FOUND);
    }
}

/**
 * Обратная геолокация - получение координат по адресу.
 *
 * @param address string Строка адреса.
 */
function geocodeReverse(address)
{
    if( !address || address.length == 0 )
        return;

    // Обратная геолокация гугла:
    geocoder.geocode({'address': $addressField.val()},
                     function(results, status){
        if(status == google.maps.GeocoderStatus.OK) {
            geocodeReverseHandleResult(results);
        } else {
            // @todo Здесь геолокация яндекса. Нужен API-ключ.
            // Но пока для простоты вывести сообщение о ненайденном адресе:
            $mapAjaxLoading.hide();
            $mapMessageText.html(LANG_ADDR_NOT_FOUND);
//            console.log('Reverse geolocation failure! Reason:', status);
        }
    });
}

/**
 * Обработка результата обратной геолокации (координат по адресу) после использования
 * виджета текстового поиска по карте.
 * Если геолокационный запрос использует формат jsonp, функция вызывается автоматически.
 *
 * @param resp object JSON-объект с результатом обратной геолокации.
 */
function geocodeReverseHandleResult(resp)
{
    $mapAjaxLoading.hide();

    if( !resp || resp.length == 0 ) {
        $mapMessageText.html(LANG_ADDR_NOT_FOUND);
        return;
    }

//    console.log('Reverse geolocation success:', resp);
    if(resp[0] && resp[0].formatted_address) { // Обрабатывается результат гугла
        resp = resp[0];
        createLocationMarker(resp.geometry.location.lat(), resp.geometry.location.lng(), false);
        var addrFoundText = LANG_ADDR_FOUND+': '+resp.formatted_address;
        $mapMessageText.html(addrFoundText);
        $('#addressFound', $addressStatus).html(addrFoundText);
        $('#changeAddress', $addressStatus).show();
        $('#addressNotFound', $addressStatus).hide();
        $addressField.val(resp.formatted_address);
        $mapSaveLocation.show();
    }
}

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

/**
 * Валидация формы изменения параметров имени пользователя.
 *
 * @param $form object Объект формы редактирования имени пользователя. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, false в противном случае.
 */
function validateNameForm($form)
{
    if( !$form )
        return false;
    $form = $($form);

    var field = {},
        value = '',
        isFocused = false,
        result = true;

    // Имя (обязательно):
    field = $('#firstName', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#firstNameError', $form).html(LANG_FIRST_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[ ]*[a-zа-я"']+[a-zа-яё '"-]*[a-zа-я"']+[ ]*$/i.test(value) ) {
        field.addClass('invalidField');
        $('#firstNameError', $form).html(LANG_FIRST_NAME_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#firstNameError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Фамилия (обязательно):
    field = $('#lastName', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#lastNameError', $form).html(LANG_LAST_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[ ]*[a-zа-я"']+[a-zа-яё '"-]*[a-zа-я"'']+[ ]*$/i.test(value) ) {
        field.addClass('invalidField');
        $('#lastNameError', $form).html(LANG_LAST_NAME_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#lastNameError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    return result;
}

/**
 * Валидация формы изменения контактов пользователя.
 *
 * @param $form object Объект формы редактирования контактов пользователя. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, false в противном случае.
 */
function validateContactsForm($form)
{
    // Телефоны основной и дополнительные (необязательно):
    var result = true,
        phoneIsSet = false,
        field = {},
        value = '',
        isFocused = false;

    $('#phonesList > li:not(#phonePrototype)', $form).find('.phone:not(.empty)').each(function(){
        var $this = $(this);

        field = $($this, $form);
        value = $.trim(field.val());
        if(value && !/^[ ]*[0-9]+[ ]*$/i.test(value)) {
            $($this, $form).addClass('invalidField');
            $this.parent().find('.phoneError').html(LANG_PHONE_INVALID).slideDown(250);
            result = result && false;
        } else {
            $($this, $form).removeClass('invalidField');
            $this.parent().find('.phoneError').slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }

        phoneIsSet = phoneIsSet || !!value;
    });

    return result;
}

/**
 * Валидация формы изменения пароля пользователя.
 *
 * @param $form object Объект формы редактирования контактов пользователя. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, false в противном случае.
 */
function validateChangePass($form)
{
    var field = {},
        value = '',
        isFocused = false,
        result = true;

    // Пароль старый (обязательно):
    field = $('#oldPass', $form);
    value = $.trim(field.val());
    if(CONST_PASSWORD_MIN_LENGTH > 0 && value.length < CONST_PASSWORD_MIN_LENGTH) {
        field.addClass('invalidField');
        $('#oldPassError', $form)
            .html(LANG_PASSWORD_SHORT.replace('%passwordMinLength', CONST_PASSWORD_MIN_LENGTH))
            .slideDown(250);
        result = result && false;
    } else if(CONST_PASSWORD_MAX_LENGTH > 0 && value.length > CONST_PASSWORD_MAX_LENGTH) {
        field.addClass('invalidField');
        $('#oldPassError', $form)
            .html(LANG_PASSWORD_LONG.replace('%passwordMaxLength', CONST_PASSWORD_MAX_LENGTH))
            .slideDown(250);
        result = result && false;
    }

    // Пароль новый (обязательно):
    field = $('#newPass', $form);
    value = $.trim(field.val());
    if(CONST_PASSWORD_MIN_LENGTH > 0 && value.length < CONST_PASSWORD_MIN_LENGTH) {
        field.addClass('invalidField');
        $('#newPassError', $form)
            .html(LANG_PASSWORD_SHORT.replace('%passwordMinLength', CONST_PASSWORD_MIN_LENGTH))
            .slideDown(250);
        result = result && false;
    } else if(CONST_PASSWORD_MAX_LENGTH > 0 && value.length > CONST_PASSWORD_MAX_LENGTH) {
        field.addClass('invalidField');
        $('#newPassError', $form)
            .html(LANG_PASSWORD_LONG.replace('%passwordMaxLength', CONST_PASSWORD_MAX_LENGTH))
            .slideDown(250);
        result = result && false;
    } else if(value != $('#newPassConfirm', $form).val()) {
        field.addClass('invalidField');
        $('#newPassConfirm', $form).addClass('invalidField');
        $('#newPassError', $form).html(LANG_PASSWORD_CONFIRM_MISMATCH).slideDown(250);
        result = result && false;
    } else if(value == $('#oldPass', $form).val()) {
        field.addClass('invalidField');
        $('#newPassError', $form).html(LANG_PASSWORD_NOT_CHANGED).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#oldPass, #newPass, #newPassConfirm', $form).removeClass('invalidField');
        $('#oldPassError, #newPassError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    return result;
}

/**
 * Валидация формы изменения параметров имени пользователя.
 *
 * @param $form object Объект формы добавления профиля волонтёрства. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, false в противном случае.
 */
function validateVpForm($form)
{
    if( !$form )
        return false;
    $form = $($form);

    var field = {},
        isFocused = false,
        result = true;

    // Название профиля (обязательно):
    field = $('.vpTitle', $form);
    if( !field.val() ) {
        $('.vpTitleError', $form).html(LANG_VP_TITLE_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('.vpTitleError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Координаты (если форма включает их, то обязательны):
    if($('#locationLatField', $form).length) {
        if($('#locationLatField', $form).val().length <= 0 && $('#locationLngField', $form).val().length <= 0) {
            $('#locationError', $form).html(LANG_LOCATION_REQUIRED).slideDown(250);
            result = result && false;
        } else {
            $('#locationError', $form).slideUp(250).html('');
        }
    }

    // Категории (если форма включает их, то обязательно наличие или хотя бы 1 категории,
    // или чекбокса "все категории"):
    if($('input.allCategories', $form).length) {
        field = $('input[name*="category"]:checked', $form);
        if(field.length <= 0 && $('input.allCategories:checked').length <= 0) {
            $('.categoryError', $form).html(LANG_CATEGORY_REQUIRED).slideDown(250);
            result = result && false;
        } else {
            $('.categoryError', $form).slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }
    }

    // Расстояние помощи в кризис (обязательно):
//    field = $('#aidingDistanceEmergency', $form);
//    if( !field.val() ) {
//        $('#aidingDistanceEmergencyError', $form).html(LANG_AIDING_DISTANCE_REQUIRED).slideDown(250);
//    } else {
//        $('#aidingDistanceEmergencyError', $form).slideUp(250).html('');
//    }
//    if( !result && !isFocused ) {
//        field.focus();
//        isFocused = true;
//    }
//
//    // Дни, в которые юзер доступен для помощи в кризис (обязательно наличие хотя бы 1 пункта):
//    field = $('input[name*="helpCrysisDays"]:checked', $form);
//    if(field.length <= 0) {
//        $('#helpCrysisDaysError', $form).html(LANG_HELP_DAYS_REQUIRED).slideDown(250);
//    } else {
//        $('#helpCrysisDaysError', $form).slideUp(250).html('');
//    }
//    if( !result && !isFocused ) {
//        field.focus();
//        isFocused = true;
//    }

    // Расстояние помощи в мирное время (обязательно):
    field = $('.aidingDistance', $form);
    if( !field.val() ) {
        $('.aidingDistanceError', $form).html(LANG_AIDING_DISTANCE_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('.aidingDistanceError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }
    
    // Дни, в которые юзер доступен для помощи в мирное время (обязательно наличие хотя бы 1 пункта):
    field = $('input[name*="helpDays"]:checked', $form);
    if(field.length <= 0) {
        $('.helpDaysError', $form).html(LANG_HELP_DAYS_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('.helpDaysError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // E-mail для рассылок (валидируется только если задан):
    field = $('.mailoutEmailField', $form);
    if( !field.attr('disabled') ) {
        var value = $.trim(field.val());
        if(value && !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value)) {
            field.addClass('invalidField');
            $('.mailoutEmailError', $form).html(LANG_EMAIL_INVALID).slideDown(250);
            result = result && false;
        } else {
            field.removeClass('invalidField');
            $('.mailoutEmailError', $form).slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }
    }

    return result;
}

/**
 * Валидация формы удаления профиля волонтёрства.
 *
 * @param $form object Объект формы удаления профиля волонтёрства. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, false в противном случае.
 */
function validateDeleteVpForm($form)
{
    if( !$form )
        return false;
    $form = $($form);

    var field = {},
        isFocused = false,
        result = true;

    $('.responseMessage', $form).hide().html('');
    
    // Пароль от учётки юзера (обязателен):
    field = $('input[name="deleteVpPass"]', $form);
    if(field.val().length <= 0) {
        $('.validationError', $form).html(LANG_PASSWORD_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('.validationError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }
    
    return result;
}

function activateFormSubmit($form, active)
{
    $form = $($form);
    var $submit = $('input.submit', $form),
        $cancel = $('input.reset', $form);
    if( !!active ) {
        $submit.removeAttr('disabled');
        $cancel.removeAttr('disabled');
    } else {
        $submit.attr('disabled', 'disabled');
        $cancel.attr('disabled', 'disabled');
    }
    /**
     * @todo Disabled не работает на форме смены пароля.
     */
}

$(function(){ // Готовность DOM
    // Плейсхолдеры полей:
    $('[placeholder]').defaultValue();

    /**
     * Загрузка аватара юзера.
     */
    // Поле для загрузки аватара:
    var $avatarFieldInfo = $('#avatarFieldInfo'),
        $avatarLoading = $('#avatarLoading');
    $('#load_avatar_off').click(function(){
        $(this).hide();
        $('#load_avatar_on').show();
        $('#load_avatar_container').slideDown(200);
    });
    $('#load_avatar_on').click(function(){
        $(this).hide();
        $('#load_avatar_off').show();
        $('#load_avatar_container').slideUp(200);
    });
    var button = $('#button1'), interval;
            new AjaxUpload('avatarField', {
                    action: '/ajax_forms/editUserdataImageUpload',
                    name: 'avatar',
                    autoSubmit: true,
                    responseType: 'json', // Возможные значения: json, text/html (по умолч.)
                    data: {mediaType: CONST_MEDIA_IS_TYPE_AVATAR, mediaFieldName: 'avatar'},
                    // Fired after the file is selected.
                    // Useful when autoSubmit is disabled.
                    // You can return false to cancel upload
                    // @param fileName basename of uploaded file
                    // @param fileExt of that file
                    onChange: function(fileName, fileExt){
                        var $message = $('#avatarMessage', $avatarFieldInfo);
                        if(fileExt && /^(jpg|jpeg|png|gif)$/i.test(fileExt)) {
                            $message
                                .stop(true).fadeOut(200)
                                .html('Выбран файл: '+fileName)
                                .stop(true).fadeIn(200);
                            return true;
                        } else {
                            $message
                                .html('<div class="errorMessage">Вы должны выбрать файл фотки!</div>')
                                .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                            return false;
                        }
                    },
                    onSubmit: function(fileName, fileExt){
                        $avatarLoading.show();
                        $('#avatarSubmit').attr('disabled', 'disabled');
                    },
                    onComplete: function(userFileName, resp){
                        $avatarLoading.hide();
                        $('#avatarSubmit').removeAttr('disabled');

                        if(resp.status && resp.status == 'success') {
                            $('#avatarImageItself').attr('src', resp.fileData.pictureUrl);
                            if(resp.message) {
                                $('#avatarMessage', $avatarFieldInfo)
                                    .html('<div class="lightGreen">'+resp.message+'</div>')
                                    .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                            }
                        } else {
                            if(resp.message) {
                                $('#avatarMessage', $avatarFieldInfo)
                                    .html('<div class="red">'+resp.message+'</div>')
                                    .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                            } else {
                                $('#avatarMessage', $avatarFieldInfo)
                                    .html('<div class="red">Ошибка при загрузке файла</div>')
                                    .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                            }
                        }
                    }});
    $('#avatarSubmit').click(function(){
        fileUploader.submit();
    });

    // Кнопки для удаления фоток из списка уже загруженных:
//    $('.photoDeleteDiv', '#photoUploadedList').live('click', function(){
//        var $this = $(this);
//        $.ajax('/ajax_forms/addMessageImageRemove',
//               {data: {id: $this.attr('id')},
//                type: 'post',
//                async: true,
//                dataType: 'json',
//                cache: false})
//         .success(function(resp){
//             // ...
//         })
//         .error(function(jqXHR, textStatus){
//             // ...
//         });
//
//        $this.parent()
//             .slideUp(200, function(){ // Удалить файл из списка загруженных
//                 $(this) // Также удалить соответствующее файлу скрытое поле
//                     .parents('#photoUploadedList')
//                     .find('input[name^="photoAttached"][value="'+$this.attr('id')+'"]')
//                         .remove();
//                 $(this).remove(); // И саму строку файла в списке, безусловно
//             });
//    });

    var $nameForm = $('#userNameForm'),
        $nameFormIndicator = $('.responseLoading', $nameForm),
        $nameFormMessage = $('.responseMessage', $nameForm);

    $nameForm.data('original', $nameForm.serialize());

    $nameForm.submit(function(e){
        e.preventDefault();

        if(validateNameForm($nameForm) && $nameForm.serialize() != $nameForm.data('original')) {
            $nameFormIndicator.show();
            activateFormSubmit($nameForm, false);
            $.ajax('/ajax_forms/editUserPersonalData',
                   {data: $nameForm.serialize()+'&userId='+CONST_USER_ID,
                    type: 'post',
                    async: true,
                    dataType: 'json',
                    cache: false})
             .success(function(resp){
                 if(resp && resp.status && resp.status == 'success') {
                     $nameForm.data('original', $nameForm.serialize());
                     if(resp.message && resp.message.length)
                         $nameFormMessage.html(resp.message).stop(true).fadeIn(200);
                     else
                         $nameFormMessage.html('').stop(true).fadeOut(200);
                 } else {
                     if(resp && resp.errors) {
                         $nameFormMessage
                            .html('<div class="errorMessage">'+resp.errors+'</div>')
                            .stop(true).fadeIn(200);
                     } else if(resp && resp.message) {
                         $nameFormMessage
                            .html('<div class="errorMessage">'+resp.message+'</div>')
                            .stop(true).fadeIn(200);
                     } else {
                         $nameFormMessage
                            .html(LANG_AJAX_ERROR)
                            .stop(true).fadeIn(200);
                     }
                 }
                 $nameFormIndicator.hide();
                 activateFormSubmit($nameForm, true);
             })
             .error(function(jqXHR, textStatus){
                 $nameFormIndicator.hide();
                 activateFormSubmit($nameForm, true);
                 $nameFormMessage
                    .html(LANG_AJAX_ERROR)
                    .stop(true).fadeIn(200);
             });
        }
    });
    
    /**
     * Форма редактирования контактов юзера.
     */
    var $contactsForm = $('#contactsForm'),
        $contactsFormIndicator = $('.responseLoading', $contactsForm),
        $message = $('.responseMessage', $contactsForm);

    $contactsForm.data('original', $contactsForm.serialize());

    // Кнопка "добавить ещё телефон":
    $('#addPhone', $contactsForm).click(function(){
        var $this = $(this);
        if($this.data('is-off'))
            return;

        $('#phonePrototype', $contactsForm)
            .clone(true)
            .removeAttr('id').removeAttr('style')
            .show()
            .appendTo( $('#phonesList', $contactsForm) )
            .find('input')
                .removeAttr('disabled');

        // Макс. 3 номера. Скрыть кнопку "добавить телефон", если на форме 3 и больше номеров:
        if($('#phonesList li:visible', $contactsForm).length >= 3)
            $this.animate({opacity: 0.25}).css('cursor', 'default').data('is-off', true);
    });

    // Кнопки "удалить телефон":
    $('.removePhone', $contactsForm).click(function(){
        $(this).parent().slideUp(200, function(){
            $(this).remove();

            // Вывести кнопку "добавить телефон", если на форме менее 3 номеров:
            if($('#phonesList li:visible', $contactsForm).length < 3)
            $('#addPhone', $contactsForm)
                .animate({opacity: 1.0})
                .css('cursor', 'pointer')
                .removeData('is-off').removeAttr('data-is-off');
        });
    });

    // Раскрывающийся див для полей соц.сетей:
    $('#expandableSwitch', $contactsForm).click(function(e){
        e.preventDefault();
        $('#expandableContent', $contactsForm).slideToggle(250);
    });

    $contactsForm.submit(function(e){
        e.preventDefault();

        if(validateContactsForm($contactsForm)) {
            $message.html('').stop(true).fadeOut(200);
            if($contactsForm.serialize() != $contactsForm.data('original')) {
                $contactsFormIndicator.show();
                activateFormSubmit($contactsForm, false);
                $.ajax('/ajax_forms/editUserContacts',
                    {data: $contactsForm.serialize()+'&userId='+CONST_USER_ID,
                        type: 'post',
                        async: true,
                        dataType: 'json',
                        cache: false})
                .success(function(resp){
                    if(resp && resp.status && resp.status == 'success') {
                        $contactsForm.data('original', $contactsForm.serialize());
                        if(resp.message && resp.message.length)
                            $message.html(resp.message).stop(true).fadeIn(200);
                        else
                            $message.html('').stop(true).fadeOut(200);
                    } else {
                        if(resp && resp.errors) {
                            $message
                                .html('<div class="errorMessage">'+resp.errors+'</div>')
                                .stop(true).fadeIn(200);
                        } else if(resp && resp.message) {
                            $message
                                .html('<div class="errorMessage">'+resp.message+'</div>')
                                .stop(true).fadeIn(200);
                        } else {
                            $message
                                .html(LANG_AJAX_ERROR)
                                .stop(true).fadeIn(200);
                        }
                    }
                    $contactsFormIndicator.hide();
                    activateFormSubmit($contactsForm, true);
                })
                .error(function(jqXHR, textStatus){
                    $contactsFormIndicator.hide();
                    activateFormSubmit($contactsForm, true);
                    $message
                        .html(LANG_AJAX_ERROR)
                        .stop(true).fadeIn(200);
                });
            }
        }
    });
    
    /**
     * Окно смены пароля.
     */
    var $changePassForm = $('form#changePassForm'),
        $changePassLoading = $('img#changePassLoading', $changePassForm),
        $changePassMessage = $('#changePassMessage', $changePassForm),
        $changePassButtons = $('input#changePassSubmit, input#changePassReset', $changePassForm);

    $('#changePass').click(function(){
        $(this).colorbox({inline: true,
                          href: $('#changePassModal'),
						  width: 500,
						  height: 212,
                          fixed: true,
//                          transition: 'fade'
                          overlayClose: false,
                          scrolling: false,
                          onComplete: function(){
                              $changePassForm[0].reset();
                              $changePassMessage.html('').hide();
                              $(':input', $changePassForm).removeClass('invalidField');
                              $('.form_error', $changePassForm).html('').hide();
                              $changePassButtons.removeAttr('disabled').animate({opacity: 1.0}).show();
                              $('#changePassSuccess', $changePassForm).hide();
                          }});
    });

    $changePassForm.submit(function(e){
        e.preventDefault();

        if( !validateChangePass($changePassForm) )
            return;

        $changePassLoading.show();
        $changePassButtons.attr('disabled', 'disabled').animate({opacity: 0.25});

        $.ajax('/auth/changePasswordProcess',
               {data: $changePassForm.serialize(),
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false})
            .success(function(resp){ // Обработка успешного запроса
                if(resp.status && resp.status == 'success') {
                    $changePassMessage.html('<div class="successMessage">'+resp.message+'</div>')
                                      .stop(true).fadeIn(200, function(){
                                          $changePassButtons.hide();
                                          $('#changePassSuccess', $changePassForm).show();
                                      });
                } else {
                    if(resp.errors) {
                        $changePassMessage.html('<div class="errorMessage">'+resp.errors+'</div>')
                                          .stop(true).fadeIn(200);
                    } else if(resp.message) {
                        $changePassMessage.html('<div class="errorMessage">'+resp.message+'</div>')
                                          .stop(true).fadeIn(200);
                    } else {
                        $changePassMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                          .stop(true).fadeIn(200);
                    }
                    $changePassButtons.removeAttr('disabled').animate({opacity: 1.0});
                }

                $changePassLoading.hide();
            })
            .error(function(jqXHR, textStatus){ // Обработка ошибочного запроса
                $changePassMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                  .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                $changePassLoading.hide();
                $changePassButtons.removeAttr('disabled').animate({opacity: 1.0});
            });
    });

    $('#changePassReset, #changePassSuccess', $changePassForm).click(function(e){
        $.colorbox.close();
        $changePassButtons.removeAttr('disabled').animate({opacity: 1.0}).show();
        $('#changePassSuccess', $changePassForm).hide();
    });

    /**
     * Вкладки профилей волонтёрства.
     */
    var $vpTabs = $('#vpList');
    // Если кол-во профилей уже максимально, по умолчанию выбрана вкладка первого профиля;
    // иначе выбрана вкладка добавления профиля:
    $vpTabs.tabs({fx: {opacity: 'toggle', duration: 'fast'}});

    if($vpTabs.tabs('length') > CONST_VP_MAX) {
        $vpTabs.tabs('select', 1);
        $vpTabs.tabs('disable', 0); // Выключить вкладку добавления профиля
    } else
        $vpTabs.tabs('select', 0);
    
   $vpTabs.delegate('.helpDaysCheckAll', 'change', function(){
       var $this = $(this),
           $form = $this.parents(':eq(2)');

       if($this.attr('checked'))
           $('input[type="checkbox"][name*="helpDays"]').attr('checked', 'checked');
       else
           $('input[type="checkbox"][name*="helpDays"]').removeAttr('checked');
   });

    // Кнопки удаления профилей:
    $vpTabs.delegate('.deleteVp', 'click', function(){
        var $this = $(this),
            $form = $( $('#vpDeleteFormTmpl').tmpl({id: $this.data('vp-id'),
                                                    title: $this.data('vp-title')}) );
        $this.colorbox({inline: true,
                        href: $form,
                        title: 'Удаление профиля волонтёрства «'+$this.data('vp-title')+'»',
                        fixed: true,
//                        transition: 'fade'
                        overlayClose: false,
                        scrolling: false,
                        onComplete: function(){
                            $form.find('input[name="deleteVpPass"]').val('');
                            $('.responseMessage, .validationError', $form).hide().html('');
                            $('.submit, .deleteVpSuccess', $form).show();
                            $('.deleteVpSuccess', $form).hide();
                        }});
    });

    $(document).delegate('.deleteVpConfirm', 'submit', function(e){
        e.preventDefault();

        var $form = $(this),
            $ajaxLoading = $('.responseLoading', $form),
            $message = $('.responseMessage', $form);

        if( $form.data('submitOff') || !validateDeleteVpForm($form) )
            return;

        $ajaxLoading.show();
        activateFormSubmit($form, false);

        $.ajax('/ajax_forms/deleteVolunteerProfileProcess',
               {data: $form.serialize(),
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false})
            .success(function(resp){ // Обработка успешного запроса
                if(resp.status && resp.status == 'success') {
                    if(resp.message && resp.message.length)
                         $message.html(resp.message).stop(true).fadeIn(200);
                     else
                         $message.html('').stop(true).fadeOut(200);

                     $form.data('submitOff', true); // Выключение дальнейших сабмитов формы
                     $('.submit, .reset', $form).hide();
                     $('.deleteVpSuccess', $form).show();
                     $vpTabs.tabs('remove',
                                  $('a[href="#vp'+$form.data('vp-id')+'"]', $vpTabs).parent().index())
                            .tabs('enable', 0);
                } else {
                    if(resp.errors) {
                        $message.html('<div class="errorMessage">'+resp.errors+'</div>')
                                .stop(true).fadeIn(200);
                    } else if(resp.message) {
                        $message.html('<div class="errorMessage">'+resp.message+'</div>')
                                .stop(true).fadeIn(200);
                    } else {
                        $message.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                .stop(true).fadeIn(200);
                    }
                }

                activateFormSubmit($form, true);
                $ajaxLoading.hide();
            })
            .error(function(jqXHR, textStatus){ // Обработка ошибочного запроса
                $message.html('<div class="errorMessage">Ошибка при обработке!</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                $ajaxLoading.hide();
                activateFormSubmit($form, true);
            });

    }).delegate('.deleteVpReset, .deleteVpSuccess', 'click', function(e){
//        e.preventDefault();
        $.colorbox.close();
        var $form = $(this).parents('.deleteVpConfirm');
        $form.find('input[name="deleteVpPass"]').val('');
        $('.responseMessage, .validationError', $form).html('');
        $('.submit, .reset', $form).show();
//        $('.deleteVpReset', $form).hide();
    });

    /**
     * Формы добавления/редактирования профилей волонтёрства.
     */
    // Специфика формы добавления профиля:
    $('.addVpReset', $addVpForm).click(function(e){
        e.preventDefault();

        $('.vpTitle', $addVpForm).val('');

        // Возвращение карты в исходное состояние:
        $('#addressFound', $addressStatus).html('');
        $('#changeAddress', $addressStatus).hide();
        $('#addressNotFound', $addressStatus).show();
        $('#locationLatField, #locationLngField, #locationRegionId, #locationAddress', $addVpForm).val('');
        $mapMessageText.html('');
        $('#locationCoords', $mapMessage).html('');
        $mapSaveLocation.hide();
        marker.setMap(null);
        $('#locationRegion', $searchControl).val(userRegionCookie()).change();

        if($('.allCategories', $addVpForm).attr('checked'))
            $('.allCategories', $addVpForm).click();
        $('.categoryListIcon', $addVpForm).each(function(){
            var $this = $(this);
            if( !$this.hasClass('collapsed') ) {
                $this.parents('.categoryGroup').click();
            }
        });
        $('input[type="checkbox"][name*="category"]:checked', $addVpForm).removeAttr('checked');

        $('.aidingDistanceSlider', $addVpForm).slider('value', 0);
        $('.aidingDistance', $addVpForm).val('');
        $('.distCurrent', $addVpForm).html('');

        $('input[type="checkbox"][name*="helpDays"]:checked', $addVpForm).removeAttr('checked');
        $('input[type="checkbox"].helpDaysCheckAll:checked', $addVpForm).removeAttr('checked');

        if( !$('.addMailoutEmail', $addVpForm).attr('checked') )
            $('.addMailoutEmail', $addVpForm).click();
    });

    // Аякс-список категорий на форме добавления профиля:
    $('.categoryGroup', $addVpForm).click(function(){
        var $this = $(this),
            $parent = $this.parent();

        if($this.parents('#categoryListing').data('disabled'))
            return;

        $this.find('.categoryListIcon').toggleClass('collapsed');
        if( !$this.data('isLoaded') ) {
            var catParentId = $this.find('.categoryListIcon').attr('id').split('_').splice(2, 1);
            $('img.subCatsLoading', $parent).show();
            $.ajax('/ajax_views/getCategoryChildren/'+catParentId,
                   {type: 'get',
                    dataType: 'json',
                    cache: true,
                    success: function(resp){
                        $('img.subCatsLoading', $parent).hide();
                        $this
                            .data('isLoaded', true)
                            .data('isDisplayed', true);

                        if(resp && resp.length) {
                            var subCatsHtml = '';
                            for(var i=0; i<resp.length; i++) {
                                subCatsHtml += '<li id="'+resp[i].id+'">\
                                        <label class="label_show alignleft pr10 mt4">\
                                            <input type="checkbox" name="category[]" value="'+
                                            resp[i].id+'" />'+resp[i].name+'\
                                        </label>\
                                    </li>';
                            }

                            subCatsHtml = $(subCatsHtml);
//                            $('input[type="checkbox"]', subCatsHtml).ezMark(); // Apply checkbox style

                            $('.catChilds', $parent)
                                .html('')
                                .append(subCatsHtml)
                                .slideDown(200);
                        }
                    }, error: function(){
                        $('img.subCatsLoading', $parent).hide();
            }});
        } else if( !$this.data('isDisplayed') ) {
            $this.data('isDisplayed', true);
            $('.catChilds', $parent).slideDown(200);
        } else {
            $this.data('isDisplayed', false);
            $('.catChilds', $parent).slideUp(200);
        }
    });

    $('input.allCategories', $addVpForm).change(function(){
        if($(this).attr('checked')) {
            $('#categoryListing', $addVpForm).data('disabled', true).animate({opacity: 0.25});
        } else {
            $('#categoryListing', $addVpForm).data('disabled', false).animate({opacity: 1.0});
        }
    });

    $('#vpEditFormTmpl').template('vpEditFormTemplate'); // Шаблон для формы редактирования профиля
//    $('.vpEditTabsContent').append($.tmpl('vpEditFormTemplate', {}));

    // Поведение, характерное для форм добавления и редактирования профиля:
    function attachVpFormBehavior($form)
    {
        var $form = $($form),
            $vpLoading = $('.responseLoading', $form),
            $vpMessage = $form.siblings('.vpResponseMessage');

        // Специфика формы редактирования профиля:
        if($form.hasClass('editVpForm')) {
           var helpDaysOriginal = [];

           $('input[name*="helpDays"]:checked', $form).each(function(){
               helpDaysOriginal.push($(this).val());
           });

           $form.data('titleOriginal', $('.vpTitle', $form).val())
                .data('helpDaysOriginal', helpDaysOriginal)
                .data('aidingDistanceOriginal', $('.aidingDistance', $form).val())
                .data('addMailoutEmailOriginal', $('.addMailoutEmail:checked', $form).length)
                .data('mailoutEmailOriginal', $('.mailoutEmailField', $form).val());

           // Сброс полей формы редактирования:
           $('.editVpReset', $form).click(function(e){
               e.preventDefault();

               $('.vpTitle', $form).val( $form.data('titleOriginal') );
               $('input[name*="helpDays"]', $form).each(function(){
                   var $this = $(this);
                   if($.inArray($this.val(), $form.data('helpDaysOriginal')) == -1)
                       $this.removeAttr('checked');
                   else
                       $this.attr('checked', 'checked');
               });
               $('.aidingDistanceSlider', $form).slider('value', $form.data('aidingDistanceOriginal'));
               $('.mailoutEmailField', $form).val( $form.data('mailoutEmailOriginal') );
               if($('.addMailoutEmail:checked', $form).length != $form.data('addMailoutEmailOriginal'))
                   $('.addMailoutEmail', $form).click();
           });
       }
            
        // Поля-слайдеры для расстояний:
        if($.ui) {
//            $('#aidingDistanceEmergencySlider', $addVpForm).slider({animate: 'normal', min: 0, max: 105,
//                                                               range: 'min', step: 5,
//                                                               slide: function(event, ui){
//                switch(ui.value) {
//                    case 0:
//                        $('#distEmergCurrent', $addVpForm).html(LANG_AIDING_DISTANCE_MIN_LABEL);
//                        break;
//                    case 105:
//                        $('#distEmergCurrent', $addVpForm).html(LANG_AIDING_DISTANCE_MAX_LABEL);
//                        break;
//                    default:
//                        $('#distEmergCurrent', $addVpForm).html(LANG_AIDING_DISTANCE_LABEL.replace('#DISTANCE#',
//                                                                                                   ui.value));
//                }
//                $('input#aidingDistanceEmergency').val(ui.value);
//            }});
            var $slider = $('.aidingDistanceSlider', $form);
            $slider.slider({animate: 'normal', min: 0, max: 105,
                            range: 'min', step: 5,
                            change: function(event, ui){
                                switch(ui.value) {
                                    case 0:
                                        $('.distCurrent', $form).html(LANG_AIDING_DISTANCE_MIN_LABEL);
                                        break;
                                    case 105:
                                        $('.distCurrent', $form).html(LANG_AIDING_DISTANCE_MAX_LABEL);
                                        break;
                                    default:
                                        $('.distCurrent', $form)
                                            .html(LANG_AIDING_DISTANCE_LABEL.replace('#DISTANCE#', ui.value));
                                }
                                $('input.aidingDistance', $form).val(ui.value);
            }});

            var sliderInitValue = $('input.aidingDistance', $form).val();
            if(sliderInitValue)
                $slider.slider('value', $('input.aidingDistance', $form).val());
        }

        // Чекбокс "рассылать на e-mail":
        $('input.addMailoutEmail', $form).change(function(){
            if($(this).attr('checked')) {
                $('.mailoutEmailField', $form).removeAttr('disabled').animate({opacity: 1.0});
            } else {
                $('.mailoutEmailField', $form).attr('disabled', '1').animate({opacity: 0.25});
            }
        });

        // Отправка формы:
        $form.submit(function(e){
            e.preventDefault();

            if( !validateVpForm($form) )
                return;

            $vpLoading.show();
            activateFormSubmit($form, false);

            var formFields = $form.serializeArray();
            if($('.vpTitle', $form).val() != $form.data('titleOriginal')) {
                formFields.push({name: 'titleChanged', value: true});
                $form.data('titleOriginal', $('.vpTitle', $form).val());
            }

            var req = $.ajax($form.attr('id') == 'addVpForm' ?
                                 '/ajax_forms/addVolunteerProfileProcess' :
                                 '/ajax_forms/editVolunteerProfile',
                             {data: formFields,
                              type: 'post',
                              async: true,
                              dataType: 'json',
                              cache: false})
                        .error(function(jqXHR, textStatus){ // Обработка ошибочного запроса
                            $vpMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                      .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                            $vpLoading.hide();
                            activateFormSubmit($form, true);
                        });

            if($form.attr('id') == 'addVpForm') { // Обработка добавления профиля
                req.success(function(resp){ // Обработка успешного запроса
                    if(resp.status && resp.status == 'success') {
//                            $vpMessage.html('<div class="successMessage">'+LANG_ADD_PROFILE_SUCCESS+'</div>')
//                                      .stop(true).fadeIn(250);

                        // Добавление вкладки нового профиля:
                        var $newTabContent = $('<div id="vp'+resp.id+'"></div>')
                        $newTabContent.append($.tmpl('vpEditFormTemplate',
                                                        {showSuccessMessage: true,
                                                        id: resp.id,
                                                        title: resp.title,
                                                        isAllCategories: resp.isAllCategories,
                                                        userId: resp.userId,
                                                        distance: resp.distance/1000,
                                                        days: resp.days,
                                                        mailoutEmail: resp.mailoutEmail,
                                                        lat: resp.lat,
                                                        lng: resp.lng,
                                                        address: resp.address,
                                                        regionId: resp.regionId,
                                                        regionName: resp.regionName,
                                                        categories: resp.categories},
                                                        {inArray: function(val, array){
                                                            return $.inArray(val.toString(), array) != -1;
                                                        }}));
                        $vpTabs.append($newTabContent)
                                .tabs('add', '#vp'+resp.id, resp.title)
                                .tabs('select', '#vp'+resp.id);
                        $('a[href="#vp'+resp.id+'"]', $vpTabs)
                            .after( $('#vpNewTabTmpl').tmpl({id: resp.id,
                                                                title: resp.title}) );

                        attachVpFormBehavior($('form.editVpForm', $newTabContent));

                        $vpMessage.html('').hide();

                        if($vpTabs.tabs('length') <= CONST_VP_MAX) {
                            $('.addVpReset', $addVpForm).click();
                        } else
                            $vpTabs.tabs('disable', 0); // Запретить добавление нового профиля
                    } else {
                        if(resp.errors) {
                            $vpMessage.html('<div class="errorMessage">'+resp.errors+'</div>')
                                        .stop(true).fadeIn(200);
                        } else if(resp.message) {
                            $vpMessage.html('<div class="errorMessage">'+resp.message+'</div>')
                                        .stop(true).fadeIn(200);
                        } else {
                            $vpMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                        .stop(true).fadeIn(200);
                        }
                    }

                    activateFormSubmit($form, true);
                    $vpLoading.hide();
//                        $('.reset', $addVpForm).click();
                });
            } else { // Обработка редактирования профиля
                req.success(function(resp){ // Обработка успешного запроса
                    if(resp.status && resp.status == 'success') {
                        // На случай, если название профиля было изменено, изменить
                        // название его вкладки:
                        $('a[href="#vp'+resp.id+'"]', $vpTabs).html(resp.newVpTitle);

                        $vpMessage.html('<div class="successMessage">'+LANG_EDIT_PROFILE_SUCCESS+'</div>')
                                    .stop(true).fadeIn(250);
                    } else {
                        if(resp.errors) {
                            $vpMessage.html('<div class="errorMessage">'+resp.errors+'</div>')
                                        .stop(true).fadeIn(200);
                        } else if(resp.message) {
                            $vpMessage.html('<div class="errorMessage">'+resp.message+'</div>')
                                        .stop(true).fadeIn(200);
                        } else {
                            $vpMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                        .stop(true).fadeIn(200);
                        }
                    }

                    activateFormSubmit($form, true);
                    $vpLoading.hide();
                });
            }
        });
    }
    
    // Начальная привязка поведения к формам:
    $('.vpForm').each(function(){
        attachVpFormBehavior(this);
    });

    /**
     * Начало карты для выбора координат
     */
    $('#regionModal', '#auth').bind('regionChanged', function(e, newRegionId){
        if( !marker )
            $('#locationRegion', $searchControl).val(newRegionId).change();
    });

    // Собственно карта:
    $('.locationMapSwitch').click(function(e){
        e.preventDefault();
            
        $(this).colorbox({inline: true,
                          href: $locationMap,
                          title: 'Выбор Вашего местонахождения',
                          fixed: true,
//                          transition: 'fade'
                          overlayClose: false,
                          scrolling: false,
                          onComplete: function(){
                              if(marker)
                                  map.setCenter(marker.getPosition());
                          }});

        // Виджет вып.списка регионов для быстрого перемещения по ним на карте:
        $('#locationRegion', $searchControl).change(function(){
            var $this = $(this),
                $optionSelected = $this.find('option:selected');

            if($optionSelected.val() > 0) {
                map.panTo(new google.maps.LatLng($optionSelected.data('center-lat'),
                                                 $optionSelected.data('center-lng')));
                map.setZoom($optionSelected.data('zoom-level'));
            }
        });

        // Виджет поисковой строки для поиска координат сообщения:
        $('#locationSearchSubmit', $searchControl).click(function(e){
            e.preventDefault();

            var addressEntered = $.trim($addressField.val());
            if($addressField.data('addressLastEntered') != addressEntered) {
                geocodeReverse(addressEntered);
                $addressField.data('addressLastEntered', addressEntered);
            }
        });

        // Див "сохранить положение на карте":
        $mapSaveLocation.click(function(e){
            e.preventDefault();
            $.colorbox.close();
        });

        // Местоположение карты по умолчанию:
        var mapInitZoom = 3,
            mapInitCenter = new google.maps.LatLng(64.25, 108.15);

        // Попытка геолокации текущего местоположения юзера по его IP:
        if(google && google.loader && google.loader.clientLocation) {
//                console.log(google.loader.ClientLocation);
//                mapInitZoom = ;
//                mapInitCenter = ;
        }

        if(map) // Повторно карту не инициализировать
            return;

        map = new google.maps.Map($('#locationMapCanvas', $locationMap).get(0), {
            zoom: mapInitZoom,
            noClear: true,
            overviewMapControl: false,
            scaleControl: true,
            zoomControl: true,
            zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE},
            panControl: true,
            rotateControl: false,
            streetViewControl: false,
            minZoom: 3,
            maxZoom: 20,
            center: mapInitCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        google.maps.event.addListener(map, 'tilesloaded', function(){
            google.maps.event.trigger(map, 'resize');
            if( !marker && typeof userRegionCookie() != 'undefined' && !$locationMap.data('mapCentered') ) {
                $locationMap.data('mapCentered', true);
                $('#locationRegion', $searchControl).val(userRegionCookie()).change();
            }
        });

        google.maps.event.addListener(map, 'click', function(event){
            createLocationMarker(event.latLng.lat(), event.latLng.lng(), true);
        });

        geocoder = new google.maps.Geocoder(); // Объект для геолокации гугла
//        }
    });

    // Восстановить поля адреса, если поля координат при загрузке страницы уже заполнены
    // (напр., если координаты были выбраны, но на странице формы нажали "назад", а затем вернулись):
//    if($('#locationLatField', $form).val() && $('#locationLngField', $form).val())
//        createLocationMarker($('#locationLatField', $form).val(), $('#locationLngField', $form).val());
    /**
     * Конец карты для выбора координат
     */   
});
