/**
 * ******************************
 * jQuery-плагины для функционала
 * ******************************
 */

/**
 * Textfill.
 * 
 * Resizes an inner element's font so that the inner element completely fills the outer element.
 * @author Russ Painter WebDesign@GeekyMonkey.com
 * @version 0.1
 * @param {Object} Options which are maxFontPixels (default=40), innerTag (default='span')
 * @return All outer elements processed
 * @example <div class='mybigdiv filltext'><span>My Text To Resize</span></div>
 */
(function(a){a.fn.textfill=function(c){var e=jQuery.extend({maxFontPixels:40,innerTag:"span"},c);return this.each(function(){var b=e.maxFontPixels,d=a(e.innerTag+":visible:first",this),c=a(this).height(),h=a(this).width(),f,g;do d.css("font-size",b),f=d.height(),g=d.width(),b-=1;while((f>c||g>h)&&3<b)})}})(jQuery);

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

var $messagesList = $('#messagesList'), // Список сообщений
    $messagesListMessage = $('#controlResponseMessage'), // Див для текста сообщения об аякс-запросе
    $messagesCount = $('#messagesCount'), // Див с кол-вом сообщений в списке
    $ajaxLoading = $('img#controlResponseLoading');

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

/**
 * Сбор параметров сортировки с управляющих элементов списка сообщений и получение массива
 * сообщений, соответствующих этим параметрам.
 * 
 * @return object Deferred-объект запроса для получения сообщений.
 */
function getListItems()
{
    return $.ajax('/ajax_forms/ajaxGetMessages',
                  {data: {userId: CONST_USER_ID,
                          subdomain: 0,
                          dateAddedFrom: $('.dateAddedFrom.activeDateFilter').attr('id'),
                          statusId: [CONST_MESSAGE_STATUS_RECEIVED,
                                     CONST_MESSAGE_STATUS_MODERATED,
                                     CONST_MESSAGE_STATUS_VERIFIED,
                                     CONST_MESSAGE_STATUS_REACTION,
                                     CONST_MESSAGE_STATUS_REACTED,
                                     CONST_MESSAGE_STATUS_CLOSED],
//                          orderBy: orderBy,
                          limit: 10},
                   type: 'post',
                   async: true,
                   dataType: 'json',
                   /** * @todo После релиза протестировать эффективность кэширования этого запроса: */
                   cache: false});
}

$(function(){ // Готовность DOM
    $('#listItemTmpl').template('listItemTemplate'); // Шаблон для вывода сообщения в списке

    // Исправление бага с наползанием длинной фамилии юзера на другой текст:
    $('.user_FI').textfill({maxFontPixels: 70});

    // Show contacts:
    $('#showContacts').click(function(){
        $(this).parent().slideUp(250);
        $('#contactsData').slideDown(250);
    });

    /**
     * Вкладки профилей волонтёрства.
     */
    var $vpTabs = $('#vpList');
    $vpTabs.tabs({fx: {opacity: 'toggle', duration: 'fast'},
                       selected: 0});

    $('.listFilter').live('click', function(e){
        e.preventDefault();

        var $this = $(this);
        if($this.hasClass('dateAddedFrom')) {
            $('.dateAddedFrom').removeClass('activeDateFilter');
            $this.addClass('activeDateFilter');
        }

        $ajaxLoading.show();

        $.when(getListItems())
         .done(function(resp){
             $ajaxLoading.hide();

             $messagesList.fadeOut(500, function(){
                 if(resp && resp.status == 'success') {
                     if(resp.data.length > 0) {
                         $messagesListMessage.fadeOut(250).html('');
                         $messagesCount.html('('+resp.data.length+')').fadeIn(250);
                         $.tmpl('listItemTemplate', resp.data, {formatDateAdded: function(){
                                 var date = new Date(1000*this.data.dateAdded),
                                     dateStr = '',
                                     tmp;

                                 tmp = date.getDate();
                                 dateStr += (tmp < 10 ? '0'+tmp : tmp)+'.';

                                 tmp = date.getMonth()+1;
                                 dateStr += (tmp < 10 ? '0'+tmp : tmp)+'.';

                                 dateStr += date.getFullYear()+' ';

                                 tmp = date.getHours()+1; // К часам прибавляется 1 из-за того,
                                 // что по какой-то причине браузер неверно рассчитывает часы
                                 // для переданного timestamp-а. Возможно, дело в пресловутой
                                 // отмене перевода на летнее время.
                                 dateStr += (tmp < 10 ? '0'+tmp : tmp)+':';

                                 tmp = date.getMinutes();
                                 dateStr += tmp < 10 ? '0'+tmp : tmp;

                                 return dateStr;
                             }, nl2br: function(str){
                                 return str.replace(/\n/g, '<br />');
                             }})
                             .appendTo($messagesList.empty());
                     } else {
                         $messagesList.empty();
                         $messagesCount.fadeOut(250);
                         $messagesListMessage.html(LANG_MESSAGES_NOT_FOUND).fadeIn(250);
                     }
                     $messagesList.fadeIn(500);
                 } else if(resp && resp.status == 'error')
                    $messagesListMessage.html(resp.message).fadeIn(250);
             });
        })
        .fail(function(jqXHR, textStatus){
            $ajaxLoading.hide();
        });
    });

    $('.activeDateFilter').click();
});