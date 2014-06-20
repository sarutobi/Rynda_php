<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Partners_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/partners_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.5
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с системами-партнёрами платформы Rynda.org.
 */
class Partners_Model extends Rynda_Model
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
     * Получение списка партнёрских систем, зарегистрированных в системе Rynda.org.
     *
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id integer ID партнёрской системы в системе Rynda.org или массив ID.
     * * name string Наименование партнёрской системы.
     * * description string Описание партнёрской системы.
     * * url string URL сайта партнёрской системы.
     * * isActive boolean Активность партнёрской системы. По умолчанию TRUE.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей сообщения, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return array Массив партнёрских систем, содержащий строки со следующими полями:
     * * id integer ID партнёрской системы в системе Rynda.org.
     * * name string Наименование партнёрской системы.
     * * description string Описание партнёрской системы.
     * * url string URL сайта партнёрской системы.
     * * isActive boolean Активность партнёрской системы.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,
                                  'isActive' => TRUE,);
        $query = $this->db->from('partner');
        
        if((int)$filter['id'] > 0)
            $query->where('id', (int)$filter['id']);

        if( !empty($filter['name']) )
            $query->like('name', trim($filter['name']));

        if( !empty($filter['description']) )
            $query->like('description', trim($filter['description']));

        if( !empty($filter['url']) )
            $query->like('url', trim($filter['url']));

//        $query->where('active', (int)$filter['isActive']);
        
        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $query->order_by('id', $orderBy);
                    break;
                case 'name':
                    $query->order_by('name', $orderBy);
                    break;
                case 'isActive':
                    $query->order_by('active', $orderBy);
                    break;
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }

        $partners = $query->get();

        if( !$partners )
            return array();

        $res = array();
        foreach((array)$partners->result() as $partner) {
            $res[] = array('id' => (int)$partner->id,
                           'name' => $partner->name,
                           'description' => $partner->description,
                           'url' => $partner->uri,
                           'isActive' => $partner->active == 't',);
        }

        return $res;
    }
    
    /**
     * Получение списка менеджеров ввода/вывода, зарегистрированных в системе Rynda.org.
     *
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id integer ID менеджера в системе Rynda.org или массив таких ID.
     * * name string Наименование менеджера.
     * * type integer Тип менеджера. Возможные значения: 1 - ввод, 0 - вывод.
     * * isActive boolean Активность менеджера. По умолчанию TRUE.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей сообщения, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return array Массив менеджеров, содержащий строки со следующими полями:
     * * id integer ID менеджера в системе Rynda.org.
     * * name string Наименование менеджера.
     * * description string Описание менеджера.
     * * type integer Тип менеджера. Возможные значения: 1 - ввод, 0 - вывод.
     * * isActive boolean Активность менеджера.
     */
    public function getManagersList(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,
                                  'type' => '-',
                                  'isActive' => TRUE,);
        $query = $this->db->from('partner_manager');
        
        if((int)$filter['id'] > 0)
            $query->where('id', (int)$filter['id']);

        if( !empty($filter['name']) )
            $query->like('name', trim($filter['name']));

//        if($filter['type'] != '-')
//            $query->where('type', (int)$filter['type'] > 0 ? 1 : 0);

//        $query->where('active', (int)$filter['isActive']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $query->order_by('id', $orderBy);
                    break;
                case 'name':
                    $query->order_by('name', $orderBy);
                    break;
                case 'isActive':
                    $query->order_by('active', $orderBy);
                    break;
//                case 'type':
//                    $query->order_by('type', $orderBy);
//                    break;
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }

        $managers = $query->get();

        if( !$managers )
            return array();

        $res = array();
        foreach((array)$managers->result() as $manager) {
            $res[] = array('id' => (int)$manager->id,
                           'name' => $manager->name,
                           'description' => $manager->description,
//                           'type' => $manager->type,
                           'isActive' => $manager->active == 't',);
        }

        return $res;
    }

    /**
     * Получение значений параметров для менеджера ввода/вывода.
     *
     * @param $partnerId integer ID партнёрской системы в БД.
     * @param $managerId integer ID менеджера ввода/вывода в БД.
     * @return mixed Ассоциативный массив имён и соответствующих значений параметров
     * (может быть пустым), либо FALSE, если запрос выполнился с ошибкой.
     */
    public function getParamsValues($partnerId, $managerId)
    {
        $partnerId = (int)$partnerId;
        $managerId = (int)$managerId;
        if( $partnerId <= 0 || $managerId <= 0 )
            return FALSE;
        
        $query = $this->db->select('name "key", value')
                          ->from('partner_parameter')
                          ->where(array('partner_id' => $partnerId,
                                        'manager_id' => $managerId))->get();
        if( !$query )
            return FALSE;

        $res = array();
        foreach((array)$query->result() as $managerParam) {
            $res[$managerParam->key] = $managerParam->value;
        }
        
        return $res;
    }
}