<?php if( !defined('BASEPATH') ) exit('No direct script access allowed');
/**
 * Файл содержит определение класса Messages_Model (модель данных)
 *
 * @copyright  Copyright (c) 2011 Звягинцев Л. aka Ahaenor
 * @link       /application/models/messages_model.php
 * @version    0.1
 * @since      Файл доступен начиная с версии проекта 0.1
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/
 */

/**
 * Модель для работы с сообщениями на сайте.
 */
class Messages_Model extends Rynda_Model
{
    /**
     * Для удобства обращения с кодами ошибок модели можно определить их как
     * константы класса модели:
     */
    const ERROR_INSERTING_LOCATION = 1;
    const ERROR_INSERTING_MESSAGE = 2;
    const ERROR_INSERTING_MESSAGE_FIELDS_MISSING = 3;
    const ERROR_INSERTING_MESSAGE_UNKNOWN_REGION = 4;
    const ERROR_INSERTING_MESSAGE_UNKNOWN_SUBDOMAIN = 5;

    public function __construct()
    {
        parent::__construct();
        self::$_errorTypes = array(self::ERROR_INSERTING_LOCATION =>
                                       $this->lang->line('modelMessages_errorLocationInsertFailed'),
                                   self::ERROR_INSERTING_MESSAGE =>
                                       $this->lang->line('modelMessages_errorMessageInsertFailed'),
                                   self::ERROR_INSERTING_MESSAGE_FIELDS_MISSING =>
                                       $this->lang->line('modelMessages_errorMessageFieldsMissing'),
                                   self::ERROR_INSERTING_MESSAGE_UNKNOWN_REGION =>
                                       $this->lang->line('modelMessages_errorMessageUnknownRegion'),
                                   self::ERROR_INSERTING_MESSAGE_UNKNOWN_SUBDOMAIN =>
                                       $this->lang->line('modelMessages_errorMessageUnknownSubdomain'),);
    }
    
    /**
     * Служебный метод для предобработки параметров запроса на получение списка сообщений.
     * 
     * @param $filter array Массив параметров фильтрации. Возможные значения полей:
     * * id mixed ID сообщения.
     * * title string Заголовок сообщения.
     * * text string Текст сообщения.
     * * full string Строка для полнотекстового поиска по заголовку, тексту и адресу сообщения.
     * * dateAddedFrom mixed Нижняя граница даты/времени добавления сообщения. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateAddedTo mixed Верхняя граница даты/времени добавления сообщения. Если передано
     * целое число, оно воспринимается как timestamp. Если передана строка, она воспринимается
     * как аргумент PHP-функции strtotime().
     * * dateModifiedFrom mixed Нижняя граница даты/времени обновления сообщения. Если
     * передано целое число, оно воспринимается как timestamp. Если передана строка, она
     * воспринимается как аргумент PHP-функции strtotime().
     * * dateModifiedTo mixed Верхняя граница даты/времени обновления сообщения. Если
     * передано целое число, оно воспринимается как timestamp. Если передана строка, она
     * воспринимается как аргумент PHP-функции strtotime().
     * * subdomain mixed ID или название субдомена (дочерней карты), к которому относится
     * сообщение. Если передано FALSE, 0, NULL или пустая строка, будут извлечены сообщения,
     * привязанные к главной карте.
     * * isActive boolean Активность сообщения на данный момент.
     * * isUntimed boolean Срочность сообщения (TRUE - несрочно, FALSE - срочно).
     * * isPublic boolean Анонимность автора сообщения (TRUE - данные отправителя открыты,
     * FALSE - скрыты).
     * * isNotify boolean Высылать ли автору сообщения уведомления об ответных сообщениях.
     * * typeId mixed ID типа сообщения. Можно передать один из ID типов сообщений
     * или массив из нескольких таких ID.
     * * typeSlug mixed Короткое название (slug) типа сообщения. Можно передать один из
     * slug типов сообщений или массив из нескольких таких slug.
     * * lat float Широта точки сообщения.
     * * lng float Долгота точки сообщения.
     * * address string Адрес точки, к которому относится сообщение.
     * * regionId integer ID региона, которому принадлежит точка сообщения.
     * * category mixed ID категории сообщения или массив ID категорий.
     * * statusId mixed Номер текущего статуса сообщения или массив номеров статусов.
     * * userId integer ID пользователя, являющегося автором сообщения.
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
                                  'title' => '',
                                  'text' => '',
                                  'typeId' => array(),
                                  'address' => '',
                                  'category' => array(),);

        $query = $this->db->from('message_view');

        if((int)$filter['id'] > 0)
            $query->where('message_id', (int)$filter['id']);

        if( !empty($filter['title']) )
            $query->like('message_title', trim($filter['title']));

        if( !empty($filter['text']) )
            $query->like('message_text', trim($filter['text']));

        if( !empty($filter['dateAddedFrom']) ) {
            if((int)$filter['dateAddedFrom'] === $filter['dateAddedFrom']) // Передан timestamp
                $query->where('message_date >= ', date('Y-m-d', $filter['dateAddedFrom']));
            else // Передана строка - аргумент для strtotime()
                $query->where('message_date >= ', date('Y-m-d', strtotime($filter['dateAddedFrom'])));
        }

        if( !empty($filter['dateAddedTo']) ) {
            if((int)$filter['dateAddedTo'] === $filter['dateAddedTo']) // Передан timestamp
                $query->where('message_date <= ', date('Y-m-d', $filter['dateAddedTo']));
            else // Передана строка - аргумент для strtotime()
                $query->where('message_date <= ', date('Y-m-d', strtotime($filter['dateAddedTo'])));
        }
 
        if( !empty($filter['dateModifiedFrom']) ) {
            if((int)$filter['dateModifiedFrom'] === $filter['dateModifiedFrom']) // Передан timestamp
                $query->where('date_modify >= ', date('Y-m-d', $filter['dateModifiedFrom']));
            else // Передана строка - аргумент для strtotime()
                $query->where('date_modify >= ', date('Y-m-d', strtotime($filter['dateModifiedFrom'])));
        }

        if( !empty($filter['dateModifiedTo']) ) {
            if((int)$filter['dateModifiedTo'] === $filter['dateModifiedTo']) // Передан timestamp
                $query->where('date_modify <= ', date('Y-m-d', $filter['dateModifiedTo']));
            else // Передана строка - аргумент для strtotime()
                $query->where('date_modify <= ', date('Y-m-d', strtotime($filter['dateModifiedTo'])));
        }

        if(isset($filter['subdomain'])) {
            if((int)$filter['subdomain'] > 0) // Передан ID субдомена
                $query->where('subdomain_id', (int)$filter['subdomain']);
            else if(trim($filter['subdomain'])) // Передано название субдомена
                $query->where('subdomain', trim($filter['subdomain']));
            // ТЕСТ: Если передан 0 или пустая строка, происходит выборка сообщений со всех доменов системы
        }

        if( isset($filter['isActive']) )
            $query->where('is_active', (int)$filter['isActive']);

        if( isset($filter['isUntimed']) )
            $query->where('is_untimed', (int)$filter['isUntimed']);

        if( isset($filter['isPublic']) )
            $query->where('is_public', (int)$filter['isPublic']);

        if( isset($filter['isNotify']) )
            $query->where('is_notify', (int)$filter['isNotify']);

        if( !empty($filter['typeId']) ) {
            $query->where_in('message_type',
                             array_map(function($value){ return (int)$value; },
                                       (array)$filter['typeId']));
        }
        
        if( !empty($filter['typeSlug']) ) {
            $query->where_in('message_typeslug',
                             array_map(function($value){ return urlencode(trim($value)); },
                                       (array)$filter['typeSlug']));
        }

        if( isset($filter['lat']) )
            $query->where('latitude', (float)$filter['lat']);

        if( isset($filter['lng']) )
            $query->where('longtitude', (float)$filter['lng']);

        if( !empty($filter['address']) ) {
            $search = $query->query("SELECT message_id id
                                     FROM rynda.search_messages_by_location_name('".
                                     trim($filter['address'])."')");
            $messagesMatched = array();
            foreach($search->result() as $message) {
                $messagesMatched[] = $message->id;
            }
            if( empty($messagesMatched) )
                $messagesMatched[] = 0;

            $query->where_in('message_id', $messagesMatched);
        }

        if( !empty($filter['full']) ) {
            $filter['full'] = str_replace(' ', ' & ', trim($filter['full']));
            $search = $query->query("SELECT message_id id
                                               FROM rynda.search_messages_with('".$filter['full']."')
                                        UNION
                                        SELECT message_id id
                                               FROM rynda.search_titles_with('".$filter['full']."')
                                        UNION
                                        SELECT message_id id
                                               FROM rynda.search_messages_by_location_name('".$filter['full']."')");
            $messagesMatched = array();
            foreach($search->result() as $message) {
                $messagesMatched[] = $message->id;
            }
            if( empty($messagesMatched) )
                $messagesMatched[] = 0;

            $query->where_in('message_id', $messagesMatched);
        }

        if( !empty($filter['regionId']) && (int)$filter['regionId'] >= 0 )
            $query->where('region_id', (int)$filter['regionId']);

        if( !empty($filter['statusId']) )
            $query->where_in('message_status',
                             array_map(function($value){ return (int)$value; },
                                       (array)$filter['statusId']));

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
                    $query->order_by('message_id', $orderBy);
                    break;
                case 'title':
                    $query->order_by('message_title', $orderBy);
                    break;
                case 'text':
                    $query->order_by('message_text', $orderBy);
                    break;
                case 'isActive':
                    $query->order_by('is_active', $orderBy);
                    break;
                case 'isUntimed':
                    $query->order_by('is_untimed', $orderBy);
                    break;
                case 'isPublic':
                    $query->order_by('is_public', $orderBy);
                    break;
                case 'isNotify':
                    $query->order_by('is_notify', $orderBy);
                    break;
                case 'type':
                    $query->order_by('message_type', $orderBy);
                    break;
//                case 'address':
//                    $query->order_by('location_name',
//                                        trim($order['address']) == 'desc' ? 'desc' : 'asc');
                    break;
                case 'regionId':
                    $query->order_by('region_id', $orderBy);
                    break;
//                case 'regionName':
//                    $query->order_by('region_name',
//                                        trim($order['regionName']) == 'desc' ? 'desc' : 'asc');
                    break;
                case 'dateAdded':
                    $query->order_by('message_date', $orderBy);
                    break;
                case 'dateModified':
                    $query->order_by('date_modify', $orderBy);
                    break;
                case 'statusId':
                    $query->order_by('message_status', $orderBy);
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
     * Получение количества сообщений, соответствующих указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @return integer Количество сообщений.
     */
    public function getCount(array $filter = array())
    {
        unset($filter['limit']); // На всякий случай, если задан лимит выборки - отменить
        return $this->_processListParams($filter)->count_all_results();
    }

    /**
     * Получение списка сообщений, соотв. указанным параметрам фильтрации.
     *
     * @param $filter array Массив параметров фильтрации. Описание аргумента - 
     * см. в описании аргумента $filter метода _processListParams() этого класса.
     * @param $order array Массив параметров сортировки результатов. Описание аргумента - 
     * см. в описании аргумента $order метода _processListParams() этого класса.
     * @return array Массив сообщений, содержащий строки со следующими полями:
     * * id integer ID сообщения.
     * * title string Заголовок сообщения.
     * * text string Текст сообщения.
     * * dateAdded string Timestamp даты/времени добавления сообщения.
     * * dateModified string Timestamp даты/времени обновления сообщения.
     * * subdomainId integer ID субдомена, к которому относится сообщение.
     * * subdomainName string Название субдомена, к которому относится сообщение.
     * * isActive boolean Активность сообщения на данный момент.
     * * userId integer ID пользователя, создавшего сообщение. Если оно было создано гостем,
     * поле имеет значение 0.
     * * firstName string Имя пользователя, создавшего сообщение.
     * * patrName string Отчество пользователя, создавшего сообщение.
     * * lastName string Фамилия пользователя, создавшего сообщение.
     * * phones array Массив телефонов отправителя сообщения. Телефоны представлены в виде
     * строк чисел.
     * * email string E-mail отправителя.
     * * locationId integer ID локации сообщения.
     * * lat float Широта точки сообщения.
     * * lng float Долгота точки сообщения.
     * * address string Адрес точки, к которому относится сообщение.
     * * regionId integer ID региона, которому принадлежит точка сообщения.
     * * regionName string Название региона, которому принадлежит точка сообщения.
     * * categories array Массив категорий сообщения. Каждая категория имеет следующие поля:
     * * * id integer ID категории.
     * * * name string Название категории.
     * * * slug string Короткое название категории.
     * * statusId integer ID текущего статуса сообщения.
     * * status string Название текущего статуса сообщения.
     */
    public function getList(array $filter = array(), array $order = array())
    {
        $messages = $this->_processListParams($filter, $order)->get();
        if( !$messages )
            return array();

        $res = array();
        foreach($messages->result() as $message) {
            // Телефоны отправителя сообщения:
            $phones = array();
            foreach(explode(',', trim($message->sender_phone, '{}')) as $phone) {
                if( !empty($phone) )
                    $phones[] = $phone;
            }

            // Категории сообщения:
            $cats = array();
            foreach(explode(',', trim($message->categories, '{}')) as $category) {
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

            $res[] = array('id' => $message->message_id,
                           'title' => /*htmlentities(*/$message->message_title/*, ENT_QUOTES, 'UTF-8', FALSE)*/,
                           'text' => /*htmlentities(*/$message->message_text/*, ENT_QUOTES, 'UTF-8', FALSE)*/,
                           'dateAdded' => strtotime($message->message_date),
                           'isActive' => $message->is_active,
                           'isUntimed' => $message->is_untimed,
                           'isPublic' => $message->is_public,
                           'isNotify' => $message->is_notify,
        //                   'is' => $message->, // Другие флаги сообщения...
                           'subdomainId' => $message->subdomain_id,
                           'subdomainName' => $message->subdomain,
                           'typeId' => $message->message_type,
                           'typeSlug' => $message->message_typeslug,
                           'typeName' => $message->message_typename,
                           'statusId' => $message->message_status,
                           'userId' => (int)$message->user_id,
                           'firstName' => $message->sender_first_name,
                           'patrName' => $message->sender_patr_name,
                           'lastName' => $message->sender_last_name,
                           'phones' => $phones,
                           'email' => $message->sender_email,
                           'locationId' => $message->location_id,
                           'lat' => $message->latitude,
                           'lng' => $message->longtitude,
                           'address' => $message->location_name,
                           'regionId' => $message->region_id,
                           'regionName' => $message->region_name,
                           'categories' => $cats,);
        }

        return $res;
    }

    /**
     * Получение данных сообщения по его ID-у.
     *
     * @param $id integer ID сообщения.
     * @return mixed Если сообщение не найдено, возвращается FALSE. Иначе будет возвращён
     * массив полей сообщения, содержащий следующие поля:
     * * id integer ID сообщения.
     * * title string Заголовок сообщения.
     * * text string Текст сообщения.
     * * dateAdded string Timestamp даты/времени добавления сообщения.
     * * subdomainId integer ID субдомена, к которому относится категория.
     * * subdomainName string Название субдомена, к которому относится категория.
     * * isActive boolean Активность сообщения на данный момент.
     * * statusId integer Статус сообщения.
     * * userId integer ID пользователя, создавшего сообщение. Если оно было создано гостем,
     * поле имеет значение 0.
     * * firstName string Имя отправителя.
     * * patrName string Отчество отправителя.
     * * lastName string Фамилия отправителя.
     * * phones array Массив телефонов отправителя сообщения. Телефоны представлены в виде
     * строк чисел.
     * * email string E-mail отправителя.
     * * locationId integer ID локации сообщения.
     * * lat float Широта точки сообщения.
     * * lng float Долгота точки сообщения.
     * * address string Адрес точки, к которому относится сообщение.
     * * regionId integer ID региона, которому принадлежит точка сообщения.
     * * regionName string Название региона, которому принадлежит точка сообщения.
     * * categories array Массив категорий сообщения. Каждая категория имеет следующие поля:
     * * * id integer ID категории.
     * * * name string Название категории.
     * * * slug string Короткое название категории.
     * * tags array Массив тэгов сообщения. Каждый тэг имеет следующие поля:
     * * * id integer ID тэга.
     * * * name string Название тэга.
     * * photo array Массив, содержащий ID фотографий, приложенных к сообщению.
     */
    public function getById($id)
    {
        $this->_clearErrors();

        $id = (int)$id;
        if($id <= 0)
            return FALSE;

        $res = $this->db->get_where('message_view', array('message_id' => $id), 1)->row();
        if(empty($res))
            return FALSE;

        // Телефоны отправителя сообщения:
        $phones = array();
        foreach( explode(',', trim($res->sender_phone, '{}')) as $phone) {
            if( !empty($phone) )
                $phones[] = $phone;
        }

        // Категории сообщения:
        $resCats = $this->db->select('messagecategories.category_id "id",
                                      category_name "name",
                                      category_slug "slug"')
                        ->from('messagecategories')
                        ->join('category_view',
                               'messagecategories.category_id = category_view.category_id')
                        ->where('messagecategories.message_id', $res->message_id)->get();
        $cats = $resCats && $resCats->num_rows() ? $resCats->result_array() : array();

        // Медиа сообщения (фотки, видео, пр.):
        $photo = explode(',', trim($res->multimedia_id, '{}'));
        $photo = count($photo) > 1 || $photo[0] ? $photo : array();

        return array('id' => $res->message_id,
                     'title' => /*htmlentities(*/$res->message_title/*, ENT_QUOTES, 'UTF-8')*/,
                     'text' => /*htmlentities(*/$res->message_text/*, ENT_QUOTES, 'UTF-8')*/,
                     'dateAdded' => strtotime($res->message_date),
                     'isActive' => $res->is_active,
                     'isUntimed' => $res->is_untimed,
                     'isPublic' => $res->is_public,
                     'isNotify' => $res->is_notify,
    //                 'is' => $message->, // Другие флаги сообщения...
                     'typeId' => $res->message_type,
                     'typeSlug' => $res->message_typeslug,
                     'typeName' => $res->message_typename,
                     'statusId' => $res->message_status,
                     'subdomainId' => $res->subdomain_id,
                     'subdomainName' => $res->subdomain,
                     'userId' => (int)$res->user_id,
                     'firstName' => $res->sender_first_name,
                     'patrName' => $res->sender_patr_name,
                     'lastName' => $res->sender_last_name,
                     'phones' => $phones,
                     'email' => $res->sender_email,
                     'locationId' => $res->location_id,
                     'lat' => $res->latitude,
                     'lng' => $res->longtitude,
                     'address' => $res->location_name,
                     'regionId' => $res->region_id,
                     'regionName' => $res->region_name,
                     'categories' => $cats,
                     'photo' => $photo,);
    }

    /**
     * Получение списка сообщений, релевантных указанному сообщению. Релевантность
     * определяется по типу сообщений, категориям и расстоянию.
     *
     * @param $messageId integer ID сообщения, для которого выполняется поиск.
     * @param $messageTypes mixed Массив коротких названий (slug-ов) типов сообщений,
     * которые будут участвовать в поиске. По умолчанию, поиск производится среди следующих типов:
     * * если переданное сообщение имеет тип "просьба о помощи" - поиск по типу "предложение
     * помощи";
     * * если переданное сообщение имеет тип "предложение помощи" - поиск по типу "просьба
     * помощи";
     * * если переданное сообщение имеет тип "информация" - поиск по типам "просьба
     * помощи", "предложение помощи" и "информация".
     * @param $limit integer Максимальное кол-во возвращаемых сообщений. По умолчанию,
     * кол-во не ограничено.
     * @return array Массив строк с полями сообщения.
     */
    public function getRelevantByMessage($messageId, $messageTypes = array(), $limit = FALSE)
    {
        $messageId = (int)$messageId;
        if($messageId <= 0)
            return FALSE;

        $limit = (int)$limit;

        if(empty($messageTypes)) { // Целевые типы не переданы
            $message = $this->getById($messageId);
            switch($message['typeSlug']) {
                case 'request':
                    $messageTypes = array('offer');
                    break;
                case 'offer':
                    $messageTypes = array('request');
                    break;
                case 'info':
                    $messageTypes = array('info', 'request', 'offer');
                    break;
                default:
            }
        } else // Передан массив целевых типов
            array_walk($messageTypes, function(&$value){ $value = trim($value); });

        $res = array();
        $relevant = $this->db->query('SELECT id FROM "Message" m
                                      WHERE m.id IN (SELECT "rynda".recommended_messages('.$messageId.",'{".implode(',', $messageTypes)."}'::character varying[]))
                                      AND m.id IN (SELECT ".'"rynda".find_nearest_messages('.$messageId.','.$this->config->item('recommended_messages_distance').'))
                                      AND m.subdomain_id = '.$message['subdomainId']
                                     .($limit > 0 ? " LIMIT $limit" : ''));
        if($relevant && $relevant->num_rows()) {
            foreach($relevant->result() as $relevantId) {
                $res[] = $this->getById($relevantId->id);
            }
        }
        return $res;
    }

    /**
     * Вставка в БД нового сообщения.
     *
     * @param $data array Массив данных нового сообщения. Возможные значения полей:
     * * title string Заголовок сообщения. По умолчанию не задан.
     * * text string Текст сообщения. Обязательно должен быть передан.
     * * type mixed Тип сообщения. Должно иметь значение ID или slug одной из строк в таблице message_type. По умолчанию используется ID, указанный в константе NEW_MESSAGE_DEFAULT_TYPE.
     * * category mixed Категории сообщения. Может быть передан ID категории или массив
     * ID-ов. По умолчанию не задано.
     * * subdomain mixed ID или название субдомена (дочерней карты), к которой относится
     * сообщение. Если передано FALSE, 0, NULL или пустая строка, сообщение будет
     * привязано к главной карте. По умолчанию используется субдомен текущей страницы.
     * * regionId integer ID региона, которому принадлежит точка сообщения. По умолчанию
     * не задан.
     * * address string Адрес точки, к которому относится сообщение. По умолчанию не задан.
     * * lat float Широта точки сообщения. По умолчанию не задана.
     * * lng float Долгота точки сообщения. По умолчанию не задана.
     * * statusId integer Текущий статус сообщения. По умолчанию MESSAGE_STATUS_RECEIVED.
     * * userId integer ID пользователя, который добавляет сообщение. Если пользователь
     * незарегистрирован, поле имеет значение 0. По умолчанию 0.
     * * isActive boolean TRUE, если сообщение активно, иначе FALSE. По умолчанию TRUE.
     * * isUntimed boolean TRUE, если сообщение не является срочным, иначе FALSE.
     * По умолчанию TRUE.
     * * isPublic boolean TRUE, если автор сообщения согласен на публикацию своих данных,
     * иначе FALSE. По умолчанию TRUE.
     * * isNotifySender boolean TRUE, если автор сообщения желает, чтобы его уведомляли об
     * ответах на сообщение, иначе FALSE. По умолчанию TRUE.
     * * photo array Массив с ID элементов медиа, соответствующих фотографиям, приложенным
     * к сообщению.
     * * sender array Ассоциативный массив данных о пользователе, отправившем сообщение.
     * Возможные значения полей:
     * * * firstName string Имя отправителя. По умолчанию не задано.
     * * * patrName string Отчество отправителя. По умолчанию не задано.
     * * * lastName string Фамилия отправителя. По умолчанию не задана.
     * * * phones array Массив с телефонами отправителя. По умолчанию не задан.
     * * * email string E-mail отправителя. По умолчанию не задан.
     * * ***************************************************************************
     * * ******* Далее данные только для сообщений типа "предложение помощи" *******
     * * ***************************************************************************
     * * * period string Описание временного периода, когда отправитель готов помогать.
     * Если не задано, подразумевается "в любое время". По умолчанию не задано.
     * * * distance float Расстояние, на котором "в мирное время" могут располагаться
     * случаи, интересные отправителю. Если расстояние равно 0 или не задано, оно
     * считается любым. По умолчанию не задано.
     * * * distanceEmergency float Расстояние, на котором в период кризиса могут
     * располагаться случаи, интересные отправителю. Если расстояние равно 0 или не
     * задано, оно считается любым. По умолчанию не задано.
     * @return mixed ID нового сообщения при успешной вставке, иначе FALSE.
     */
    public function add(array $data)
    {
        $this->_clearErrors();
        $data = $data + array('title' => '',
                              'type' => NEW_MESSAGE_DEFAULT_TYPE,
                              'subdomain' => getSubdomain(),
                              'category' => array(),
                              'lat' => 0.0,
                              'lng' => 0.0,
                              'regionId' => 0,
                              'address' => '',
                              'statusId' => MESSAGE_STATUS_RECEIVED,
                              'userId' => 0,
                              'isActive' => TRUE,
                              'isUntimed' => TRUE,
                              'isPublic' => TRUE,
                              'isNotifySender' => TRUE,
                              'photo' => array(),);
        if( empty($data['text']) ) {
            $this->_addError(self::ERROR_INSERTING_MESSAGE_FIELDS_MISSING);
            return FALSE;
        }
        
        $data['type'] = $this->getType($data['type']);
        if( !$data['type'] )
            $data['type'] = $this->getType(NEW_MESSAGE_DEFAULT_TYPE);
        $data['type'] = $data['type']['id'];

        // Предобработка субдомена сообщения:
        if( !empty($data['subdomain']) ) {
            if((int)$data['subdomain'] > 0) // Передан ID субдомена
                $data['subdomain'] = (int)$data['subdomain'];
            else if(trim($data['subdomain'])) { // Передано название субдомена
                $res = $this->db->query( 'SELECT id FROM subdomain_view WHERE url = ? LIMIT 1',
                                         array(urlencode(trim($data['subdomain']))) );
                if($res && $res->num_rows())
                    $data['subdomain'] = $res->row()->id;
                else { // Субдомен в базе не найден - завершить с ошибкой
                    $this->_addError(self::ERROR_INSERTING_MESSAGE_UNKNOWN_SUBDOMAIN,
                                     "Unknown subdomain: {$data['subdomain']}");
                    return FALSE;
                }
            }
        } else
            $data['subdomain'] = 0;

        // Предобработка данных по территориальным привязкам сообщения (координатам,
        // адресу и региону):
        $data['regionId'] = (int)$data['regionId'];
        $data['lat'] = (float)$data['lat'];
        $data['lng'] = (float)$data['lng'];

        // Предобработка данных по отправителю сообщения:
        $data['sender'] = array('firstName' => empty($data['sender']['firstName']) ?
                                                   '' : $this->_cleanText($data['sender']['firstName']),
                                'patrName' => empty($data['sender']['patrName']) ?
                                                  '' : $this->_cleanText($data['sender']['patrName']),
                                'lastName' => empty($data['sender']['lastName']) ?
                                                  '' : $this->_cleanText($data['sender']['lastName']),
                                'phones' => empty($data['sender']['phones']) ?
                                                array() : (array)$data['sender']['phones'],
                                'email' => empty($data['sender']['email']) ?
                                               '' : trim($data['sender']['email']),
                                'period' => empty($data['sender']['period']) ?
                                                '' : trim($data['sender']['period']),
                                'distance' => 0.0,
                                'distanceEmergency' => 0.0,);
        foreach($data['sender']['phones'] as $key => $phone) {
            if( empty($phone) )
                unset($data['sender']['phones'][$key]);
            else
                $phone = preg_replace(array('/[^0-9]/'), '', $phone);
        }

        $this->db->trans_start();

        if($data['lat'] == 0.0 && $data['lng'] == 0.0) { // Координаты сообщения не выбраны
            // Если у сообщения задан регион, использовать координаты этого региона.
            // Если регион сообщения не задан - использовать регион по умолчанию:
            if($data['regionId'] <= 0)
                $data['regionId'] = $this->config->item('message_default_region');

            $coords = $this->db->select('latitude, longtitude')
                               ->from('regions_view')
                               ->where('region_id', $data['regionId'])->get();
            if( !$coords->num_rows() ) {
                $this->_addError(self::ERROR_INSERTING_MESSAGE_UNKNOWN_REGION);
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

        // Флаги сообщения:
        $flags = ($data['isActive'] ? MESSAGE_IS_ACTIVE: 0x0)
               | ($data['isUntimed'] ? MESSAGE_IS_UNTIMED : 0x0)
               | ($data['isPublic'] ? MESSAGE_IS_PUBLIC_SENDER : 0x0)
               | ($data['isNotifySender'] ? MESSAGE_IS_NOTIFY_SENDER : 0x0);

        // Отправитель сообщения:
        $xml = "<sender>
            <firstname>{$data['sender']['firstName']}</firstname>
            <patrname>{$data['sender']['patrName']}</patrname>
            <lastname>{$data['sender']['lastName']}</lastname>
            <email>{$data['sender']['email']}</email>
            <phone>".implode('</phone><phone>', $data['sender']['phones'])."</phone>";

        if($data['type'] == 'offer') {
            $xml .= "<period>{$data['sender']['period']}</period>".
                    '<distance>'.(float)$data['sender']['distance'].'</distance>
                     <distance-emergency>'.(float)$data['sender']['distanceEmergency'].
                    '</distance-emergency>';
        }
        $xml .= "</sender>";
        
        $this->db->insert('Message',
                          array('title' => $this->_cleanText(str_replace(array('ё', 'Ё'),
                                                                         array('е', 'Е'),
                                                                         $data['title'])),
                                'message' => $this->_cleanText(str_replace(array('ё', 'Ё'),
                                                                           array('е', 'Е'),
                                                                           $data['text']), TRUE),
                                'location_id' => $this->db->insert_id(),
                                'message_type' => $data['type'],
                                'status' => (int)$data['statusId'],
                                'subdomain_id' => $data['subdomain'],
                                'flags' => $flags,
                                'user_id' => (int)$data['userId'] > 0 ? (int)$data['userId'] : NULL,
                                'sender' => $xml,
                                'sender_ip' => $this->input->ip_address(),));
        $messageId = $this->db->insert_id();
        
        if($this->db->affected_rows() <= 0) {
            $this->_addError(self::ERROR_INSERTING_MESSAGE);
        }

        foreach((array)$data['category'] as $category) {
            $this->db->insert('messagecategories', array('category_id' => (int)$category,
                                                         'message_id' => $messageId));
        }
        
        foreach((array)$data['photo'] as $photoId) {
            $this->db->update('multimedia',
                              array('message_id' => $messageId),
                              array('id' => (int)$photoId));
        }

        $this->db->trans_complete();

        return $this->db->trans_status() ? $messageId : FALSE;
    }

    /**
     * Получение всех возможных типов сообщений.
     *
     * @param $exclude Массив slug-ов типов, которые требуется исключить из списка.
     * @return array Массив типов сообщений, содержащий строки со следующими полями:
     * * id integer Числовое значение типа (можно считать ID-ом типа).
     * * name string Название типа.
     * * slug string Короткое название типа.
     */
    public function getTypesList(array $exclude = array())
    {
        $res = array();
        
        $types = $this->db->from('message_type');
        if($exclude)
            $types->where_not_in('slug', $exclude);
        $types = $types->get();
        
        foreach($types->result() as $type) {
            $res[] = array('id' => $type->id, 'name' => $type->name, 'slug' => $type->slug);
        }
        
        return $res;
    }
    
    /**
     * Получение данных о типе сообщения в виде массива.
     *
     * @param $type ID или slug типа сообщения. Обязателен.
     * @return mixed Массив данных о типе сообщения или FALSE, если указанный тип не найден.
     */
    public function getType($type)
    {
        if((int)$type > 0)
            $type = $this->db->get_where('message_type', array('id' => (int)$type));
        else if( !empty($type) )
            $type = $this->db->get_where('message_type', array('slug' => trim($type)));
                
        return $type && $type->num_rows() > 0 ? $type->row_array() : FALSE;
    }
    
    /**
     * Получение текстового названия статуса сообщения по его ID.
     *
     * @param $statusId integer ID статуса.
     * @return mixed Название статуса либо FALSE, если передан некорректный ID.
     */
    public function getStatusName($statusId)
    {
        $this->lang->load('rynda_models');
        switch((int)$statusId) {
            case MESSAGE_STATUS_RECEIVED:
                return $this->lang->line('modelMessages_statusReceivedName');
            case MESSAGE_STATUS_MODERATED:
                return $this->lang->line('modelMessages_statusModeratedName');
            case MESSAGE_STATUS_VERIFIED:
                return $this->lang->line('modelMessages_statusVerifiedName');
            case MESSAGE_STATUS_REACTION:
                return $this->lang->line('modelMessages_statusReactionName');
            case MESSAGE_STATUS_REACTED:
                return $this->lang->line('modelMessages_statusReactedName');
            case MESSAGE_STATUS_CLOSED:
                return $this->lang->line('modelMessages_statusClosedName');
            default:
                return FALSE;
        }
    }
}
