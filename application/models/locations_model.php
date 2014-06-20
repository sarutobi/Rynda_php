<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Locations_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi
 * @link       /application/models/locations_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с геоточками.
 */
class Locations_Model extends Rynda_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Проверка, существует ли в базе геолокация, по координатам и названию.
     *
     * @param $lat float Широта геолокации.
     * @param $lng float Долгота геолокации.
     * @param $name string Описание (адрес) геолокации.
     * @return boolean Известна или нет геолокация.
     * @todo Функция-заглушка, необходима нормальная реализация
     */
    public function isLocationExists($lat, $lng, $name = '')
    {
        return FALSE;
    }

   
    /**
     * Добавление новой геоточки в базу.
     * 
     * @param $lat float Широта геоточки.
     * @param $lng float Долгота геоточки.
     * @param $regionId int ID региона геоточки.
     * @param $name string Описание (адрес?) геоточки.
     * @return integer ID добавленной либо найденной локации.
     **/
    public function insert($lat, $lng, $regionId, $name)
    {
        if( !$this->isLocationExists($lat, $lng, $name) ) {
            $this->db->query('INSERT INTO "Location"(latitude, longtitude, region_id, name)
                              VALUES (?, ?, ?, ?)',
                             array((float)$lat, (float)$lng, (int)$regionId, trim($name)));
            return $this->db->insert_id();
        } else
            return FALSE;
    }

    /**
     * Опредление региона по геогр. координатам.
     *
     * @param $lat float Широта геоточки.
     * @param $lng float Долгота геоточки.
     * @return mixed Если регион не определён, возвращается FALSE. Иначе, возвращается
     * массив, содержащий ID регионов, к которым принадлежит геоточка.
     */
    public function getRegionByCoords($lat, $lng)
    {
        if(empty($lat) && empty($lng))
            return FALSE;
        $regions = $this->db->query("SELECT region_id id
                                     FROM regions_poly
                                     WHERE st_coveredby(st_geogfromtext('POINT(? ?)'), geom)",
                                    array((float)$lng, (float)$lat));
        if( !$regions->num_rows() )
            return FALSE;
        
        $res = array();
        foreach($regions->result() as $region) {
            $res[] = $region->id;
        }

        return $res;
    }
    
    /**
     * Расчёт расстояния в метрах между 2 точками по ID их локаций.
     * 
     * @param $locationIdFirst integer ID первой локации.
     * @param $locationIdSecond integer ID второй локации.
     * @param $distanceInKm boolean Если передано TRUE, будет возвращён результат в км,
     * иначе в м. По умолчанию TRUE.
     * @return float Расстояние между локациями в км или в м, в зависимсоти от аргумента
     * $distanceInKm.
     */
    public function getDistance($locationIdFirst, $locationIdSecond, $distanceInKm = TRUE)
    {
        $distance = $this->db->query('SELECT rynda.get_distance('.(int)$locationIdFirst.', '.(int)$locationIdSecond.') distance')->row()->distance;
        return $distanceInKm ? round($distance/1000, 3) : (float)$distance;
    }
}
