<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон представления для страницы просмотра сообщения типа
 * "просьба помощи".
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/messagesDetailRequest.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

$title = "Просьба о помощи №{$message['id']}".($message['title'] ? ": {$message['title']}" : "");
?>
<div class="g960" id="content">
    <!-- Левая панель -->
    <div class="column" id="vm_1_column">
    	<div class="breadcrumbs mb10">
        	<a title="Главная" href="/">Главная</a> &raquo;
            <a href="/vse" title="Сообщения">Сообщения</a> &raquo;
            <a href="/pomogite" title="Сообщения типа «просьба о помощи»">Просьбы о помощи</a> &raquo;
            <?php echo $title;?>
        </div>
        <h2 class="h24 allcaps mb10">
        <?php if($message['statusId'] >= MESSAGE_STATUS_MODERATED
              && $message['statusId'] <= MESSAGE_STATUS_REACTED) {?>
            <div id="messageVerified"><?php echo $message['statusName'];?></div>
        <?php } else if($message['statusId'] == MESSAGE_STATUS_CLOSED) {?>
            <div id="messageVerified"><?php echo $message['statusName'];?></div>
        <?php } else {?>
            <div id="messageUnverified"><?php echo $message['statusName'];?></div>
        <?php }?>
			<?php echo $title;?>
        </h2>
        <?php if($this->ion_auth->in_group('moder')) {?>
        <a title="Редактировать сообщение в админке" href="http://master.dev-rynda.ru/show/message/<?php echo $message['id'];?>">[Редактировать сообщение]</a>
        <?php }?>
        <div class="grey">
        <?php echo date('d ', $message['dateAdded']).
                        $this->lang->line( 'cal_'.strtolower(date('M', $message['dateAdded'])) ).
                        date(' Y г., H:i', $message['dateAdded']).
                        ' (МСК)';?>
        </div>
        <?php if($message['text']) {?>
            <div class="vm_main_text rounded_all"><?php echo $message['text'];?></div>
        <?php }
        if($message['categories']) {?>
            <div class="vm_categories">
                Категории: <?php echo formatCategoryList($message['categories'], array('delimiter' => ''));?>
            </div>
        <?php }
        if($photo) {?>
        <div class="vm_pics">
            <?php foreach($photo as $photoData) {?>
        	<a href="<?php echo $photoData['uri'];?>" class="photoHref" rel="photo">
                <img src="<?php echo $photoData['thumb_uri'];?>" alt="" />
            </a>
            <?php }?>
        </div>
        <?php }?>
    </div>
    <!-- Левая панель завершена -->
    
    <!-- Правая панель -->
    <div class="column" id="vm_2_column">
        <ul class="aid_list">
            <li>
<!--                <img src="/images/incognito.png" alt="Инкогнито" />-->
                От:
                <strong>
                <?php echo empty($message['isPublic']) ?
                              // Данные об имени частично скрыты (выводится И + первая буква Ф):
                               (empty($message['firstName']) ? '' : $message['firstName'])
                              .(empty($message['lastName']) ? '' : ' '.mb_substr($message['lastName'], 0, 1).'.').' '
                               :
                               // Данные об имени открыты (выводится ФИО в виде ссылки на страницу юзера):
                               ($message['userId'] ? '<a href="/info/u/'.$message['userId'].'">' : '')
                              .(empty($message['firstName']) ? '' : $message['firstName'])
                              .(empty($message['patrName']) ? '' : " {$message['patrName']}")
                              .(empty($message['lastName']) ? '' : " {$message['lastName']}")
                              .($message['userId'] ? '</a>' : '');?>
                </strong>
    			<br />
                Как связаться:
				<?php if( !empty($message['isPublic']) ) {?>
                <div id="contactsData" style="display:none">
                <?php foreach((array)$message['phones'] as $phone) {?>
                    <span class="phone-ico mr10"></span><span><?php echo formatPhone($phone);?></span>
					<br />
					<span class="email-ico mr10"></span>
                <?php }?>
                    <span><a href="mailto:<?php echo $message['email'];?>"><?php echo $message['email'];?></a></span>
                </div>
                <div id="contactsHidden" class="fs1e">
				    <?php foreach((array)$message['phones'] as $phone) {?>
                    <span class="phone-ico mr10"></span>
                    <span class="vat fs12"><?php echo substr(formatPhone($phone), 0, 4).'XXX-XX-XX';?></span>
				 <br />
                <?php }?>
                    <span class="email-ico mr10"></span>
					<span class="vat fs12">XXXXX@user.ru</span>
                    <div id="showContacts" class="dotted freshGreen mt4">Открыть контактную информацию</div>
                </div>
                <?php } else {?>
                <div>
                    Пользователь предпочёл скрыть свою контактную информацию.<br /><br />
                    Чтобы связаться с ним, используйте <a href="#comments">комментарии</a>!
                </div>
                <?php }?>
                <div class="clearfix"></div>
            </li>
        </ul>    
        <?php if($message['address'] || $message['regionName']) {?>
            <strong>На карте:</strong>
            <?php echo ($message['address'] ? $message['address'].', ' : '')
                 .($message['regionName'] ? $message['regionName'] : '');
        }

        if($message['lat'] || $message['lng']) {?>
            <div id="locationMap">
                <div id="locationMapCanvas" class="mt10" style="width: 410px; height: 250px;"></div>
            </div>
            <div id="messageLat" style="display:none;"><?php echo $message['lat'];?></div>
            <div id="messageLng" style="display:none;"><?php echo $message['lng'];?></div>
        <?php }?>
         <!-- AddThis Button BEGIN -->
        <div class="social_networks mt25">
			<script type="text/javascript">
                var addthis_config = {ui_language: 'ru'}
            </script>		
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
            <a class="addthis_button_vk" title="Запостить во ВКонтакте"></a>
            <a class="addthis_button_facebook" title="Запостить в Facebook"></a>
            <a class="addthis_button_twitter" title="Запостить в Twitter"></a>
            <a class="addthis_button_mymailru" title="Добавить в Мой Мир на Mail.ru"></a>
            <a class="addthis_button_favorites" title="Сохранить в закладках браузера"></a>
            <a class="addthis_button_google" title="Сохранить в закладках Google"></a>
            <a class="addthis_button_livejournal" title="Запостить в ЖЖ"><img alt="LiveJournal" src="/css/i/lj.png" width="32px" height="32px" /></a>
            <a class="addthis_button_compact" title="Сохранить в..."></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4d1233ce7f701763"></script>
        </div>
        <!-- AddThis Button END -->
    </div>
    <!-- Правая панель завершена -->
	<?php echo('');
/**
    <?php if( !empty($advises) ) {?>
        <div class="g960 inside" id="nh_actualRecommendations">
            <!--<div class="more_rec">
            <a class="orange rynda_icon" href="#">Другие рекомендации</a>
            </div>-->
            <h3 class="no_text">Актуальные рекомендации</h3>
            <div class="clearfix grey_line"></div>
            <div class="act_rec_cont">
                <ul id="recommendationsList" class="no_list floatleft">
                <?php foreach($advises as $advise) {?>
                    <li>
    <!--                    <div style="width:135px; height:100px; background:#CCC; float:left; margin-right:20px;">Тут картинка</div>-->
    <!--                    <a href="/info/s/<?php echo $advise['id'];?>">-->
                        <h4 class="mb10"><?php echo $advise['title'];?></h4>
    <!--                    </a>-->
                        <div>
                        <?php echo $advise['text'];?>
                        <br /><br />
                        Категории: <?php echo formatCategoryList($advise['categories']);?>
                        <br /><br />
    <!--                    <a class="orange" href="/info/s/<?php echo $advise['id'];?>">Подробнее &raquo;</a>-->
                        </div>
                    </li>
                <?php }?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="nh_act_rec_bottom"></div>
        </div>
    <?php }?>
	 */

;
?>
    <div class="clearfix"></div>
    
    <?php $this->load->view('widgets/commentsBlock', array());?>
</div>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>

<script src="/javascript/messagesDetail.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js" type="text/javascript"></script>