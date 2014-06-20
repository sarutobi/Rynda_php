<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления страницы регистрации пользователя в системе.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/auth/register.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */ ?>
<div class="clearfix"></div>
<div class="login_box_container rounded_all">
	<div class="header_container rounded_top">
    	<h3 class="mb0 rounded_top regulartext_header">Восстановление пароля</h3>    
    </div>
    <div class="login_box_form_container">
        <form id="forgotForm" action="#" method="post">
        	<div>
                <label for="loginField">Введите ваш E-mail:</label> <br />
                <input type="text" name="loginField" id="loginField"  class="fname_box_input"  />            
                <input type="submit" id="forgotSubmit" value="ВОССТАНОВИТЬ ПАРОЛЬ" class="login_box_submit mt10" />
                <img id="formResponseLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
                <div id="loginError" class="errorMessage red mt10"></div>
            	<div id="formResponseMessage" class="login_box_message" style="display: none;"></div>
            </div>
        </form>
     </div>
</div>        
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>

<script src="/javascript/authForgotPassword.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>