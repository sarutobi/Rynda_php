<?php
/**
 * Файл содержит шаблон представления виджета для фильтра списка пользователей.
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/widgets/usersFilter.php
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
        Сортировать список по:
        <select name="filterOrderBy" id="filterOrderBy">
            <option value="dateAdded" selected="selected">дате регистрации</option>
            <option value="name">именам</option>
        </select>
        <select name="orderByDir" id="orderByDir">
            <option value="desc" selected="selected">&#x25BC;</option>
            <option value="asc">&#x25B2;</option>
        </select>

        <?php if(in_array('region', $filterShowFields) && !empty($regions)) {?>
        <select name="filterRegion" id="filterRegion">
            <option value="">Все регионы</option>
        <?php foreach((array)$regions as $region) {?>
            <option value="<?php echo $region['id'];?>" <?php echo $this->input->cookie('ryndaorg_region') == $region['id'] ? 'selected' : '';?>><?php echo $region['name'];?></option>
        <?php }?>
        </select>
        <?php }?>

        <?php if(in_array('category', $filterShowFields) && !empty($categories)) {?>
		<h4 class="mt10">Категории</h4>
        <?php $this->load->view('widgets/categorySelect',
                                array('categories' => $categories,
                                      'expandable' => FALSE));?>
        <div class="clear">&nbsp;</div>
        <?php }?>
        <?php if(in_array('searchString', $filterShowFields)) {?>
        <input type="text" name="filterString" id="filterString" placeholder="Введите здесь текст для поиска" />
        <?php }?>
        <div class="clearfix mt10"></div>
        <input type="submit" id="filterSubmit"  name="filterSubmit" value="Обновить поиск" />
        <!--<img id="filterResponseLoading" src="/images/loading.gif" alt="" style="display: none;" />-->
    </form>
</div>