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
 * jQuery Cookie plugin
 *
 * Copyright (c) 2010 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */
jQuery.cookie=function(e,b,a){if(arguments.length>1&&String(b)!=="[object Object]"){a=jQuery.extend({},a);if(b===null||b===void 0)a.expires=-1;if(typeof a.expires==="number"){var d=a.expires,c=a.expires=new Date;c.setDate(c.getDate()+d)}b=String(b);return document.cookie=[encodeURIComponent(e),"=",a.raw?b:encodeURIComponent(b),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+a.domain:"",a.secure?"; secure":""].join("")}a=b||{};c=a.raw?function(a){return a}: decodeURIComponent;return(d=RegExp("(?:^|; )"+encodeURIComponent(e)+"=([^;]*)").exec(document.cookie))?c(d[1]):null};

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

var map, // Объект карты
    marker; // Объект маркера на карте, указывающего координаты местоположения организации

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

$(function(){ // Готовность DOM
    // Карта для показа координат сообщения:
    var markerCoords = new google.maps.LatLng( parseFloat($('#organizationLat').text()),
                                               parseFloat($('#organizationLng').text()) );
    map = new google.maps.Map($('#locationMapCanvas').get(0), {
        zoom: 7,
        center: markerCoords,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    new google.maps.Marker({
        position: markerCoords,
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP,
        title: 'Местоположение организации'
    });
});