<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для страницы редактирования личных данных пользователя.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/auth/userPersonalEdit.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.6
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */?>
<div class="clearfix"></div>
<div class="breadcrumbs mb10 ml10 ">
    <a title="Главная" href="/">Главная</a> &raquo;
    <a href="/user/<?php echo $user->id;?>" title="Моя страница">Моя страница</a> &raquo; 
    Редактирование профиля
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>

<!-- Модальное окно смены пароля-->
<div style="display:none;">
    <div id="changePassModal" class="rounded_all">
        <form id="changePassForm" action="#" autocomplete="off">
            <label>
                <input type="password" id="oldPass" name="oldPass" maxlength="<?php echo $this->config->item('max_password_length');?>" /> Старый пароль
            </label>
            <div id="oldPassError" class="form_error"></div>
            <br /><br />
            <label>
                <input type="password" id="newPass" name="newPass" maxlength="<?php echo $this->config->item('max_password_length');?>" /> Новый пароль
            </label>
            <div id="newPassError" class="form_error"></div>
            <br /><br />
            <label>
                <input type="password" id="newPassConfirm" name="newPassConfirm" maxlength="<?php echo $this->config->item('max_password_length');?>" /> Новый пароль (ещё раз)
            </label><br /><br />
            <div id="changePassMessage" style="display:none;"></div>
            <img id="changePassLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
            <div class="passchange_button_panel">
                <input type="submit" id="changePassSubmit" value="ИЗМЕНИТЬ ПАРОЛЬ" />
                <input type="reset" id="changePassReset" value="ОТМЕНА" />
                <input type="reset" id="changePassSuccess" value="OK" style="display:none;" />
            </div>
        </form>
    </div>
</div>
<!-- Окончание модального окна -->

<div class="clearfix"></div>
<div id="user_profile_edit_box_container" class="rounded_all">
	<div class="header_container rounded_top">
		<a id="back_to_profile" href="/user/<?php echo $user->id;?>"><div id="editHeader">Вернуться на личную страницу</div></a>
		<h3 class="mb0 rounded_top regulartext_header"> Изменение ваших данных </h3>
	</div>
	<div id="user_profile_edit_box">
		<div id="edit_avatar_container">
			<div id="avatar_image">
				<img id="avatarImageItself" src="<?php echo $avatar ? site_url($avatar['uri']) : '/css/i/anonymous.png';?>" />
			</div>
			<div id="change_avatar_container">
				<div id="load_avatar_container">
					<br />
					<span>
							<div type="file" id="avatarField" name="avatar">Загрузить новый аватар</div>
							<img id="avatarLoading" src="/css/i/blue_sm_loader2.gif" style="display:none; padding-left:10px;" />
					</span>
					<div id="avatarFieldInfo">
						<div id="avatarMessage"></div>
	<!--                 <div id="avatarUploadedList"></div>-->
					</div>
				</div>
			</div>
		</div>
		<div id="userNameForm_container">
			<form id="userNameForm" action="#">
				<div id="attention_container">
					<span class="attention">
						Обратите внимание, что поля, помеченные знаком *, обязательны для заполнения.
					</span>
				</div>
				<div id="userName_container">
                    <label id="first_name">
						*Имя:&nbsp;<input type="text" id="firstName" name="firstName" value="<?php echo htmlentities($user->first_name, ENT_QUOTES, 'utf-8');?>" maxlength="25" />
						<div id="firstNameError" class="validationError"></div>
					</label>
					<label id="surname">
						*Фамилия:&nbsp;<input type="text" id="lastName" name="lastName" value="<?php echo htmlentities($user->last_name, ENT_QUOTES, 'utf-8');?>" maxlength="25" />
						<div id="lastNameError" class="validationError"></div>
					</label>
				</div>
				<div id="chpass-button-container"><a href="#changePassModal" id="changePass" class="rounded_all" title="Смена пароля для Вашей учётной записи на сайте">Сменить пароль</a>
				</div>
				<div id="short_info_container">
					<br />
					Пол:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<label><input type="radio" name="gender" value="1" <?php echo $user->gender == 1 ? 'checked' : '';?>>&nbsp;Муж.</label>&nbsp;&nbsp;
					<label><input type="radio" name="gender" value="2" <?php echo $user->gender == 2 ? 'checked' : '';?>>&nbsp;Жен.</label>&nbsp;&nbsp;
					<label><input type="radio" name="gender" value="0" <?php echo $user->gender == 0 ? 'checked' : '';?>>&nbsp;Не скажу :)</label>
					<label>
				</div>
				<div id="about_me_edit">
                    <span>О себе:</span>&nbsp;<textarea name="about" maxlength="500" ><?php echo $user->about_me;?></textarea>
				</div>
				<div class="ml64 alignleft">
                <label>
                    <input type="checkbox" id="isPrivate" name="isPrivate" value="1" <?php echo $this->rynda_user->isPrivate($user) ? 'checked' : '';?> /> <span>Скрыть всю мою информацию на сайте. Она будет доступна только модераторам</span>
                </label>
				</div>
				<div class="sr_button_panel">
					<img src="/css/i/blue_sm_loader.gif" class="responseLoading" style="display:none" />
					<input type="submit" class="submit" value="Ок" /> 
					<input type="reset" class="reset" value="Отмена" />
				</div>
				<div class="responseMessage"></div>
			</form>
		</div>
		<div class="clearfix"></div>

		<h4><span>Контакты</span></h4>
		<div class="edit_contact_form">
			<form id="contactsForm" action="#">
				*E-mail:&nbsp;<input type="text" id="email" name="email" value="<?php echo $user->email;?>" disabled />
				<div id="emailError" class="validationError"></div>
				<?php $phones = $this->rynda_user->getPhones($user);?>
				<div id="addPhone" class="no_text" <?php if(count($phones) >= 3) {?> style="opacity:0.25; cursor:default;" data-is-off="true" <?php }?>>Добавить ещё телефон</div>
				<ul id="phonesList"><?php
				  if($phones) {?>
				  <li>
					  <label for="contact_phone">Телефон:</label>
					  <input type="text" id="contact_phone" class="phone ie6_input_fixer_phone ie6_input_fixer" name="phone[]" tabindex="3" value="<?php echo formatPhone($phones[0]);?>" maxlength="20" />
					  <div class="validationError phoneError" style="display:none;"></div>
				  </li>
				  <?php for($i=1; $i<count($phones); $i++) {?>
				  <li>
					<input type="text" id="contact_phone contact_phone_exist" class="phone ie6_input_fixer_phone ie6_input_fixer" name="phone[]" tabindex="3" value="<?php echo formatPhone($phones[$i]);?>" maxlength="20" />
					<div class="no_text removePhone">Удалить добавочный телефон</div>

					<div class="validationError phoneError" style="display:none;"></div>
					<div class="clearfix"></div> 
				  </li>
				  <?php }
				  } else {?>
				  <li>
					  <input type="text" id="contact_phone" class="phone ie6_input_fixer_phone ie6_input_fixer" name="phone[]" tabindex="3" maxlength="20" />
					  <div class="validationError phoneError" style="display:none;"></div>
				  </li>
				  <?php }?>
					<li id="phonePrototype" style="display:none;">
						<input type="text" name="phone[]" class="phone  ie6_input_fixer_phone ie6_input_fixer" maxlength="20" disabled="disabled" />
						<div class="no_text removePhone">Удалить добавочный телефон</div>
						<!--<label for="additional_phone">Телефон:</label>-->
						<div class="validationError phoneError" style="display:none;"></div>
						<div class="clearfix"></div>
					</li>
				</ul>

                <div class="clearfix"></div>
				<a name="socNet"></a>
				<div id="expandableSwitch" > <a href="#socNet" class="expandLink" title="Ваши профили в социальных сетях">Ваши профили в соцмедиа </a></div>
               
				<div id="expandableContent">
                    <ul class="socNetList">
                        
                <?php foreach((array)$socNets as $socNet) {?>
                        <li>
                            <img src="<?php echo $socNet['icon'];?>" /><span style="display: none;"><?php echo $socNet['title'];?>:&nbsp;</span>
                            <input type="text" name="socNetProfile_<?php echo $socNet['id'];?>" value="<?php echo empty($socNetProfiles[$socNet['id']]) ? '' : $socNetProfiles[$socNet['id']]['profileUrl'];?>" />
                        </li>
                <?php }?>
                    </ul>
                </div>

<!--			    <label>
					<input type="checkbox" name="isPublic" value="1" />&nbsp;Моя контактная информация видна на сайте
				</label>-->
				<div id="contactsError" class="validationError" style="display:none;"></div>
				<div class="sr_button_panel">
					<img src="/css/i/blue_sm_loader.gif" class="responseLoading" style="display:none" />
					<input type="submit" class="submit" value="Ок" /> 
					<input type="reset" class="reset" value="Отмена" />
				</div>
				<div class="responseMessage"></div>
			</form>
		</div>
		<div class="clear"></div>
		<div id="vp_title"><h4><span>Ваши профили волонтёрства</span></h4></div>
		<div id="vpList">
			<ul>
				<li><a href="#addVp">[+] Добавить профиль</a></li>
				<?php if( !empty($volunteerProfiles) ) {
				foreach($volunteerProfiles as $profile) {?>
				<li>
					<a href="#vp<?php echo $profile['id'];?>">
						<?php echo $profile['title'] ? $profile['title'] : 'Профиль №'.$profile['number'];?>
					</a>
					<span class="deleteVp" data-vp-id="<?php echo $profile['id'];?>" data-vp-title="<?php echo $profile['title'];?>">
						<img src="/css/i/delete_button.png"/>
					</span>
				</li>
				<?php }
				}?>
			</ul>
			<div id="addVp">
				<form id="addVpForm" class="vpForm" action="#" autocomplete="off">
					<label>
						*Название вашего профиля:&nbsp;<input type="text" name="title" class="vpTitle" placeholder="Например, «Профиль 1»" maxlength="15" />
						<div class="vpTitleError validationError"></div>
					</label>
					<fieldset>
						<legend>*Ваше местонахождение</legend>
						<div id="locationAddressStatus">
							<div id="addressFound"></div>
							<div id="locationError" class="validationError"></div>
							<a href="#" id="changeAddress" class="locationMapSwitch dotted orange icon_edit" style="display: none;" title="Выбрать на карте Ваше местоположение">Изменить</a>
							<div id="addressNotFound">
								Пока неизвестно — <a href="#" class="locationMapSwitch dotted">укажите его на карте</a>, пожалуйста!
							</div>
						</div>
						<div style="display: none;" id="locationMapSwitch" class="no_text locationMapSwitch" title="Выбрать на карте Ваше местоположение"></div>
						<div style="display: none;">
							<div id="locationMap">
								<div id="locationMapMessage">
									<img id="mapResponseLoading" src="/images/white_sm_loader.gif" style="display:none;" alt="" />
									<div id="mapResponseText"></div>
									<div id="mapSaveLocation" class="lightGreen" style="display: none;">Сохранить местоположение</div>
									<div class="clearfix"></div>
									Координаты (Ш, Д): <span id="locationCoords">неизвестны</span>
								</div>
								<div id="locationMapCanvas" style="width: 950px; height: 300px;"></div>
								<div id="locationMapControl">
									<label for="locationRegion">Регион:</label>
									<select tabindex="5" name="locationRegion" id="locationRegion">
										<option value="0" <?php if( !$this->input->cookie('ryndaorg_region') ) {?> selected <?php }?> >Все регионы</option>
										<?php foreach($regions as $region) {?>
										<option value="<?php echo $region['id'];?>"
												data-zoom-level="<?php echo $region['zoom'];?>"
												data-center-lat="<?php echo $region['lat'];?>"
												data-center-lng="<?php echo $region['lng'];?>"
												<?php if($this->input->cookie('ryndaorg_region') == $region['id']) {?> selected <?php }?> >
											<?php echo $region['name'];?>
										</option>
										<?php }?>
									</select>
									<input type="hidden" id="locationRegionId" name="locationRegionId" value="" />
									<input type="text" tabindex="6" name="locationAddress" class="ie6_input_fixer_phone ie6_input_fixer" id="locationAddress" maxlength="100" />
									<input type="submit" id="locationSearchSubmit" value="Найти" />
								</div>
							</div>
						</div>
						<input type="hidden" id="locationLatField" name="locationLat" value="" />
						<input type="hidden" id="locationLngField" name="locationLng" value="" />
					</fieldset>
					
					<fieldset>
						<legend>*Категории</legend>
						<div class="categoryError validationError"></div>
						<label>
							<input type="checkbox" class="allCategories" name="allCategories" value="1" />&nbsp;Все категории
						</label>
						<div class="list_style_corrector">
							<?php $this->load->view('widgets/categorySelect',
													array('categories' => $categories,
														  'expandable' => FALSE));?>
						</div>
					</fieldset>
					
					<fieldset>
					<legend>*На каком расстоянии от дома Вы можете помогать?</legend>
			<!--        Во время ЧП: <div id="distEmergCurrent"></div>
					<div id="aidingDistanceEmergencySlider"></div>
					<input type="hidden" id="aidingDistanceEmergency" name="aidingDistanceEmergency" value="" />
					<div id="aidingDistanceEmergencyError" class="validationError"></div>
					<div class="clearfix"></div>

					В «мирное» время: -->
					<div class="distCurrent"></div>
					<div class="aidingDistanceSlider"></div>
					<input type="hidden" class="aidingDistance" name="aidingDistance" value="" />
					<div class="aidingDistanceError validationError"></div>

			<!--        <h3>По каким дням Вы вы можете помогать во время кризиса?</h3>
					<label><input type="checkbox" name="helpCrysisDays[]" value="1" />&nbsp;Понедельник</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="2" />&nbsp;Вторник</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="3" />&nbsp;Среда</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="4" />&nbsp;Четверг</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="5" />&nbsp;Пятница</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="6" />&nbsp;Суббота</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="7" />&nbsp;Воскресенье</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="0" />&nbsp;Время нужно согласовывать со мной</label>
					<div id="helpCrysisDaysError" class="validationError"></div>-->
					</fieldset>
			
					<fieldset>
						<legend>*По каким дням Вы вы можете помогать<!--«в мирное время»-->?</legend>
						<label><input type="checkbox" class="helpDaysCheckAll" value="1" />&nbsp;Все дни</label><br />
						<label><input type="checkbox" name="helpDays[]" value="1" />&nbsp;Понедельник</label><br />
						<label><input type="checkbox" name="helpDays[]" value="2" />&nbsp;Вторник</label><br />
						<label><input type="checkbox" name="helpDays[]" value="3" />&nbsp;Среда</label><br />
						<label><input type="checkbox" name="helpDays[]" value="4" />&nbsp;Четверг</label><br />
						<label><input type="checkbox" name="helpDays[]" value="5" />&nbsp;Пятница</label><br />
						<label><input type="checkbox" name="helpDays[]" value="6" />&nbsp;Суббота</label><br />
						<label><input type="checkbox" name="helpDays[]" value="7" />&nbsp;Воскресенье</label><br />
						<label><input type="checkbox" name="helpDays[]" value="0" />&nbsp;Время нужно согласовывать со мной</label>
						<div class="helpDaysError validationError"></div>
					</fieldset>
					
					<fieldset>
						<legend>Рассылка подходящих просьб о помощи</legend>
						<label>
							<input type="checkbox" class="addMailoutEmail" checked />&nbsp;На e-mail:
						</label>
						<input type="text" class="mailoutEmailField ie6_input_fixer_phone ie6_input_fixer" name="mailoutEmail" tabindex="3" value="<?php echo $user->email;?>" maxlength="30" />
						<div class="mailoutEmailError validationError"></div>

						<div class="vpError validationError" style="display:none;"></div>
						<div class="sr_button_panel">
							<img src="/css/i/blue_sm_loader2.gif" class="responseLoading" style="display:none" />
							<input type="submit" class="submit" value="Ок" /> 
							<input type="reset" class="addVpReset" value="Отмена" />
						</div>
					</fieldset>
					<span class="attention">Обратите внимание, что поля, помеченные знаком * обязательны для заполнения.</span>
				</form>
				<div class="vpResponseMessage"></div>
			</div>
			<?php if( !empty($volunteerProfiles) ) {
				foreach($volunteerProfiles as $profile) {?>
			<div id="vp<?php echo $profile['id'];?>">
				<form class="vpForm editVpForm" action="#" autocomplete="off">
					<label>
						Название вашего профиля:&nbsp;<input type="text" name="title" class="vpTitle" value="<?php echo $profile['title'];?>" maxlength="15" />
						<div class="vpTitleError validationError"></div>
					</label>
					<p><span class=""></span>Местоположение: <?php echo $profile['address'];?> (регион: <a href="/info/r/<?php echo $profile['regionId'];?>"><?php echo $profile['regionName'];?></a>)</p>

					<p><span class="categoryhelp"></span>Категории помощи: <?php echo $profile['isAllCategories'] ? 'любые' : formatCategoryList($profile['categories']);?></p>

					<fieldset>
					<legend>На каком расстоянии от дома Вы можете помогать?</legend>
			<!--        Во время ЧП: <div id="distEmergCurrent"></div>
					<div id="aidingDistanceEmergencySlider"></div>
					<input type="hidden" id="aidingDistanceEmergency" name="aidingDistanceEmergency" value="" />
					<div id="aidingDistanceEmergencyError" class="validationError"></div>
					<div class="clearfix"></div>

					В «мирное» время: -->
					<div class="distCurrent"></div>
					<div class="aidingDistanceSlider"></div>
					<input type="hidden" class="aidingDistance" name="aidingDistance" value="<?php echo $profile['distance'] ? $profile['distance']/1000 : '';?>" />
					<div class="aidingDistanceError validationError"></div>

			<!--        <h3>По каким дням Вы вы можете помогать во время кризиса?</h3>
					<label><input type="checkbox" name="helpCrysisDays[]" value="1" />&nbsp;Понедельник</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="2" />&nbsp;Вторник</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="3" />&nbsp;Среда</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="4" />&nbsp;Четверг</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="5" />&nbsp;Пятница</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="6" />&nbsp;Суббота</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="7" />&nbsp;Воскресенье</label><br />
					<label><input type="checkbox" name="helpCrysisDays[]" value="0" />&nbsp;Время нужно согласовывать со мной</label>
					<div id="helpCrysisDaysError" class="validationError"></div>-->
					</fieldset>

					<fieldset>
						<legend>По каким дням Вы вы можете помогать<!--«в мирное время»-->?</legend>
						<label><input type="checkbox" class="helpDaysCheckAll" value="1" />&nbsp;Все дни</label><br />
						<label><input type="checkbox" name="helpDays[]" value="1" <?php echo in_array(1, $profile['days']) ? 'checked' : '';?> />&nbsp;Понедельник</label><br />
						<label><input type="checkbox" name="helpDays[]" value="2" <?php echo in_array(2, $profile['days']) ? 'checked' : '';?> />&nbsp;Вторник</label><br />
						<label><input type="checkbox" name="helpDays[]" value="3" <?php echo in_array(3, $profile['days']) ? 'checked' : '';?> />&nbsp;Среда</label><br />
						<label><input type="checkbox" name="helpDays[]" value="4" <?php echo in_array(4, $profile['days']) ? 'checked' : '';?> />&nbsp;Четверг</label><br />
						<label><input type="checkbox" name="helpDays[]" value="5" <?php echo in_array(5, $profile['days']) ? 'checked' : '';?> />&nbsp;Пятница</label><br />
						<label><input type="checkbox" name="helpDays[]" value="6" <?php echo in_array(6, $profile['days']) ? 'checked' : '';?> />&nbsp;Суббота</label><br />
						<label><input type="checkbox" name="helpDays[]" value="7" <?php echo in_array(7, $profile['days']) ? 'checked' : '';?> />&nbsp;Воскресенье</label><br />
						<label><input type="checkbox" name="helpDays[]" value="0" <?php echo in_array(0, $profile['days']) ? 'checked' : '';?> />&nbsp;Время нужно согласовывать со мной</label>
						<div class="helpDaysError validationError"></div>
					</fieldset>

					<fieldset>
						<legend>Рассылка подходящих просьб о помощи</legend>
						<label>
							<input type="checkbox" class="addMailoutEmail" <?php echo $profile['mailoutEmail'] ? 'checked' : '';?> />&nbsp;На e-mail:
						</label>
						<input type="text" class="mailoutEmailField ie6_input_fixer_phone ie6_input_fixer" name="mailoutEmail" tabindex="3" value="<?php echo $profile['mailoutEmail'] ? $profile['mailoutEmail'] : '';?>" maxlength="30" <?php echo $profile['mailoutEmail'] ? '' : 'disabled';?> />
						<div class="mailoutEmailError validationError"></div>

						<div class="vpError validationError" style="display:none;"></div>
						<div class="sr_button_panel">
							<img src="/css/i/blue_sm_loader.gif" class="responseLoading" style="display:none" />
							<input type="submit" class="submit" value="Ок" /> 
							<input type="reset" class="editVpReset" value="Отмена" />
						</div>
					</fieldset>
					<fieldset>
					<span class="attention">Обратите внимание, что поля, помеченные знаком * обязательны для заполнения.</span>
					</fieldset>
					<input type="hidden" name="vpId" value="<?php echo $profile['id'];?>" />
				</form>
				<div class="vpResponseMessage"></div>
			</div>
			<?php }
			}?>
		</div>
	</div>
</div>

<script id="vpNewTabTmpl" type="text/x-jquery-tmpl">
    <span class="deleteVp" data-vp-id="${id}" data-vp-title="${title}"><img src="/css/i/delete_button.png"/></span>
</script>

<script id="vpDeleteFormTmpl" type="text/x-jquery-tmpl">
    <form action="#" class="deleteVpConfirm" data-vp-id="${id}">
        Ваш профиль волонтёрства «${title}» будет удалён навсегда! <br />Подтвердите это, пожалуйста.<br />
        <input type="hidden" name="vpId" value="${id}" />
        <br />
        <label>Ваш пароль: <input type="password" name="deleteVpPass" /></label><img src="/images/white_sm_loader.gif" class="responseLoading" style="display:none; margin:0 0 0 20px;" />
        <div class="validationError" style="display:none;"></div>
        <div class="responseMessage"></div>
            <div class="vpDel_button_panel">
            <input type="submit" class="submit" value="Удалить профиль" /> 
            <input type="reset" class="reset deleteVpReset" value="Отмена" />
            <input type="reset" class="deleteVpSuccess" value="OK" style="display:none;" />
        </div>
    </form>
</script>

<script id="vpEditFormTmpl" type="text/x-jquery-tmpl">
    {{if showSuccessMessage}}
    <div class="vpSysRep">Профиль волонтёрства успешно добавлен!</div>
    {{/if}}
    <form class="vpForm editVpForm" action="#" autocomplete="off">
        <label>
            Название вашего профиля:&nbsp;<input type="text" name="title" class="vpTitle" value="${title}" maxlength="15" />
            <div class="vpTitleError validationError"></div>
        </label>
        <p><span class=""></span>Местоположение: ${address} (регион: <a href="/info/r/${regionId}">${regionName}</a>)</p>

        <p><span class="categoryhelp"></span>Категории помощи:
            {{if isAllCategories == 1}} любые
            {{else}}
                {{each categories}}
                    <a href="/info/c/${$value.id}" title="Все сообщения из категории «${$value.name}»">${$value.name}</a>
                    {{if $index < $data.categories.length - 1}}, {{/if}}
                {{/each}}
            {{/if}}</p>

        <fieldset>
        <legend>На каком расстоянии от дома Вы можете помогать?</legend>
<!--        Во время ЧП: <div id="distEmergCurrent"></div>
        <div id="aidingDistanceEmergencySlider"></div>
        <input type="hidden" id="aidingDistanceEmergency" name="aidingDistanceEmergency" value="" />
        <div id="aidingDistanceEmergencyError" class="validationError"></div>
        <div class="clearfix"></div>

        В «мирное» время: -->
        <div class="distCurrent"></div>
        <div class="aidingDistanceSlider"></div>
        <input type="hidden" class="aidingDistance" name="aidingDistance" value="${distance}" />
        <div class="aidingDistanceError validationError"></div>

<!--        <h3>По каким дням Вы вы можете помогать во время кризиса?</h3>
        <label><input type="checkbox" name="helpCrysisDays[]" value="1" />&nbsp;Понедельник</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="2" />&nbsp;Вторник</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="3" />&nbsp;Среда</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="4" />&nbsp;Четверг</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="5" />&nbsp;Пятница</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="6" />&nbsp;Суббота</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="7" />&nbsp;Воскресенье</label><br />
        <label><input type="checkbox" name="helpCrysisDays[]" value="0" />&nbsp;Время нужно согласовывать со мной</label>
        <div id="helpCrysisDaysError" class="validationError"></div>-->
        </fieldset>

        <fieldset>
            <legend>По каким дням Вы вы можете помогать<!--«в мирное время»-->?</legend>
            <label><input type="checkbox" class="helpDaysCheckAll" value="1" />&nbsp;Все дни</label><br />
            <label><input type="checkbox" name="helpDays[]" value="1" {{if $item.inArray(1, days)}} checked{{/if}} />&nbsp;Понедельник</label><br />
            <label><input type="checkbox" name="helpDays[]" value="2" {{if $item.inArray(2, days)}} checked{{/if}} />&nbsp;Вторник</label><br />
            <label><input type="checkbox" name="helpDays[]" value="3" {{if $item.inArray(3, days)}} checked{{/if}} />&nbsp;Среда</label><br />
            <label><input type="checkbox" name="helpDays[]" value="4" {{if $item.inArray(4, days)}} checked{{/if}} />&nbsp;Четверг</label><br />
            <label><input type="checkbox" name="helpDays[]" value="5" {{if $item.inArray(5, days)}} checked{{/if}} />&nbsp;Пятница</label><br />
            <label><input type="checkbox" name="helpDays[]" value="6" {{if $item.inArray(6, days)}} checked{{/if}} />&nbsp;Суббота</label><br />
            <label><input type="checkbox" name="helpDays[]" value="7" {{if $item.inArray(7, days)}} checked{{/if}} />&nbsp;Воскресенье</label><br />
            <label><input type="checkbox" name="helpDays[]" value="0" {{if $item.inArray(0, days)}} checked{{/if}} />&nbsp;Время нужно согласовывать со мной</label>
            <div class="helpDaysError validationError"></div>
        </fieldset>

        <fieldset>
            <legend>Рассылка подходящих просьб о помощи</legend>
            <label>
                <input type="checkbox" class="addMailoutEmail" {{if mailoutEmail}} checked{{/if}} />&nbsp;На e-mail:
            </label>
            <input type="text" class="mailoutEmailField ie6_input_fixer_phone ie6_input_fixer" name="mailoutEmail" tabindex="3" value="{{if mailoutEmail}} ${mailoutEmail}{{/if}}" maxlength="30" {{if !mailoutEmail}} disabled {{/if}} />
            <div class="mailoutEmailError validationError"></div>

            <div class="vpError validationError" style="display:none;"></div>
            <div class="sr_button_panel">
                <img src="/css/i/blue_sm_loader.gif" class="responseLoading" style="display:none" />
                <input type="submit" class="submit" value="Ок" /> 
                <input type="reset" class="editVpReset" value="Отмена" />
            </div>
        </fieldset>
        <input type="hidden" name="vpId" value="${id}" />
    </form>
    <div class="vpResponseMessage"></div>
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<script src="/javascript/userPersonalEdit.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
