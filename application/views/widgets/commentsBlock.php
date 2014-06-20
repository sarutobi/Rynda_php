<?php $jsVars['LANG_COMMENT_TEXT_REQUIRED'] = $this->lang->line('forms_CommentTextRequired'); 
      $jsVars['LANG_COMMENT_NAME_REQUIRED'] = $this->lang->line('forms_CommentNameRequired'); 
      $jsVars['LANG_COMMENT_EMAIL_REQUIRED'] = $this->lang->line('forms_CommentEmailRequired');
      $jsVars['LANG_COMMENT_EMAIL_INVALID'] = $this->lang->line('forms_CommentEmailInvalid');
      $jsVars['LANG_COMMENT_PREMODERATING'] = $this->lang->line('forms_CommentPremoderating');
      $jsVars['VAR_USER_ID'] = isset($user->id) ? $user->id: null;
      $jsVars['CONST_COMMENTS_MAX_DEPTH'] = json_encode($this->config->item('comments_max_depth'));
      $jsVars['CONST_ALLOWED_TAGS'] = json_encode($this->config->item('allowed_html'));
      
      $this->load->view('jsVars', array('jsVars' => $jsVars));
?>

<div class="g960 inside" id="comments">
  </script>
	<div id="is_comment_buttonContainer"></div>
	
	<div id="loading" style="display:none; background: url('/css/i/sw_loader.gif') no-repeat; width: 16px; height: 16px; margin: 0 auto"></div>
    <h3 class="showcomments mt0 mb0" >
        Комментарии
    </h3>
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
    
    <div id="commentsText" style="display: none">
        Всего комментариев: <span class="all-comments"></span>, 
        ожидают модерации: <span class="premoderating-comments"></span>. 
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
          Всего комментариев: <span class="all-comments"></span>, 
          ожидают модерации: <span class="premoderating-comments"></span>.
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
<div class="commentBlock_inner rounded_all">
  <div class="commentBlock_avatar">
	<img class="zin1 absolute ml5" src="${avatar_url}" title="${userName}" />
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
			</div>
		{{if $item.data.depth < CONST_COMMENTS_MAX_DEPTH-1}}
			{{tmpl({id: id, text: 'Отправить'}) "#commentAnswerTmpl"}}
		{{/if}}
		</div>
	
	</div>
<div class="clear"></div>
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
	   
        <div class="expandableComents_form_toping">&nbsp;
        </div>	
        <div class="expandableComents_form_inside rounded_all">
          <div class="close-container" style="position:relative; left: 640px; cursor:pointer">
            <a>&nbsp;</a>
          </div>
            <div class="notice" style="color:green;">
            </div>
            <div class="error" style="color:red">
            </div>
          <div id="textError${$data.id}" class="absolute ml25 red" style="top: 6px"></div>
            <textarea id="textarea${$data.id}" class="rounded_all" style="height: 150px; width: 600px;"></textarea><br/>
            {{if !VAR_USER_ID}}
              <div id="nameError${$data.id}" class="absolute ml25 red"></div>
              <div id="emailError${$data.id}" class="absolute ml25 red"></div>
              <div class="relative ml25 mt25">
                <label for="name" class="relative ml10">имя:</label>      
                <input type="text" class="username" name="name"/>
                <label for="email" class="relative ml25">email:</label>       
                <input type="text" class="email" name="email"/><br/>
                <input type="checkbox" class="checkbox mt10">
                  отправлять уведомления об ответах в этой ветке
                </input> 
              </div>
            {{/if}}
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
