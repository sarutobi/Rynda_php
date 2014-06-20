<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Categories_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/categories_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с категориями сообщений.
 */
class Categories_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
//    const ERROR_OF_SOME_TYPE = 1;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(//ERROR_OF_SOME_TYPE => $this->lang->line('modelCategories_someType'),
                                   //'' => '',
                                   '' => '',);
    }

    /**
     * Получение данных категории по её ID-у.
     *
     * @param $id integer ID категории.
     * @return mixed Если категория не найдена, возвращается FALSE. Иначе будет возвращён
     * массив полей категории, содержащий следующие поля:
     * * id integer ID категории в БД.
     * * name string Название категории.
     * * slug string Короткое название категории.
     * * parentId integer ID родительской категории в БД.
     * * order integer Индекс сортировки категории.
     * * subdomainId integer ID субдомена, к которому относится категория.
     * * subdomainName string Название субдомена, к которому относится категория.
     */
    public function getById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;
        else {
            $row = $this->db->get_where('category_view', array('category_id' => $id), 1)->row();
            return empty($row) ? FALSE : array('id' => $row->category_id,
                                               'name' => $row->category_name,
                                               'slug' => $row->category_slug,
                                               'parentId' => $row->parent_id,
//                                               'children' => $row->children,
                                               'subdomainId' => $row->subdomain_id,
                                               'subdomainName' => $row->subdomain,);
        }
    }

    /**
     * Получение данных категории по её короткому названию.
     *
     * @param $slug string Короткое название категории.
     * @return mixed Если категория не найдена, возвращается FALSE. Иначе будет возвращён
     * массив полей категории, содержащий следующие поля:
     * * id integer ID категории в БД.
     * * name string Название категории.
     * * slug string Короткое название категории.
     * * parentId integer ID родительской категории в БД.
     * * order integer Индекс сортировки категории.
     * * subdomainId integer ID субдомена, к которому относится категория.
     * * subdomainName string Название субдомена, к которому относится категория.
     */
    public function getBySlug($slug)
    {
        $this->_clearErrors();

        $slug = trim($slug);
        if(empty($slug))
            return FALSE;
        else {
            $row = $this->db->get_where('category_view', array('category_slug' => $slug), 1)->row();
            return empty($row) ? FALSE : array('id' => $row->category_id,
                                               'name' => $row->category_name,
                                               'slug' => $row->category_slug,
                                               'parentId' => $row->parent_id,
//                                               'children' => $row->children,
                                               'subdomainId' => $row->subdomain_id,
                                               'subdomainName' => $row->subdomain,);
        }
    }

    /**
     * Получение категорий в виде списка.
     * 
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id integer ID категории.
     * * name string Название категории.
     * * slug string Короткое название категории.
     * * parentId integer ID родительской категории.
     * * subdomain mixed ID или название субдомена (дочерней карты), к которому относится
     * категория. Если передано FALSE, 0, NULL или пустая строка, будут извлечены категории,
     * привязанные к главной карте.
     * * isStructured boolean Если равно true, то результат будет структуризированным -
     * у каждой строки корневой категории будет поле children, содержащее список её
     * подкатегорий. Если равно false, то результат будет обычным одноуровневым списком.
     * По умолчанию true.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей фильтрации, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * сортируются по полю индекса сортировки в порядке возрастания.
     * @return array Массив категорий, содержащий строки со следующими полями:
     * * id integer ID категории в БД.
     * * name string Название категории.
     * * slug string Короткое название категории.
     * * parentId integer ID родительской категории в БД.
     * * order integer Индекс сортировки категории.
     * * subdomainId integer ID субдомена, к которому относится категория.
     * * subdomainName string Название субдомена, к которому относится категория.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $this->_clearErrors();

        $filter = $filter + array('isStructured' => TRUE,);

        // Параметры фильтрации:
        $this->db->from('category_view');
        if( !empty($filter['id']) )
            $this->db->where('category_id', (int)$filter['id']);

        if( !empty($filter['name']) )
            $this->db->where('category_name', trim($filter['name']));

        if( !empty($filter['slug']) )
            $this->db->where('category_slug', trim($filter['slug']));

        if( !empty($filter['parentId']) ) {
            $filter['parentId'] = (int)$filter['parentId'];
            $filter['parentId'] = $filter['parentId'] >= 0 ? $filter['parentId'] : 0;
            $this->db->where('parent_id', $filter['parentId']);
        }

        if(isset($filter['subdomain'])) {
            if($filter['subdomain']) {
                if((int)$filter['subdomain'] > 0) // Передан Id субдомена
                    $this->db->where('subdomain_id', (int)$filter['subdomain']);
                else if(trim($filter['subdomain'])) // Передано название субдомена
                    $this->db->where('subdomain', trim($filter['subdomain']));
                $this->db->or_where('subdomain_id IS NULL', NULL, false);
            } else
                $this->db->where('subdomain_id IS NULL', NULL, false);
        }

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        if(empty($order))
            $this->db->order_by('order', 'asc');
        else {
            if( !empty($order['id']) )
                $this->db->order_by('category_id',
                                    trim($order['id']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['name']) )
                $this->db->order_by('category_name',
                                    trim($order['name']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['parentId']) )
                $this->db->order_by('parent_id',
                                    trim($order['parentId']) == 'desc' ? 'desc' : 'asc');
            if( !empty($order['order']) )
                $this->db->order_by('order',
                                    trim($order['order']) == 'desc' ? 'desc' : 'asc');
        }

        $res = array();
        if( !empty($filter['isStructured']) ) {
            foreach($this->db->get()->result() as $row) {
                if($row->parent_id == 0) { // Корневая категория
                    $res[$row->category_id]['id'] = $row->category_id;
                    $res[$row->category_id]['name'] = $row->category_name;
                    $res[$row->category_id]['slug'] = $row->category_slug;
                    $res[$row->category_id]['parentId'] = $row->parent_id;
//                    $res[$row->category_id]['childsCount'] = $row->children;
                    $res[$row->category_id]['order'] = $row->order;
                    $res[$row->category_id]['subdomainId'] = (int)$row->subdomain_id;
                    $res[$row->category_id]['subdomainName'] = $row->subdomain;
                } else { // Подкатегория
                    if( empty($res[$row->parent_id]['children']) )
                        $res[$row->parent_id]['children'] = array();

                    $res[$row->parent_id]['children'][] =
                        array('id' => $row->category_id,
                              'name' => $row->category_name,
                              'slug' => $row->category_slug,
                              'parentId' => $row->parent_id,
//                              'childsCount' => $row->children,
                              'order' => $row->order,
                              'subdomainId' => (int)$row->subdomain_id,
                              'subdomainName' => $row->subdomain,);
                }
            }
        } else {
            foreach($this->db->get()->result() as $row) {
                $res[] = array('id' => $row->category_id,
                               'name' => $row->category_name,
                               'slug' => $row->category_slug,
                               'parentId' => $row->parent_id,
//                               'childsCount' => $row->children,
                               'order' => $row->order,
                               'subdomainId' => (int)$row->subdomain_id,
                               'subdomainName' => $row->subdomain,);
            }
        }

        return $res;
    }

    /**
     * Получение всех категорий, дочерних для указанной.
     *
     * @param $parentId integer ID категории, потомки которой требуется извлечь.
     * Если передан 0, будут извлечены категории, не имеющие родительской. По умолчанию 0.
     * @param $subdomain mixed ID или название субдомена (дочерней карты), к которому относятся
     * категории. Если передано FALSE, 0 или пустая строка, будут извлечены категории,
     * привязанные к главной карте. По умолчанию используется текущий домен.
     * @return array Массив, содержащий элементы с полями категории.
     */
    public function getChilds($parentId = 0, $subdomain = -1)
    {
        $subdomain = $subdomain == -1 ? getSubdomain() : $subdomain;

        $this->db->select('category_id id, category_name "name", category_slug "slug",
                           parent_id "parentId", order "order",
                           subdomain_id "subdomainId", subdomain "subdomainName"')
                 ->from('category_view')
                 ->where('parent_id', (int)$parentId)
                 ->order_by('order', 'asc');
        if((int)$subdomain > 0) // Передан ID субдомена
            $this->db->where("(subdomain_id = '$subdomain' OR subdomain_id IS NULL)", NULL, FALSE);
        else if($subdomain !== NULL) // Передано название субдомена
            $this->db->where("(subdomain = '$subdomain' OR subdomain_id IS NULL)", NULL, FALSE);

        $result = array();
        foreach($this->db->get()->result_array() as $category) {
            $childsCount = $this->db->select('COUNT(*) count')
                                    ->from('category_view')
                                    ->where('parent_id', $category['id']);
            if((int)$subdomain > 0) // Передан ID субдомена
                $childsCount->where("(subdomain_id = '$subdomain' OR subdomain_id IS NULL)", NULL, FALSE);
            else if($subdomain !== NULL) // Передано название субдомена
                $childsCount->where("(subdomain = '$subdomain' OR subdomain_id IS NULL)", NULL, FALSE);

            $result[] = array_merge($category,
                                    array('childsExists' => $childsCount->get()->row()->count > 0));
        }

        return $result;
    }
}
