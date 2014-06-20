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

// ...

/**
 * ************************
 * Собственный код страницы
 * ************************
 */

var $form = $('form#forgotForm'),
    $message = $('#formResponseMessage', $form);


/**
 * Обновление всех фильтруемых данных на странице.
 * Примечание: эта функция имеет полиморфное поведение в зависимости от страницы.
 */
function refreshAll()
{
    // На странице нет данных, зависящих от региона
}

/**
 * Валидация формы сброса пароля.
 *
 * Для локализации сообщений об ошибках использует константы, которые должны передаваться
 * контроллером страницы в вид jsVars.php, создающий из этих констант js-переменные.
 *
 * @param $form object Объект формы регистрации. Можно передать с jQuery-обёрткой.
 * @return boolean Возвращается true, если форма валидна, и false в противном случае.
 */
function validateForgotPassword($form)
{
    if( !$form )
        return false;
    $form = $($form);

    var field = {},
        value = '',
        isFocused = false,
        result = true;

    // Логин/e-mail (обязательно):
    field = $('#loginField', $form);
    value = $.trim(field.val());
    if(value.length <= 0) {
        field.addClass('invalidField');
        $('#loginError', $form).html(LANG_LOGIN_REQUIRED).fadeIn(250);
        result = result && false;
    } else if( !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value) ) {
        field.addClass('invalidField');
        $('#loginError', $form).html(LANG_LOGIN_INVALID).fadeIn(250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#loginError', $form).fadeOut(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    return result;
}

$(function(){ // Готовность DOM
    // Отправка формы:
    $form.submit(function(e){
        e.preventDefault();

        if( !validateForgotPassword($form) ) // Отменить сабмит, если валидация не пройдена
            return;

        var $ajaxLoading = $('img#formResponseLoading', $form);
        var $formSubmit  = $('input#addSubmit', $form);

        $ajaxLoading.show();
        $formSubmit.attr('disabled', 'disabled').animate({opacity: 0.25});

        $.ajax('/auth/forgotPasswordProcess', {data: $form.serialize(),
                                               type: 'post',
                                               async: true,
                                               dataType: 'json',
                                               cache: false})
         .success(function(resp){
             if(resp.status && resp.status == 'success') {
                 $message.html('<div class="successMessage red">'+resp.message+'</div>')
                         .stop(true).fadeIn(200);
             } else {
                 if(resp.errors) {
                     $message.html('<div class="errorMessage red">'+resp.errors+'</div>')
                             .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 } else if(resp.message) {
                     $message.html('<div class="errorMessage red">'+resp.message+'</div>')
                             .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 } else {
                     $message.html('<div class="errorMessage red">Ошибка при обработке!</div>')
                             .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
                 }
             }

             $ajaxLoading.hide();
             $formSubmit.removeAttr('disabled').animate({opacity: 1.0});
         })
         .error(function(jqXHR, textStatus){
             $message.html('<div class="errorMessage red">Ошибка при обработке!</div>')
                     .stop(true).fadeIn(200).fadeTo(5000, 1.0).fadeOut(200);
             $ajaxLoading.hide();
             $formSubmit.removeAttr('disabled').animate({opacity: 1.0});
         });
    });
});