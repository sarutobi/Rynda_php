<?php $jsVars['LANG_COMMENT_TEXT_REQUIRED'] = $this->lang->line('forms_CommentTextRequired'); 
      $jsVars['LANG_COMMENT_NAME_REQUIRED'] = $this->lang->line('forms_CommentNameRequired'); 
      $jsVars['LANG_COMMENT_EMAIL_REQUIRED'] = $this->lang->line('forms_CommentEmailRequired');
      $jsVars['LANG_COMMENT_EMAIL_INVALID'] = $this->lang->line('forms_CommentEmailInvalid');
      $jsVars['LANG_COMMENT_PREMODERATING'] = $this->lang->line('forms_CommentPremoderating');
      $jsVars['VAR_USER_ID'] = isset($user->id) ? $user->id: null;
      $jsVars['VAR_USER_NANE'] = isset($user->id) ? $user->first_name.' '.$user->last_name: null;
      $jsVars['VAR_USER_EMAIL'] = isset($user->id) ? $user->email: null;
      $jsVars['VAR_USER_IS_MODER'] = isset($user->id) && isset($user->group) ? ($user->group == 'moder'? true: false): false;
      $jsVars['VAR_MESSAGE_ID'] = $message['id'];
      $jsVars['CONST_COMMENTS_MAX_DEPTH'] = json_encode($this->config->item('comments_max_depth'));
      $jsVars['CONST_ALLOWED_TAGS'] = json_encode($this->config->item('allowed_html'));
      
      $this->load->view('jsVars', array('jsVars' => $jsVars));
?>

<div class="g960 inside" id="comments">
	<div id="is_comment_buttonContainer"></div>
	
	<div id="loading" style="display:none; background: url('/css/i/sw_loader.gif') no-repeat; width: 16px; height: 16px; margin: 0 auto"></div>
    <h3 class="showcomments mt0 mb0" >
        Комментарии
    </h3>
    <div class="subscribe-b">
      
    </div>
  
    <?php
    /*
      <script type="text/javascript">
        var idcomments_acct = '2e27faf8f891ba4499a93f2e002ecfc5';
        var idcomments_post_id;
        var idcomments_post_url;
      </script>
      <span id="IDCommentsPostTitle" style="display:none"></span>
      <script type="text/javascript" src="http://www.intensedebate.com/js/genericCommentWrapperV2.js"></script>
    */?>
	<div id="submitComments_container">
    <div id="submitComments_true_container" class="<?php if($isSubscribed) echo 'no_display'?>">
      <input type="hidden" name="subscribeType" value="0" />
      <input type="hidden" name="subscribeTo" value="<?php echo $message['id'] ?>" />      
      <a id="submitComments_do" title="Уведомлять меня о новых комментариях">Уведомлять меня о новых комментариях</a>
    </div>
    <div id="submitComments_false_container" class="<?php if(!$isSubscribed) echo 'no_display'?>">
      <a id="submitComments_dont" title="Больше не уведомлять меня о новых комментариях" >Больше не уведомлять меня о новых комментариях</a>
    </div>      
		<div id="submitComments_status_container" >
			<div id="submitComments_true" class="green no_display ">Подписка на уведомления прошла успешно</div>
			<div id="submitComments_false" class="red no_display">Подписка на уведомления не состоялась</div>
			<div id="loading_submit" style="background: url('/css/i/sw_loader.gif') no-repeat scroll 0% 0% transparent; width: 16px; height: 16px;" class="no_display">&nbsp;</div>
		</div>
    <?php if(!isset($user->id)): ?>
      <div id="submitComments_enterEmail_Container" class="no_display">
        <div id="submitComments_enterEmail_panel" class="rounded_all bgwhite">
          <div id="submitComments_enterEmail_header" class="bggrey rounded_top pl10 pt5">
            Введите адрес вашей почты
          </div>
          <div class="pa20">
			<div class="block_display alignleft">
            <div>email:</div><input id="submitComments_enterEmail" class="pa5 alignright relative" type="text" name="email" tabindex="1">
			</div>
			<div class="block_display alignleft mt10">
            <div>имя:</div><input id="submitComments_enterName" class="pa5 alignright relative" type="text" name="name" tabindex="1">
			</div>
            	<a class="" >
				  <div id="submitEmail-Button" class="all_caps white mb10 mt10 ml5 bluebutton pa5 alignleft">
					OK
				  </div>
				</a>
				<a >
				  <div id="cancelEmail-Button" class="all_caps white mb10 mt10 greybutton ml10 pa5 alignleft">
					ОТМЕНА
				  </div>
				</a>
			
          </div>
        </div>
      </div>
    <? endif ?>  
	</div>
    
    <div id="commentsText" style="display: none">
        Всего комментариев: <span class="all-comments"></span>. 
        <a class="showcomments" title="Развернуть комментарии к записи">
            <b>Развернуть комментарии к записи</b>
        </a>
    </div>        

    <div id="commentsPanel" style="display: none">
        <div class="header_container rounded_top">
          <h3 class="mb0 alignright bottom20 right20 relative regulartext_header">Комментарии 
          </h3>
        </div>
        <div id="comments_panel">
          <div id="com_white-pan">
          Всего комментариев: <span class="all-comments"></span>.
          </div>
        </div>    
    </div>  
    <div id="commentsBlock">
     
    </div>
    <div id="commentAnswer">
     
    </div>  
</div>

<?php
/*
 * код шаблона комментариев
 */?>
<script id="commentFolderTmpl" type="text/x-jquery-tmpl">

    <div id="comment${id}" class="commentBlock" style="padding-left:30px;margin-top:12px">
        {{tmpl "#commentItemTmpl"}}
        {{if $item.data.children}}
            {{tmpl(children) "#commentFolderTmpl"}}
        {{/if}}

    </div>
</script>

<?php
/*
 * код шаблона одного комментария
 */?>
<script id="commentItemTmpl" type="text/x-jquery-tmpl">
{{if $item.data.statusId == 2}}
    {{if $item.data.children}}
        <div >удалено</div>  
    {{/if}}
{{else $item.data.statusId == 3}}
    {{if $item.data.children}}
        <div >удалено модератором</div>  
    {{/if}}  
{{else $item.data.statusId == 4}}
    {{if $item.data.children}}
        <div >спам</div>  
    {{/if}}     
{{else}}   
    <div class="commentBlock_inner rounded_all">
        <div class="commentBlock_avatar">
            <img class="zin1 absolute" src="${avatar_url}" title="${userName}" />
            <img class="zin5 relative" src="/css/i/com_av_blue.png" />
        </div>

        <div class="commentBlock_textCol">
            <div class="commentItem_atr">
              <span class="comment_userName">${userName}</span>
              <span class="comment_dateAdded">${dateAdded}</span>
            </div>
            <div class="comment_mainText_Container">
              <div class="comment_mainText  rounded_all">
                {{html text}}
              </div>
              {{if VAR_USER_ID && VAR_USER_IS_MODER}}
                  <a class="del_message" cid="${id}" action="spam">пометить как спам</a>
                  <a class="del_message" cid="${id}" action="del_by_moder">удалить как модератор</a>
              {{else VAR_USER_ID && ($item.data.userId == VAR_USER_ID)}}
                  <a class="del_message" cid="${id}" action="delete">удалить</a>
              {{/if}}               
            </div>
            {{if $item.data.depth < CONST_COMMENTS_MAX_DEPTH-1}}
              {{tmpl({id: id, text: 'Отправить', isSubscribed: isSubscribed}) "#commentAnswerTmpl"}}
            {{/if}}
      </div>

      </div>
    <div class="clear"></div>
{{/if}}
</script>

<?php
/*
 * код шаблона добавления комментария (отправить)
 */?>
<script id="commentAnswerTmpl" type="text/x-jquery-tmpl">
    <div class="expandAnswer_button" id="answer${$data.id}" idq="${$data.id}">
      <div class="expandComents_form_Container">
          <a class="expandComents_form">
              <div {{if $data.buttonId}}id="${$data.buttonId}"{{/if}} 
                   class="answerComment_button tc rounded_all"
              >
                Ответить
              </div>
          </a>
        
      </div>      
      
      <div class="expandableComents_form">
        <div class="expandableComents_form_toping">&nbsp;</div>	
        <div class="expandableComents_form_inside rounded_all">
          <div class="close-container" style="position:relative; left: 640px; cursor:pointer">
            <a>&nbsp;</a>
          </div>
            <div class="notice" style="color:green;"></div>
            <div class="error" style="color:red"></div>
            <div id="textError${$data.id}" class="absolute ml25 red" style="top: 6px"></div>
            <textarea id="textarea${$data.id}" class="rounded_all" style="height: 150px; width: 600px;"></textarea><br/>
              <div id="nameError${$data.id}" class="absolute ml65 red"></div>
              <div id="emailError${$data.id}" class="absolute ml280 red"></div>
              <div class="relative ml25 mt25">
              {{if !VAR_USER_ID}}                
                <label for="name" class="relative ml10">имя:</label>      
                <input type="text" class="username" name="name"/>
                <label for="email" class="relative ml25">email:</label>       
                <input type="text" class="email" name="email"/><br/>
              {{else}}
                <label for="name" class="relative ml10">имя:</label>      
                <input type="text" class="username" name="name" value="${VAR_USER_NANE}" readonly="1"/>
                <label for="email" class="relative ml25">email:</label>       
                <input type="text" class="email" name="email" value="${VAR_USER_EMAIL}" readonly="1"/><br/>                
              {{/if}} 
              {{if !$data.isSubscribed}}  
                <input type="checkbox" name="subscribe" class="checkbox mt10">
                  отправлять уведомления об ответах в этой ветке
                </input>  
              {{/if}}
              </div>
          
            <br/>
            <div class="answer-button sendComment_answerButton rounded_all" itemid="${$data.id}">
                ${$data.text}
            </div>
          <?php /*<button class="answer-button" itemid="${$data.id}">${$data.text}</button>*/ ?>
        </div>
		</div>
      </div>
	  <div class="clearfix"></div>
</script>


<script src="/javascript/comments.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
