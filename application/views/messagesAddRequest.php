<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для страницы добавления просьбы о помощи
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/messagesAddRequest.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="g960 needhelppage rounded_all mb35" id="content">
    <div id="submitMessage" class="submitmessage" style="display:none"></div>
    <div id="possibleHelp" style="display:none">
        <div id="listVolunteers"></div>
        <div id="listNco"></div>
        <div id="listGo"></div>
    </div>
    <div id="formTotal">
        <div class="header_container rounded_top">
            <h2 class="no_text">Вам нужна помощь?</h2>
        </div>
		<div class="relative mt10 mb10">		
			<span class="attention">
				Обратите внимание, что поля, помеченные знаком * обязательны для заполнения.
			</span>
		</div>
        <form id="mainForm" action="#">
            <!-- Левая панель -->
            <div class="column" id="nh1column">
                <div class="column_container width_fixer" id="nh_1_column_container">

                    <h3>1. Расскажите о себе</h3>

                    <input type="hidden" id="messageType" name="messageType" value="<?php echo $messageTypeSlug;?>" />

                    <label for="firstName">*Имя:</label>
                    <input type="text" name="firstName" class="ie6_input_fixer" id="firstName"  value="<?php echo empty($user->first_name) ? '' : $user->first_name;?>" tabindex="1" maxlength="25" />
                    <div id="firstNameError" class="validationError" style="display:none;"></div>
                    <br />
                    <label for="lastName">*Фамилия:</label>
                    <input type="text" name="lastName" id="lastName" class="ie6_input_fixer" value="<?php echo empty($user->last_name) ? '' : $user->last_name;?>" tabindex="2" maxlength="25" />
                    <div id="lastNameError" class="validationError" style="display:none;"></div>
                    <br />
                    <div id="addPhone" class="no_text">Добавить ещё телефон</div>
                    <ul id="phonesList"><?php
                      $phones = $this->rynda_user->getPhones($user);
                      if($phones) {?>
                      <li>
                          <label for="contact_phone">*Телефон:</label>
                          <input type="text" id="contact_phone" class="phone ie6_input_fixer_phone ie6_input_fixer" name="phone[]" tabindex="3" value="<?php echo formatPhone($phones[0]);?>" maxlength="30" />
                          <div class="validationError phoneError" style="display:none;"></div>
                      </li>
                      <?php
                          for($i=1; $i<count($phones); $i++) {?>
                      <li>
                          <div class="no_text removePhone">Удалить добавочный телефон</div>
                          <label for="contact_phone">Телефон:</label>
                          <input type="text" id="contact_phone" class="phone ie6_input_fixer_phone ie6_input_fixer" name="phone[]" tabindex="3" value="<?php echo formatPhone($phones[$i]);?>" maxlength="30" />
                          <div class="validationError phoneError" style="display:none;"></div>
                          <div class="clearfix"></div>
                      </li>
                          <?php }
                      } else {?>
                      <li>
                          <label for="contact_phone">Телефон:</label>
                          <input type="text" id="contact_phone" class="phone ie6_input_fixer_phone ie6_input_fixer" name="phone[]" tabindex="3" maxlength="30" />
                          <div class="validationError phoneError" style="display:none;"></div>
                      </li>
                      <?php }?>
                        <li id="phonePrototype" style="display:none;">
                            <div class="no_text removePhone">Удалить добавочный телефон</div>
                            <!--<label for="additional_phone">Телефон:</label>-->
                            <input type="text" name="phone[]" class="phone  ie6_input_fixer_phone ie6_input_fixer" maxlength="30" disabled="disabled" />
                            <div class="validationError phoneError" style="display:none;"></div>
                            <div class="clearfix"></div>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <label for="email">*E-mail: </label>
                    <input type="text" name="email" id="email" class="ie6_input_fixer" value="<?php echo empty($user->email) ? '' : $user->email;?>" tabindex="4" maxlength="30" />
                    <div id="emailError" class="validationError" style="display:none;"></div>
                    <label class="label_show">
                        <input type="checkbox" name="isPrivate" value="1" <?php echo $this->rynda_user->isPrivate($user) ? 'checked' : '';?> />
                        Не публиковать мои контактные данные (они будут доступны только модераторам)
                    </label>
                    <div id="contactsError" class="validationError" style="display:none;"></div>
                    <br /><br />

                    <h3>2. Ваше местонахождение</h3>
                    <div id="locationAddressStatus">
                        <div id="addressFound"></div><a href="#" id="changeAddress" class="locationMapSwitch dotted orange icon_edit" style="display: none;" title="Выбрать на карте Ваше местоположение">Изменить</a>
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
                                <div id="mapSaveLocation" class="lightGreen" style="display: none;"> Сохранить местоположение</div>
                                <div class="clearfix"></div>
                                Координаты (Ш, Д): <span id="locationCoords">неизвестны</span>
                            </div>
                            <div id="locationMapCanvas" style="width: 950px; height: 300px;"></div>
                            <div id="locationMapControl" <!--style="display: none;"--> >
                                <label for="locationRegion">Регион:</label>
                                <select tabindex="5" name="locationRegion" id="locationRegion">
                                    <option value="0"
                                            data-zoom-level="<?php echo $this->config->item('main_map_default_zoom');?>"
                                            data-center-lat="<?php echo $this->config->item('main_map_default_lat');?>"
                                            data-center-lng="<?php echo $this->config->item('main_map_default_lng');?>"
                                    <?php if( !$this->input->cookie('ryndaorg_region') ) {?> selected <?php }?> >Все регионы</option>
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
                            <!--<div id="locationMapLocateClient">Найти меня!</div>-->
                        </div>
                    </div>
                    <input type="hidden" id="locationLatField" name="locationLat" value="" />
                    <input type="hidden" id="locationLngField" name="locationLng" value="" />
                    <br />
                    <br />
                    <h3>3. Опишите вашу проблему</h3>
                    <label for="messageTitle">Заголовок:</label>
                    <input type="text" name="messageTitle" class="ie6_input_fixer" id="messageTitle" maxlength="100" tabindex="7" />
                    <br />

                    <label for="messageText">*Описание проблемы:</label>
                    <textarea name="messageText" id="messageText" cols="5" rows="5" style="background: #f4f4f4;" class="ie6_input_fixer"></textarea>
                    <div id="messageTextError" class="validationError" style="display:none;"></div>
                <div id="photoload_container">
                    <label for="photo">Загрузить фотографию:</label>
                    <input type="file" id="photoField" name="photo" /><img id="photoLoading" src="/images/white_sm_loader.gif" style="display:none; padding-left:10px;" />
                    <div id="photoFieldInfo">
                        <div id="photoMessage"></div>
                        <div id="photoUploadedList"></div>
                    </div>
				</div>
                    <br />
<!--                    <label>Ссылка на видео:
                        <ul id="videosList">
                            <li id="videoPrototype" style="display: none;">
                                <input type="text" name="video[]" maxlength="30" disabled />
                                <span class="removeVideo">Удалить</span>
                            </li>
                            <li><input type="text" name="video[]" maxlength="30" /></li>
                        </ul>
                        <div id="moreVideo">Добавить ещё ссылку на видео</div>
                    </label>
                    <br />-->
                    
                    <!-- Функции загрузки фоток и ссылок на видео - конец. -->
                    <!--<label class="label_show">
                        <input type="checkbox" name="sendNotices" value="1" />
                        Получать уведомления о предложениях помощи
                    </label>-->
                </div>
            </div>
            <!-- Левая панель завершена -->

            <!-- Правая панель -->
            <div class="column" id="nh_2_column">
                <div class="column_container" id="nh_2_column_container">
                    <!-- Выбор категорий -->
                    <h3>4. Отметьте категории Вашей проблемы:</h3>
                    <div class="list_style_corrector">
                        <?php $this->load->view('widgets/categorySelect',
                                                array('categories' => $categories, 'expandable' => FALSE));?>
                    </div>
                    <div id="categoryError" class="validationError" style="display:none;"></div>
                    <div class="clearfix">&nbsp;</div>
                    <!-- Выбор категорий -->
                    <?php if(empty($user)) {?>
                    <label>
                        <input type="checkbox" id="agreed" value="1" />
                            Я разрешаю использование и обработку  указанных в анкете данных
                    </label>
                    <div id="agreedError" class="validationError" style="display:none;"></div>
                    <?php }?>
                    <input type="submit" id="addSubmit" class="no_text" value="Отправить!" />
                    <!--<div class="tooltip ml110 g240">Нажимая эту кнопку, Вы разрешаете сайту Рында.орг вывести на общедоступную карту Вашу информацию. </div>-->
                    <div class="clearfix"></div>
                    <div id="formResponseMessage"></div>
                    <img id="formResponseLoading" src="/images/loading.gif" style="display:none;" alt="" />
                </div>
            </div>
            <input type="hidden" name="userId" value="<?php echo empty($user->id) ? 0 : (int)$user->id;?>" />
        </form>
    </div>
    </div>
    <div class="clear1">&nbsp;</div>
    <!--<div class="g960" id="nh_actualRecommendations">
        <div class="more_rec">
        <a class="orange rynda_icon" href="#">Другие рекомендации</a>
        </div>
        <h3 class="no_text">Актуальные рекомендации</h3>
        <div class="clearfix grey_line"></div>
        <div class="act_rec_cont">
            <ul id="recommendationsList" class="no_list floatleft">
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="nh_act_rec_bottom"></div>
    </div>
    <div class="clear1">&nbsp;</div>-->
<script id="possibleDataTmpl" type="text/x-jquery-tmpl">
    <div class="header_container rounded_top">
        <h3 class="mb0 rounded_top regulartext_header">Кто готов помочь рядом с вами</h3>
    </div>
    {{if volunteers && volunteers.length}}
        <ul class="no_list datelist grayDivider aid_list pa20">
        {{each(key, volunteer) volunteers}}
            <li class="vcard">
                <img src="/images/incognito.png" alt="" class="photo" />
                <strong>
                    <span class="fn n">
                        <a href="/info/s/${volunteer.id}" class="url">
                            <span class="given-name">${volunteer.firstName}</span>
                            <span class="family-name">{{if volunteer.lastName}} ${volunteer.lastName}{{/if}}</span>
                        </a>
                    </span>
                </strong>
                {{if volunteer.isPublic}}
                    {{if volunteer.email || volunteer.phones.length}} (
                        {{if volunteer.email}}e-mail: <a class="email" href="mailto:${volunteer.email}">${volunteer.email}</a>{{/if}}
                        {{if volunteer.phones.length}}тел.:
                            {{each(phoneKey, phone) volunteer.phones}}
                                <span class="tel">${phone}</span>{{if phoneKey < volunteer.phones.length - 1}}, {{/if}}
                            {{/each}}
                        {{/if}}
                    ){{/if}}
                {{else}}
                    Пользователь предпочёл не открывать своей контактной информации.
                    <br /><br />
                    Вы можете обратиться к нему, <a href="/info/s/${volunteer.id}">оставив комментарий</a> к его предложению помощи.
                {{/if}}
                <br />
                {{if volunteer.categories.length}}
                    Может помочь с:
                    {{each(catKey, cat) volunteer.categories}}
                        <a href="/info/c/${cat.id}">${cat.name}</a>{{if catKey < volunteer.categories.length - 1}}, {{/if}}
                    {{/each}}
                {{/if}}
                <div class="clearfix"></div>
            </li>
        {{/each}}
        </ul>
		<!--<div class="header_container rounded_top">
            <h3 class="mb0 rounded_top regulartext_header">Актуальные рекомендации</h3>
        </div>
		<ul class="no_list datelist grayDivider aid_list pa20">
				<li>(Название рекомендации) | (ссылка рекомендации) (категория рекомендации) </li>
		</ul>-->
    {{/if}}

    {{if ncOrg && ncOrg.length}}
    
    {{/if}}

    {{if gOrg && gOrg.length}}

    {{/if}}

    {{if volunteers.length == 0 && ncOrg.length == 0 && gOrg.length == 0}}
   <div class="fail pa20">
    К сожалению, прямо сейчас мы не смогли найти предложений помощи, подходящих к Вашей просьбе.
    <br /><br />
    Но не отчаивайтесь — ваше сообщение сохранено и уже передано на обработку! Мы постараемся найти для вас помощь как можно скорее.
    <br /><br />
    А пока, пожалуйста, попробуйте найти помощь на <strong><a href="/vse">странице поиска</a></strong>.
    </div>
    {{/if}}
	<div class="alignright ml40">
</div>
    <div class="g960">
		<div id="anotherMessage_container" class="alignleft">
			<div id="need_help_button">
				<a class="no_text" title="Нужна помощь" href="/pomogite/dobavit">Я хочу попросить о помощи ещё раз</a>
			</div>
			<div id="want_to_help_button">
				<a class="no_text" title="Хочу помочь" href="/pomogu/dobavit">Я хочу предложить свою помощь</a>
			</div>
		</div>
        		
    </div>
</script>

<script id="recommendationTmpl" type="text/x-jquery-tmpl">
    <li>
        <img src="/images/rec.jpg" alt="актуальные рекомендации" class="alignleft mr10" />
        <div>
            <h4></h4><br />
            <!--Категория: -->
            <br />
            <!--<a href="#" class="orange">Подробнее &raquo;</a>-->
        </div>
        <div class="clearfix"></div>
    </li>
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>

<script src="/javascript/messagesAdd.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
