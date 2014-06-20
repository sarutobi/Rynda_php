<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Ajax_Views (контроллер служебных скриптов)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/controllers/ajax_views.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Контроллер скриптов для обслуживания аякс-запросов от представлений
 * (в основном для различных виджетов).
 */
class Ajax_Views extends Rynda_Controller
{
    /**
     * Получение JSON-списка всех подкатегорий указанной.
     *
     * @param $parentId integer Id категории, подкатегории которой требуется вернуть.
     * Если передан 0, будут получены все категории корневого уровня. По умолчанию
     * используется 0.
     */
    public function getCategoryChildren($parentId = 0)
    {
        $parentId = (int)$parentId;
        if($parentId < 0)
            return FALSE;

        $this->load->model('Categories_Model', 'categories', TRUE);

        echo json_encode($this->categories->getChilds($parentId, NULL),
                         JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP);
    }
}
