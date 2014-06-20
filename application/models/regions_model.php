<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Regions_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/regions_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с регионами страны.
 */
class Regions_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
//    const ERROR_OF_SOME_TYPE = 1;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(//ERROR_OF_SOME_TYPE => $this->lang->line('modelRegions_someType'),
                                   //'' => '',
                                   '' => '',);
    }

    /**
     * Метод для получения данных региона по его ID-у.
     *
     * @param $id integer ID региона.
     * @return mixed Если регион не найден, возвращается FALSE. Иначе будет возвращён
     * массив полей региона, содержащий следующие поля:
     * * id integer ID региона в БД.
     * * name string Название региона.
     * * slug string Короткое название региона.
     * * capitalName string Название столицы (центр.города) региона.
     * * lat float Широта региона (центральной точки).
     * * lng float Долгота региона (центральной точки).
     * * zoom integer Уровень приближения карты при автоматическом "полном охвате" региона.
     */
    public function getById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;
        else {
            $res = $this->db->get_where('regions_view', array('region_id' => $id), 1)->row();
            return empty($res) ? FALSE : array('id' => $res->region_id,
                                               'name' => $res->region_name,
                                               'slug' => $res->region_slug,
                                               'capitalName' => $res->capital_name,
                                               'lat' => $res->latitude,
                                               'lng' => $res->longtitude,
                                               'zoom' => $res->zoom_lvl);
        }
    }

    /**
     * Метод для получения данных региона по его короткому названию.
     *
     * @param $slug string Короткое название региона.
     * @return mixed Если регион не найден, возвращается FALSE. Иначе будет возвращён
     * массив полей региона, содержащий следующие поля:
     * * id integer ID региона в БД.
     * * name string Название региона.
     * * slug string Короткое название региона.
     * * capitalName string Название столицы (центр.города) региона.
     * * lat float Широта региона (центральной точки).
     * * lng float Долгота региона (центральной точки).
     * * zoom integer Уровень приближения карты при автоматическом "полном охвате" региона.
     */
    public function getBySlug($slug)
    {
        $this->_clearErrors();

        $slug = trim($slug);
        if(empty($slug))
            return FALSE;
        else {
            $res = $this->db->get_where('regions_view', array('region_slug' => $slug), 1)->row();
            return empty($res) ? FALSE : array('id' => $res->region_id,
                                               'name' => $res->region_name,
                                               'slug' => $res->region_slug,
                                               'capitalName' => $res->capital_name,
                                               'lat' => $res->latitude,
                                               'lng' => $res->longtitude,
                                               'zoom' => $res->zoom_lvl);
        }
    }

    /**
     * Метод для получения регионов в виде списка.
     *
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id integer Id региона.
     * * name string Название региона.
     * * slug string Короткое имя региона.
     * * capitalName string Название главной точки региона ("столицы").
     * * capitalLat float Широта главной точки региона.
     * * capitalLng float Долгота главной точки региона.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей фильтрации, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * сортируются по названию региона в порядке возрастания.
     * @return array Массив регионов, содержащий строки со следующими полями:
     * * id integer ID региона в БД.
     * * name string Название региона.
     * * slug string Короткое название региона.
     * * capitalName string Название столицы (центр.города) региона.
     * * lat float Широта региона (центральной точки).
     * * lng float Долгота региона (центральной точки).
     * * zoom integer Уровень приближения карты при автоматическом "полном охвате" региона.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $this->db->from('regions_view');

        // Параметры фильтрации:
        if( !empty($filter['id']) )
            $this->db->where('region_id', (int)$filter['id']);

        if( !empty($filter['name']) )
            $this->db->where('region_name', trim($filter['name']));

        if( !empty($filter['slug']) )
            $this->db->where('region_slug', trim($filter['slug']));

        if( !empty($filter['capitalName']) )
            $this->db->where('capital_name', trim($filter['capitalName']));

        if( !empty($filter['capitalLat']) )
            $this->db->where('latitude', trim($filter['capitalLat']));

        if( !empty($filter['capitalLng']) )
            $this->db->where('longtitude', trim($filter['capitalLng']));

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        if(empty($order)) // Сортировка по умолчанию
            $this->db->order_by('order', 'asc');
        else {
            if( !empty($order['id']) )
                $this->db->order_by('region_id',
                                    trim($order['id']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['name']) ) {
                $this->db->order_by('region_name',
                                    trim($order['name']) == 'desc' ? 'desc' : 'asc');
            }
            if( !empty($order['capitalName']) )
                $this->db->order_by('capital_name',
                                    trim($order['capitalName']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['lat']) )
                $this->db->order_by('latitude',
                                    trim($order['lat']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['lng']) )
                $this->db->order_by('longtitude',
                                    trim($order['lng']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['order']) )
                $this->db->order_by('region_order',
                                    trim($order['lng']) == 'desc' ? 'desc' : 'asc');
        }
        $res = array();
        foreach($this->db->get()->result() as $row) {
            $res[] = array('id' => $row->region_id,
                           'name' => $row->region_name,
                           'slug' => $row->region_slug,
                           'capitalName' => $row->capital_name,
                           'lat' => (float)$row->latitude,
                           'lng' => (float)$row->longtitude,
                           'zoom' => (int)$row->zoom_lvl);
        }

        return $res;
    }
}