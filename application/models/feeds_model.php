<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Feeds_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/feeds_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с RSS-потоками.
 */
class Feeds_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
//    const ERROR_OF_SOME_KIND = 1;

//    public function __construct()
//    {
//        parent::__construct();
//        self::$_errorTypes = array(self::ERROR_OF_SOME_KIND =>
//                                       $this->lang->line(''),);
//    }

    /**
     * Получение списка элементов потока, соотв. указанным параметрам фильтрации.
     *
     * @param array $filter Массив параметров фильтрации. Возможные значения полей:
     * * id mixed ID элемента или массив ID.
     * * title string Заголовок элемента.
     * * dateAddedFrom mixed Нижняя граница даты/времени добавления элемента. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateAddedTo mixed Верхняя граница даты/времени добавления элемента. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param array $order Массив параметров сортировки результатов. Ключи массива -
     * названия полей сообщения, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return array Массив сообщений, содержащий строки со следующими полями:
     * * id integer ID элемента.
     * * title string Заголовок элемента.
     * * content string Текст элемента.
     * * dateAdded string Timestamp даты/времени добавления элемента.
     * * link string URL, на который ведёт элемент.
     */
    public function getItemsList(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,
                                  'title' => '',);
        $this->db->from('feed_item');

        if((int)$filter['id'] > 0)
            $this->db->where('id', (int)$filter['id']);

        if( !empty($filter['title']) )
            $this->db->like('title', trim($filter['title']));

        if((int)$filter['feedId'] > 0)
            $this->db->where('feed_id', (int)$filter['feedId']);

        if( !empty($filter['dateAddedFrom']) ) {
            if((int)$filter['dateAddedFrom'] === $filter['dateAddedFrom']) // Передан timestamp
                $this->db->where('date >= ', date('Y-m-d H:i:s', $filter['dateAddedFrom']));
            else // Передана строка - аргумент для strtotime()
                $this->db->where('date >= ', date('Y-m-d H:i:s', strtotime($filter['dateAddedFrom'])));
        }

        if( !empty($filter['dateAddedTo']) ) {
            if((int)$filter['dateAddedTo'] === $filter['dateAddedTo']) // Передан timestamp
                $this->db->where('date <= ', date('Y-m-d H:i:s', $filter['dateAddedTo']));
            else // Передана строка - аргумент для strtotime()
                $this->db->where('date <= ', date('Y-m-d H:i:s', strtotime($filter['dateAddedTo'])));
        }

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $this->db->order_by('id', $orderBy);
                    break;
                case 'title':
                    $this->db->order_by('title', $orderBy);
                    break;
                case 'content':
                    $this->db->order_by('content', $orderBy);
                    break;
                case 'dateAdded':
                    $this->db->order_by('date', $orderBy);
                    break;
                case 'feedId':
                    $this->db->order_by('feed_id', $orderBy);
                    break;
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }

       $items = $this->db->get();

        if(!$items)
            return array();

        $res = array();
        foreach($items->result() as $item) {
            $res[] = array('id' => $item->id,
                           'title' => $item->title,
                           'content' => $item->content,
                           'dateAdded' => strtotime($item->date),
                           'link' => $item->link,
                           'feedId' => $item->feed_id,);
        }

        return $res;
    }
}