<?php
/**
 * Файл содержит шаблон представления виджета для простого списка категорий
 * (в строчку, разделённого запятыми).
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/views/widgets/categoryList.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

$hrefs = array();
foreach((array)$categories as $category) {
    $hrefs[] = "<a href='/pomogite/c/{$category['id']}' class='fs10'
                   title='Все сообщения категории «".mb_ucfirst($category['name'])."»'>".
                       mb_ucfirst($category['name'])
               .'</a>';
}

echo implode(', ', $hrefs);
