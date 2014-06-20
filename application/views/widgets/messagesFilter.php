<?php
/**
 * Файл содержит шаблон представления виджета для фильтра списка сообщений.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/widgets/messagesFilter.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */ ?>
<div class="header_container_grey rounded_top">
    <h3 class="mb0 rounded_top regulartext_header_grey">Выберите параметры поиска</h3>
</div>
<div class="greyFilter" id="messages_filter">
	<div class="filter_corner"></div>
    <form id="filterForm" action="#">
        <?php if(in_array('messageType', $filterShowFields) && !empty($messageTypes)) {?>
        <select name="filterTypeId" id="filterTypeId" class="filterbox">
            <option value="">Все типы сообщений</option>
        <?php foreach((array)$messageTypes as $type) {?>
            <option value="<?php echo $type['id'];?>"><?php echo $type['name'];?></option>
        <?php }?>
        </select>
        <?php }

        if(in_array('region', $filterShowFields) && !empty($regions)) {?>
        <select name="filterRegion" id="filterRegion">
            <option value="">Все регионы</option>
        <?php foreach((array)$regions as $region) {?>
            <option value="<?php echo $region['id'];?>" <?php echo $this->input->cookie('ryndaorg_region') == $region['id'] ? 'selected' : '';?>><?php echo $region['name'];?></option>
        <?php }?>
        </select>
        <?php }?>
        <!-- <select name="submap"></select><label for="submap">Тип карты</label> -->
        <?php if(in_array('category', $filterShowFields) && !empty($categories)) {?>
		<h4 class="mt10">Категории</h4>
        <?php $this->load->view('widgets/categorySelect',
                                array('categories' => $categories,
                                      'expandable' => FALSE));?>
        <div class="clear">&nbsp;</div>
        <?php }?>
        <?php if(in_array('isUntimed', $filterShowFields)) {?>
        <select name="untimed" id="filterUntimed">
            <option value="">Как срочно?</option>
            <!--<option value="nm">Всё равно</option>-->
            <option value="0">Срочные</option>
            <option value="1">Несрочные</option>
        </select>
        <div class="clearfix"></div>
        <?php }?>
        <?php if(in_array('searchString', $filterShowFields)) {?>
        <input type="text" name="filterString" id="filterString" placeholder="Введите здесь текст для поиска" />
        <?php }?>
        <div class="clearfix mt10"></div>
        <input type="submit" id="filterSubmit"  name="filterSubmit" value="Обновить поиск" />
        <?php if(!empty($filterPersistVars)) {
            foreach((array)$filterPersistVars as $varName => $value) {
                if(is_array($value)) {
                    foreach($value as $arrElementValue) {?>
        <input type="hidden" class="filterPersistVar" name="<?php echo $varName;?>[]" value="<?php echo $arrElementValue;?>" />
        <?php       }
                } else {?>
        <input type="hidden" class="filterPersistVar" name="<?php echo $varName;?>" value="<?php echo $value;?>" />
        <?php   }
            }
        }?>
        <!--<img id="filterResponseLoading" src="/images/loading.gif" alt="" style="display: none;" />-->
    </form>
</div>