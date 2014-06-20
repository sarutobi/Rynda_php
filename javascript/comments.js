/**
 * ************************
 * Собственный код страницы
 * ************************
 */

$(document).ready(function(){
    // Отображает кнопку ответа на пост:
    $('#commentAnswerTmpl')
      .tmpl({id: 0, text: 'Добавить комментарий', buttonId: 'is_comment_button'})
      .appendTo('#commentAnswer')
	  .appendTo('#is_comment_buttonContainer');
    updateCommentsButtonsAttach();
    
    // Выводит количество комментов:
    showCommentsCount();
    
    // Раскрывает комменты:
    if(VAR_COMMENTS_EXPAND){
        expandComments();
    } else {
        $('.showcomments').click(function(){
            $('#loading').show();
            expandComments();
        });
    }
    
    //разворачивание блока ответа на комментарий
    $('#comments').delegate('a.expandComents_form div','click', function(){
        $('.expandableComents_form').hide();
        $('.answerComment_button').removeClass('active_answer');
        $('.commentBlock').removeClass('answerTarget');
		$('.commentBlock_inner').removeClass('answerTarget');
		$('.commentBlock_avatar img.relative').attr('src', '/css/i/com_av_blue.png');
        $(this).addClass('active_answer');
        $parent = $(this).parent().closest('div');
        $parent.next().slideDown('2000');
        greatParent = $parent.parent().parent().parent();
        greatParent.addClass('answerTarget');
        if(greatParent.attr('id') != 'comments'){
           $('img.relative', greatParent).attr('src', '/css/i/com_av_white.png');
        }
        
        $('textarea', $parent.parent('div')).htmlarea({
            css: '../css/jHtmlArea.Editor.css',
            toolbar: [{
                // Кастомная кнопка p:
                css: 'p',
                text: 'Параграф',
                action: function(btn) {
                    // 'this' = jHtmlArea object
                    // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
                    this.pasteHTML('<p>'+this.getSelectedHTML()+'</p>');
                }
            },'|',{
                // Кастомная кнопка b:
                css: 'bold',
                text: 'Полужирный',
                action: function(btn) {
                    // 'this' = jHtmlArea object
                    // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
                    this.pasteHTML('<b>'+this.getSelectedHTML()+'</b>');
                }
            }, {
                // Кастомная кнопка i:
                css: 'italic',
                text: 'Курсив',
                action: function(btn) {
                    // 'this' = jHtmlArea object
                    // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
                    this.pasteHTML('<i>'+this.getSelectedHTML()+'</i>');
                }
            }, {
                // Кастомная кнопка u:
                css: 'underline',
                text: 'Подчёркнутый',
                action: function(btn) {
                    // 'this' = jHtmlArea object
                    // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
                    this.pasteHTML('<u>'+this.getSelectedHTML()+'</u>');
                }
            },{
                // Кастомная кнопка s:
                css: 'strikethrough',
                text: 'Зачёркнутый',
                action: function(btn) {
                    // 'this' = jHtmlArea object
                    // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
                    this.pasteHTML('<strike>'+this.getSelectedHTML()+'</strike>');
                }
            }, '|',
                      'orderedlist', 'unorderedlist', '|',
                { // Кастомная кнопка ссылки:
                    css: 'link',
                    text: 'Ссылка',
                    action: function(btn) {
                        // 'this' = jHtmlArea object
                        // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
                        var url = prompt('Введите ссылку:');
                        this.pasteHTML('<a href="'+url+'" class="jhtmlarea">'+this.getSelectedHTML()+'</a>');
                    }
                }],
            toolbarText: $.extend({},jHtmlArea.defaultOptions.toolbarText, {
                'p': 'Параграф',      
                'bold': 'Полужирный',
                'italic': 'Курсив',
                'underline': 'Подчеркнутый',
                'strikethrough': 'Зачёркнутый'
            })
        });
    });

    // Для сворачивания блока:
    $('.close-container').live('click',function(){
        var parent = $(this).parent().parent().parent().parent();
        closeBlock(parent);
    });

    //подписка на все комментарии
    $('#submitComments_do').click( function(){
      var subscribeBlock = $('#submitComments_container');
      if(!VAR_USER_ID){
          $('#submitComments_enterEmail_Container').show();
          $('#submitEmail-Button').click(function(){
              if(subscribeProcess(subscribeBlock)){
                  $('#submitComments_enterEmail_Container input').removeAttr('value');
                  $('#submitComments_enterEmail_Container').hide();
              }
          });
          $('#cancelEmail-Button').click(function(){
              $('#submitComments_enterEmail_Container').hide();
          });
      } else {
          subscribeProcess(subscribeBlock);
      }
    });
    
    //Отписка от комментариев
    $('#submitComments_dont').click(function(){
        var loading_submit = $('#loading_submit');
        loading_submit.show();
        $('#submitComments_false_container').hide();
        
        $.ajax('/subscriber/unsubscribeProcess',
             {data: {userId: VAR_USER_ID, messageId: VAR_MESSAGE_ID},
              type: 'post',
              async: true,
              dataType: 'json',
              cache: false})
        .success(function(resp){
            loading_submit.hide();
            $('#submitComments_true_container').show();
        })
        .error(function(jqXHR, textStatus){ 
            loading_submit.hide();
            $('#submitComments_false_container').show();
        });        
    })
    
    //удаление сообщений, удалить как модератор, пометить как спам
    $('#comments').delegate('.del_message','click', function(){
        $.ajax('/ajax_forms/setStatusCommentProcess',
             {data: {commentId: $(this).attr('cid'), userId: VAR_USER_ID, action: $(this).attr('action')},
              type: 'post',
              async: true,
              dataType: 'json',
              cache: false})
        .success(function(resp){
           
        })
        .error(function(jqXHR, textStatus){ 

        });       
    });

    
});

//подписка на комменты
function subscribeProcess(subscribeBlock)
{
    var loading_submit = $('#loading_submit');
    
    $('#submitComments_true_container').hide();
    
    if(VAR_USER_ID){
        email = '';
        name = '';
    } else {
        email = $('input:text[name=email]', subscribeBlock).attr('value');
        name = $('input:text[name=name]', subscribeBlock).attr('value');
        
        //валидация
        if(name.length < 2){
            alert('имя обязательно');
            return false;
        }else if(email.length < 5){
            alert('email обязателен');
            return false;
        } else if(!/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(email)){
            alert(LANG_COMMENT_EMAIL_INVALID);
            return false;
        }
    }
    loading_submit.show();
  
    $.ajax('/subscriber/addSubscriberProcess',
         {data: {subscribeType: $('input:hidden[name=subscribeType]', subscribeBlock).attr('value'),
                 subscribeTo: $('input:hidden[name=subscribeTo]', subscribeBlock).attr('value'),
                 email: email,
                 name: email,
                 userId: VAR_USER_ID
                },
          type: 'post',
          async: true,
          dataType: 'json',
          cache: false})
    .success(function(resp){
        var submitComments_true = $('#submitComments_true');
        loading_submit.hide();
        submitComments_true.show();
        setTimeout(function(){
            submitComments_true.hide();
            if(VAR_USER_ID){
                $('#submitComments_false_container').show();
            }
        }, 3000);        
    })
    .error(function(jqXHR, textStatus){
        loading_submit.hide();
        var submitComments_false = $('#submitComments_false');
        submitComments_false.show();
        setTimeout(function(){
            submitComments_false.hide();
            $('#submitComments_true_container').show();
        }, 3000);         
    });
    
    return true;
}

function showCommentsCount()
{
    $('#loading').show();
    $.ajax('/ajax_forms/countComments',
         {data: {messageId: VAR_MESSAGE_ID},
          type: 'post',
          async: true,
          dataType: 'json',
          cache: false})
    .success(function(resp){
        var allComments = eval(resp.allComments);
        $('#loading').hide();
        if(allComments > 0){
            $('#commentsText').show();
            $('.all-comments').text(allComments);
            $('#commentsText a').show();
        }
    });     
}

function expandComments()
{
    $('.showcomments').hide();
    $.ajax('/ajax_forms/getCommentsTree',
         {data: {messageId: VAR_MESSAGE_ID},
          type: 'post',
          async: true,
          dataType: 'json',
          cache: false})
    .success(function(resp){
        $('#loading').hide();
        $('#commentsText').hide();
        $('#commentsPanel').show();
        $('#commentsBlock').show();

        $('#commentFolderTmpl').tmpl(eval(resp)).appendTo('#commentsBlock');
        
        if(VAR_COMMENTS_EXPAND){
            $.scrollTo(window.location.hash);
        }
    })
    .error(function(jqXHR, textStatus){ 

    });    
}

function updateCommentsButtonsAttach()
{
    $('body').delegate('.answer-button','click',function(){
        if($(this).attr('disabled')) 
            return true;

        var parent = $(this).parent().parent().parent();
        var id = $(this).attr('itemid');
        var answerBlock = $('#answer'+id);

        if(!validateCommentAdd(id))
            return false;

        // Удаление из текста всех тэгов кроме CONST_ALLOWED_TAGS:
        var tags = [];
        $.each(JSON.parse(CONST_ALLOWED_TAGS), function(key, val){
        tags[key] = val.substr(1, val.length-2);
        }); 
        var textareaField = $('textarea', answerBlock);
        var value = textareaField.htmlarea('toHtmlString');    
        var formattedText = $.htmlClean(value, {allowedTags: tags,
                                                allowedAttributes: [['class']],
                                                removeAttrs: [],
                                                allowedClasses: ['jhtmlarea']});
//        console.log(formattedText, $(formattedText));
//        $.each($(formattedText), function(){
//            if(this.nodeName.toLowerCase() == 'a' && !$(this).hasClass('jhtmlarea'))
//                console.log(this);
//            //$($(this).parent()).html($(this).text());
//        });
        return false;

        var data = {messageId: VAR_MESSAGE_ID,
                    parentId: id,
                    text: formattedText,
                    subscribe: $('.checkbox', answerBlock).is(":checked")
        }
        if(VAR_USER_ID) {
            data.userId = VAR_USER_ID;
        } else {
            data.userName = $('.username', answerBlock).val();
            data.email = $('.email', answerBlock).val();
          
        }
        $.ajax('/ajax_forms/addCommentProcess',
            {data: data,
            type: 'post',
            async: true,
            dataType: 'json',
            cache: false})
        .success(function(resp){
            notice = $('.notice', answerBlock);
            notice.html(LANG_COMMENT_PREMODERATING).slideDown(250);
            setTimeout(function(){
                notice.slideUp(250).html(''); 
                closeBlock(parent.parent());
            }, 3000);
            incCommentsCount();
        })
        .error(function(jqXHR, textStatus){
            error = $('.error', answerBlock);
            error.html('Произошла ошибка').slideDown(250);
            setTimeout(function(){
                error.slideUp(250).html(''); 
                closeBlock(parent.parent());
            }, 3000);
        });
    });
}

$('expandComents_form').click(function(){
    $('.expandableComents_form').removeClass('no_display');
});

function validateCommentAdd(parentId)
{
    var result = true;
    var isFocused = false;
    var answerBlock = $('#answer'+parentId);

    // Текст(обязательно):
    field = $('textarea', answerBlock);
    text = field.htmlarea('toHtmlString');
    value = $.trim(text);
    if(value.length <= 4) {
        field.addClass('invalidField');
        $('#textError'+parentId, answerBlock).html(LANG_COMMENT_TEXT_REQUIRED).slideDown( 250);
        result = result && false;
    } else {
        field.removeClass('invalidField');
        $('#textError'+parentId, answerBlock).slideUp(250).html('');
    }
    if( !result && !isFocused ) {
        field.focus();
        isFocused = true;
    }

    if(!VAR_USER_ID){
        //имя(обязательно)
        field = $('.username', answerBlock);
        text = field.val();
        value = $.trim(text);
        if(value.length <= 1) {
            field.addClass('invalidField');
            $('#nameError'+parentId, answerBlock).html(LANG_COMMENT_NAME_REQUIRED).slideDown(250);
            result = result && false;
        } else {
            field.removeClass('invalidField');
            $('#nameError'+parentId, answerBlock).slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }

        // Email (необязательно, но при влюченном чекбоксе - обязательно):
        field = $('.email', answerBlock);
        value = $.trim(field.val());
        if($('.checkbox', answerBlock).is(':checked') && value.length <= 1) {
            field.addClass('invalidField');
            $('#emailError'+parentId, answerBlock).html(LANG_COMMENT_EMAIL_REQUIRED).slideDown(250);
            result = result && false;            
        } else if(value && !/^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i.test(value)) {
            field.addClass('invalidField');
            $('#emailError'+parentId, answerBlock).html(LANG_COMMENT_EMAIL_INVALID).slideDown(250);
            result = result && false;
        } else {
            field.removeClass('invalidField');
            $('#emailError'+parentId, answerBlock).slideUp(250).html('');
        }
        if( !result && !isFocused ) {
            field.focus();
            isFocused = true;
        }        
    }

    // Блокирование кнопки "отправить":
    $('answer-button', answerBlock).attr('disabled', 'true');

    return result;
}

function closeBlock(block)
{
    // Очистка поля сообщения:
    $('textarea', block).attr('value','').htmlarea('updateHtmlArea');
    $('.username', block).removeAttr('value');
    $('.email', block).removeAttr('value');
    $('.checkbox', block).removeAttr('checked');

    $('.expandableComents_form', block).slideUp('2000');
    $('.answerComment_button', block).removeClass('active_answer');  

    greatParent = block.parent().parent();
    $('.answerTarget',greatParent).removeClass('answerTarget');
    $('img.relative', greatParent).attr('src', '/css/i/com_av_blue.png');

    // Снятие блока кнопки "отправить":
    $('.answer-button', block).removeAttr('disabled');
}

function incCommentsCount()
{
    var counts = $('#com_white-pan');
    var all = $('span.all-comments', counts);
    var await = $('span.premoderating-comments', counts);

    allCount = eval(all.text()); 
    awaitCount = eval(await.text()); 
    all.html(++allCount);
    await.html(++awaitCount);
}

