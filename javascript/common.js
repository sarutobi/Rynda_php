/**
 * *******************************
 * jQuery-plugins for page' design
 * *******************************
 */

/**
 * ezMark (Minified) - A Simple Checkbox and Radio button Styling plugin.
 *
 * @copyright Abdullah Rubiyath <http://www.itsalif.info/>.
 */
(function($){$.fn.ezMark=function(options){options=options||{};var defaultOpt={checkboxCls:options.checkboxCls||'ez-checkbox',radioCls:options.radioCls||'ez-radio',checkedCls:options.checkedCls||'ez-checked',selectedCls:options.selectedCls||'ez-selected',hideCls:'ez-hide'};return this.each(function(){var $this=$(this);var wrapTag=$this.attr('type')=='checkbox'?'<div class="'+defaultOpt.checkboxCls+'">':'<div class="'+defaultOpt.radioCls+'">';if($this.attr('type')=='checkbox'){$this.addClass(defaultOpt.hideCls).wrap(wrapTag).change(function(){if($(this).is(':checked')){$(this).parent().addClass(defaultOpt.checkedCls);}
else{$(this).parent().removeClass(defaultOpt.checkedCls);}});if($this.is(':checked')){$this.parent().addClass(defaultOpt.checkedCls);}}
else if($this.attr('type')=='radio'){$this.addClass(defaultOpt.hideCls).wrap(wrapTag).change(function(){$('input[name="'+$(this).attr('name')+'"]').each(function(){if($(this).is(':checked')){$(this).parent().addClass(defaultOpt.selectedCls);}else{$(this).parent().removeClass(defaultOpt.selectedCls);}});});if($this.is(':checked')){$this.parent().addClass(defaultOpt.selectedCls);}}});}})(jQuery);

/**
 * **************************************
 * jQuery-plugins for the page' functions
 * **************************************
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
 * ColorBox v1.3.18 - a full featured, light-weight, customizable lightbox based on jQuery 1.3+
 * @copyright (c) 2011 Jack Moore - jack@colorpowered.com
 * Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
 */ 
(function(a,b,c){function Y(c,d,e){var g=b.createElement(c);return d&&(g.id=f+d),e&&(g.style.cssText=e),a(g)}function Z(a){var b=y.length,c=(Q+a)%b;return c<0?b+c:c}function $(a,b){return Math.round((/%/.test(a)?(b==="x"?z.width():z.height())/100:1)*parseInt(a,10))}function _(a){return K.photo||/\.(gif|png|jpe?g|bmp|ico)((#|\?).*)?$/i.test(a)}function ba(){var b;K=a.extend({},a.data(P,e));for(b in K)a.isFunction(K[b])&&b.slice(0,2)!=="on"&&(K[b]=K[b].call(P));K.rel=K.rel||P.rel||"nofollow",K.href=K.href||a(P).attr("href"),K.title=K.title||P.title,typeof K.href=="string"&&(K.href=a.trim(K.href))}function bb(b,c){a.event.trigger(b),c&&c.call(P)}function bc(){var a,b=f+"Slideshow_",c="click."+f,d,e,g;K.slideshow&&y[1]?(d=function(){F.text(K.slideshowStop).unbind(c).bind(j,function(){if(Q<y.length-1||K.loop)a=setTimeout(W.next,K.slideshowSpeed)}).bind(i,function(){clearTimeout(a)}).one(c+" "+k,e),r.removeClass(b+"off").addClass(b+"on"),a=setTimeout(W.next,K.slideshowSpeed)},e=function(){clearTimeout(a),F.text(K.slideshowStart).unbind([j,i,k,c].join(" ")).one(c,function(){W.next(),d()}),r.removeClass(b+"on").addClass(b+"off")},K.slideshowAuto?d():e()):r.removeClass(b+"off "+b+"on")}function bd(b){if(!U){P=b,ba(),y=a(P),Q=0,K.rel!=="nofollow"&&(y=a("."+g).filter(function(){var b=a.data(this,e).rel||this.rel;return b===K.rel}),Q=y.index(P),Q===-1&&(y=y.add(P),Q=y.length-1));if(!S){S=T=!0,r.show();if(K.returnFocus)try{P.blur(),a(P).one(l,function(){try{this.focus()}catch(a){}})}catch(c){}q.css({opacity:+K.opacity,cursor:K.overlayClose?"pointer":"auto"}).show(),K.w=$(K.initialWidth,"x"),K.h=$(K.initialHeight,"y"),W.position(),o&&z.bind("resize."+p+" scroll."+p,function(){q.css({width:z.width(),height:z.height(),top:z.scrollTop(),left:z.scrollLeft()})}).trigger("resize."+p),bb(h,K.onOpen),J.add(D).hide(),I.html(K.close).show()}W.load(!0)}}var d={transition:"elastic",speed:300,width:!1,initialWidth:"600",innerWidth:!1,maxWidth:!1,height:!1,initialHeight:"450",innerHeight:!1,maxHeight:!1,scalePhotos:!0,scrolling:!0,inline:!1,html:!1,iframe:!1,fastIframe:!0,photo:!1,href:!1,title:!1,rel:!1,opacity:.9,preloading:!0,current:"image {current} of {total}",previous:"previous",next:"next",close:"close",open:!1,returnFocus:!0,loop:!0,slideshow:!1,slideshowAuto:!0,slideshowSpeed:2500,slideshowStart:"start slideshow",slideshowStop:"stop slideshow",onOpen:!1,onLoad:!1,onComplete:!1,onCleanup:!1,onClosed:!1,overlayClose:!0,escKey:!0,arrowKey:!0,top:!1,bottom:!1,left:!1,right:!1,fixed:!1,data:undefined},e="colorbox",f="cbox",g=f+"Element",h=f+"_open",i=f+"_load",j=f+"_complete",k=f+"_cleanup",l=f+"_closed",m=f+"_purge",n=a.browser.msie&&!a.support.opacity,o=n&&a.browser.version<7,p=f+"_IE6",q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X="div";W=a.fn[e]=a[e]=function(b,c){var f=this;b=b||{},W.init();if(!f[0]){if(f.selector)return f;f=a("<a/>"),b.open=!0}return c&&(b.onComplete=c),f.each(function(){a.data(this,e,a.extend({},a.data(this,e)||d,b)),a(this).addClass(g)}),(a.isFunction(b.open)&&b.open.call(f)||b.open)&&bd(f[0]),f},W.init=function(){if(!r){if(!a("body")[0]){a(W.init);return}z=a(c),r=Y(X).attr({id:e,"class":n?f+(o?"IE6":"IE"):""}),q=Y(X,"Overlay",o?"position:absolute":"").hide(),s=Y(X,"Wrapper"),t=Y(X,"Content").append(A=Y(X,"LoadedContent","width:0; height:0; overflow:hidden"),C=Y(X,"LoadingOverlay").add(Y(X,"LoadingGraphic")),D=Y(X,"Title"),E=Y(X,"Current"),G=Y(X,"Next"),H=Y(X,"Previous"),F=Y(X,"Slideshow").bind(h,bc),I=Y(X,"Close")),s.append(Y(X).append(Y(X,"TopLeft"),u=Y(X,"TopCenter"),Y(X,"TopRight")),Y(X,!1,"clear:left").append(v=Y(X,"MiddleLeft"),t,w=Y(X,"MiddleRight")),Y(X,!1,"clear:left").append(Y(X,"BottomLeft"),x=Y(X,"BottomCenter"),Y(X,"BottomRight"))).find("div div").css({"float":"left"}),B=Y(X,!1,"position:absolute; width:9999px; visibility:hidden; display:none"),a("body").prepend(q,r.append(s,B)),L=u.height()+x.height()+t.outerHeight(!0)-t.height(),M=v.width()+w.width()+t.outerWidth(!0)-t.width(),N=A.outerHeight(!0),O=A.outerWidth(!0),r.css({"padding-bottom":L,"padding-right":M}).hide(),G.click(function(){W.next()}),H.click(function(){W.prev()}),I.click(function(){W.close()}),J=G.add(H).add(E).add(F),q.click(function(){K.overlayClose&&W.close()}),a(b).bind("keydown."+f,function(a){var b=a.keyCode;S&&K.escKey&&b===27&&(a.preventDefault(),W.close()),S&&K.arrowKey&&y[1]&&(b===37?(a.preventDefault(),H.click()):b===39&&(a.preventDefault(),G.click()))})}},W.remove=function(){r.add(q).remove(),r=null,a("."+g).removeData(e).removeClass(g)},W.position=function(a,b){function g(a){u[0].style.width=x[0].style.width=t[0].style.width=a.style.width,C[0].style.height=C[1].style.height=t[0].style.height=v[0].style.height=w[0].style.height=a.style.height}var c=0,d=0,e=r.offset();z.unbind("resize."+f),r.css({top:-99999,left:-99999}),K.fixed&&!o?r.css({position:"fixed"}):(c=z.scrollTop(),d=z.scrollLeft(),r.css({position:"absolute"})),K.right!==!1?d+=Math.max(z.width()-K.w-O-M-$(K.right,"x"),0):K.left!==!1?d+=$(K.left,"x"):d+=Math.round(Math.max(z.width()-K.w-O-M,0)/2),K.bottom!==!1?c+=Math.max(z.height()-K.h-N-L-$(K.bottom,"y"),0):K.top!==!1?c+=$(K.top,"y"):c+=Math.round(Math.max(z.height()-K.h-N-L,0)/2),r.css({top:e.top,left:e.left}),a=r.width()===K.w+O&&r.height()===K.h+N?0:a||0,s[0].style.width=s[0].style.height="9999px",r.dequeue().animate({width:K.w+O,height:K.h+N,top:c,left:d},{duration:a,complete:function(){g(this),T=!1,s[0].style.width=K.w+O+M+"px",s[0].style.height=K.h+N+L+"px",b&&b(),setTimeout(function(){z.bind("resize."+f,W.position)},1)},step:function(){g(this)}})},W.resize=function(a){S&&(a=a||{},a.width&&(K.w=$(a.width,"x")-O-M),a.innerWidth&&(K.w=$(a.innerWidth,"x")),A.css({width:K.w}),a.height&&(K.h=$(a.height,"y")-N-L),a.innerHeight&&(K.h=$(a.innerHeight,"y")),!a.innerHeight&&!a.height&&(A.css({height:"auto"}),K.h=A.height()),A.css({height:K.h}),W.position(K.transition==="none"?0:K.speed))},W.prep=function(b){function g(){return K.w=K.w||A.width(),K.w=K.mw&&K.mw<K.w?K.mw:K.w,K.w}function h(){return K.h=K.h||A.height(),K.h=K.mh&&K.mh<K.h?K.mh:K.h,K.h}if(!S)return;var c,d=K.transition==="none"?0:K.speed;A.remove(),A=Y(X,"LoadedContent").append(b),A.hide().appendTo(B.show()).css({width:g(),overflow:K.scrolling?"auto":"hidden"}).css({height:h()}).prependTo(t),B.hide(),a(R).css({"float":"none"}),o&&a("select").not(r.find("select")).filter(function(){return this.style.visibility!=="hidden"}).css({visibility:"hidden"}).one(k,function(){this.style.visibility="inherit"}),c=function(){function q(){n&&r[0].style.removeAttribute("filter")}var b,c,g=y.length,h,i="frameBorder",k="allowTransparency",l,o,p;if(!S)return;l=function(){clearTimeout(V),C.hide(),bb(j,K.onComplete)},n&&R&&A.fadeIn(100),D.html(K.title).add(A).show();if(g>1){typeof K.current=="string"&&E.html(K.current.replace("{current}",Q+1).replace("{total}",g)).show(),G[K.loop||Q<g-1?"show":"hide"]().html(K.next),H[K.loop||Q?"show":"hide"]().html(K.previous),K.slideshow&&F.show();if(K.preloading){b=[Z(-1),Z(1)];while(c=y[b.pop()])o=a.data(c,e).href||c.href,a.isFunction(o)&&(o=o.call(c)),_(o)&&(p=new Image,p.src=o)}}else J.hide();K.iframe?(h=Y("iframe")[0],i in h&&(h[i]=0),k in h&&(h[k]="true"),h.name=f+ +(new Date),K.fastIframe?l():a(h).one("load",l),h.src=K.href,K.scrolling||(h.scrolling="no"),a(h).addClass(f+"Iframe").appendTo(A).one(m,function(){h.src="//about:blank"})):l(),K.transition==="fade"?r.fadeTo(d,1,q):q()},K.transition==="fade"?r.fadeTo(d,0,function(){W.position(0,c)}):W.position(d,c)},W.load=function(b){var c,d,e=W.prep;T=!0,R=!1,P=y[Q],b||ba(),bb(m),bb(i,K.onLoad),K.h=K.height?$(K.height,"y")-N-L:K.innerHeight&&$(K.innerHeight,"y"),K.w=K.width?$(K.width,"x")-O-M:K.innerWidth&&$(K.innerWidth,"x"),K.mw=K.w,K.mh=K.h,K.maxWidth&&(K.mw=$(K.maxWidth,"x")-O-M,K.mw=K.w&&K.w<K.mw?K.w:K.mw),K.maxHeight&&(K.mh=$(K.maxHeight,"y")-N-L,K.mh=K.h&&K.h<K.mh?K.h:K.mh),c=K.href,V=setTimeout(function(){C.show()},100),K.inline?(Y(X).hide().insertBefore(a(c)[0]).one(m,function(){a(this).replaceWith(A.children())}),e(a(c))):K.iframe?e(" "):K.html?e(K.html):_(c)?(a(R=new Image).addClass(f+"Photo").error(function(){K.title=!1,e(Y(X,"Error").text("This image could not be loaded"))}).load(function(){var a;R.onload=null,K.scalePhotos&&(d=function(){R.height-=R.height*a,R.width-=R.width*a},K.mw&&R.width>K.mw&&(a=(R.width-K.mw)/R.width,d()),K.mh&&R.height>K.mh&&(a=(R.height-K.mh)/R.height,d())),K.h&&(R.style.marginTop=Math.max(K.h-R.height,0)/2+"px"),y[1]&&(Q<y.length-1||K.loop)&&(R.style.cursor="pointer",R.onclick=function(){W.next()}),n&&(R.style.msInterpolationMode="bicubic"),setTimeout(function(){e(R)},1)}),setTimeout(function(){R.src=c},1)):c&&B.load(c,K.data,function(b,c,d){e(c==="error"?Y(X,"Error").text("Request unsuccessful: "+d.statusText):a(this).contents())})},W.next=function(){!T&&y[1]&&(Q<y.length-1||K.loop)&&(Q=Z(1),W.load())},W.prev=function(){!T&&y[1]&&(Q||K.loop)&&(Q=Z(-1),W.load())},W.close=function(){S&&!U&&(U=!0,S=!1,bb(k,K.onCleanup),z.unbind("."+f+" ."+p),q.fadeTo(200,0),r.stop().fadeTo(300,0,function(){r.add(q).css({opacity:1,cursor:"auto"}).hide(),bb(m),A.remove(),setTimeout(function(){U=!1,bb(l,K.onClosed)},1)}))},W.element=function(){return a(P)},W.settings=d,a("."+g,b).live("click",function(a){a.which>1||a.shiftKey||a.altKey||a.metaKey||(a.preventDefault(),bd(this))}),W.init()})(jQuery,document,this);

/**
 * ***************
 * Page's own code
 * ***************
 */
 
/**
 * An interface to work with user region cookie.
 * 
 * @param regionId integer If an argument is given, region cookie will have it.
 * Otherwise, function will return the current cookie value.
 * @return mixed Ig an argument is given, NULL returns. Otherwise, function will return
 * the current user region cookie value.
 */
function userRegionCookie(regionId)
{
//    if(typeof regionId == 'undefined')
//        console.log('read:', $.cookie('ryndaorg_region'));
//    else
//        console.log('write:', regionId);

    if(typeof regionId == 'undefined')
        return $.cookie('ryndaorg_region') ? $.cookie('ryndaorg_region') : false;
    else
        $.cookie('ryndaorg_region', parseInt(regionId),
                 {expires: 14, path: '/', domain: CONST_COOKIE_DOMAIN.substr(1)});
}

/**
 * Валидация формы логина.
 *
 * Для локализации сообщений об ошибках использует константы, которые должны передаваться
 * контроллером страницы в вид jsVars.php, создающий из этих констант js-переменные.
 *
 * @param $form object Объект формы, которая подвергается валидации. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, и false в противном случае.
 */
function validateLogin($form, $message)
{
    if( !$form )
        return false;
    $form = $($form);
    $message = $message ? $message : $('#loginMessage', $form);

    var field = {},
        value = '',
        isFocused = false,
        result = true;

    // Login/email (nessessary):
    field = $('#loginField', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $message.addClass('validation_error').html(LANG_LOGIN_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value) ) {
        field.addClass('invalidField');
        $message.addClass('validation_error').html(LANG_LOGIN_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $message.removeClass('validation_error').slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        return result;
    }

    // Pass (nessessary):
    field = $('#passwordField', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $message.addClass('validation_error').html(LANG_PASSWORD_REQUIRED).slideDown(250);
        result = result && false;
    } else if(value.length < 6 || value.length > 20) {
        field.addClass('invalidField');
        $message.addClass('validation_error').html(LANG_PASSWORD_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $message.removeClass('validation_error').slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        return result;
    }

    return result;
}

/**
 * Валидация формы регистрации.
 *
 * Для локализации сообщений об ошибках использует константы, которые должны передаваться
 * контроллером страницы в вид jsVars.php, создающий из этих констант js-переменные.
 *
 * @param $form object Объект формы регистрации. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, и false в противном случае.
 */
function validateRegister($form, $message)
{
    if( !$form )
        return false;
    $form = $($form);
    $message = $message ? $message : $('#regMessage', $form);

    var field = {},
        value = '',
        isFocused = false,
        result = true;
    
    // First name (nessessary):
    field = $('#firstNameField', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#firstNameError', $form).html(LANG_FIRST_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[ ]*[a-zа-я'\"]+[a-zа-яё "'-]*[a-zа-я'\"]+[ ]*$/i.test(value) ) {
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

    // Last name (nessessary):
    field = $('#lastNameField', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#lastNameError', $form).html(LANG_LAST_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[ ]*[a-zа-я'\"]+[a-zа-яё "'-]*[a-zа-я'\"]+[ ]*$/i.test(value) ) {
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

    // Login/email (nessessary):
    field = $('#loginField', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#loginError', $form).html(LANG_LOGIN_REQUIRED).slideDown(250);
        result = result && false;
    } else if( !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value) ) {
        field.addClass('invalidField');
        $('#loginError', $form).html(LANG_LOGIN_INVALID).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#loginError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Pass (nessessary):
    field = $('#passwordField', $form);
    value = $.trim(field.val());
    if(CONST_PASSWORD_MIN_LENGTH > 0 && value.length < CONST_PASSWORD_MIN_LENGTH) {
        field.addClass('invalidField');
        $('#passwordError', $form)
            .html(LANG_PASSWORD_SHORT.replace('%passwordMinLength', CONST_PASSWORD_MIN_LENGTH))
            .slideDown(250);
        result = result && false;
    } else if(CONST_PASSWORD_MAX_LENGTH > 0 && value.length > CONST_PASSWORD_MAX_LENGTH) {
        field.addClass('invalidField');
        $('#passwordError', $form)
            .html(LANG_PASSWORD_LONG.replace('%passwordMaxLength', CONST_PASSWORD_MAX_LENGTH))
            .slideDown(250);
        result = result && false;
    } else if(value != $('#passwordConfirmField', $form).val()) {
        field.addClass('invalidField');
        $('#passwordConfirmField', $form).addClass('invalidField');
        $('#passwordError', $form).html(LANG_PASSWORD_CONFIRM_MISMATCH).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#passwordConfirmField', $form).removeClass('invalidField');
        $('#passwordError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    // Согласие на обработку данных (обязательно):
    field = $('#agreed', $form);
    value = field.attr('checked');
    if( !value ) {
        field.addClass('invalidField');
        $('#agreedError', $form).html(LANG_AGREE_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#agreedError', $form).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    return result;
}

$(function(){
    var $auth = $('#auth'), // Auth block
        $authLoading = $('img#authResponseLoading', $auth),
        $regionModal = $('#regionModal', $auth),
        $loginForm = $('form#loginForm'),
        $regForm = $('form#regForm');

    /**
     * Code for design:
     */
	$('input:checkbox', $loginForm).ezMark(); // Apply the styles to the checkboxes

    // Subdomains widget' dropdown:
    $('.mapTabs-button-sub-click').mouseover(function(){
        $('.mapTabs-sub').addClass('display_none');   
    });
    $('.mapTabs-sub').mouseover(function(){
        $('.mapTabs-sub').addClass('display_none');   
    });	
    $('.mapTabs-sub').mouseout(function(){
        $('.mapTabs-sub').removeClass('display_none');
    });

    // Auth block:
    $('a#authLogout', $auth).click(function(e){
        e.preventDefault();

        $authLoading.show();
        $.get('/auth/logout', function(){
            window.location = window.location;
        });
    });

    /**
     * Login modal window:
     */
    $('#authLogIn', $auth).click(function(e){
        e.preventDefault();
        $(this).colorbox({inline: true,
                          href: $('#authModal', $auth),
						  width: 430,
						  title: "Вход в систему",
                          fixed: true,
//                          transition: 'fade'
                          overlayClose: false,
                          scrolling: false,
                          onComplete: function(){
                              $loginForm[0].reset();
                              $('.formField', $loginForm).removeClass('invalidField');
                              $('.form_error, #loginMessage', $loginForm).html('').hide();
                          }
//                          onCleanup: function(){
//                          }
                         });
    });

    $('#loginCancel', $loginForm).click(function(e){
        e.preventDefault();
        $.colorbox.close();
    });

    // Login form processing:
    $loginForm.submit(function(e){
        e.preventDefault();

        if( !validateLogin($loginForm) )
            return;
        // Защита от дабл-поста - отменить сабмит, если форма не изменилась:
//        if(curFormData == initFormData)
//            return;

        var $ajaxLoading = $('img#loginLoading', $loginForm),
            $loginButtons = $('input#loginSubmit, input#loginCancel', $loginForm),
            $loginMessage = $('#loginMessage', $loginForm);

        $ajaxLoading.show();
        $loginButtons.attr('disabled', 'disabled').animate({opacity: 0.25});

        $.ajax('/auth/loginProcess',
               {data: $loginForm.serialize(),
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false})
            .success(function(resp){
                if(resp.status && resp.status == 'success') {
                    $loginMessage.html('<div class="successMessage">'+resp.message+'</div>')
                                 .stop(true).fadeIn(200, function(){
                                     window.location = window.location;
                                 });
                } else {
                    if(resp.errors) {
                        $loginMessage.html('<div class="errorMessage">'+resp.errors+'</div>')
                                     .stop(true).fadeIn(200);
                    } else if(resp.message) {
                        $loginMessage.html('<div class="errorMessage">'+resp.message+'</div>')
                                     .stop(true).fadeIn(200);
                    } else {
                        $loginMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                     .stop(true).fadeIn(200);
                    }
                    $loginButtons.removeAttr('disabled').animate({opacity: 1.0});
                }

                $ajaxLoading.hide();
            })
            .error(function(jqXHR, textStatus){
                $loginMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                             .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                $ajaxLoading.hide();
                $loginButtons.removeAttr('disabled').animate({opacity: 1.0});
            });
    });

    /**
     * Register modal window:
     */
    $('#authRegister', $auth).click(function(e){
        e.preventDefault();
        $(this).colorbox({inline: true,
                          href: $('#registerModal', $auth),
                          fixed: true,
//                          transition: 'fade'
						  title: "Регистрация в системе",
						  width: 520,
                          overlayClose: false,
                          scrolling: false,
                          onComplete: function(){
                              $regForm[0].reset();
                              $('.formField', $regForm).removeClass('invalidField');
                              $('.form_error, #regMessage', $regForm).html('').hide();
                          }
//                          onCleanup: function(){
//                          }
                         });
    });

    $('#regCancel', $regForm).click(function(e){
        e.preventDefault();
        $.colorbox.close();
    });

    // Register form processing:
    $regForm.submit(function(e){
        e.preventDefault();

        if( !validateRegister($regForm) )
            return;
        // Защита от дабл-поста - отменить сабмит, если форма не изменилась:
//        if(curFormData == initFormData)
//            return;

        var $ajaxLoading = $('img#regLoading', $regForm),
            $formSubmit = $('input#regSubmit', $regForm),
            $regMessage = $('#regMessage', $regForm);

        $ajaxLoading.show();
        $formSubmit.attr('disabled', 'disabled').animate({opacity: 0.25});

        $.ajax('/auth/registerProcess',
               {data: $regForm.serialize(),
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false})
            .success(function(resp){
                if(resp.status && resp.status == 'success') {
                    $regMessage.html('<div class="successMessage">'+resp.message+'</div>')
                               .stop(true).fadeIn(200);
                } else {
                    if(resp.errors) {
                        $regMessage.html('<div class="errorMessage">'+resp.errors+'</div>')
                                   .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    } else if(resp.message) {
                        $regMessage.html('<div class="errorMessage">'+resp.message+'</div>')
                                   .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    } else {
                        $regMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                                   .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                    }
                }

                $ajaxLoading.hide();
                $formSubmit.removeAttr('disabled').animate({opacity: 1.0});
            })
            .error(function(jqXHR, textStatus){
                $regMessage.html('<div class="errorMessage">Ошибка при обработке!</div>')
                           .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                $ajaxLoading.hide();
                $formSubmit.removeAttr('disabled').animate({opacity: 1.0});
            });
    });

    /**
     * User region widget. It also controls the geo-dependent pages content.
     * 
     * User region cookie values:
     * Region > 0 - region ID.
     * Region "0" - "whole map", selected by user.
     */
    $('#user_location', $auth).mouseenter(function(){
        $(this).data('cursorIsIn', true);
        $('#region_select_div', this).slideDown(100);
    }).mouseleave(function(){
        var $this = $(this);
        $this.data('cursorIsIn', false);
        setTimeout(function(){
            if( !$this.data('cursorIsIn') ) {
                $('#region_select_div', $this).slideUp(100);
            }
        }, 2000);
    });

    $('#regionSelect', $auth).click(function(e){
        e.preventDefault();
        $(this).colorbox({inline: true,
                          href: $regionModal,
                          fixed: true,
//                          transition: 'fade'
                          overlayClose: false,
                          scrolling: false,
                          onComplete: function(){
                              var userRegion = userRegionCookie();
                              if(userRegion) {
                                  $regionModal.data('regionSelected', true);
                                  $('#regionFieldModal', $regionModal).val(userRegion);
                                  $regionModal.data('prevValue', userRegion);
                              }
                          },
                          onCleanup: function(){
                              if( !$regionModal.data('regionSelected') )
                                  userRegionCookie(0);

                              // Refresh the page's content:
                              var userRegion = userRegionCookie();
                              if(userRegion != $regionModal.data('prevValue')) {
                                  $regionModal.data('prevValue', userRegion);
                                  refreshAll();
                              }
                          }
                         });
    });

//    if( !userRegionCookie() )
//        $('#regionSelect', $auth).click();
//    else
    refreshAll();
    
    $('#regionModalOk', $regionModal).click(function(){
        var selectedRegionId = $('#regionFieldModal', $regionModal).val();
        if($regionModal.data('prevValue') != selectedRegionId) {
            userRegionCookie(selectedRegionId);
            $regionModal.data('regionSelected', true);
            $('#userRegionName', $auth).fadeOut(250).html( $('#regionFieldModal > option[value="'+selectedRegionId+'"]', $regionModal).html() ).fadeIn(250);
            $regionModal.trigger('regionChanged', [selectedRegionId]);
        }
        $.colorbox.close();
    });

    $('#regionModalCancel', $regionModal).click(function(){
        $.colorbox.close();
    });

    // User region geolocation:
    if(navigator.geolocation) {
        var geolocationDfd = $.Deferred(),
            coords = {},
            $geolocationLoading = $('#findMeLoading', $regionModal),
            $geolocationMessage = $('#findMeMessage', $regionModal);

        $('#regionFindMe', $regionModal).click(function(){
            // Find coords of the user:
            navigator.geolocation.getCurrentPosition(function(position){ // Точка найдена
                coords = {lat: position.coords.latitude, lng: position.coords.longitude};
                geolocationDfd.resolve();
            }, function(){ // Can't find the coords
//                console.log('Not found');
            }); 
        });

        $.when(geolocationDfd.promise())
         .then(function(){
             $geolocationLoading.show();
             // Geolocate user' region by it's coords:
             $.getJSON(CONST_REGION_SERVICE_URL+'?lat='+coords.lat+'&lng='+coords.lng+'&callback=?')
              .success(function(resp){
                  var regionName = false;
                  if(resp.length) { // Geolocation successful
                      $('#regionFieldModal', $regionModal).val(resp[0].id);
                      regionName = $.trim($('#regionFieldModal option[value="'+resp[0].id+'"]',
                                            $regionModal).html());
                  }

                  $('#geolocateRegionMessageTmpl').tmpl({'regionName': regionName})
                                                  .appendTo($geolocationMessage);
                  $geolocationMessage.fadeIn(250);
                  $geolocationLoading.hide();
              })
              .error(function(jqXHR, textStatus){
                  $geolocationLoading.hide();
                  // Show error message...
//                      console.log("ERROR: can't geolocate the region, some troubles on the line");
//                          console.log('Error', textStatus);
              });
             $('#regionFindMe', $regionModal).fadeOut(250);
         });
    } else
        $('#regionFindMe', $regionModal).hide();
    // User region geolocation ended
});