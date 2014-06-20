<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления страницы входа в систему.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/auth/login.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="clearfix"></div>
<div class="login_box_container rounded_all">
	<div class="login_box_lr rounded_top">
    	<div class="login_box_login">Логин</div>
		<a class="login_box_registration" href="/auth/register/">Регистрация</a>
       	<div class="clearfix"></div>
    </div>
	<div class="login_box_form_container">
        <?php if($activationSuccess) {?>
        <div class="activation_success rounded_all"><?php echo $activationSuccess;?></div>
        <?php } else if($activationError) {?>
        <div class="activation_error rounded_all"><?php echo $activationError;?></div>
        <?php } else if($resetPasswordComplete) {?>
        <div class="activation_success rounded_all"><?php echo $resetPasswordComplete;?></div>
        <?php }?>
        <form id="loginForm" action="#" method="post">
            <div>
            <p>
                <label for="loginField">Email:</label><br />
                <input tabindex="1"  type="text" name="loginField" id="loginField" class="login_box_input" />
            </p>
            <p>
            	<div class="login_box_forgot_pass">
                	<a href="/forgot">Я забыл пароль!</a>
                </div>
                <label for="passwordField">Пароль:</label><br />
                <input  tabindex="2" type="password" name="passwordField" id="passwordField" class="login_box_input" />
                
            </p>
            <p>
                <input tabindex="3" type="checkbox" name="rememberField" id="rememberField" value="1" checked />
                <label for="rememberField">Помнить меня</label>
            </p>
            <img id="loginLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
            <div id="loginMessage" class="login_box_message" style="display: none;"></div>
             <input  tabindex="4" type="submit" id="loginSubmit" class="login_box_submit" value="ВОЙТИ" />

            </div>
        </form>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/authLogin.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
