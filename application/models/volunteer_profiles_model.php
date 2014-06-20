<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Volunteer_Profiles_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/volunteer_profiles_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.6
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с сообщениями на сайте.
 */
class Volunteer_Profiles_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
    const ERROR_INSERTING_LOCATION = 1;
    const ERROR_INSERTING_PROFILE_NO_USER = 2;
    const ERROR_INSERTING_PROFILE_NO_LOCATION = 3;
    const ERROR_INSERTING_PROFILE_UNKNOWN_REGION = 4;
    const ERROR_INSERTING_PROFILE = 5;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_INSERTING_LOCATION =>
                                       $this->lang->line('modelVp_errorLocationInsertFailed'),
                                   self::ERROR_INSERTING_PROFILE_NO_USER =>
                                       $this->lang->line('modelVp_errorUserUndefined'),
                                   self::ERROR_INSERTING_PROFILE_NO_LOCATION =>
                                       $this->lang->line('modelVp_errorLocationUndefined'),
                                   self::ERROR_INSERTING_PROFILE_UNKNOWN_REGION =>
                                       $this->lang->line('modelVp_errorRegionUndefined'),
                                   self::ERROR_INSERTING_PROFILE =>
                                       $this->lang->line('modelVp_errorVpInsertFailed'),);
    }

    /**
     * Служебный метод для предобработки параметров запроса на получение списка профилей волонтёрства.
     * 
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id mixed ID профиля.
     * * title string Название профиля.
     * * isActive boolean Активность профиля на данный момент.
     * * isAllCategories boolean Под профиль подпадают все категории.
     * * lat float Широта точки профиля.
     * * lng float Долгота точки профиля.
     * * regionId integer ID региона, которому принадлежит точка профиля.
     * * category mixed ID категории профиля или массив ID категорий.
     * * userId integer ID пользователя, являющегося автором профиля.
     * * limit mixed Кол-во строк в ответе. Можно передать целое положительное число, либо
     * строку вида 'начальный_индекс,кол-во_эл-тов', либо массив вида
     * array(начальный_индекс, кол-во_эл-тов). По умолчанию не ограничено.
     * @param $order array Массив параметров сортировки результатов. Ключи массива -
     * названия полей сообщения, значения - порядок сортировки по соотв. полю (возможные
     * значения: asc - по возрастанию, desc - по убыванию). По умолчанию результаты
     * никак не сортируются.
     * @return object Объект Active Record, настроенный на выполнение запроса на получение
     * списка сообщений с переданными параметрами. Объект готов к выполнению запроса на
     * извлечение или подсчёт строк.
     */
    protected function _processListParams(array $filter = array(), array $order = array())
    {
        // Параметры фильтрации:
        $filter = $filter + array('id' => 0,
                                  'address' => '',
                                  'category' => array(),);
        // Сортировка результатов по умолчанию:
//        if( !$order )
//            $order = array('id' => 'asc');

        $query = $this->db->from('volunteer_profile_view');

        if((int)$filter['id'] > 0)
            $query->where('vp_id', (int)$filter['id']);

        if(isset($filter['title']))
            $query->where('title', trim($filter['title']));

        if( isset($filter['isActive']) )
            $query->where('vp_active', (int)$filter['isActive']);

        if( isset($filter['lat']) )
            $query->where('latitude', (float)$filter['lat']);

        if( isset($filter['lng']) )
            $query->where('longtitude', (float)$filter['lng']);

//        if( !empty($filter['address']) ) {
//            $search = $query->query("SELECT message_id id
//                                     FROM rynda.search_messages_by_location_name('".
//                                     trim($filter['address'])."')");
//            $messagesMatched = array();
//            foreach($search->result() as $message) {
//                $messagesMatched[] = $message->id;
//            }
//            if( empty($messagesMatched) )
//                $messagesMatched[] = 0;
//
//            $query->where_in('message_id', $messagesMatched);
//        }

        if( !empty($filter['regionId']) && (int)$filter['regionId'] >= 0 )
            $query->where('region_id', (int)$filter['regionId']);

        if( !empty($filter['category']) ) {
            $filter['category'] = '{'.implode(',', (array)$filter['category']).'}';
            $query->where("categories::INT[] && '{$filter['category']}'::INT[]", NULL, FALSE);
        }

        if( !empty($filter['userId']) )
            $query->where('user_id', (int)$filter['userId']);

        if( !empty($filter['limit']) )
            $this->_addLimit($filter['limit']);

        // Параметры сортировки результата:
        foreach($order as $field => $orderBy) {
            $orderBy = trim($orderBy) == 'desc' ? 'desc' : 'asc';
            switch($field) {
                case 'id':
                    $query->order_by('vp_id', $orderBy);
                    break;
                case 'isActive':
                    $query->order_by('vp_active', $orderBy);
                    break;
                case 'isAllCategories':
                    $query->order_by('vp_allcats', $orderBy);
                    break;
                case 'regionId':
                    $query->order_by('region_id', $orderBy);
                    break;
//                case 'regionName':
//                    $query->order_by('region_name',
//                                        trim($order['regionName']) == 'desc' ? 'desc' : 'asc');
                    break;
                case 'category':
                case 'categories':
                    $query->order_by('categories', $orderBy);
                    break;
                /**
                 * @todo Если потом понадобится, добавить возможность сортировки по тэгам.
                 */
//                case '':
//                    break;
                default:
                    // Неизвестное поле для сортировки
            }
        }

        return $query;
    }
    
    /**
     * Получение количества профилей, соответствующих указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @return integer Количество профилей.
     */
    public function getCount(array $filter = array())
    {
        unset($filter['limit']); // На всякий случай, если задан лимит выборки - отменить
        return $this->_processListParams($filter)->count_all_results();
    }

    /**
     * Получение списка профилей, соотв. указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @param $order array Массив параметров сортировки результатов. Описание аргумента - 
     * см. в описании аргумента $order метода _processListParams() этого класса.
     * @return array Массив профилей, содержащий строки со следующими полями:
     * * id integer ID профиля.
     * * title string Название профиля.
     * * isActive boolean Активность профиля на данный момент.
     * * userId integer ID пользователя-автора профиля.
     * * firstName string Имя пользователя-автора.
     * * lastName string Фамилия пользователя-автора.
     * * mailoutEmail string E-mail, который используется для рассылки уведомлений по профилю.
     * * locationId integer ID локации профиля.
     * * lat float Широта точки профиля.
     * * lng float Долгота точки профиля.
     * * address string Адрес точки, к которому относится профиль.
     * * regionId integer ID региона, которому принадлежит точка профиля.
     * * regionName string Название региона, которому принадлежит точка профиля.
     * * categories array Массив категорий профиля. Каждая категория имеет следующие поля:
     * * * id integer ID категории.
     * * * name string Название категории.
     * * * slug string Короткое название категории.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $profiles = $this->_processListParams($filter, $order)->get();
        if( !$profiles )
            return array();

        $res = array();
        foreach($profiles->result() as $profile) {
            // Категории профиля:
            $cats = array();
            foreach(explode(',', trim($profile->categories, '{}')) as $category) {
                if(empty($category))
                    continue;
                $category = $this->db->select('category_id "id",
                                               category_name "name",
                                               category_slug "slug"')
                                     ->from('category_view')
                                     ->where('category_id', $category)->get();
                if($category && $category->num_rows())
                    $cats[] = $category->row_array();
            }

            $res[] = array('id' => $profile->vp_id,
                           'title' => $profile->title,
                           'isActive' => $profile->vp_active,
                           'isAllCategories' => $profile->vp_allcats,
                           'userId' => (int)$profile->user_id,
                           'distance' => (int)$profile->distance_normal,
                           'distanceCrysis' => (int)$profile->distance_crysis,
                           'days' => explode(',', trim($profile->days_normal, '{}')),
                           'daysCrysis' => explode(',', trim($profile->days_crysis, '{}')),
                           'mailoutEmail' => $profile->vp_email,
                           'locationId' => $profile->location_id,
                           'lat' => $profile->latitude,
                           'lng' => $profile->longtitude,
                           'address' => $profile->location_name,
                           'regionId' => $profile->region_id,
                           'regionName' => $profile->region_name,
                           'categories' => $cats,);
        }

        return $res;
    }

    /**
     * Получение данных профиля по его ID.
     *
     * @param $id integer ID профиля.
     * @return mixed Если профиль не найден, возвращается FALSE. Иначе будет возвращён
     * массив полей профиля, содержащий следующие поля:
     * * id integer ID профиля.
     * * title string Название профиля.
     * * isActive boolean Активность профиля на данный момент.
     * * isAllCategories boolean Под профиль подпадают все категории.
     * * userId integer ID пользователя-автора профиля.
     * * mailoutEmail string E-mail для рассылки уведомлений по профилю.
     * * locationId integer ID локации профиля.
     * * lat float Широта точки профиля.
     * * lng float Долгота точки профиля.
     * * address string Адрес точки, к которому относится профиль.
     * * regionId integer ID региона, которому принадлежит точка профиля.
     * * regionName string Название региона, которому принадлежит точка профиля.
     * * categories array Массив категорий профиля. Каждая категория имеет следующие поля:
     * * * id integer ID категории.
     * * * name string Название категории.
     * * * slug string Короткое название категории.
     */
    public function getById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;
        else {
            $res = $this->db->get_where('volunteer_profile_view', array('vp_id' => $id), 1)->row();
            if(empty($res))
                return FALSE;

            // Категории профиля:
            $resCats = $this->db->select('volunteer_profile_category.category_id "id",
                                          category_name "name",
                                          category_slug "slug"')
                            ->from('volunteer_profile_category')
                            ->join('category_view',
                                   'volunteer_profile_category.category_id = category_view.category_id')
                            ->where('volunteer_profile_category.vp_id', $res->vp_id)->get();
            $cats = $resCats && $resCats->num_rows() ? $resCats->result_array() : array();

            return array('id' => $res->vp_id,
                         'title' => $res->title,
                         'isActive' => $res->vp_active,
                         'isAllCategories' => $res->vp_allcats,
                         'userId' => (int)$res->user_id,
                         'distance' => (int)$res->distance_normal,
                         'distanceCrysis' => (int)$res->distance_crysis,
                         'days' => explode(',', trim($res->days_normal, '{}')),
                         'daysCrysis' => explode(',', trim($res->days_crysis, '{}')),
                         'mailoutEmail' => $res->vp_email,
                         'locationId' => $res->location_id,
                         'lat' => $res->latitude,
                         'lng' => $res->longtitude,
                         'address' => $res->location_name,
                         'regionId' => $res->region_id,
                         'regionName' => $res->region_name,
                         'categories' => $cats,);
        }
    }

    /**
     * Вставка в БД нового профиля.
     *
     * @param $data array Массив данных нового сообщения. Возможные значения полей:
     * * title string Название профиля. По умолчанию не задано.
     * * category mixed Категории профиля. Может быть передан ID категории или массив
     * ID. По умолчанию не задано.
     * * regionId integer ID региона, которому принадлежит точка профиля. По умолчанию
     * не задан.
     * * address string Адрес точки, к которому относится профиль.
     * * lat float Широта точки профиля.
     * * lng float Долгота точки профиля.
     * * userId integer ID пользователя-автора профиля.
     * * isActive boolean TRUE, если сообщение активно, иначе FALSE. По умолчанию TRUE.
     * * days array Массив, описывающий дни, когда отправитель готов помогать (целые числа
     * 0-7, где 0 означает "согласовывать с пользователем в любом случае").
     * * daysCrysis array Массив, описывающий дни, когда отправитель готов помогать (целые
     * числа 0-7, где 0 означает "согласовывать с пользователем в любом случае").
     * * distance integer Расстояние в метрах, на котором "в мирное время" могут
     * располагаться случаи, подходящие к профилю.
     * * distanceCrysis integer Расстояние в метрах, на котором во время кризиса могут
     * располагаться случаи, подходящие к профилю.
     * * mailoutEmail string E-mail, на который будут высылаться уведомления по профилю.
     * @return mixed ID нового профиля при успешной вставке, иначе FALSE.
     */
    public function add(array $data)
    {
        $this->_clearErrors();
        $data = $data + array('title' => '',
                              'category' => array(),
                              'lat' => 0.0,
                              'lng' => 0.0,
                              'regionId' => 0,
                              'address' => '',
                              'userId' => 0,
                              'isActive' => TRUE,);

        $data['userId'] = (int)$data['userId'];
        if( !$data['userId'] ) {
            $this->_addError(self::ERROR_INSERTING_PROFILE_NO_USER);
            return FALSE;
        }

        // Предобработка данных по территориальным привязкам сообщения (координатам,
        // адресу и региону):
        $data['regionId'] = (int)$data['regionId'];
        $data['lat'] = (float)$data['lat'];
        $data['lng'] = (float)$data['lng'];

        $this->db->trans_start();

        if($data['lat'] == 0.0 && $data['lng'] == 0.0) { // Координаты профиля не выбраны
            if( !$data['regionId'] ) {
                $this->_addError(self::ERROR_INSERTING_PROFILE_NO_LOCATION);
                return FALSE;
            }

            // Если у профиля задан регион, использовать координаты этого региона.
            $coords = $this->db->select('latitude, longtitude')
                               ->from('regions_view')
                               ->where('region_id', $data['regionId'])->get();
            if( !$coords->num_rows() ) {
                $this->_addError(self::ERROR_INSERTING_PROFILE_UNKNOWN_REGION);
                return FALSE;
            } else
                $coords = $coords->row();
            $data['lat'] = (float)$coords->latitude;
            $data['lng'] = (float)$coords->longtitude;
        }

        $this->db->insert('Location', array('latitude' => $data['lat'],
                                            'longtitude' => $data['lng'],
                                            'region_id' => $data['regionId'],
                                            'name' => $this->_cleanText($data['address']),));
        if($this->db->affected_rows() <= 0) {
            $this->_addError(self::ERROR_INSERTING_LOCATION);
        }

        // Флаги профиля:
        $flags = (empty($data['isActive']) ? 0x0 : PROFILE_IS_ACTIVE)
               | (empty($data['isAllCategories']) ? 0x0 : PROFILE_IS_ALL_CATEGORIES);

        $this->db->insert('volunteer_profile',
                          array('title' => trim($data['title']),
                                'user_id' => $data['userId'],
                                'location_id' => $this->db->insert_id(),
                                'flags' => $flags,
                                'distance_normal' => (int)$data['distance'],
                                'distance_crysis' => (int)$data['distance'], //(int)$data['distanceEmergency'],
                                'days_normal' => '{'.implode(',', (array)$data['days']).'}',
                                'days_crysis' => '{'.implode(',', (array)$data['days']).'}', //'{'.implode(',', (array)$data['daysCrysis']).'}',
                                'email' => trim($data['mailoutEmail']),));
        $profileId = $this->db->insert_id();
        
        if($this->db->affected_rows() <= 0) {
            $this->_addError(self::ERROR_INSERTING_PROFILE);
        }

        foreach((array)$data['category'] as $category) {
            if( !$category )
                continue;
            $this->db->insert('volunteer_profile_category', array('category_id' => (int)$category,
                                                                  'vp_id' => $profileId));
        }

        $this->db->trans_complete();

        return $this->db->trans_status() ? $profileId : FALSE;
    }
    
    /**
     * Изменение параметров профиля в БД.
     *
     * @param $vpId integer ID редактируемого профиля.
     * @param $data array Массив новых параметров профиля. Возможные значения:
     * * title string Название профиля.
     * * distance integer Расстояние в км, на котором пользователь способен оказать помощь.
     * * days array Массив с описанием дней недели, в которые пользователь способен
     * оказывать помощь. Возможные значения: целые числа 1-7 (дни с пн по вс) и число 0
     * (означает, что оказание помощи в любом случае необходимо согласовывать с пользователем).
     * * mailoutEmail string Электронная почта, на которую необходимо высылать уведомления
     * о сообщениях, соответствующих профилю.
     * @return boolean TRUE при успехе обновления профиля, иначе FALSE.
     */
    public function update($vpId, $data = array())
    {
        $vpId = (int)$vpId;
        if($vpId <= 0)
            return FALSE;

        $fields = array();
        if( !empty($data['title']) )
            $fields['title'] = trim($data['title']);
        if( !empty($data['distance']) && (int)$data['distance'] > 0 )
            $fields['distance_normal'] = (int)$data['distance'];
        if( !empty($data['days']) )
            $fields['days_normal'] = '{'.implode(',', (array)$data['days']).'}';
        if( isset($data['mailoutEmail']) )
            $fields['email'] = trim($data['mailoutEmail']);

        return $this->db->update('volunteer_profile', $fields, array('id' => $vpId));
    }

    /**
     * Удаление профиля волонтёрства пользователя.
     *
     * @param $vpId integer ID удаляемого профиля.
     * @return boolean TRUE при успехе операции, иначе FALSE.
     */
    public function delete($vpId)
    {
        $vpId = (int)$vpId;
        if($vpId <= 0)
            return FALSE;

        return $this->db->delete('volunteer_profile', array('id' => $vpId));
    }
    
    /**
     * Получение списка профилей, для которых необходимо выполнить рассылку почтовых
     * уведомлений.
     * @param string $mailoutType Тип рассылки. Возможные значения: 'email'.
     * @return array Массив профилей волонтёрства, для которых должна выполняться рассылка.
     * Элементы массива содержат поля:
     * * id integer ID профиля.
     * * title string Название профиля.
     * * email string Email для рассылки, указанный в профиле.
     * * userId integer ID пользователя, создавшего профиль.
     * * locationId integer ID локации, указанной в профиле. Нужен для вычисления
     * расстояния между геоточками профиля и каждого из сообщений, относящихся к нему.
     */
    function getListForMailout($mailoutType)
    {
        switch($mailoutType) {
            case 'email':
            case 'sms':
                break;
            default:
                return; /** * @todo Бросать исключение "неизвестное значение аргумента". */
        }
        $vps = array();
        $query = $this->db->select('vp_id id, title, user_id, vp_email email, location_id')
                          ->from('volunteer_profile_view')
                          ->where('vp_email !=', '')
                          ->where('vp_active', 1)
                          ->order_by('email asc')
                          ->get();
        if( !$query->num_rows() )
            return $vps = array();
        foreach($query->result() as $vp) {
            $vps[] = array('id' => $vp->id,
                           'title' => $vp->title,
                           'email' => $vp->email,
                           'userId' => $vp->user_id,
                           'locationId' => $vp->location_id,);
        }

        return $vps;      
    }

    /**
     * Получение списка сообщений типа "просьба о помощи", релевантных указанному профилю.
     * Релевантность определяется по категориям и расстоянию.
     *
     * @param $vpId integer ID профиля, для которого выполняется поиск.
     * @param $limit integer Максимальное кол-во возвращаемых сообщений. По умолчанию,
     * кол-во не ограничено.
     * @return array Массив элементов с полями представления сообщений (message_view) в БД.
     */
    public function getRelevantMessages($vpId, $limit = FALSE)
    {
        $vpId = (int)$vpId;
        if($vpId <= 0) /** * @todo Бросать исключение "некорректное значение аргумента". */
            return FALSE;
        $limit = (int)$limit;

        $params = $limit ? array($vpId, $limit) : array($vpId);
        $query = $this->db->query('SELECT * FROM "message_view" WHERE message_id IN
                                   (SELECT * FROM rynda.search_relevant_messages(?))
                                   ORDER BY "message_date" DESC'.($limit ? ' LIMIT ?' : ''),
                                  $params);
        if( !$query->num_rows() )
            return array();

        $res = array();
        foreach($query->result_array() as $message) {
            $res[] = $message;
        }

        return $res;         
    }
    
    /**
     * Get VP author' user ID.
     *
     * @param $vpId integer Volunteer profile ID.
     * @return integer User ID of volunteer profile author.
     */
    public function getProfileUserId($vpId)
    {
         $vpId = (int)$vpId;
        if($vpId <= 0) /** * @todo Throw "wrong argument" exc. */
            return FALSE;
        $res = $this->db->select('user_id')
                        ->from('volunteer_profile_view')
                        ->where('vp_id', $vpId)
                        ->get();
        return $res && $res->num_rows() ? $res->row()->user_id : FALSE;
    }
}