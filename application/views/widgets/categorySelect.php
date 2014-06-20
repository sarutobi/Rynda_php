<?php
/**
 * Файл содержит шаблон представления виджета для раскрывающегося древовидного
 * списка категорий с возможностью множественного выбора (чекбоксами).
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/widgets/categorySelect.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

if( !empty($expandable) ) {?>
<div class="expandable">
    <div class="expandableTitle">Свернуть/развернуть</div>
    <div class="expandableIsOpened" style="display: none;">
        <?php echo empty($expandedByDefault) ? 0 : 1;?>
    </div>
    <div class="expandableContent">
<?php }?>

<ul class="no_list form_corrector cat_select" id="categoryListing">
    <?php foreach($categories as $category) {?>
    <li id="<?php echo $category['id'];?>">
        <?php if($category['childsExists'] > 0) {?>
        <div class="categoryGroup">
            <div id="childs_of_<?php echo $category['id'];?>" class="categoryListIcon collapsed">&nbsp;</div>
            <?php echo mb_ucfirst($category['name']);?>
        </div>
        <div class="clearfix"></div>
        <img class="subCatsLoading" src="/images/white_sm_loader.gif" alt="" style="display: none;" />
        <ul class="no_list subcategory catChilds" style="display: none;"></ul>
        <?php } else {?>
        <label class="cat_label cat_label_child">
            <input type="checkbox" name="category[]" value="<?php echo $category['id'];?>" />
            <?php echo mb_ucfirst($category['name']);?>
        </label>
        <?php }?>
    </li>
    <?php }?>
</ul>
<?php if( !empty($expandable) ) {?>
    </div>
</div>
<?php }
