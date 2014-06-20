<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит шаблон страницы детальной информации об организации.
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi
 * @link       /application/views/organizationsDetail.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */
?>

<div class="g960" id="content">
    <!-- Левая панель -->
    <div class="column" id="vm_1_column">
    	<div class="breadcrumbs mb10">
        	<a title="Главная" href="/">Главная</a> &raquo;
            <a href="/org" title="Список организаций">Организации</a> &raquo;
            <?php echo $org['title'];?>
        </div>
        <h2 class="h24 allcaps mb10"><?php echo $org['title'];?></h2>
        <div class="grey">Тип: <?php echo $org['typeName'];?></div>
        <div class="grey">Добавлена на сайт: <?php echo date('d.m.Y', $org['dateAdded']);?></div>
        <div class="vm_main_text rounded_all">	
			<?php if($org['text']) {
                echo $org['text'];
            } else { ?>Описание организации отсутствует.<?php }?>
         </div>
         	<br />    
            <br />
            <?php if($org['sites']) {?>
            <div class="grey">Сайты организации:
                <?php foreach($org['sites'] as $site) {
                    if( !empty($site) ) {?>
                <a href="http://<?php echo $site;?>" title="Сайт организации"><?php echo $site;?></a>&nbsp;
                <?php }
                }?>
            </div>
            <?php }?>
        
        <?php if($org['categories']) {?>
        <div class="grey">Категории организации:
        <?php foreach($org['categories'] as $category) {?>
            <a href="/info/c/<?php echo $category['id'];?>" title="Все сообщения категории «<?php echo $category['name'];?>»"><?php echo $category['name'];?></a>
        <?php }?> 
        </div>
        <?php }?>
    </div>
    <!-- Левая панель завершена -->
    
    <!-- Правая панель -->
    <div class="column" id="vm_2_column">
        <ul class="aid_list">
            <li>
<!--                <img src="/images/ngo.png" alt="Инкогнито" />-->
                <?php if($org['contacts']) {?>
                Контактные лица: <?php echo $org['contacts'];?><br />
                <?php }
                if($org['phones']) {?>
				Телефоны: <?php echo implode(', ', $org['phones']);?><br />
                <?php }
                if($org['emails']) {?>
				Email: <?php
                    foreach($org['emails'] as &$email) {
                        $email = "<a href='mailto:$email'>$email</a>";
                    }
                    echo implode(', ', (array)$org['emails']);
                }?>
                <div class="clearfix mb35"></div>
            </li>
        </ul>    
        <strong>На карте</strong>:
        <?php echo $org['regionName'] ? $org['regionName'] : '';?>
        <?php if($org['address']) {?>, <?php echo $org['address'];?><?php }?>
        <div id="locationMap">
            <div id="locationMapCanvas" class="mt10" style="width: 410px; height: 250px;"></div>
        </div>
        <div id="organizationLat" style="display:none;"><?php echo $org['lat'];?></div>
        <div id="organizationLng" style="display:none;"><?php echo $org['lng'];?></div>
    </div>
    <!-- Правая панель завершена -->

    <div class="clear1">&nbsp;</div>
    <?php $this->load->view('widgets/commentsBlock', array());?>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>
<script src="/javascript/lib/jQueryTemplates.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>

<script src="/javascript/organizationsDetail.js?v=<?php echo sha1($this->config->item('js_version'));?>" type="text/javascript"></script>