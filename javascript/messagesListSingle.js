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
 * Defaultvalue jQuery plugin (gives value to empty inputs)
 *
 * @version 1.4.2
 * @requires jQuery >= 1.3.2
 * @copyright Jan Jarfalk
 * @license MIT License
 *
 * @param str string The default value
 * @param callback function Callback function
 */
(function(c){c.fn.extend({defaultValue:function(e){if("placeholder"in document.createElement("input"))return!1;return this.each(function(){if(c(this).data("defaultValued"))return!1;var a=c(this),h=a.attr("placeholder"),f={input:a};a.data("defaultValued",!0);var d=function(){var b;if(a.context.nodeName.toLowerCase()=="input")b=c("<input />").attr({type:"text"});else if(a.context.nodeName.toLowerCase()=="textarea")b=c("<textarea />");else throw"DefaultValue only works with input and textareas";b.attr({value:h,
"class":a.attr("class")+" empty",size:a.attr("size"),style:a.attr("style"),tabindex:a.attr("tabindex"),rows:a.attr("rows"),cols:a.attr("cols"),name:"defaultvalue-clone-"+((1+Math.random())*65536|0).toString(16).substring(1)});b.focus(function(){b.hide();a.show();setTimeout(function(){a.focus()},1)});return b}();f.clone=d;d.insertAfter(a);var g=function(){a.val().length<=0?(d.show(),a.hide()):(d.hide(),a.show().trigger("click"))};a.bind("blur",g);g();e&&e(f)})}})})(jQuery);

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    $form.find('#filterRegion').val(userRegionCookie());
    $form.submit();
}

var $form = $('#filterForm'), // Форма фильтра для списка сообщений
    $formMessage = $('#filterResponseMessage'),
    $ajaxLoading = $('img#filterResponseLoading'),
    $list = $('#itemsList'), // Собственно список
    $itemsCount = $('#itemsCount'), // Див с кол-вом сообщений в списке
    $pagination = $('#paginationBlock'),
    $listPages = $('#paginationPages', $pagination);

/**
 * Сбор параметров фильтрации с формы фильтра списка сообщений и получение массива
 * сообщений, соответствующих этим параметрам.
 * 
 * @param $form object Объект формы фильтра. Можно передать с jQuery-обёрткой.
 * @return object Deferred-объект запроса для получения сообщений.
 */
function getListItems($form)
{
    $form = $($form);

    var filterVars = {},
        value = null;

    filterVars.persist = {};
    $('input.filterPersistVar', $form).each(function(){
        var $this = $(this),
            nameParts = $this.attr('name').split('[]');
        if(nameParts.length > 1) {
            if( !filterVars.persist[nameParts[0]] )
                filterVars.persist[nameParts[0]] = [];
            filterVars.persist[nameParts[0]].push($this.val());
        } else
            filterVars.persist[$this.attr('name')] = $this.val();
    });

    filterVars.dateAddedFrom = $('.dateAddedFrom.selected').attr('id');
    
    filterVars.itemsPerPage = $('#paginationItemsPerPage', $pagination).val();
    filterVars.currentPage = $listPages.data('current-page');

    value = $('select#filterTypeId', $form).val();
    if(value)
        filterVars.typeId = value;

    value = $('select#filterRegion', $form).val();
    if(value)
        filterVars.regionId = value;

    value = $(':checkbox[name*="category"]:checked', $form);
    if(value.length > 0) {
        filterVars.category = [];
        for(var i=0; i<value.length; i++) {
            filterVars.category.push($(value[i]).val());
        }
    }

    value = $('select#filterUntimed', $form).val();
    if(value)
        filterVars.untimed = value;

    value = $.trim($('input#filterString', $form).val());
    if(value.length > 0)
        filterVars.searchString = value;

    return $.ajax('/ajax_forms/ajaxGetMessages', {data: filterVars,
                                                  type: 'post',
                                                  async: true,
                                                  dataType: 'json',
                                                  cache: false});
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
        return '';
    else {
        string = $.trim(string).replace(/<.*?>/gi, '');
        if(string.length <= 0)
            return '';
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
        var words = string.split(' '),
            wordNum = 0;
        while(stringRes.length < max_length) {
            stringRes += ' '+words[wordNum];
            wordNum++;
        }
        stringRes += '…';
    }

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
 
 function nl2br(str)
 {
     return str.replace(/\n/g, '<br />');
 }

$(function(){ // Готовность DOM
    $('#listItemTmpl').template('listItemTemplate'); // Шаблон для элемента списка
    $('#paginationPagesTmpl').template('pagesTemplate'); // Шаблон для постраничной навгации по списку

	/**
     * Начало применения кода для дизайна
     */
    // Плейсхолдеры полей.
    // В браузерах, поддерживающих HTML 5, это реализуется с помощью атрибута placeholder;
    // в остальных - с помощью jQuery-плагина defaultValue.
    $('[placeholder]').defaultValue();
	$('input:checkbox', $form).ezMark(); // Применение стилей к чекбоксам
    /**
     * Конец применения кода для дизайна
     */

    // Аякс-список категорий:
    $('.categoryGroup', $form).click(function(){
        var $this = $(this);
        var $parent = $this.parent();

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
                            $('input[type="checkbox"]', subCatsHtml).ezMark(); // Apply checkbox style

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

    $('a.dateAddedFrom').click(function(e){
        e.preventDefault();
        
        $('a.dateAddedFrom').removeClass('selected');
        $(this).addClass('selected');
        $listPages.data('current-page', 1);
        $form.submit();
    });
    
    $('#paginationItemsPerPage', $pagination).change(function(){
        $listPages.data('current-page', 1);
        $form.submit();
    });
    
    $('a.paginationLink').live('click', function(e){
        e.preventDefault();

        var $this = $(this);
        $listPages.data('items-begin-at', $this.data('begin-at'))
                  .data('current-page', parseInt($this.data('page-num')));
        $form.submit();
    });

    $list.delegate('a.filterByRegion', 'click', function(e){
        e.preventDefault();

        $('select#filterRegion option[value='+$(this).data('region-id')+']', $form).attr('selected', 1);
        $form.submit();
    });
    
    $form.change(function(){
        $listPages.data('current-page', 1);
    });

    $form.submit(function(e){
        e.preventDefault();

        var $formSubmit = $('input#filterSubmit', $form);

        $ajaxLoading.show();
        $formSubmit.attr('disabled', 'disabled')
                   .animate({opacity: 0.25})
                   .css('cursor', 'default');

        $.when(getListItems($form))
         .done(function(resp){
             $ajaxLoading.hide();
             $formSubmit
                 .removeAttr('disabled')
                 .animate({opacity: 1.0})
                 .css('cursor', 'pointer');

             $list.fadeOut(250, function(){
                 if(resp && resp.status == 'success') {
                     $listPages.fadeOut(250);

                     if(resp.data.length > 0) {
                         $formMessage.fadeOut(250).html('');
                         $itemsCount.html(resp.totalDataCount).fadeIn(250);

                         // Если постраничная навигация требуется:
                         if($('#paginationItemsPerPage', $pagination).val() > 0) {
                             var paginationVars = {pages: []},
                                 currentPageNum = $listPages.data('current-page'),
                                 itemsPerPage = $('#paginationItemsPerPage', $pagination).val(),
                                 totalPages = resp.totalDataCount%itemsPerPage > 0 ?
                                     parseInt(resp.totalDataCount/itemsPerPage)+1 :
                                     parseInt(resp.totalDataCount/itemsPerPage);

                             if(totalPages > 1) {
                                 for(var pageNum=currentPageNum-3; pageNum <= currentPageNum+3; pageNum++) {
                                     if(pageNum <= 0 || pageNum > totalPages)
                                         continue;

                                     paginationVars.pages.push({beginAt: (pageNum-1)*itemsPerPage,
                                                                pageNum: pageNum});
                                 }
                                 if(currentPageNum > 1) {
                                     paginationVars.showLinkPrev = true;
                                     if(currentPageNum > 4)
                                        paginationVars.showLinkFirst = true;
                                 }
                                 if(currentPageNum < totalPages) {
                                     paginationVars.showLinkNext = true;
                                     if(currentPageNum < totalPages-4)
                                        paginationVars.showLinkLast = true;
                                 }
                                 paginationVars.itemsPerPage = itemsPerPage;
                                 paginationVars.currentPageNum = currentPageNum;
                                 paginationVars.totalPages = totalPages;
                                 $listPages.html($.tmpl('pagesTemplate', paginationVars))
                                           .fadeIn(250);
                             }
                         }
                         $.tmpl('listItemTemplate',
                                resp.data,
                                {formatDate: formatDate, formatTextTrimmed: jsFormatTextTrimmed, nl2br: nl2br})
                          .appendTo($list.empty());
                     } else {
                         $list.empty();
                         $listPages.data('current-page', 1);
                         $itemsCount.fadeOut(250);
                         $formMessage.html(LANG_MESSAGES_NOT_FOUND).fadeIn(250);
                     }
                     $list.fadeIn(250);
                 } else if(resp && resp.status == 'error')
                    $formMessage.html(resp.message).fadeIn(250);
             });
        })
        .fail(function(jqXHR, textStatus){
            $ajaxLoading.hide();
            $formSubmit.removeAttr('disabled')
                       .animate({opacity: 1.0})
                       .css('cursor', 'pointer');
        });
    });

    $('#export_csv').click(function(){
        var filterVars = {},
            value = null;

        $('input[type="hidden"].filterPersistVar', $form).each(function(){
            var $this = $(this);
                nameParts = $this.attr('name').split('[]');
            if(nameParts.length > 1) {
                if( !filterVars[nameParts[0]] )
                    filterVars[nameParts[0]] = [];
                filterVars[nameParts[0]].push($this.val());
            } else
                filterVars[$this.attr('name')] = $this.val();
        });

        filterVars.dateAddedFrom = $('.dateAddedFrom.selected').attr('id');

        filterVars.itemsPerPage = $('#paginationItemsPerPage', $pagination).val();
        filterVars.currentPage = $listPages.data('current-page');

        value = $('select#filterTypeId', $form).val();
        if(value)
            filterVars.typeId = value;

        value = $('select#filterRegion', $form).val();
        if(value)
            filterVars.regionId = value;

        value = $(':checkbox[name*="category"]:checked', $form);
        if(value.length > 0) {
            filterVars.category = [];
            for(var i=0; i<value.length; i++) {
                filterVars.category.push($(value[i]).val());
            }
        }

        value = $('select#filterUntimed', $form).val();
        if(value)
            filterVars.untimed = value;

        value = $.trim($('input#filterString', $form).val());
        if(value.length > 0)
            filterVars.searchString = value;

        // Текущий субдомен:
        var currentDomainParts = window.location.href.split('/').slice(2, 3)[0].split('.');
        if(currentDomainParts.length == 3) // Субдомен есть, вернуть его
            filterVars.subdomain = currentDomainParts[0];
        else // Используется базовый URL без субдомена или субдомен больше чем 3-го уровня:
            filterVars.subdomain = 0;

        var requestStr = '';
        $.each(filterVars, function(varName, value){
            // Параметр фильтрации - массив:
            if(typeof value == 'object' && typeof value[0] != 'undefined' && value[0]) {
                for(var i=0; i<value.length; i++) {
                    requestStr = requestStr+'&'+varName+'[]'+'='+value[i];
                }
            } else // Параметр фильтрации - обычная переменная
                requestStr = requestStr+'&'+varName+'='+(typeof value == 'undefined' ? '' : value);
        });

        window.open('/public_api/messages/format/csv'+(requestStr ? '?'+requestStr.substr(1) : ''), '');
    });
});