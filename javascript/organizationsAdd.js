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
 
 var $form = $('form#mainForm'),
     $message = $('#formResponseMessage', $form);
     // Переменные для защиты от дабл-поста:
//     initFormData = $form.serialize(),
//     curFormData = initFormData;

/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

 /**
 * Валидация формы добавления организации.
 *
 * @param $form object Объект формы добавления организации. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, false в противном случае.
 */
function validateOrganizationsAdd($form)
{
    if( !$form )
        return false;
    $form = $($form);

    var value = '',
        result = true;

    // Название организации (обязательно):
    value = $.trim($('#name', $form).val());
    if(value.length <= 0) {
        $('#name', $form).addClass('invalidField');
        $('#nameError', $form).html(LANG_ORG_NAME_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('#name', $form).removeClass('invalidField');
        $('#nameError', $form).slideUp(250).html('');
    }

    // Тип организации (обязательно):
    value = $.trim($('#organizationType', $form).val());
    if(value.length <= 0) {
        $('#organizationType', $form).addClass('invalidField');
        $('#organizationTypeError', $form).html(LANG_ORG_TYPE_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('#organizationType', $form).removeClass('invalidField');
        $('#organizationTypeError', $form).slideUp(250).html('');
    }

    // Категории организации (обязательно, минимум одна):
    value = $(':checkbox[name*="category"]:checked', $form);
    if(value.length <= 0) {
//        $('#organizationRegion', $form).addClass('invalidField');
        $('#categoryError', $form).html(LANG_ORG_CATEGORY_REQUIRED).slideDown(250);
        result = result && false;
    } else {
//        $('#organizationRegion', $form).removeClass('invalidField');
        $('#categoryError', $form).slideUp(250).html('');
    }

    // Регион организации (обязательно):
    value = $.trim($('#organizationRegion', $form).val());
    if(value.length <= 0) {
        $('#organizationRegion', $form).addClass('invalidField');
        $('#organizationRegionError', $form).html(LANG_ORG_REGION_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('#organizationRegion', $form).removeClass('invalidField');
        $('#organizationRegionError', $form).slideUp(250).html('');
    }

    // Адрес организации (обязательно):
    value = $.trim($('#address', $form).val());
    if(value.length <= 0) {
        $('#address', $form).addClass('invalidField');
        $('#addressError', $form).html(LANG_ORG_ADDRESS_REQUIRED).slideDown(250);
        result = result && false;
    } else {
        $('#address', $form).removeClass('invalidField');
        $('#addressError', $form).slideUp(250).html('');
    }

    // Список тел.номеров (необязательно):
    value = $.trim($('#phones', $form).val());
    if(value) {
        var phonesValid = true;
        value = value.split(',');
        for(var i=0; i<value.length; i++) {
            if( !/^[ ]*[0-9-\+\(\) ]+[ ]*$/i.test($.trim(value[i])) ) {
                phonesValid = phonesValid && false;
                break;
            }
        }
        if( !phonesValid ) {
            $('#phones', $form).addClass('invalidField');
            $('#phonesError', $form).html(LANG_ORG_PHONES_INVALID).slideDown(250);
            result = result && false;
        } else {
            $('#phones', $form).removeClass('invalidField');
            $('#phonesError', $form).slideUp(250).html('');
        }
    }

    // Список e-mail (необязательно):
    value = $.trim($('#emails', $form).val());
    if(value) {
        var emailsValid = true;
        value = value.split(',');
        for(var i=0; i<value.length; i++) {
            if( !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test($.trim(value[i])) ) {
                emailsValid = emailsValid && false;
                break;
            }
        }
        if( !emailsValid ) {
            $('#emails', $form).addClass('invalidField');
            $('#emailsError', $form).html(LANG_ORG_EMAILS_INVALID).slideDown(250);
            result = result && false;
        } else {
            $('#emails', $form).removeClass('invalidField');
            $('#emailsError', $form).slideUp(250).html('');
        }
    }

    return result;
}

$(function(){ // Готовность DOM
    /**
     * Начало применения кода для дизайна
     */
    $('[placeholder]').defaultValue(); // Replace/restore input values
	$('input:checkbox', $form).ezMark(); // Apply checkbox style
	// Column equal height:
	$('.column_container').css('height', Math.max($('#nh_1_column_container').height(),
                                                  $('#nh_2_column_container').height()));
	$('.column_container_offer').css('height', Math.max($('#ch_1_column_container').height(),
                                                  $('#ch_2_column_container').height()));

    // Раскрывающиеся списки (для полей выбора категорий):
    $('.expandable').each(function(){
        var $this = $(this);
        if($.trim($('.expandableIsOpened', $this).html()) == 1)
            $this.find('.expandableContent').show();
        else
            $this.find('.expandableContent').hide();
    });
    $('.expandable > .expandableTitle').live('click', function() {
        $expandable = $(this).parent();
        $expandable.find('.expandableContent').slideToggle(200);
    });
	/**
     * Конец применения кода для дизайна
     */

    // Защита от дабл-поста: регистрировать изменение состояния формы
//    $(':input', $form).live('change', function(){
//        curFormData = $form.serialize();
//    });

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

    // Отправка формы:
    $form.submit(function(e){
        /**
         * @todo В идеале, в продакшн-коде рекомендуется юзать preventDefault()
         * в самом конце callback-а. Но я пока не увидел в этом преимуществ, а недостаток
         * есть (дублирование preventDefault() много где в callback-е). Так что пока пусть
         * будет здесь, в начале.
         */
        e.preventDefault();

        // Проверка валидности полей формы:
        if( !validateOrganizationsAdd($form) )
            return;
        // Защита от дабл-поста - отменить сабмит, если форма не изменилась:
//        if(curFormData == initFormData)
//            return;

        var $ajaxLoading = $('img#formResponseLoading', $form);
        var $formSubmit = $('input#addSubmit', $form);

        $ajaxLoading.show();
        $formSubmit.attr('disabled', 'disabled');

        $.ajax('/ajax_admin_forms/addOrganizationProcess',
               {data: $form.serialize(),
                type: 'post',
                async: true,
                dataType: 'json',
                cache: false})
         .success(function(resp){
             if(resp.status && resp.status == 'success') {
                 if(resp.message) {
                    $message.html('<div class="successMessage">'+
                                  resp.message+'<br />'+
                                  '<a href="/org/d/'+resp.data.id+'">На страницу новой организации</a>'+' | '+
                                  '<a href="'+window.location.href+'" id="addReset">Остаться и добавить ещё одну</a>'+
                                  '</div>')
                            .stop(true).fadeIn(200).fadeTo(15000, 1.0).fadeOut(200);
                }
            } else {
                if(resp.errors) {
                    $message.html('<div class="errorMessage">'+resp.errors+'</div>')
                            .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                } else if(resp.message) {
                    $message.html('<div class="errorMessage">'+resp.message+'</div>')
                            .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                } else {
                    $message.html('<div class="errorMessage">Ошибка при обработке!</div>')
                        .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                }
            }

            $ajaxLoading.hide();
            $formSubmit.removeAttr('disabled');

            // Включить защиту от дабл-поста на текущей форме:
            formChanged = false;
            initFormData = curFormData = $form.serialize();
        })
        .error(function(jqXHR, textStatus){
            $message.html('<div class="errorMessage">Ошибка при обработке!</div>')
                    .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
            $ajaxLoading.hide();
            $formSubmit.removeAttr('disabled');
        });
    });
});