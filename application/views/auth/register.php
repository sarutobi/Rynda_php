<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления страницы регистрации пользователя в системе.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/auth/register.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="clearfix"></div>
<div class="login_box_container rounded_all">
	<div class="login_box_lr rounded_top">
		<a class="login_box_login_reg" href="/auth/login/">Логин</a>
        <div class="login_box_registration_reg">
        	Регистрация
        </div>
       	<div class="clearfix"></div>
    </div>
	<div class="reg_box_form_container">
				
        <form id="regForm" class="registration" action="#" method="post">
			<span class="attention">Все поля обязательны для заполнения.</span>
			<br />
			<div>
				          
                <div class="reg_error" id="firstNameError"></div>
                    <label for="firstNameField">Имя:</label> 
                    <input type="text" class="fname_box_input" maxlength="25" id="firstNameField" name="firstNameField"><br />
                
                <div class="reg_error" id="lastNameError"></div>
                    <label for="lastNameField">Фамилия:</label> 
                    <input type="text" class="lname_box_input" maxlength="25" id="lastNameField" name="lastNameField"><br />
				                                    
                <div class="reg_error" id="loginError"></div>
                    <label for="loginField">Email:</label> 
                    <input type="text" class="login_box_input" id="loginField" name="loginField"><br />  
                                  
                <div class="reg_error" id="passwordError"></div>
                    <label for="passwordField">Пароль:</label>
                    <input type="password" class="login_box_input" id="passwordField" name="passwordField"><br />
                                  
                    <label for="passwordConfirmField">Повторите пароль:</label>
                    <input type="password" class="login_box_input" id="passwordConfirmField" name="passwordConfirmField"><br />
                </div>
				<div class="clear"></div>
                <label  class="nofloat">
                    <input type="checkbox" value="1" id="agreed">
                        Я разрешаю использование и обработку  указанных в анкете данных
                </label>
<br />
                <div style="display:none;" class="validationError" id="agreedError"></div>
				<br />
                <input type="submit" class="nofloat login_box_submit" value="ЗАРЕГИСТРИРОВАТЬСЯ" id="regSubmit">
                <img alt="Загружается..." id="regLoading" style="display:none;" src="/images/white_sm_loader.gif">
            	<div style="display: none;" class="login_box_message" id="regMessage"></div>
				<div class="tc mt10">
					
				</div>
            </div>
        </form>		
    </div>
</div>        
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="/javascript/authRegister.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>