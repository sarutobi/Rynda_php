<?php
/**
 * Файл содержит шаблон представления виджета для фильтра списка организаций.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/widgets/organizationsFilter.php
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
        <?php if(in_array('organizationType', $filterShowFields) && !empty($organizationTypes)) {?>
        <select name="filterTypeId" id="filterTypeId">
            <option value="">Все типы</option>
        <?php foreach($organizationTypes as $type) {?>
            <option value="<?php echo $type['id'];?>"><?php echo $type['name'];?></option>
        <?php }?>
        </select>
        <?php }?>
        <?php if(in_array('region', $filterShowFields) && !empty($regions)) {?>
        <select name="regionId" id="filterRegion">
            <option value="">Все регионы</option>
        <?php foreach((array)$regions as $region) {?>
            <option value="<?php echo $region['id'];?>"><?php echo $region['name'];?></option>
        <?php }
        ?>
        </select>
        <?php }?>
        <!-- <select name="submap"></select><label for="submap">Тип карты</label> -->
        <?php if(in_array('category', $filterShowFields) && !empty($categories)) {?>
        <br />
		<h4>Категории</h4>
        <?php $this->load->view('widgets/categorySelect',
                                array('categories' => $categories,
                                      'expandable' => FALSE));?>
        <div class="clear">&nbsp;</div>
        <?php }?>
        <?php if(in_array('filterString', $filterShowFields)) {?>
        <input type="text" name="filterString" id="filterString" placeholder="Введите здесь текст для поиска" />
        <?php }?>
        <div class="clearfix"></div>
        <input type="submit" id="filterSubmit" name="filterSubmit" value="Обновить поиск" />
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
    </form>
</div>