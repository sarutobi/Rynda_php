/**
 * **************************
 * jQuery-плагины для дизайна
 * **************************
 */
/**
 * jCarouselLite - jQuery plugin to navigate images/any content in a carousel style widget.
 * @requires jQuery v1.2 or above
 *
 * http://gmarwaha.com/jquery/jcarousellite/
 *
 * Copyright (c) 2007 Ganeshji Marwaha (gmarwaha.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 * Version: 1.0.1
 * Note: Requires jquery 1.2 or above from version 1.0.1
 */
(function($){$.fn.jCarouselLite=function(o){o=$.extend({btnPrev:null,btnNext:null,btnGo:null,mouseWheel:false,auto:null,speed:200,easing:null,vertical:false,circular:true,visible:3,start:0,scroll:1,beforeStart:null,afterEnd:null},o||{});return this.each(function(){var b=false,animCss=o.vertical?"top":"left",sizeCss=o.vertical?"height":"width";var c=$(this),ul=$("ul",c),tLi=$("li",ul),tl=tLi.size(),v=o.visible;if(o.circular){ul.prepend(tLi.slice(tl-v-1+1).clone()).append(tLi.slice(0,v).clone());o.start+=v}var f=$("li",ul),itemLength=f.size(),curr=o.start;c.css("visibility","visible");f.css({overflow:"hidden",float:o.vertical?"none":"left"});ul.css({margin:"0",padding:"0",position:"relative","list-style-type":"none","z-index":"1"});c.css({overflow:"hidden",position:"relative","z-index":"2",left:"0px"});var g=o.vertical?height(f):width(f);var h=g*itemLength;var j=g*v;f.css({width:f.width(),height:f.height()});ul.css(sizeCss,h+"px").css(animCss,-(curr*g));c.css(sizeCss,j+"px");if(o.btnPrev)$(o.btnPrev).click(function(){return go(curr-o.scroll)});if(o.btnNext)$(o.btnNext).click(function(){return go(curr+o.scroll)});if(o.btnGo)$.each(o.btnGo,function(i,a){$(a).click(function(){return go(o.circular?o.visible+i:i)})});if(o.mouseWheel&&c.mousewheel)c.mousewheel(function(e,d){return d>0?go(curr-o.scroll):go(curr+o.scroll)});if(o.auto)setInterval(function(){go(curr+o.scroll)},o.auto+o.speed);function vis(){return f.slice(curr).slice(0,v)};function go(a){if(!b){if(o.beforeStart)o.beforeStart.call(this,vis());if(o.circular){if(a<=o.start-v-1){ul.css(animCss,-((itemLength-(v*2))*g)+"px");curr=a==o.start-v-1?itemLength-(v*2)-1:itemLength-(v*2)-o.scroll}else if(a>=itemLength-v+1){ul.css(animCss,-((v)*g)+"px");curr=a==itemLength-v+1?v+1:v+o.scroll}else curr=a}else{if(a<0||a>itemLength-v)return;else curr=a}b=true;ul.animate(animCss=="left"?{left:-(curr*g)}:{top:-(curr*g)},o.speed,o.easing,function(){if(o.afterEnd)o.afterEnd.call(this,vis());b=false});if(!o.circular){$(o.btnPrev+","+o.btnNext).removeClass("disabled");$((curr-o.scroll<0&&o.btnPrev)||(curr+o.scroll>itemLength-v&&o.btnNext)||[]).addClass("disabled")}}return false}})};function css(a,b){return parseInt($.css(a[0],b))||0};function width(a){return a[0].offsetWidth+css(a,'marginLeft')+css(a,'marginRight')};function height(a){return a[0].offsetHeight+css(a,'marginTop')+css(a,'marginBottom')}})(jQuery);

/**
 * ******************************
 * jQuery-плагины для функционала
 * ******************************
 */
/**
 * Плагин Marker Clusterer для Google Maps API v.3
 */
(function(){var d=true,f=null,i=false;function j(a){return function(b){this[a]=b}}function k(a){return function(){return this[a]}}var l;
function m(a,b,c){this.extend(m,google.maps.OverlayView);this.b=a;this.a=[];this.f=[];this.ca=[53,56,66,78,90];this.j=[];this.A=i;c=c||{};this.g=c.gridSize||60;this.l=c.minimumClusterSize||2;this.Y=c.maxZoom||f;this.j=c.styles||[];this.X=c.imagePath||this.Q;this.W=c.imageExtension||this.P;this.O=d;if(c.zoomOnClick!=undefined)this.O=c.zoomOnClick;this.r=i;if(c.averageCenter!=undefined)this.r=c.averageCenter;n(this);this.setMap(a);this.J=this.b.getZoom();var e=this;google.maps.event.addListener(this.b,
"zoom_changed",function(){var g=e.b.mapTypes[e.b.getMapTypeId()].maxZoom,o=e.b.getZoom();if(!(o<0||o>g))if(e.J!=o){e.J=e.b.getZoom();e.m()}});google.maps.event.addListener(this.b,"idle",function(){e.i()});b&&b.length&&this.C(b,i)}l=m.prototype;l.Q="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m";l.P="png";l.extend=function(a,b){return function(c){for(var e in c.prototype)this.prototype[e]=c.prototype[e];return this}.apply(a,[b])};
l.onAdd=function(){if(!this.A){this.A=d;q(this)}};l.draw=function(){};function n(a){if(!a.j.length)for(var b=0,c;c=a.ca[b];b++)a.j.push({url:a.X+(b+1)+"."+a.W,height:c,width:c})}l.S=function(){for(var a=this.o(),b=new google.maps.LatLngBounds,c=0,e;e=a[c];c++)b.extend(e.getPosition());this.b.fitBounds(b)};l.z=k("j");l.o=k("a");l.V=function(){return this.a.length};l.I=function(){return this.Y||this.b.mapTypes[this.b.getMapTypeId()].maxZoom};
l.G=function(a,b){for(var c=0,e=a.length,g=e;g!==0;){g=parseInt(g/10,10);c++}c=Math.min(c,b);return{text:e,index:c}};l.aa=j("G");l.H=k("G");l.C=function(a,b){for(var c=0,e;e=a[c];c++)t(this,e);b||this.i()};function t(a,b){b.s=i;b.draggable&&google.maps.event.addListener(b,"dragend",function(){b.s=i;a.K()});a.a.push(b)}l.q=function(a,b){t(this,a);b||this.i()};
function u(a,b){var c=-1;if(a.a.indexOf)c=a.a.indexOf(b);else for(var e=0,g;g=a.a[e];e++)if(g==b){c=e;break}if(c==-1)return i;a.a.splice(c,1);return d}l.Z=function(a,b){var c=u(this,a);if(!b&&c){this.m();this.i();return d}else return i};l.$=function(a,b){for(var c=i,e=0,g;g=a[e];e++){g=u(this,g);c=c||g}if(!b&&c){this.m();this.i();return d}};l.U=function(){return this.f.length};l.getMap=k("b");l.setMap=j("b");l.w=k("g");l.ba=j("g");
l.v=function(a){var b=this.getProjection(),c=new google.maps.LatLng(a.getNorthEast().lat(),a.getNorthEast().lng()),e=new google.maps.LatLng(a.getSouthWest().lat(),a.getSouthWest().lng());c=b.fromLatLngToDivPixel(c);c.x+=this.g;c.y-=this.g;e=b.fromLatLngToDivPixel(e);e.x-=this.g;e.y+=this.g;c=b.fromDivPixelToLatLng(c);b=b.fromDivPixelToLatLng(e);a.extend(c);a.extend(b);return a};l.R=function(){this.m(d);this.a=[]};
l.m=function(a){for(var b=0,c;c=this.f[b];b++)c.remove();for(b=0;c=this.a[b];b++){c.s=i;a&&c.setMap(f)}this.f=[]};l.K=function(){var a=this.f.slice();this.f.length=0;this.m();this.i();window.setTimeout(function(){for(var b=0,c;c=a[b];b++)c.remove()},0)};l.i=function(){q(this)};
function q(a){if(a.A)for(var b=a.v(new google.maps.LatLngBounds(a.b.getBounds().getSouthWest(),a.b.getBounds().getNorthEast())),c=0,e;e=a.a[c];c++)if(!e.s&&b.contains(e.getPosition())){var g=a;e=e;for(var o=4E4,r=f,x=0,p=void 0;p=g.f[x];x++){var h=p.getCenter();if(h){h=h;var s=e.getPosition();if(!h||!s)h=0;else{var y=(s.lat()-h.lat())*Math.PI/180,z=(s.lng()-h.lng())*Math.PI/180;h=Math.sin(y/2)*Math.sin(y/2)+Math.cos(h.lat()*Math.PI/180)*Math.cos(s.lat()*Math.PI/180)*Math.sin(z/2)*Math.sin(z/2);h=
6371*2*Math.atan2(Math.sqrt(h),Math.sqrt(1-h))}if(h<o){o=h;r=p}}}if(r&&r.F.contains(e.getPosition()))r.q(e);else{p=new v(g);p.q(e);g.f.push(p)}}}function v(a){this.k=a;this.b=a.getMap();this.g=a.w();this.l=a.l;this.r=a.r;this.d=f;this.a=[];this.F=f;this.n=new w(this,a.z(),a.w())}l=v.prototype;
l.q=function(a){var b;a:if(this.a.indexOf)b=this.a.indexOf(a)!=-1;else{b=0;for(var c;c=this.a[b];b++)if(c==a){b=d;break a}b=i}if(b)return i;if(this.d){if(this.r){c=this.a.length+1;b=(this.d.lat()*(c-1)+a.getPosition().lat())/c;c=(this.d.lng()*(c-1)+a.getPosition().lng())/c;this.d=new google.maps.LatLng(b,c);A(this)}}else{this.d=a.getPosition();A(this)}a.s=d;this.a.push(a);b=this.a.length;b<this.l&&a.getMap()!=this.b&&a.setMap(this.b);if(b==this.l)for(c=0;c<b;c++)this.a[c].setMap(f);b>=this.l&&a.setMap(f);
if(this.b.getZoom()>this.k.I())for(a=0;b=this.a[a];a++)b.setMap(this.b);else if(this.a.length<this.l)B(this.n);else{b=this.k.H()(this.a,this.k.z().length);this.n.setCenter(this.d);a=this.n;a.B=b;a.fa=b.text;a.da=b.index;if(a.c)a.c.innerHTML=b.text;b=Math.max(0,a.B.index-1);b=Math.min(a.j.length-1,b);b=a.j[b];a.N=b.url;a.h=b.height;a.p=b.width;a.L=b.textColor;a.e=b.anchor;a.M=b.textSize;a.D=b.backgroundPosition;this.n.show()}return d};
l.getBounds=function(){for(var a=new google.maps.LatLngBounds(this.d,this.d),b=this.o(),c=0,e;e=b[c];c++)a.extend(e.getPosition());return a};l.remove=function(){this.n.remove();this.a.length=0;delete this.a};l.T=function(){return this.a.length};l.o=k("a");l.getCenter=k("d");function A(a){a.F=a.k.v(new google.maps.LatLngBounds(a.d,a.d))}l.getMap=k("b");
function w(a,b,c){a.k.extend(w,google.maps.OverlayView);this.j=b;this.ea=c||0;this.u=a;this.d=f;this.b=a.getMap();this.B=this.c=f;this.t=i;this.setMap(this.b)}l=w.prototype;
l.onAdd=function(){this.c=document.createElement("DIV");if(this.t){this.c.style.cssText=C(this,D(this,this.d));this.c.innerHTML=this.B.text}this.getPanes().overlayMouseTarget.appendChild(this.c);var a=this;google.maps.event.addDomListener(this.c,"click",function(){var b=a.u.k;google.maps.event.trigger(b,"clusterclick",a.u);b.O&&a.b.fitBounds(a.u.getBounds())})};function D(a,b){var c=a.getProjection().fromLatLngToDivPixel(b);c.x-=parseInt(a.p/2,10);c.y-=parseInt(a.h/2,10);return c}
l.draw=function(){if(this.t){var a=D(this,this.d);this.c.style.top=a.y+"px";this.c.style.left=a.x+"px"}};function B(a){if(a.c)a.c.style.display="none";a.t=i}l.show=function(){if(this.c){this.c.style.cssText=C(this,D(this,this.d));this.c.style.display=""}this.t=d};l.remove=function(){this.setMap(f)};l.onRemove=function(){if(this.c&&this.c.parentNode){B(this);this.c.parentNode.removeChild(this.c);this.c=f}};l.setCenter=j("d");
function C(a,b){var c=[];if(document.all)c.push('filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="'+a.N+'");');else{c.push("background-image:url("+a.N+");");c.push("background-position:"+(a.D?a.D:"0 0")+";")}if(typeof a.e==="object"){typeof a.e[0]==="number"&&a.e[0]>0&&a.e[0]<a.h?c.push("height:"+(a.h-a.e[0])+"px; padding-top:"+a.e[0]+"px;"):c.push("height:"+a.h+"px; line-height:"+a.h+"px;");typeof a.e[1]==="number"&&a.e[1]>0&&a.e[1]<a.p?c.push("width:"+(a.p-a.e[1])+
"px; padding-left:"+a.e[1]+"px;"):c.push("width:"+a.p+"px; text-align:center;")}else c.push("height:"+a.h+"px; line-height:"+a.h+"px; width:"+a.p+"px; text-align:center;");c.push("cursor:pointer; top:"+b.y+"px; left:"+b.x+"px; color:"+(a.L?a.L:"black")+"; position:absolute; font-size:"+(a.M?a.M:11)+"px; font-family:Arial,sans-serif; font-weight:bold");return c.join("")}window.MarkerClusterer=m;m.prototype.addMarker=m.prototype.q;m.prototype.addMarkers=m.prototype.C;m.prototype.clearMarkers=m.prototype.R;
m.prototype.fitMapToMarkers=m.prototype.S;m.prototype.getCalculator=m.prototype.H;m.prototype.getGridSize=m.prototype.w;m.prototype.getExtendedBounds=m.prototype.v;m.prototype.getMap=m.prototype.getMap;m.prototype.getMarkers=m.prototype.o;m.prototype.getMaxZoom=m.prototype.I;m.prototype.getStyles=m.prototype.z;m.prototype.getTotalClusters=m.prototype.U;m.prototype.getTotalMarkers=m.prototype.V;m.prototype.redraw=m.prototype.i;m.prototype.removeMarker=m.prototype.Z;m.prototype.removeMarkers=m.prototype.$;
m.prototype.resetViewport=m.prototype.m;m.prototype.repaint=m.prototype.K;m.prototype.setCalculator=m.prototype.aa;m.prototype.setGridSize=m.prototype.ba;m.prototype.onAdd=m.prototype.onAdd;m.prototype.draw=m.prototype.draw;v.prototype.getCenter=v.prototype.getCenter;v.prototype.getSize=v.prototype.T;v.prototype.getMarkers=v.prototype.o;w.prototype.onAdd=w.prototype.onAdd;w.prototype.draw=w.prototype.draw;w.prototype.onRemove=w.prototype.onRemove;
})();

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

//if( !$.tmpl )
//    document.write('<'+'script src="/javascript/lib/jQueryTemplates.js" type="text/javascript"><'+'/script>');
//if( !google.maps )
//    document.write('<'+'script src="/javascript/lib/googleMapsApi.js" type="text/javascript"><'+'/script>');

var map, // Объект карты
    markersMessages = [], // Массив объектов маркеров сообщений
    markersOrg = [], // Массив объектов маркеров организаций
    infoWindow = new google.maps.InfoWindow({maxWidth: 300}), // Всплывающее окно для маркеров/кластеров
    markerClusterer, // Объект менеджера кластеров
    $filterForm = $('#mapFilterForm'),
    $markerTypesForm = $('#markerTypesForm'),
    $regionFilterField = $('#searchRegion', $filterForm),
    $ajaxLoading = $('img#mapResponseLoading'),
    $message = $('#mapResponseMessage'),
    mapCenterLatLng = '';

/**
 * Приближение карты к координатам. Для обработки кнопок "приблизить" во всплывающих окнах.
 *
 * @param lat float Широта точки, к которой приближается карта.
 * @param lng float Долгота точки, к которой приближается карта.
 * @return boolean True в случае успеха, иначе false.
 */
function zoomTo(lat, lng)
{
    if( !lat || !lng )
        return false;
    map.panTo(new google.maps.LatLng(parseFloat(lat), parseFloat(lng)));
    map.setZoom(map.getZoom()+1);
    return true;
}

/**
 * Удаление с карты всех маркеров сообщений (вкл. кластеризованные).
 * 
 * Выполняется, например, при запросе новых маркеров перед их выводом на карту.
 */
function clearMarkersMessages()
{
    $.each(markersMessages, function(messageTypeId, markers){
        if(markers) {
            for(var i=0; i<markers.length; i++) {
                markers[i].setMap(null);
            }
            markerClusterer.removeMarkers(markers)
        }
    });

    markersMessages = [];
}

/**
 * Удаление с карты всех маркеров организаций (вкл. кластеризованные).
 * 
 * Выполняется, например, при запросе новых маркеров перед их выводом на карту.
 */
function clearMarkersOrg()
{
    $.each(markersOrg, function(orgTypeId, markers){
        if(markers) {
            for(var i=0; i<markers.length; i++) {
                markers[i].setMap(null);
            }
            markerClusterer.removeMarkers(markers)
        }
    });

    markersOrg = [];
}

/**
 * Форматирование текстовой строки с учётом максимально допустимой длины. Если она
 * превышена, добавляется троеточие.
 *
 * @param string string Строка текста для форматирования.
 * @param max_length integer Максмально допустимая длина текста (в символах). Если она
 * превышена, текст укорачивается до неё и к нему приписывается троеточие. По умолчанию 30.
 * @param trim_by_words boolean Если передано true, строка будет укорачиваться с точностью
 * до 1 слова, иначе - с точностью до 1 символа. По умолчанию true.
 * @return string Отформатированная строка.
 */
function jsFormatTextTrimmed(string, max_length, trim_by_words)
{
    if( !string )
        return;
    else {
        string = $.trim(string).replace(/<.*?>/gi, '');
        if(string.length <= 0)
            return;
    }

    if( !max_length )
        max_length = 30;
    else
        max_length = parseInt(max_length) > 0 ? parseInt(max_length) : 30;

    if( !trim_by_words )
        trim_by_words = true;

    var stringRes = '';
    if(string.length <= max_length)
        stringRes = string;
    else if(trim_by_words) {
        var words = string.split(' ');
        for(var i=0; i<words.length; i++) {
            if((stringRes+' '+words[i]).length > max_length)
                break;
            stringRes += ' '+words[i];
        }
        stringRes += '…';
    } else
        stringRes = string.substr(0, max_length)+'…';

    return stringRes;
}

function formatDate(date)
{
     date = new Date(1000*date);

     var dateStr = '',
         tmp;

     tmp = date.getDate();
     dateStr += (tmp < 10 ? '0'+tmp : tmp)+'.';

     tmp = date.getMonth()+1;
     dateStr += (tmp < 10 ? '0'+tmp : tmp)+'.';

     dateStr += date.getFullYear()+' ';

     tmp = date.getHours();
     dateStr += (tmp < 10 ? '0'+tmp : tmp)+':';

     tmp = date.getMinutes();
     dateStr += tmp < 10 ? '0'+tmp : tmp;

     return dateStr;
 }

function getMonthDay(date)
{
    date = new Date(1000*date);
    var dateStr = '',
        tmp;

    tmp = date.getDate();
    dateStr += (tmp < 10 ? '0'+tmp : tmp)+'.';

    tmp = date.getMonth()+1;
    dateStr += (tmp < 10 ? '0'+tmp : tmp);

    return dateStr;
}

function getYear(date)
{
    date = new Date(1000*date);

    return date.getFullYear();
}

/**
 * Включение/выключение форм фильтра и типов маркеров.
 */
function formsEnabled(isEnabled)
{
    $([$filterForm, $markerTypesForm]).each(function(){
        if( !!isEnabled ) {
            $(':input', this).removeAttr('disabled')
                             .animate({opacity: 1.0});
        } else {
            $(':input', this).attr('disabled', 'disabled')
                             .animate({opacity: 0.25});
        }
    });
}

/**
 * Обновление блока "просьбы о помощи".
 */
function refreshRequestsList()
{
    var dfd = $.Deferred(),
        data = {persist: {typeSlug: CONST_MESSAGE_TYPE_REQUEST, limit: 5},
                category: $('#searchCategory', $filterForm).val(),
                dateAddedFrom: $('#searchDateAddedFrom', $filterForm).val(),
                regionId: $regionFilterField.val(),
                untimed: $('#searchUntimed', $filterForm).val()};
    
    $.ajax('/ajax_forms/ajaxGetMessages', {data: data,
                                           type: 'post',
                                           async: true,
                                           dataType: 'json',
                                           cache: false}) /** * @todo После релиза протестировать эффективность кэширования этого запроса. */
     .success(function(resp){
         $('#requestsList')
             .fadeOut(500, function(){
                 var $this = $(this);
                 $('#requestsNotFound').hide();
                 if(resp.data.length) {
                     $this.html($.tmpl('messagesBlockTmpl',
                                       resp.data.slice(0, 5),
                                       {getMonthDay: getMonthDay,
                                        getYear: getYear,
                                        formatTextTrimmed: jsFormatTextTrimmed}));
                     if(resp.data.length >= 5)
                         $('#requestsMore').show();
                 } else {
                     $this.empty();
                     $('#requestsNotFound').show();
                 }
             })
             .slideDown(500, dfd.resolve);
     });
    /** * @todo Сделать обработку ситуации, когда ajax-запрос выполнился с ошибкой! */

    return dfd.promise();
}

/**
 * Обновление блока "предложения помощи".
 */
function refreshOffersList()
{
    var dfd = $.Deferred(),
        data = {persist: {typeSlug: CONST_MESSAGE_TYPE_OFFER, limit: 5},
                category: $('#searchCategory', $filterForm).val(),
                dateAddedFrom: $('#searchDateAddedFrom', $filterForm).val(),
                regionId: $regionFilterField.val(),
                untimed: $('#searchUntimed', $filterForm).val()};

    $.ajax('/ajax_forms/ajaxGetMessages', {data: data,
                                           type: 'post',
                                           async: true,
                                           dataType: 'json',
                                           cache: false}) /** * @todo После релиза протестировать эффективность кэширования этого запроса. */
     .success(function(resp){
         $('#offersList')
             .fadeOut(500, function(){
                 var $this = $(this);
                 $('#offersNotFound').hide();
                 if(resp.data.length) {
                     $this.html($.tmpl('messagesBlockTmpl',
                                       resp.data.slice(0, 5),
                                       {getMonthDay: getMonthDay,
                                        getYear: getYear,
                                        formatTextTrimmed: jsFormatTextTrimmed}));
                     if(resp.data.length >= 5)
                         $('#offersMore').show();
                 } else {
                     $this.empty();
                     $('#offersNotFound').show();
                 }
             })
             .slideDown(500, dfd.resolve);
     });
     /** * @todo Сделать обработку ситуации, когда ajax-запрос выполнился с ошибкой! */
    
    return dfd.promise();
}

/**
 * Обновление блока "информационные сообщения".
 */
function refreshInfoList()
{
    var dfd = $.Deferred(),
        data = {persist: {typeSlug: CONST_MESSAGE_TYPE_INFO, limit: 5},
                category: $('#searchCategory', $filterForm).val(),
                dateAddedFrom: $('#searchDateAddedFrom', $filterForm).val(),
                regionId: $regionFilterField.val(),
                untimed: $('#searchUntimed', $filterForm).val()};

    $.ajax('/ajax_forms/ajaxGetMessages', {data: data,
                                           type: 'post',
                                           async: true,
                                           dataType: 'json',
                                           cache: false}) /** * @todo После релиза протестировать эффективность кэширования этого запроса. */
     .success(function(resp){
         $('#infoList')
             .fadeOut(500, function(){
                 var $this = $(this);
                 $('#infoNotFound').hide();
                 if(resp.data.length) {
                     $this.html($.tmpl('messagesBlockTmpl',
                                       resp.data.slice(0, 5),
                                       {getMonthDay: getMonthDay,
                                        getYear: getYear,
                                        formatTextTrimmed: jsFormatTextTrimmed}));
                     if(resp.data.length >= 5)
                         $('#infoMore').show();
                 } else {
                     $this.empty();
                     $('#infoNotFound').show();
                 }
             })
             .slideDown(500, dfd.resolve);
     });
     /** * @todo Сделать обработку ситуации, когда ajax-запрос выполнился с ошибкой! */
    
    return dfd.promise();
}

/**
 * Обновление блока "оказанная помощь".
 */
function refreshHelpedList()
{
    var dfd = $.Deferred(),
        data = {persist: {typeSlug: [CONST_MESSAGE_TYPE_REQUEST, CONST_MESSAGE_TYPE_OFFER],
                          statusId: [CONST_MESSAGE_STATUS_REACTED, CONST_MESSAGE_STATUS_CLOSED],
                          limit: 5},
                category: $('#searchCategory', $filterForm).val(),
                dateModifiedFrom: $('#searchDateAddedFrom', $filterForm).val(),
                regionId: $regionFilterField.val(),
                untimed: $('#searchUntimed', $filterForm).val()};

    $.ajax('/ajax_forms/ajaxGetMessages', {data: data,
                                           type: 'post',
                                           async: true,
                                           dataType: 'json',
                                           cache: false}) /** * @todo После релиза протестировать эффективность кэширования этого запроса. */
     .success(function(resp){
         $('#helpedList')
             .fadeOut(500, function(){
                 var $this = $(this);
                 $('#helpedNotFound').hide();
                 if(resp.data.length) {
                     $this.html($.tmpl('messagesBlockTmpl',
                                       resp.data.slice(0, 5),
                                       {getMonthDay: getMonthDay,
                                        getYear: getYear,
                                        formatTextTrimmed: jsFormatTextTrimmed}));
                     if(resp.data.length >= 5)
                         $('#helpedMore').show();
                 } else {
                     $this.empty();
                     $('#helpedNotFound').show();
                 }
             })
             .slideDown(500, dfd.resolve);
     });
     /** * @todo Сделать обработку ситуации, когда ajax-запрос выполнился с ошибкой! */

    return dfd.promise();
}

/**
 * Выравнивание высоты 2-х элементов DOM. Используется для блоков-списков под главной картой.
 * 
 * @param $elements object Массив блоков, высоту которых требуется выравнять.
 */
function equalizeElementsHeight(elements)
{
    if(Object.prototype.toString.apply(elements) != '[object Array]' || elements.length <= 0)
        return;
    
    var totalHeight = 0;
    for(var i=0; i<elements.length; i++) {
        var currentHeight = 0,
            $children = $(elements[i]).children(':visible');

        if($children.length) {
            $children.each(function(){
                currentHeight += $(this).height();
            });
    //        currentHeight = $(elements[i]).height();
        } else {
            currentHeight = $(elements[i]).height();
        }

        if(currentHeight > totalHeight)
            totalHeight = currentHeight;
    }
    
    for(var i=0; i<elements.length; i++) {$(elements[i]).css('height', totalHeight);}
}

/**
 * Обновление контента блоков данных под картой.
 */
function refreshInfoBlocks()
{
    // Обновление блоков верхней строки:
    $.when(refreshHelpedList(), refreshRequestsList(), refreshOffersList())
     .then(function(){
         equalizeElementsHeight([$('#mp4'), $('#mp1'), $('#mp2')]);
     });

    // Обновление блоков нижней строки:
    $.when(refreshInfoList())
     .then(function(){
         equalizeElementsHeight([$('#mp5'), $('#mp6'), $('#mp7')]);
     });
}

/**
 * Обновление маркеров сообщений на карте.
 */
function refreshMessages(useIndicator)
{
    var messageTypes = [];
    
    useIndicator = !!useIndicator;

    if( !useIndicator ) {
        var dfd = new $.Deferred();
    }

    $('input[type="checkbox"][name="messageTypeId[]"]:checked', $markerTypesForm).each(function(){
        messageTypes.push($(this).val());
    });

    if(messageTypes.length) {
        if(useIndicator) {
            $ajaxLoading.show();
            formsEnabled(false);
        }

        $message.fadeOut(200);

        $.ajax('/ajax_forms/ajaxGetMessages',
               {data: {category: $('#searchCategory', $filterForm).val(),
                       dateAddedFrom: $('#searchDateAddedFrom', $filterForm).val(),
                       regionId: $('#searchRegion', $filterForm).val(),
                       untimed: $('#searchUntimed', $filterForm).val(),
                       typeId: messageTypes},
                type: 'post',
                async: true,
                dataType: 'json',
                /** * @todo Протестировать эффективность кэширования: */
                cache: false})
         .success(function(resp){
             if(resp.status && resp.status == 'success') {
                 clearMarkersMessages(); // Удаление с карты всех старых маркеров

//                 if(resp.data.length == 0)
//                     $message.html('<div class="successMessage">Сообщений не найдено</div>')
//                             .stop(true).fadeIn(200);
//                 else
//                     $message.stop(true).fadeOut(200, function(){
//                         $(this).html('');
//                     });

                 // Обновление маркеров на карте:
                 for(var i=0; i<resp.data.length; i++) {
                     /**
                      * @todo Сделать иконки полем типа в базе, если будет целесообразно.
                      */
                     var markerIcon = '';
                     switch(resp.data[i].typeSlug) {
                         case CONST_MESSAGE_TYPE_REQUEST:
                             markerIcon = '/images/nh_icon.png';
                             break;
                         case CONST_MESSAGE_TYPE_OFFER:
                             markerIcon = '/images/wh_icon.png';
                             break;
                         default:
                             markerIcon = '';
                     }

                     var marker = new google.maps.Marker({
                         position: new google.maps.LatLng(resp.data[i].lat, resp.data[i].lng),
                         map: map,
                         draggable: false,
//                         animation: google.maps.Animation.DROP,
                         title: (resp.data[i].title ? resp.data[i].title : '')
                     });

                     marker.setValues({'content': $.tmpl('markerTmpl',
                                                         resp.data[i],
                                                         {getMonthDay: getMonthDay,
                                                          getYear: getYear,
                                                          formatDate: formatDate,
                                                          getFirstLetter: function(text){
                                                              text = $.trim(text);
                                                              return text.length ? text.substring(0, 1)+'.' : '';
                                                          },
                                                          formatTextTrimmed: jsFormatTextTrimmed}).html(),
                                       'icon': markerIcon,
                                       // Для контента всплыв. окон кластеров нужны параметры маркеров:
                                       'id': resp.data[i].id,
                                       'type': resp.data[i].typeSlug,
                                       'title': resp.data[i].title,
                                       'text': resp.data[i].text,
                                       'isPublic': resp.data[i].isPublic,
                                       'firstName': resp.data[i].firstName ? resp.data[i].firstName : '',
                                       'lastName': resp.data[i].lastName ? resp.data[i].lastName : ''});
                     google.maps.event.addListener(marker, 'click', function(){
                         infoWindow.set('isOnCluster', false);
                         infoWindow.setContent(this.get('content'));
                         infoWindow.open(map, this);
                     });

                     if( !markersMessages[resp.data[i].typeId] )
                         markersMessages[resp.data[i].typeId] = [];
                     markersMessages[resp.data[i].typeId].push(marker);
                 } // Обновление маркеров завершено

                 // Обновление кластеров на карте:
                 $.each(markersMessages, function(messageTypeId, markers){
                     if(markers)
                        markerClusterer.addMarkers(markers);
                 });
                 markerClusterer.redraw();
             } else {
                 if(resp.status && resp.status == 'error') {
                     $message
                        .html('<div class="errorMessage">'+resp.message+'</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 } else {
                     $message
                        .html('<div class="errorMessage">Ошибка при обработке!</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 }
             }
             
             if(useIndicator) {
                 formsEnabled(true);
                 $ajaxLoading.hide();
             } else
                dfd.resolve();
         })
        .error(function(jqXhr){
             if(jqXhr.readyState == 4) {
                 $message.html('<div class="errorMessage">Ошибка при поиске сообщений!</div>')
                         .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
             }

             if(useIndicator) {
                 formsEnabled(true);
                 $ajaxLoading.hide();
             }
             else
                 dfd.resolve();
       });
    } else {
        if( !useIndicator )
            dfd.resolve();
    }
    
    return useIndicator ? true : dfd.promise();
}

/**
 * Обновление маркеров организаций на карте.
 */
function refreshOrganizations(useIndicator)
{
    var orgTypes = [];
    
    useIndicator = !!useIndicator;

    if( !useIndicator ) {
        var dfd = new $.Deferred();
    }

    $('input[type="checkbox"][name="orgTypeId[]"]:checked', $markerTypesForm).each(function(){
        orgTypes.push($(this).val());
    });

    if(orgTypes.length) {
        if(useIndicator) {
            $ajaxLoading.show();
            formsEnabled(false);
        }

        $message.fadeOut(200);

        $.ajax('/ajax_forms/ajaxGetOrganizations',
               {data: {category: $('#searchCategory', $filterForm).val(),
                       regionId: $('#searchRegion', $filterForm).val(),
                       typeId: orgTypes},
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false}) /** * @todo Протестировать эффективность кэширования */
         .success(function(resp){
             if(resp.status && resp.status == 'success') {
                 clearMarkersOrg();
                 
                 // Обновление маркеров организаций на карте:
                 for(var i=0; i<resp.data.length; i++) {
                     var marker = new google.maps.Marker({
                         position: new google.maps.LatLng(resp.data[i].lat, resp.data[i].lng),
                         map: map,
                         draggable: false,
                         animation: google.maps.Animation.DROP,
                         title: (resp.data[i].title ? resp.data[i].title : '')
                     });

                     marker.setValues({'content': $.tmpl('markerOrgTmpl',
                                                         resp.data[i],
                                                         {getMonthDay: getMonthDay,
                                                          getYear: getYear,
                                                          formatTextTrimmed: jsFormatTextTrimmed}).html(),
                                       // Для контента всплыв. окон кластеров нужны параметры маркеров:
                                       'id': resp.data[i].id,
                                       'orgType': resp.data[i].typeName,
                                       'orgTypeId': resp.data[i].typeId,
                                       'icon': resp.data[i].typeIcon,
                                       'title': resp.data[i].title,
                                       'text': resp.data[i].text});
//                     console.log(resp.data[i]);
                     google.maps.event.addListener(marker, 'click', function(){
                         infoWindow.set('isOnCluster', false);
                         infoWindow.setContent(this.get('content'));
                         infoWindow.open(map, this);
                     });
                     
                     if( !markersOrg[resp.data[i].typeId] )
                         markersOrg[resp.data[i].typeId] = [];
                     markersOrg[resp.data[i].typeId].push(marker);
                 } // Обновление маркеров организаций завершено

                 // Обновление кластеров на карте:
                 $.each(markersOrg, function(orgTypeId, markers){
                     if(markers)
                        markerClusterer.addMarkers(markers);
                 });
                 markerClusterer.redraw();
             } else {
                 if(resp.status && resp.status == 'error') {
                     $message
                        .html('<div class="errorMessage">'+resp.message+'</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 } else {
                     $message
                        .html('<div class="errorMessage">Ошибка при обработке!</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 }
             }
             
             if(useIndicator) {
                 formsEnabled(true);
                 $ajaxLoading.hide();
             } else
                dfd.resolve();
         })
         .error(function(jqXHR, textStatus){
             $message.html('<div class="errorMessage">Ошибка при поиске организаций!</div>')
                     .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
             if(useIndicator) {
                 formsEnabled(true);
                 $ajaxLoading.hide();
             }
             else
                 dfd.resolve();
     });
    } else {
        if( !useIndicator )
            dfd.resolve();
    }

    return useIndicator ? true : dfd.promise();
}

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    $filterForm.submit();
}

$(document).delegate('#toggleMap', 'click', {}, function(e){
    e.preventDefault();

    var $this = $(this);
    if( !$this.data('map-shown') ) { // Map were hidden, show it
        $this.html('Скрыть карту');
        $this.data('map-shown', 1);
        $.cookie('ryndaorg_main_map_shown', 1);
        $('#mapWithoutFilterForm').show();
        google.maps.event.trigger(map, 'resize');

        var $optionSelected = $regionFilterField.find('option:selected');
        map.setCenter(new google.maps.LatLng($optionSelected.data('center-lat'),
                                                $optionSelected.data('center-lng')));
        map.setZoom($optionSelected.data('zoom-level'));
    } else { // Map were shown, hide it
        $this.html('Открыть карту');
        $this.data('map-shown', 0);
        $.cookie('ryndaorg_main_map_shown', 0);
        $('#mapWithoutFilterForm').hide();
    }
});

// Кнопка сброса полей формы на дефолтные значения:
$filterForm.delegate('#reset', 'click', {}, function(){
    $selectFields = $('select', $filterForm);
    $selectFields.find('option:selected').each(function(){
        $(this).removeAttr('selected');
    });
    $selectFields.find('option[value=""]').each(function(){
        $(this).attr('selected', 'selected');
    });

    map.panTo(mapCenterLatLng);
    $filterForm.submit();
});

// Перемещение карты к регионам при их изменении:
$regionFilterField.delegate('', 'change', {}, function(){
    var $optionSelected = $(this).find('option:selected');

    map.panTo(new google.maps.LatLng($optionSelected.data('center-lat'),
                                        $optionSelected.data('center-lng')));
    map.setZoom($optionSelected.data('zoom-level'));
//        refreshInfoBlocks();
});

$('#regionModal', '#auth').bind('regionChanged', function(e, newRegionId){
    $regionFilterField.val(newRegionId);
});

// Отправка запроса при изменении любого поля формы фильтра:
$filterForm.delegate(':input', 'change', {}, function(){
    $filterForm.submit();
});

$filterForm.delegate('', 'submit', {}, function(e){
    e.preventDefault();

    $ajaxLoading.show();
    formsEnabled(false);
    $.when(refreshMessages(), refreshOrganizations(), refreshInfoBlocks())
        .then(function(){
            $ajaxLoading.hide();
            formsEnabled(true);
        });
});

$(function(){ // Готовность DOM
    // Карта:
    var mapTypeIds = [];
    for(var type in google.maps.MapTypeId) {
        mapTypeIds.push(google.maps.MapTypeId[type]);
    }
    mapTypeIds.push('OSM');

    mapCenterLatLng = new google.maps.LatLng(CONST_MAP_DEFAULT_LAT, CONST_MAP_DEFAULT_LNG);
    map = new google.maps.Map($('#locationMapCanvas').get(0), {
        center: mapCenterLatLng,
        mapTypeId: 'OSM',
        mapTypeControlOptions: {mapTypeIds: mapTypeIds},
        minZoom: 3,
        maxZoom: 20,
        zoom: CONST_MAP_DEFAULT_ZOOM,
        zoomControl: true,
        zoomControlOptions: {style: google.maps.ZoomControlStyle.LARGE},
        noClear: true,
        overviewMapControl: false,
        scaleControl: true,
        panControl: true,
        rotateControl: false,
        streetViewControl: false
    });
    map.mapTypes.set('OSM', new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
            return 'http://tile.openstreetmap.org/'+zoom+'/'+coord.x+'/'+coord.y+'.png';
        },
        tileSize: new google.maps.Size(256, 256),
        name: 'OpenStreetMap',
        maxZoom: 20
    }));
    google.maps.event.addListener(map, 'zoom_changed', function(){
        if(infoWindow.get('isOnCluster')) {
            infoWindow.set('isOnCluster', false);
            infoWindow.close();
        }
    });
    markerClusterer = new MarkerClusterer(map, [],
                                          {gridSize: 32, maxZoom: 15, zoomOnClick: false,
                                           styles: [{width: 48,
                                                     height: 48,
                                                     url: '/images/cluster.png',
                                                     textColor: '#FFFFFF'} /*, // Иконка для кластера объёмом до 10 шт
                                                     {height: 53, url: '', width: 53}, // Иконка для кластера объёмом от 10 до 99 шт
                                                     {height: 53, url: '', width: 53}*/ ]}); // Иконка для кластера объёмом от 100 шт
    google.maps.event.addListener(markerClusterer, 'clusterclick', function(cluster){
        var center = cluster.getCenter();
        infoWindow.close();
        infoWindow.setContent($.tmpl('clusterTmpl',
                                     {markers: cluster.getMarkers(),
                                      lat: center.lat(),
                                      lng: center.lng()},
                                     {getFirstLetter: function(text){
                                          text = $.trim(text);
                                          return text.length ? text.substring(0, 1)+'.' : '';
                                      },
                                      formatTextTrimmed: jsFormatTextTrimmed}).html());
         infoWindow.setPosition(cluster.getCenter());
         infoWindow.set('isOnCluster', true);
         infoWindow.open(map);
     });

//        google.maps.event.addListener(map, 'tilesloaded', function(){
//            google.maps.event.trigger(map, 'resize');
//            if(typeof userRegionCookie() != 'undefined' && !$regionFilterField.data('mapCentered')) {
//                $regionFilterField.data('mapCentered', true).val(userRegionCookie()).change();
//            }
//        });

    // Userlist widget:
    $('#usersList_leftCol').jCarouselLite({visible: 5,
                                           auto: 10000,
                                           speed: 1000,
                                           btnNext: '',
                                           btnPrev: ''});

    // Компиляция jQuery-шаблонов для всплывающих сообщений на кластерах и маркерах:
    $('#mapMarkerPopupTmpl').template('markerTmpl');
    $('#mapClusterPopupTmpl').template('clusterTmpl');
    $('#mapMarkerOrgPopupTmpl').template('markerOrgTmpl');
    
    // Компиляция jQuery-шаблона для блоков под картой:
    $('#messagesBlockTmpl').template('messagesBlockTmpl');

    // Виджет формы для управления маркерами сообщений и организаций:
    $(':input', $markerTypesForm).change(function(e){
        e.preventDefault();

        var $this = $(this),
            typeId = $this.val();
        if($this.attr('name') == 'messageTypeId[]') {
            if($this.attr('checked')) { // Чекбокс отмечен - вывести маркеры сообщений
                if(markersMessages[typeId]) { // Маркеры выбранного типа уже загружены, вывод на карту
                    for(var i=0; i<markersMessages[typeId].length; i++) {
                        markersMessages[typeId][i].setMap(map);
                    }
                    markerClusterer.addMarkers(markersMessages[typeId]);
                    markerClusterer.redraw();
                } else
                    refreshMessages(true);
            } else { // Чекбокс снят - убрать все маркеры сообщений
                if( !markersMessages[typeId] )
                    markersMessages[typeId] = [];

                for(var i=0; i<markersMessages[typeId].length; i++) {
                    markersMessages[typeId][i].setMap(null);
                }
                markerClusterer.removeMarkers(markersMessages[typeId]);
                markerClusterer.redraw();
            }
        } else {
            if($this.attr('checked')) { // Чекбокс отмечен - вывести маркеры организаций
                if(markersOrg[typeId]) { // Маркеры выбранного типа уже загружены, вывод на карту
                    for(var i=0; i<markersOrg[typeId].length; i++) {
                        markersOrg[typeId][i].setMap(map);
                    }
                    markerClusterer.addMarkers(markersOrg[typeId]);
                    markerClusterer.redraw();
                } else
                    refreshOrganizations(true);
            } else { // Чекбокс снят - убрать все маркеры организаций
                if( !markersOrg[typeId] )
                    markersOrg[typeId] = [];

                for(var i=0; i<markersOrg[typeId].length; i++) {
                    markersOrg[typeId][i].setMap(null);
                }
                markerClusterer.removeMarkers(markersOrg[typeId]);
                markerClusterer.redraw();
            }
        }
    });
});