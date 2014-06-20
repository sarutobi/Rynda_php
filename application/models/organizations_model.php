<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Organizations_Model (модель данных).
 *
 * @copyright  Copyright (c) 2011 Valery A. Ilychev aka Sarutobi 
 * @link       /application/models/organizations_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с организациями.
 */
class Organizations_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
    const ERROR_INSERTING_LOCATION = 1;
    const ERROR_INSERTING_MESSAGE = 2;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_INSERTING_LOCATION =>
                                       $this->lang->line('modelMessages_errorLocationInsertFailed'),
                                   self::ERROR_INSERTING_MESSAGE =>
                                       $this->lang->line('modelMessages_errorMessageInsertFailed'),);
    }

    /**
     * Получение краткой информации об организациях. Возможно указать параметр
     * фильтрации для выбора организации по категории или региону.
     * 
     * @param int $region регион для выбора организаций. Значение по умолчанию - 0 (все регионы)
     * @param int $type тип организации. Значение по умолчанию - 0 (все типы)
     * @return array Массив найденных организаций со следующими полями:
     * * id int идентификатор организации
     * * name string наименование организации
     * * address string адрес организации
     * * phones array телефоны организации 
     * Если ни одной организации не было найдено, вернется пустой массив.
     */
    function getShortList($region = 0, $type = 0)
    {
        $this->db->select('id', 'name', 'address', 'phone');
        $this->db->from('organization_view');
        //фильтр по региону
        if ($region != 0){
             $this->db->where('region_id', (int)$region);
        }

        //фильтр по типу организации
        if ($type != 0) {
             $this->db->where('type', (int)$type);
        }
    
        $res = array();
        foreach ($this->db->get()->result() as $r){
            $phones = explode(',', trim($r->phone, '{}'));
            $res[] = array('id'      => $r->id,
                           'name'    => $r->name,
                           'address' => $r->address,
                           'phones'  => $phones);
        }
        
        return $res;
    }
    
    /**
     * Служебный метод для предобработки параметров запроса на получение списка организаций.
     * 
     * @param array $filter Массив параметров фильтрации. Возможные значения полей:
     * * id mixed ID организации или массив ID.
     * * title string Название организации.
     * * text string Описание организации.
     * * typeId mixed ID типа организации. Можно передать один из ID типов организаций
     * или массив из нескольких таких ID.
     * * typeSlug mixed Короткое название (slug) типа организации. Можно передать один из
     * slug типов организации или массив из нескольких таких slug.
     * * dateAddedFrom mixed Нижняя граница даты/времени добавления организации. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateAddedTo mixed Верхняя граница даты/времени добавления организации. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * subdomain mixed ID или название поддомена (дочернего сайта), к которому относится
     * тип организации. По умолчанию извлекаются все организации (без учёта привязок к поддоменам).
     * * lat float Широта точки местонахождения организации.
     * * lng float Долгота точки местонахождения организации.
     * * address string Адрес организации.
     * * regionId integer ID региона, к которому принадлежит точка местонахождения организации.
     * * category mixed ID категории организации или массив ID категорий.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param array $order Массив параметров сортировки результатов. Ключи массива -
     * названия полей организации, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return object Объект Active Record, настроенный на выполнение запроса на получение
     * списка организаций с переданными параметрами. Объект готов к выполнению запроса на
     * извлечение или подсчёт строк.
     */
    protected function _processListParams(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,
                                  'title' => '',
                                  'text' => '',
                                  'type' => array(),
                                  'address' => '',
                                  'category' => array(),);

        $query = $this->db->select('organization_view.*')->from('organization_view');
        
        if((int)$filter['id'] > 0)
            $query->where('organization_id', (int)$filter['id']);

        if( !empty($filter['title']) )
           $query->like('organization_name', trim($filter['title']));

        if( !empty($filter['text']) )
            $query->like('organization_description', trim($filter['text']));

        if( !empty($filter['dateAddedFrom']) ) {
            if((int)$filter['dateAddedFrom'] === $filter['dateAddedFrom']) // Передан timestamp
                $query->where('date_add >= ', date('Y-m-d', $filter['dateAddedFrom']));
            else // Передана строка - аргумент для strtotime()
                $query->where('date_add >= ', date('Y-m-d', strtotime($filter['dateAddedFrom'])));
        }

        if( !empty($filter['dateAddedTo']) ) {
            if((int)$filter['dateAddedTo'] === $filter['dateAddedTo']) // Передан timestamp
                $query->where('date_add <= ', date('Y-m-d', $filter['dateAddedTo']));
            else // Передана строка - аргумент для strtotime()
                $query->where('date_add <= ', date('Y-m-d', strtotime($filter['dateAddedTo'])));
        }
        
        if( !empty($filter['subdomain']) ) {
            $query->join('orgtype_subdomain',
                                'organization_view.organization_type = orgtype_subdomain.orgtype_id');
            if((int)$filter['subdomain'] > 0) // Передан ID субдомена
                $query->where('orgtype_subdomain.sub_id', (int)$filter['subdomain']);
            else if(trim($filter['subdomain'])) { // Передано название субдомена
                $query->join('subdomain_view',
                                'orgtype_subdomain.sub_id = subdomain_view.id')
                         ->where('subdomain_view.url', trim($filter['subdomain']));
            }
        }
        
        if( !empty($filter['typeId']) ) {
            $query->where_in('organization_type',
                             array_map(function($value){ return (int)$value; },
                                       (array)$filter['typeId']));
        }
        
        if( !empty($filter['typeSlug']) ) {
            $query->where_in('slug',
                             array_map(function($value){ return urlencode(trim($value)); },
                                       (array)$filter['typeSlug']));
        }

        if( isset($filter['lat']) )
            $query->where('latitude', (float)$filter['lat']);

        if( isset($filter['lng']) )
            $query->where('longtitude', (float)$filter['lng']);
        
        if( !empty($filter['regionId']) && (int)$filter['regionId'] >= 0 )
            $query->where('region_id', (int)$filter['regionId']);
        
        if( !empty($filter['category']) ) {
            $filter['category'] = '{'.implode(',', (array)$filter['category']).'}';
            $query->where("categories::INT[] && '{$filter['category']}'::INT[]", NULL, FALSE);
        }
        
        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $query->order_by('organization_id', $orderBy);
                    break;
                case 'title':
                    $query->order_by('organization_name', $orderBy);
                    break;
                case 'text':
                    $query->order_by('organization_description', $orderBy);
                    break;
                case 'type':
                    $query->order_by('organization_type', $orderBy);
                    break;
                case 'address':
                    $query->order_by('organization_address', $orderBy);
                    break;
                case 'regionId':
                    $query->order_by('region_id', $orderBy);
                    break;
                case 'regionName':
                    $query->order_by('region_name', $orderBy);
                    break;
                case 'dateAdded':
                    $query->order_by('date_add', $orderBy);
                    break;
                case 'category':
                case 'categories':
                    $query->order_by('categories', $orderBy);
                    break;
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }
        
        return $query;
    }
    
    /**
     * Получение количества организаций, соответствующих указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @return integer Количество организаций.
     */
    public function getCount(array $filter = array())
    {
        unset($filter['limit']); // На всякий случай, если задан лимит выборки - отменить
        return $this->_processListParams($filter)->count_all_results();
    }


    /**
     * Получение списка организаций, соответствующих указанным параметрам фильтрации.
     * 
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @param $order array Массив параметров сортировки результатов. Описание аргумента - 
     * см. в описании аргумента $order метода _processListParams() этого класса.
     * @return array Массив организаций, содержащий строки со следующими полями:
     * * id integer ID организации.
     * * title string Название организации.
     * * text string Описание организации.
     * * typeId integer ID типа организации.
     * * typeName string Название типа организации.
     * * typeSlug string Короткое название типа организации.
     * * typeIcon string Адрес файла иконки, соответствующей типу организации.
     * * dateAdded string Timestamp даты/времени добавления организации.
     * * emails string E-mail организации.
     * * phones array Массив телефонов для связи с организацией. Телефоны представлены в виде
     * строк чисел.
     * * sites string URL-ы сайтов организации.
     * * contacts string Контактные лица организации.
     * * lat float Широта точки местонахождения организации.
     * * lng float Долгота точки местонахождения организации.
     * * address string Адрес организации.
     * * regionId integer ID региона, к которому принадлежит точка местонахождения организации.
     * * regionName string Название региона, которому принадлежит точка местонахождения организации.
     * * categories array Массив категорий организации. Каждая категория имеет следующие поля:
     * * * id integer ID категории.
     * * * name string Название категории.
     * * * slug string Короткое название категории.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $organizations = $this->_processListParams($filter, $order)->get();
        if( !$organizations )
            return array();

        $res = array();
        foreach($organizations->result() as $org) {
            // Сайты организации:
            $sites = array();
            foreach(explode(',', trim($org->site, '{}')) as $site) {
                if( !empty($site) )
                    $sites[] = $site;
            }
            
            // Телефоны организации:
            $phones = array();
            foreach(explode(',', trim($org->phone, '{}')) as $phone) {
                if( !empty($phone) )
                    $phones[] = $phone;
            }
            
            // Email-ы организации:
            $emails = array();
            foreach(explode(',', trim($org->email, '{}')) as $email) {
                if( !empty($email) )
                    $emails[] = $email;
            }

            // Категории организации:
            $categories = trim($org->categories, '{}');
            if($categories)
                $categories = explode(',', $categories);
            if($categories)
            $categories = $this->db->query('SELECT category_id "id",
                                                category_name "name",
                                                category_slug "slug"
                                         FROM category_view
                                         WHERE category_id IN ('.trim($org->categories, '{}').')')
                                   ->result_array();

            $res[] = array('id' => $org->organization_id,
                           'title' => /*htmlentities(*/$org->organization_name/*, ENT_QUOTES, 'UTF-8', false)*/,
                           'text' => /*htmlentities(*/$org->organization_description/*, ENT_QUOTES, 'UTF-8', false)*/,
                           'dateAdded' => strtotime($org->date_add),
                           'typeId' => $org->organization_type,
                           'typeName' => $org->name,
                           'typeSlug' => $org->slug,
                           'typeIcon' => $org->icon,
                           'contacts' => $org->contacts,
                           'sites' => $sites,
                           'phones' => $phones,
                           'emails' => $emails,
                           'lat' => $org->latitude,
                           'lng' => $org->longtitude,
                           'address' => $org->organization_address,
                           'regionId' => $org->region_id,
                           'regionName' => $org->region_name,
                           'categories' => $categories,);
        }

        return $res;
    }

    /**
     * Получение информации для создания списка организаций. В список включается следующая информация:
     *
     * @param $limitFrom integer Номер первой записи в результирующем списке. Начинается
     * с 0. По умолчанию 0.
     * @param $limitNum integer Кол-во записей из результирующего списка, которые будут
     * возвращены. Если равно 0, то кол-во записей не ограничивается.
     * @return array массив с полями: 
     * * id int ID организации
     * * region_name string Название региона, в котором расположена организация.
     * * name string Название организации.
     * * description string Описание организации.
     */
    public function getListAll($limitFrom = 0, $limitNum = 0)
    {
        $limitFrom = (int)$limitFrom;
        $limitNum = (int)$limitNum;

        $this->db->select('organization_id id, region_name, organization_name "name", organization_description description')
                 ->from('organization_view')
                 ->order_by('region_name asc, organization_name asc');

        if($limitFrom >= 0 && $limitNum > 0)
            $this->db->limit($limitNum, $limitFrom);

        return $this->db->get()->result_array();
    }
    
    /**
     * Получение детальной информации об организации по её ID.
     *
     * @param $id int ID организации.
     * @return mixed Если организация не найдена, возвращается FALSE. Иначе, возвращается
     * массив данных об организации, содержащий следующие поля:
     * * id integer ID организации.
     * * title string Название организации.
     * * text string Описание организации.
     * * typeId integer ID типа организации.
     * * typeName string Название типа организации.
     * * typeSlug string Короткое название типа организации.
     * * typeIcon string Адрес файла иконки, соответствующей типу организации.
     * * dateAdded string Timestamp даты/времени добавления организации.
     * * emails array Массив e-mail организации.
     * * phones array Массив телефонов для связи с организацией. Телефоны представлены в виде
     * строк чисел.
     * * sites string URL сайта организации.
     * * contacts string Контактные лица организации.
     * * lat float Широта точки местонахождения организации.
     * * lng float Долгота точки местонахождения организации.
     * * address string Адрес организации.
     * * regionId integer ID региона, к которому принадлежит точка местонахождения организации.
     * * regionName string Название региона, которому принадлежит точка местонахождения организации.
     * * categories array Массив категорий организации. Каждая категория имеет следующие поля:
     * * * id integer ID категории.
     * * * name string Название категории.
     * * * slug string Короткое название категории.
     */
    public function getById($id)
    {
        $id = (int)$id;
        if(empty($id))
            return FALSE;

        $org = $this->db->from('organization_view')
                        ->where('organization_id', (int)$id)->get();
        if( !$org || !$org->num_rows() )
            return FALSE;
        $org = $org->row();
        
        // Сайты организации:
        $sites = array();
        foreach(explode(',', trim($org->site, '{}')) as $site) {
            if( !empty($site) )
                $sites[] = $site;
        }

        // Телефоны организации:
        $phones = array();
        foreach(explode(',', trim($org->phone, '{}')) as $phone) {
            if( !empty($phone) )
                $phones[] = $phone;
        }

        // Email-ы организации:
        $emails = array();
        foreach(explode(',', trim($org->email, '{}')) as $email) {
            if( !empty($email) )
                $emails[] = $email;
        }
        
        // Категории организации:
        $categories = trim($org->categories, '{}');
        if($categories)
            $categories = explode(',', $categories);
        if($categories)
            $categories = $this->db->query('SELECT category_id "id",
                                                category_name "name",
                                                category_slug "slug"
                                            FROM category_view
                                            WHERE category_id IN ('.trim($org->categories, '{}').')')
                                   ->result_array();

        return empty($org) ? FALSE :
                             array('id' => $org->organization_id,
                                   'title' => /*htmlentities(*/$org->organization_name/*, ENT_QUOTES, 'UTF-8', false)*/,
                                   'text' => /*htmlentities(*/$org->organization_description/*, ENT_QUOTES, 'UTF-8', false)*/,
                                   'dateAdded' => strtotime($org->date_add),
                                   'typeId' => $org->organization_type,
                                   'typeName' => $org->name,
                                   'typeSlug' => $org->slug,
                                   'typeIcon' => $org->icon,
                                   'contacts' => $org->contacts,
                                   'sites' => $sites,
                                   'phones' => $phones,
                                   'emails' => $emails,
                                   'lat' => $org->latitude,
                                   'lng' => $org->longtitude,
                                   'address' => $org->organization_address,
                                   'regionId' => $org->region_id,
                                   'regionName' => $org->region_name,
                                   'categories' => $categories,);
    }

    /**
     * Вставка в БД новой организации.
     *
     * @param $data array Массив данных новой организации. Возможные значения полей:
     * * name string Название организации.
     * * description string Описание организации.
     * * type integer Номер типа организации.
     * * lat float Широта точки местонахождения организации. По умолчанию не задана.
     * * lng float Долгота точки местонахождения организации. По умолчанию не задана.
     * * category mixed Категории организации. Может быть передан ID категории, массив таких
     * ID-ов или строка из ID-ов, разделённых запятыми. По умолчанию не задано.
     * * regionId integer ID региона, где располагается организация.
     * * address string Адрес организации.
     * * phones mixed Телефоны организации. Можно передать строку телефона, массив таких строк
     * или строку из номеров, разделённых запятыми.
     * * sites mixed Веб-сайты организации. Можно передать строку домена сайта, массив
     * таких строк или строку из доменов, разделённых запятыми.
     * * emails string Email-адреса организации. Можно передать строку email-а, массив
     * таких строк или строку из email-ов, разделённых запятыми.
     * * contacts string ФИО контактных лиц организации.
     * @return mixed Если вставка выполнена успешно, возвращается ID добавленной организации.
     * Иначе, возвращается false.
     */
    function add($data)
    {
        $this->_clearErrors();
        $data = $data + array('description' => '',
                              'type' => 0,
                              'lat' => 0.0,
                              'lng' => 0.0,
                              'regionId' => 0,
                              'address' => '',
                              'category' => array(),
                              'phones' => array(),
                              'sites' => array(),
                              'emails' => array(),
                              'contacts' => '',);
        // У организации обязательно должны быть название и как мин. 1 категория:
        if(empty($data['name']))
            return FALSE;

        if(is_int($data['type'])) {
            switch($data['type']) {
                case ORGANIZATION_IS_TYPE_NC: break;
                case ORGANIZATION_IS_TYPE_GOV: break;
                case ORGANIZATION_IS_TYPE_BUSINESS: break;
                default:
                    $data['type'] = ORGANIZATION_IS_TYPE_NC;
            }
        } else {
            $data['type'] = constant("ORGANIZATION_IS_TYPE_{$data['type']}");
            if($data['type'] === NULL)
                $data['type'] = ORGANIZATION_IS_TYPE_NC;
        }

        if( !is_array($data['category']) )
            $data['category'] = explode(',', $data['category']);

        if( !is_array($data['phones']) )
            $data['phones'] = array_map(trim, explode(',', $data['phones']));

        if( !is_array($data['sites']) )
            $data['sites'] = array_map(trim, explode(',', $data['sites']));

        if( !is_array($data['emails']) )
            $data['emails'] = array_map(trim, explode(',', $data['emails']));

        $res = reset($this->db->query('SELECT insert_organization(?,?,?,?,?,?,?,?,?,?)',
                                      array($data['type'], (int)$data['regionId'],
                                            $this->_cleanText($data['name']),
                                            $this->_cleanText($data['description']),
                                            $this->_cleanText($data['address']),
                                            '{'.implode(',', $data['phones']).'}',
                                            '{'.implode(',', $data['sites']).'}',
                                            '{'.implode(',', $data['emails']).'}',
                                            $this->_cleanText($data['contacts']),
                                            '{'.implode(',', $data['category']).'}',))
                              ->row_array());
        return $res ? $res : FALSE;
    }

    /**
     * Получение всех возможных типов организаций.
     *
     * @param $exclude array Массив типов, которые требуется исключить из списка.
     * @param $subdomain string Субдомен системы, для которого извлекается список типов
     * организаций. Если не передан, то возвращаются типы для текущего субдомена.
     * По умолчанию не передаётся.
     * @return array Массив типов организаций, содержащий строки со следующими полями:
     * * id integer Числовое значение типа (можно считать ID-ом типа).
     * * name string Название типа.
     */
    public function getTypesList(array $exclude = array(), $subdomain = NULL)
    {
        $subdomain = trim($subdomain);
        if(empty($subdomain))
            $subdomain = getSubdomain();
        
        $res = $this->db->select('organization_type.*')
                    ->from('orgtype_subdomain')
                    ->join('subdomain_view', 'orgtype_subdomain.sub_id = subdomain_view.id')
                    ->join('organization_type', 'organization_type.id = orgtype_subdomain.orgtype_id');
        if(empty($subdomain))
            $res->where('subdomain_view.url', '');
        else
            $res->where('subdomain_view.url', $subdomain);
        
        $orgTypesResult = array();
        foreach((array)$res->get()->result() as $orgType) {
            /** * @todo Добавить также проверку на slug типа организации, когда они будут введены */
            if(in_array($orgType->id, $exclude))
                continue;
            else
                $orgTypesResult[] = array('id' => $orgType->id,
                                          'name' => $orgType->name,);
        }

        return $orgTypesResult;
    }
    
    /**
     * Получение данных о типе организации в виде массива.
     *
     * @param $type mixed ID или короткое название (slug) типа организации.
     * @param $subdomain mixed ID или название поддомена (дочернего сайта), к которому
     * относится тип организации. По умолчанию тип извлекается без учёта привязки к поддомену.
     * @return mixed Массив данных о типе организации или FALSE, если указанный тип не найден.
     */
    public function getType($type, $subdomain = -1)
    {
        if( !$type )
            return FALSE;
        
        $this->db->from('organization_type');
        if((int)$type > 0)
            $this->db->where('organization_type.id', (int)$type);
//        else if( !empty($type) )
//            $this->db->where('slug', trim($type));
        if($subdomain != -1) {
            $this->db->join('orgtype_subdomain', 'organization_type.id = orgtype_subdomain.orgtype_id');
            if((int)$subdomain > 0) // Передан ID субдомена
                $this->db->where('orgtype_subdomain.sub_id', (int)$subdomain);
            else if(trim($subdomain)) { // Передано название субдомена
                $this->db->join('subdomain_view', 'orgtype_subdomain.sub_id = subdomain_view.id')
                         ->where('subdomain_view.url', trim($subdomain));
            }
        }
        
        $type = $this->db->get();
                
        return $type && $type->num_rows() > 0 ? $type->row_array() : FALSE;
    }
    
    /**
     * Проверка, привязан ли тип организации к указанному поддомену (дочернему сайту).
     *
     * @param $type mixed ID или короткое название (slug) типа организации.
     * @param $subdomain mixed ID или название поддомена, к которому относится тип организации.
     * @return boolean Возвращается TRUE, если тип имеет привязку к поддомену, иначе FALSE.
     */
    public function isTypeInSubdomain($type, $subdomain)
    {
        if( !$type )
            return FALSE;

        $this->db->from('orgtype_subdomain');
        if((int)$type > 0)
            $this->db->where('orgtype_subdomain.orgtype_id', (int)$type);
//        else {
//            $this->db->join('organization_type', 'orgtype_subdomain.orgtype_id = organization_type.id')
//                     ->where('organization_type.slug', trim($type));
//        }
        
        if((int)$subdomain > 0)
            $this->db->where('orgtype_subdomain.sub_id', (int)$subdomain);
        else {
            $this->db->join('subdomain_view', 'orgtype_subdomain.sub_id = subdomain_view.id')
                     ->where('subdomain_view.url', trim($subdomain));
        }
        
        $res = $this->db->get();
        
        return $res && $res->num_rows() > 0;
    }

    /**
     * Получение списка организаций, релевантных сообщению. Релевантность определяется по
     * категориям и региону.
     *
     * @param $messageId integer ID сообщения, для которого ищутся организации.
     * @param $orgTypes mixed Номера типов организаций, которые будут участвовать в поиске.
     * Может быть передан Номер типа организации, массив таких
     * номеров или строка из номеров, разделённых запятыми. По умолчанию, поиск производится
     * среди всех типов организаций.
     * @param $limit integer Максимальное кол-во возвращаемых организаций. По умолчанию,
     * кол-во не ограничено.
     * @return array Массив строк с полями организации.
     */
    public function getRelevantByMessage($messageId, $orgTypes = array(), $limit = FALSE)
    {
        $messageId = (int)$messageId;
        if(empty($messageId))
            return FALSE;

        $limit = (int)$limit;

        $res = array();
        $org = $this->db->query('SELECT find_organizations(?) id'.($limit > 0 ? " LIMIT $limit" : ''),
                                array($messageId));
        if($org && $org->num_rows()) {
            foreach($org->result() as $orgId) {
                $res[] = $this->getById($orgId->id);
            }
        }
        
        return $res;
    }
}
