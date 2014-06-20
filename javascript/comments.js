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
    $('.showcomments').click(function(){
        $('.showcomments').hide();
        $('#loading').show();
        $.ajax('/ajax_forms/getCommentsTree',
             {data: { messageId: VAR_MESSAGE_ID },
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

            //updateCommentsButtonsAttach();
            
        })
        .error(function(jqXHR, textStatus){ 

        });          
    });
    
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
        $('img.relative', greatParent).attr('src', '/css/i/com_av_white.png');
        
        $('textarea', $parent.parent('div')).htmlarea({
            css: '../css/jHtmlArea.Editor.css',
            toolbar: ['p','|','bold','italic','underline','strikethrough', '|',
                      'orderedlist', 'unorderedlist', '|', 'link', 'unlink'],
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
});


function showCommentsCount()
{
    $('#loading').show();
    $.ajax('/ajax_forms/countComments',
         {data: { messageId: VAR_MESSAGE_ID },
          type: 'post',
          async: true,
          dataType: 'json',
          cache: false})
    .success(function(resp){
        $('#loading').hide();
        $('#commentsText').show();

        $('.all-comments').text(eval(resp.allComments));
        $('.premoderating-comments').text(eval(resp.premoderating));
        $('#commentsText a').show();
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
//        alert(value);
        var formattedText = $.htmlClean(value, {allowedTags : tags});
//        alert(formattedText);


        if(VAR_USER_ID) {
            var data = {messageId: VAR_MESSAGE_ID,
                        parentId: id,
                        text: formattedText,
                        userId: VAR_USER_ID}
        } else {
            var data = {messageId: VAR_MESSAGE_ID,
                        parentId: id,
                        text: formattedText,
                        userName: $('.username', answerBlock).val(),
                        email: $('.email', answerBlock).val()}  
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

