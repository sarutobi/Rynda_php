<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для личной страницы пользователя.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/auth/userPersonal.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.3
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */?>
<div class="clearfix"></div>
<div class="breadcrumbs mb10 ml10 "><a title="Главная" href="/">Главная</a> &raquo; Моя страница</div>
<div id="user_profile_container" class="rounded_all">
    <div class="header_container rounded_top">
        <?php if($isOwner) {?>
        <a href="/user/edit/<?php echo $user->id;?>" class="profile_edit_settings"><div id="editHeader">Редактировать</div></a>
        <?php }?>
        <h3 class="mb0 rounded_top regulartext_header">
			Профиль пользователя
        </h3>
    </div>
	<div id="user_profile_warp1_container">
	<div id="user_profile_warp2_container">
	<div class="UP_left_column">

    <div id="avatar_container">
        <div id="avatar_image">
            <img src="<?php echo $avatar ? site_url($avatar['uri']) : '/css/i/anonymous.png';?>" />
            <div id="avatar_image-circle"></div>
        </div>
    </div>
	<div id="user_profile_left_column_shortData">
        <div class="UP_left_column_shortData_cont">
            <span class="darkGrey">Пол:</span>
            <?php if($user->gender == 1) {?> муж. <?php }
            else if($user->gender == 2) {?> жен. <?php }
            else {?> не указан <?php }?>
        </div>
		<br />
        <?php if( !empty($user->about_me) ) { ?>
        <div class="UP_left_column_shortData_cont">
		<span class="darkGrey">О себе:</span>
        <?php echo nl2br($user->about_me);?>
		</div>
        <?php }?>
		<br />
<!--		<div class="UP_left_column_shortData_cont">
		<span class="darkGrey">Группа крови:</span>
		</div>-->
	</div>

	</div>
	<div class="UP_center_column">
        <div id="user_profile_box_container">
			<div id="user_FI_Container">
				<h3 class="user_FI">
				<?php
					if( !$user->first_name && !$user->last_name )
						echo $user->email;
					else if($isOwner)
                        echo $user->first_name.' '.$user->last_name;
                    else
						echo $this->rynda_user->isPrivate($user) ?
                           $user->first_name.' '.(mb_substr($user->last_name, 0, 1)).'.'
                         : $user->first_name.' '.$user->last_name;
				?>
				</h3>
			</div>
			<div class="relative">
				<div class="prof_info_container absolute">
					<ul>
					<li class="green">Последний вход на сайт: <br /><?php echo date('d.m.Y', $user->last_login);?></li>
					<li class="green">Зарегистрирован: <br /><?php echo date('d.m.Y', $user->created_on);?></li>
					</ul>
				</div>
			</div>
			<div class="short_info_container">
                <div id="contactsData" <?php if( !$isOwner ) {?> style="display:none" <?php }?>>
                    <ul>
                        <li>
                            <span class="email-ico"></span> 
                            <a href="mailto:<?php echo $user->email;?>" title="Написать письмо на эту почту"><?php echo $user->email;?></a>
                        </li>
                        <li>
                            <span>
                            <?php foreach($this->rynda_user->getPhones($user) as $phone) {
                                if($phone) {?>
                                <span class="phone-ico"></span><?php echo formatPhone($phone);?><br />
                            <?php }?>
                            </span>
                        <?php }?>
                        </li>
                    </ul>
                </div>
                <?php if( !$isOwner ) {?>
				<div id="contactsHidden" class="pl20 mt25 mb10 darkGrey ">
                    Контактная информация скрыта!
                    <div id="showContacts" class="dotted green mt4">Открыть</div>
                </div>
                <?php }?>
<!--				<ul>
					<li><span class="email-ico"></span> 
                        <?php if($this->rynda_user->isPrivate($user)) {?>
						Пользователь предпочёл скрыть свои данные
					<?php } else {?>
						<a href="mailto:<?php echo $user->email;?>" title="Написать письмо на эту почту"><?php echo $user->email;?></a>
					<?php }?>
					</li>
					<li>
					<?php if($this->rynda_user->isPrivate($user)) {?>
						<span class="phone-ico"></span>Пользователь предпочёл скрыть свои данные
					<?php } else {?>
						<span>
						<?php foreach($this->rynda_user->getPhones($user) as $phone) {
							if($phone) {?>
							<span class="phone-ico"></span><?php echo formatPhone($phone);?> <br />
						<?php }
						}?>
						</span>
					<?php }?>
					</li>
				</ul>-->
                
                <?php if( !empty($socNetProfiles) ) {?>
                <h4><span>В соц.сетях:</span></h4>
                <div id="socialNet_showContainer">
                    <?php foreach($socNetProfiles as $profile) {?>
                    <div class="socialNet_show"><a href="<?php echo $profile['profileUrl'];?>"><img src="<?php echo $profile['socNetIcon'];?>" /><span><?php echo $profile['socNetTitle'];?></span></a></div>
                    <?php }?>
                </div>
                <?php }?>
			</div>
			<div class="clear">&nbsp;</div>
			<?php if( !empty($volunteerProfiles) ) {?>
			<h4><span>Профили волонтёрства</span></h4>
			<div id="vpList">
				<ul>
					<?php foreach($volunteerProfiles as $profile) {?>
					<li><a href="#vp<?php echo $profile['id'];?>"><?php echo $profile['title'];?></a></li>
					<?php }?>
				</ul>
				<?php foreach($volunteerProfiles as $profile) {?>
				<div id="vp<?php echo $profile['id'];?>">
		<?php /*?>            Профиль №<?php echo $profile['id'];?><?php */?>
					<p><span class="region"></span>Местоположение: <?php echo $profile['address'];?> (<a href="/info/r/<?php echo $profile['regionId'];?>" title="Все сообщения по этому региону"><?php echo $profile['regionName'];?></a>)</p>
					<p><span class="distance"></span>На каком расстоянии готов(а) помогать:
						<strong><?php echo formatAidingDistance($profile['distance']);?></strong></p>
					<p><span class="days"></span>По каким дням готов(а) помогать:
					<strong><?php echo formatWeekDays($profile['days']);?></strong>
					<p><span class="categoryhelp"></span>Категории помощи: <?php echo $profile['isAllCategories'] ? 'любые' : formatCategoryList($profile['categories']);?></p>
					<?php if($isOwner) {?>
					<p><span class="mailoutEmail"></span>Присылать уведомления о подходящих просьбах о помощи на: <?php echo $profile['mailoutEmail'];?></p>
					<?php }?>
				</div>
				<?php }?>
			</div>
			<?php }?>
			<div class="clear">&nbsp;</div>
			<h4><span>
				Сообщения пользователя <div id="messagesCount"></div>
				<img id="controlResponseLoading" src="/css/i/blue_sm_loader.gif" alt="" style="display: none;" /></span>
			</h4>
			<div id="messageList_container">
				<div class="date_select_profile mb10">
					<a href="#" class="dateAddedFrom listFilter" id="<?php echo mktime(0, 0, 0);?>" title="Только сообщения, поступившие сегодня">За сегодня</a> |
					<a href="#" class="dateAddedFrom listFilter" id="<?php echo strtotime('-3 day', mktime(0, 0, 0));?>" title="Только сообщения, поступившие за последние 3 дня">За 3 дня</a> |
					<a href="#" class="dateAddedFrom listFilter" id="<?php echo strtotime('-1 week', mktime(0, 0, 0));?>" title="Только сообщения, поступившие за последнюю неделю">За неделю</a> |
					<a href="#" class="dateAddedFrom listFilter activeDateFilter" id="0" title="Сообщения, поступившие за все время">За все время</a>
				</div>
				<div id="controlResponseMessage"></div>
				<ul id="messagesList" class="no_list profile_list"></ul>
				<div class="clearfix"></div>
		   </div>
		   
		</div>
	</div>
	<div class="clearfix"></div>
	</div>
	</div>

</div>
<script id="listItemTmpl" type="text/x-jquery-tmpl">
    <li>
        {{if typeSlug == '<?php echo $messageTypeRequest;?>'}}
            <a href="/info/s/${id}" title="Просьба о помощи #${id}{{if title}}: ${title} {{/if}}" class="darkRed">#${id}</a> -
                <a href="/info/s/${id}" title="Просьба о помощи №${id}{{if title}}: ${title} {{/if}}">
                    Просьба о помощи #${id}{{if title}}: ${title}{{/if}}
                </a>     
        {{else typeSlug == '<?php echo $messageTypeOffer;?>'}}
            <a href="/info/s/${id}" title="Предложение помощи #${id}{{if title}}: ${title} {{/if}}" class="darkRed">#${id}</a> -
                <a href="/info/s/${id}" title="Предложение помощи №${id}{{if title}}: ${title} {{/if}}">
                    Предложение помощи #${id}{{if title}}: ${title}{{/if}}
                </a>   
        {{else}}
            <a href="/info/s/${id}" title="Информационное сообщение #${id}{{if title}}: ${title} {{/if}}" class="darkRed">#${id}</a> -
                <a href="/info/s/${id}" title="Информационное сообщение #${id}{{if title}}: ${title} {{/if}}">
                    Информационное сообщение #${id}{{if title}}: ${title} {{/if}}
                </a>
        {{/if}}
    </li>
</script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<script src="/javascript/userPersonal.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
