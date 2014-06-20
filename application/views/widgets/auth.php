<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления виджета для блока аутентификации пользователя.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/widgets/auth.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div id="auth">
    <!-- Контент модального окна для выбора региона: -->
    <div style="display: none">
        <div id="regionModal" class="rounded_all">
            <div class="regionModal_wrap">Выберите регион, который Вам интересен, пожалуйста:<br /><br />
            <select name="regionId" id="regionFieldModal">
                <option value="0" selected>Все регионы</option>
                <?php foreach((array)$regions as $region) {?>
                    <option value="<?php echo $region['id'];?>">
                        <?php echo $region['name'];?>
                    </option>
                <?php }?>
            </select>
            <div id="regionFindMe"><span class="expandableTitle">Найти, в каком регионе я сейчас</span></div>
            <div id="findMeMessage" style="display:none"></div>
            <img id="findMeLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
            <div id="regionModalOk" class="pointer">Ок</div>
            <div id="regionModalCancel" class="pointer">Отмена</div></div>
        </div>
    </div>
    <!-- Контент модального окна для выбора региона - завершён -->
	<!-- Блок выбора региона пользователя: -->
    <div id="user_location">
        <div class="globe_icon pointer" id="region_select_switch"></div>
        <div class="region_location_div" id="region_select_div" style="display:none;">
            <?php if($this->input->cookie('ryndaorg_region')) { // Весь этот кусок надо перенести в mainIndex?>
            Ваш регион: <span id="userRegionName"><?php echo $userRegion ? $userRegion['name'] : 'вся страна';?></span> <br />
        (<span id="regionSelect" class="expandableTitle">изменить</span>)
            <?php } else {?>
            Ваш регион: <span id="userRegionName">вся страна</span> <br />
        (<span id="regionSelect" class="expandableTitle">выбрать регион</span>)
            <?php }?>
        </div>
    </div>
    <!-- Блок выбора региона пользователя - завершён -->
    <!-- Контент модального окна для аутентификации: -->
    <div style="display: none">
        <div id="authModal">
            <form id="loginForm" action="#" method="post">
                <div>
                <p>
                    <label for="loginField">Email:</label><br />
                    <input tabindex="1" type="text" name="loginField" id="loginField" class="formField login_box_input" />
                </p>
                <p>
                    <div class="login_box_forgot_pass">
                        <a href="/forgot">Я забыл пароль!</a>
                    </div>
                    <label for="passwordField">Пароль:</label><br />
                    <input  tabindex="2" type="password" name="passwordField" id="passwordField" class="formField login_box_input" />

                </p>
                <p>
                    <input tabindex="3" type="checkbox" name="rememberField" id="rememberField" value="1" checked />
                    <label for="rememberField">Помнить меня</label>
                </p>
				<img id="loginLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
                <div id="loginMessage" class="login_box_message" style="display: none;"></div>
			<div id="authmodal_button_panel">
                <input tabindex="4" type="submit" id="loginSubmit" class="login_box_submit" value="ВОЙТИ" />
                <input tabindex="5" type="reset" id="loginCancel" class="login_box_submit" value="ОТМЕНА" />
            </div>
        </div>
    </form>
        </div>
    </div>
    <!-- Контент модального окна для аутентификации - завершён -->
    <!-- Контент модального окна для регистрации: -->
    <div style="display: none">
        <div id="registerModal">
            <form id="regForm" action="#" method="post">
                <div>
					<span class="attention">
						Обратите внимание, что поля, помеченные знаком * обязательны для заполнения.
					</span>
					
					<div id="regfio_container">
						<p>
							<div id="firstNameError" class="form_error"></div>
							<label for="firstNameField">*Имя:</label>
							<input type="text" name="firstNameField" id="firstNameField" class="formField fname_box_input"  />
						</p>
						<p>
						
							<div id="lastNameError" class="form_error"></div>
							<label for="lastNameField">*Фамилия:</label>
							<input type="text" name="lastNameField" id="lastNameField" class="formField lname_box_input"  />
						</p>
					</div>

					<div id="reg_mail_pass_container">
                    <p>
					
                        <div id="loginError" class="form_error"></div>
                        <label for="loginField">*Email:</label>
                        <input type="text" name="loginField" id="loginField" class="formField login_box_input"  />
                    </p>
                    <p>
                        <div id="passwordError"  class="form_error"></div>
                        <label for="passwordField">*Пароль:</label>
                        <input type="password" name="passwordField" id="passwordField" class="formField login_box_input"  />
                    </p>
                    <p>
                        <label for="passwordConfirmField">*Повторите пароль:</label>
                        <input type="password" name="passwordConfirmField" id="passwordConfirmField" class="formField login_box_input" />
                    </p>
					</div>
                    <label>
                        <input type="checkbox" id="agreed" class="mb10" value="1" />
                            Я разрешаю использование и обработку  указанных в анкете данных
                    </label>
                    <div id="agreedError" class="validationError" style="display:none;">Вы должны отметить этот пункт, если хотите зарегистрироваться. Иначе мы не имеем права хранить ваши персональные данные, согласно законодательству РФ.</div>
					<div id="regMessage" class="login_box_message" style="display: none;"></div>
                    <input type="submit" id="regSubmit" value="ЗАРЕГИСТРИРОВАТЬСЯ" class="login_box_submit" />
                    <input tabindex="5" type="reset" id="regCancel" class="login_box_submit" value="ОТМЕНА" />
                    <img id="formResponseLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
                    
                </div>
            </form>
        </div>
    </div>
    <!-- Контент модального окна для регистрации - завершён -->
    <!-- Блок аутентификации: -->
    <?php if(empty($user)) { ?>
    <a id="authLogIn" href="/login">Войти в систему</a> | <a id="authRegister" href="/register">Зарегистрироваться</a>
    <?php } else {?>
    Вы вошли как <?php echo $this->rynda_user->getReference($user);?> |
    <?php if(empty($loginHrefOff)) {?>
        <a href="/user/<?php echo $user->id;?>">Моя страница</a>
    <?php } else {?>Моя страница<?php }?>
     |
    <a id="authLogout" href="#">Выйти</a>
    <!--<img id="authResponseLoading" src="/images/white_sm_loader.gif" alt="" style="display: none;" />-->
    <?php }?>
    <!-- Блок аутентификации - завершён -->
    
    
</div>