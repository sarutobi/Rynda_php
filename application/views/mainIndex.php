<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для главной страницы системы
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/mainIndex.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>
<div class="g960" id="content">
    <div class="clearfix"></div>
    <?php if( !getSessionCookie() ) {?>	
        <a href="http://blog.rynda.org/?p=82"><img src="/images/start_message.png" style="margin-bottom:-2px" /></a>
    <?php }?>
    <div id="locationMap" class="mainPageMapCont">
        <form id="mapFilterForm" action="#">
            <div id="mapFormContainer">
                <div>
                    <label for="regionId" class="no_display">Регион</label>
                    <select name="regionId" id="searchRegion">
                    <option value="0"
                            data-zoom-level="<?php echo $this->config->item('main_map_default_zoom');?>"
                            data-center-lat="<?php echo $this->config->item('main_map_default_lat');?>"
                            data-center-lng="<?php echo $this->config->item('main_map_default_lng');?>"
                            <?php if( !$this->input->cookie('ryndaorg_region') ) {?> selected <?php }?> >Все регионы</option>
                        <?php foreach((array)$regions as $region) {?>
                            <option value="<?php echo $region['id'];?>"
                                    data-zoom-level="<?php echo $region['zoom'];?>"
                                    data-center-lat="<?php echo $region['lat'];?>"
                                    data-center-lng="<?php echo $region['lng'];?>"
                                    <?php if($this->input->cookie('ryndaorg_region') == $region['id']) {?> selected <?php }?> >
                                <?php echo $region['name'];?>
                            </option>
                        <?php }?>
                    </select>

                    <label for="dateAddedFrom" class="no_display">За какое время?</label>
                    <select name="dateAddedFrom" id="searchDateAddedFrom">
                        <option value="" title="Сообщения, поступившие за все время">За все время</option>
                        <option value="<?php echo mktime(0, 0, 0);?>" title="Только сообщения, поступившие сегодня">За сегодня</option>
                        <option value="<?php echo strtotime('-3 day', mktime(0, 0, 0));?>" title="Только сообщения, поступившие за последние 3 дня">За последние 3 дня</option>
                        <option value="<?php echo strtotime('-1 week', mktime(0, 0, 0));?>" title="Только сообщения, поступившие за последнюю неделю">За неделю</option>
                        <option value="<?php echo strtotime('-1 month', mktime(0, 0, 0));?>" title="Только сообщения, поступившие за последний месяц">За месяц</option>
                    </select>
                    <label for="category" class="no_display">Категория</label>
                    <select name="category" id="searchCategory">
                        <option value="">Все категории</option>
                    <?php foreach($categories as $category) {
                        if( !empty($category['children']) ) { // Надкатегории ?>
                            <optgroup label="<?php echo mb_ucfirst($category['name']);?>">
                                <?php foreach((array)$category['children'] as $child) {?>
                                <option value="<?php echo $child['id'];?>">
                                    <?php echo mb_ucfirst($child['name']);?>
                                </option>
                                <?php }?>
                            </optgroup>
                        <?php } else { // Подкатегории ?>
                            <option value="<?php echo $category['id'];?>">
                                <?php echo mb_ucfirst($category['name']);?>
                            </option>
                    <?php }
                    }?>
                    </select>
                    <label for="untimed" class="no_display">Как срочно?</label>
                    <select name="untimed" id="searchUntimed">
                        <option value="">Как срочно?</option>
                        <!--<option value="nm">Всё равно</option>-->
                        <option value="0">Срочно</option>
                        <option value="1">Несрочно</option>
                    </select>
                    <span id="toggleMap" style="border:1px solid black;margin-left:10px;padding:5px;font-size:15px;font-weight:bold;cursor:pointer;background-color:#7EB32B;color:white;text-decoration-color:white;" data-map-shown="<?php echo $this->input->cookie('ryndaorg_main_map_shown') ? 1 : 0;?>">
                        <?php echo $this->input->cookie('ryndaorg_main_map_shown') ? 'Скрыть карту' : 'Открыть карту';?>
                    </span>
                    <img id="mapResponseLoading" src="/images/white_sm_loader.gif" alt="" style="display: none; margin:0px 0px 0px 5px;" />
                    <div id="reset" class="mainpagecancel">Сбросить форму</div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
        <div id="mapWithoutFilterForm" <?php echo $this->input->cookie('ryndaorg_main_map_shown') ? '' : 'style="display:none;"';?>>
            <div id="locationMapCanvas" style="width: 948px; height: 320px;"></div>
            <form id="markerTypesForm" action="#">
                <div class="map_markers_switcher">
                    <strong>Включить:</strong>
                    <?php if($messageTypes) {?>
                    <ul>
                        <li><strong>Сообщения</strong></li>
                        <?php foreach($messageTypes as $type) {?>
                        <li class="switcher_messagetype_<?php echo $type['id'];?>">
                            <label>
                                <input type="checkbox" name="messageTypeId[]" value="<?php echo $type['id'];?>" checked />
                                <span><?php echo $type['name'];?></span>
                            </label>
                        </li>
                        <?php } ?>
                    </ul>
                    <br />
                    <?php }
                    if($organizationTypes) {?>
                    <strong>Организации</strong>
                    <ul>	
                        <?php foreach($organizationTypes as $type) {?>
                        <li class="switcher_orgtype_<?php echo $type['id'];?>">
                            <label>
                                <input type="checkbox" name="orgTypeId[]" value="<?php echo $type['id'];?>" />
                                <span><?php echo $type['name'];?></span>
                            </label>
                        </li>
                        <?php }?>
                    </ul>
                    <?php }?>
                </div>
            </form>
            <div id="mapResponseMessage" style="display:none;"></div>
        </div>
    </div>
    <div class="clear">&nbsp;</div>

    <?php if($disclaimer) {?>
    <div id="disclaimer" class="rounded_all g720"><?php echo $disclaimer;?></div>
    <?php }?>

    <div class="clear">&nbsp;</div>

<?php if( !empty($usersForList) && !getSubdomain() ) {?>
<div id="usersList_container" class="rounded_all">
	<div id="usersList_leftCol">
		<ul id="usersList">
			<?php foreach((array)$usersForList as $userData) {?>
            <li class="usersList_unit">
                <div class="usersList_avatar">
                    <a href="/user/<?php echo $userData['userId'];?>" title="<?php echo "{$userData['firstName']} {$userData['lastName']}";?>">
                        <img rel="" src="<?php echo $userData['avatarUrl'];?>" />
                    	<div class="avatar_image-smallcircle"></div>
					</a>
                </div>
                
               
                <div class="usersList_name">
                    <a href="/user/<?php echo $userData['userId'];?>" title="<?php echo "{$userData['firstName']} {$userData['lastName']}"?>">
                        <?php echo "{$userData['firstName']} {$userData['lastName']}"?>
                    </a>
                </div>
            </li>
            <?php }?>
		</ul>

    <?php if( !empty($user) ) {?>
		<a href="/user/edit/<?php echo $user->id;?>#vp_title" title="C помощью этой кнопки вы можете создать новый профиль взаимопомощи"><div id="add-vpButton" class="rounded_all relative">Создать профиль взаимопомощи</div></a>
	<?php } else {?>
	<a href="/register" title="Регистрация на сайте"><div id="add-vpButton" class="rounded_all relative">Зарегистрироваться</div></a>
    <?php }?>
	</div>
    <?php if( !empty($user) ) {?>
	<div id="usersList_disclaimer">
		<div id="usersList_disclaimerHead">ПРИСОЕДИНЯЙТЕСЬ!</div>
		<div id="usersList_disclaimerText">С радостью сообщаем, что на сайте есть возможность открыть персональные профили помощи. В таком профиле вы можете указать, какую помощь готовы оказывать, и ваше месторасположение. <br />С помощью профилей помощи вы сможете своевременно получать информацию о том, где и в чём вы можете быть полезны.</div>
	</div>
    <?php } else {?>
	<div id="usersList_disclaimer">
		<div id="usersList_disclaimerHead">ПРИСОЕДИНЯЙТЕСЬ!</div>
		<div id="usersList_disclaimerText">На нашем сайте вы можете зарегистрироваться и пользоваться всеми сервисами. Например, персональные профили помощи доступны только зарегистрированным пользователям. В таком профиле вы можете указать, какую помощь готовы оказывать, и ваше месторасположение. <br />С помощью профилей помощи вы сможете своевременно получать информацию о том, где и в чём вы можете быть полезны.</div>
	</div>
    
    <?php }?>
</div>
<?php }?>
	
    <div class="column triple_column mr25">
        <a href="/pomogite"><h3 class="mb0 helpneeded no_text rounded_top">Просьбы о помощи</h3></a>
        <div class="triple_container" id="mp1">
          <ul id="requestsList" class="no_list triplelist">
              <li>Загружаем сообщения...</li>
          </ul>
          <a id="requestsMore" href="/pomogite" style="display: none;" class="alignright green triple_more_info" title="Все просьбы  о помощи &raquo;"><strong>Другие просьбы о помощи&#8230;</strong></a>
          <div id="requestsNotFound" style="display:none">
          		<ul class="no_list triplelist">
          			<li>Просьб о помощи не найдено — <a href="/pomogite/dobavit">добавить свою</a></li>
          		</ul>
          </div>
          <div class="clearfix"></div>
       </div>    
    </div>
    
    <div class="column triple_column mr25">
        <a href="/pomogu"><h3 class="mb0 helpprovided no_text rounded_top">Предложения помощи</h3></a>
        <div class="triple_container" id="mp2">
            <ul id="offersList" class="no_list triplelist">
                <li>Загружаем сообщения...</li>
            </ul>
            <a id="offersMore" style="display: none;" href="/pomogu" class="alignright green triple_more_info" title="Все предложения помощи &raquo;"><strong>Другие предложения помощи&#8230;</strong></a>
            <div id="offersNotFound" style="display:none">
            	<ul class="no_list triplelist">
            		<li>Предложений помощи не найдено — <a href="/pomogu/dobavit">добавить своё</a></li>
            	</ul>
           	</div>
            <div class="clearfix"></div>
        </div>
    </div>
    
    <div class="column triple_column">
        <a href="/pomogite/pomogli"><h3 class="mb0 helpgiven no_text rounded_top">Помощь нашлась</h3></a>
        <div class="triple_container" id="mp4">
            <ul id="helpedList" class="no_list triplelist">
                <li>Загружаем сообщения...</li>
            </ul>
            <a id="helpedMore" style="display: none;" href="/pomogite/pomogli" class="alignright green triple_more_info" title="Все успешные сообщения &raquo;">
                <strong>Другие успешные сообщения&#8230;</strong>
            </a>
            <div id="helpedNotFound" style="display:none">
                <ul class="no_list triplelist">	
                	<li>Успешно оказанной помощи пока нет — попробуйте <a href="/pomogu/dobavit">добавить</a> Ваше предложение помощи или <a href="/pomogite">найти кого-то</a>, кому Вы можете помочь!</li>
            	</ul>
            </div>
            <div class="clearfix"></div>
        </div>    
    </div>
    
    <div class="clearfix mt25">&nbsp;</div>
    
    <div class="column triple_column mr25" >
    	<a href="/info">
            <h3 class="mb0 infomessages no_text rounded_top">Информационные сообщения</h3>
        </a>
        <div class="triple_container" id="mp5">
            <ul id="infoList" class="no_list triplelist">
                <li>Загружаем сообщения...</li>
            </ul>
            <a id="infoMore" style="display: none;" href="/info" class="alignright green triple_more_info" title="Все информационные сообщения &raquo;"><strong>Другие информационные сообщения &#8230;</strong></a>
            <div id="infoNotFound" style="display:none">
            	<ul class="no_list triplelist">
            		<li>Информационных сообщений не найдено</li>
                </ul>    
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    
    <div class="column triple_column mr25" >
    	<a href="http://blog.rynda.org/">
            <h3 class="mb0 latestnews no_text rounded_top">Последние новости</h3>
        </a>
        <div class="triple_container" id="mp6">
            <?php if(count($newsRecent) > 0) {?>
            <ul class="no_list triplelist">
            <?php foreach($newsRecent as $newsItem) {?>
                <li>
                    <div class="triple_date">
						<?php echo date('d.m', $newsItem['dateAdded']);?><br />
						<span class="triple_year"><?php echo date('Y', $newsItem['dateAdded']);?></span>
                    </div>
                    <a class="triple_link" href="<?php echo $newsItem['link'];?>" title="<?php echo $newsItem['title'];?>"><?php echo $newsItem['title'];?></a>
                    <div class="clearfix"></div>
                </li>
            <?php }?>
            </ul>
            <?php if(count($newsRecent) >= 5) {?>
            <a id="newsMore" href="http://blog.rynda.org/?cat=4" class="alignright green triple_more_info" title="Все новости &raquo;"><strong>Другие новости &#8230;</strong></a>
            <?php }
            } else {?>
            <ul class="no_list triplelist">
            	<li>Пока никаких новостей</li>
            </ul>
            <?php }?>
            <div class="clearfix"></div>
        </div>
        
    </div>
    
    <div class="column triple_column" >
    	 <a href="http://rynda.org/info/about">
         	<h3 class="mb0 aboutproject no_text rounded_top">Информация о проекте</h3>
         </a>
        <div class="triple_container" id="mp7">
 			<p>
            	<strong>«Виртуальная Рында»</strong> - это средство для координации взаимопомощи, задача которого – раскрыть потенциал российского сетевого сообщества в области сотрудничества между пользователями Интернета и различными организациями, включая некоммерческие организации, государственные структуры и бизнес.<br />
<br />
Сайт позволяет каждому сообщить о своей беде или желании помочь. Сообщения поступают в публичное пространство и позволяют привлечь внимание к разного рода событиям и проблемам. Сбор, анализ, обработка и предоставление такой информации помогают максимально эффективно связать участников событий с теми, кто может им помочь.

            </p>
            <a href="http://rynda.org/info/about" class="alignright green triple_more_info" title="Читать далее &raquo;"><strong>Читать далее &#8230;</strong></a>
            <div class="clearfix"></div>
        </div>
        
    </div>
    <div class="clearfix "></div>
    <div class="g960 inside mt25 rounded_top" id="connection">
        <h3 class="mb0 rounded_top no_text rounded_top">Каналы связи</h3>
        <ul class="floatleft no_list ml20 mt25">
        	<!--<li>СМС: <div class="lightGreen inline fs14"><strong>4444</strong></div></li>-->
            <li>E-MAIL: <a href="mailto:a@rynda.org" title="Напишите нам на E-Mail"><div class="lightGreen inline fs14"><strong>a@rynda.org</strong></div></a></li>
            <li>TWITTER: <a href="http://twitter.com/#!/Ryndaorg" title="Следите за нами в Twitter" target="_blank"><div class="lightGreen inline fs14"><strong>@Ryndaorg</strong></div></a></li>
            <li>SKYPE: <a href="skype:ryndaorg?chat" title="Skype"><div class="lightGreen inline fs14"><strong>ryndaorg</strong></div></a></li>
            <!--<li>ТЕЛЕФОН: <div class="lightGreen inline fs14"><strong>+7 (916) 1234567</strong></div></li>-->
        </ul>
		<div class="clearfix "></div>	
        <div class="nh_act_rec_bottom mt25"></div>
    </div>
</div>
<script id="mapMarkerPopupTmpl" type="text/x-jquery-tmpl">
    <div class="mainPopupWindow">
        <h4>
            <a href="info/s/${id}">
            {{if isPublic == 1}}
                ${firstName}{{if lastName}} ${lastName}{{/if}}:
            {{else}}
                ${firstName}{{if lastName}} ${$item.getFirstLetter(lastName)}:{{/if}}
            {{/if}}
            </a>
            {{if title && title.length > 0}}
                <a href="/info/s/${id}">${title}</a>
            {{else}}
                <a href="info/s/${id}">
                    ${$item.formatTextTrimmed(text, 50)}
                </a>
            {{/if}}
        </h4>
        <div class="mainPopupText">
            <div class="mainPopupDate">${$item.formatDate(dateAdded)}</div>
            <a href="/info/s/${id}">
                <strong><span class='orange'>Подробнее &raquo;</span></strong>
            </a>
        </div>
    </div>
</script>
<script id="mapClusterPopupTmpl" type="text/x-jquery-tmpl">
    <div class="mainPopupWindow">
        <h4 class="popup_h4">Всего отметок в группе: ${markers.length}</h4>
        <ul class="mainPopupText">
        {{each(i, marker) markers}}
            {{if marker.get("orgType")}}
            <li class="clusterOrganization">
                {{if marker.get("title").length > 0}}
                <a href='/info/o/${marker.get("id")}'>${marker.get("title")}</a>
                {{else}}
                <a href='/info/o/${marker.get("id")}'>
                    {{html $item.formatTextTrimmed(marker.get("text"), 50)}}&nbsp;<strong><span class='orange'>&raquo;</span></strong>
                </a>
                {{/if}}
            </li>
            {{else}}
            <li class="clusterMessage">
                <a href='/info/s/${marker.get("id")}'>
                {{if marker.get("isPublic") == 1}}
                    ${marker.get("firstName")}{{if marker.get("lastName").length > 0}} ${marker.get("lastName")}{{/if}}:
                {{else}}
                    ${marker.get("firstName")}{{if marker.get("lastName").length > 0}} ${$item.getFirstLetter(marker.get("lastName"))}{{/if}}:
                {{/if}}
                </a>
                {{if marker.get("title").length > 0}}
                <a href='/info/s/${marker.get("id")}'>${marker.get("title")}</a>
                {{else}}
                <a href='/info/s/${marker.get("id")}'>
                    {{html $item.formatTextTrimmed(marker.get("text"), 50)}}&nbsp;<strong><span class='orange'>&raquo;</span></strong>
                </a>
                {{/if}}
            </li>
            {{/if}}
        {{/each}}
        </ul>
        <div class="pointer fs10 grey" onclick="zoomTo(${lat}, ${lng});" class="zoomInControl">
            Приблизить карту
        </div>
    </div>
</script>
<script id="mapMarkerOrgPopupTmpl" type="text/x-jquery-tmpl">
    <div class="mainPopupWindow">
    {{if typeSlug == 'gov'}}
        <h4 class="popup_h4 switcher_gov">
    {{else typeSlug == 'nco'}}
        <h4 class="popup_h4 switcher_org">
    {{else typeSlug == 'co'}}
        <h4 class="popup_h4 switcher_com">
    {{else typeSlug == 'bloodstation'}}
        <h4 class="popup_h4 switcher_blood">
    {{else}}
        <h4 class="popup_h4 switcher_org">
    {{/if}}
            <a href="/org/d/${id}">${title}</a>
        </h4>
        <div class="ml20"><a href="/org/d/${id}">{{html text}} <strong><span class='orange'>&raquo;</span></strong></a></div>
    </div>
</script>
<script id="messagesBlockTmpl" type="text/x-jquery-tmpl">
    <li>
        <div class="triple_date">${$item.getMonthDay(dateAdded)}<br />
			<span class="triple_year">${$item.getYear(dateAdded)}</span>
		</div>
        {{if typeSlug == '<?php echo $messageTypeRequest;?>'}}
        <a class="triple_link" href="/info/s/${id}" title="Просьба о помощи #${id}{{if title.length > 0}}: ${title}{{/if}}">
        {{else typeSlug == '<?php echo $messageTypeOffer;?>'}}
        <a href="/info/s/${id}" class="triple_link" title="Предложение помощи #${id}">
        {{else}}
        <a href="/info/s/${id}" class="triple_link" title="Информационное сообщение #${id}{{if title.length > 0}}: ${title}{{/if}}">
        {{/if}}
        {{if title.length > 0}}
            {{html title}}
        {{else}}
            {{html $item.formatTextTrimmed(text, 50)}}
        {{/if}}
        </a>
        <div class="clearfix"></div>
    </li>
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>

<script src="/javascript/mainIndex.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
